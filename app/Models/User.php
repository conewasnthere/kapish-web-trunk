<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\AsCollection;

use App\Models\Item;
use App\Models\OwnedItem;
use App\Models\Sale;
use App\Models\AdminLog;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'register_ip',
        'last_ip',
        'verified_hoster',
        'scribbler',
        'old_cores',
        'booster',
		'money',
        'invite_key',
        'discord_id',
        'last_online',
        'added_servers',
        'qa',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'password',
        'remember_token',
        'register_ip',
        'last_ip',
        'invite_key',
        'discord_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
        'added_servers' => AsCollection::class,
    ];

    protected $dates = [
        'joined',
        'last_online'
    ];

    public function servers()
    {
        return $this->hasMany('App\Models\Server', 'creator');
    }

    public function threads()
    {
        return $this->hasMany('App\Models\ForumThread', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\ForumPost', 'user_id');
    }

	public function countPosts()
	{
		return $this->posts->count() + $this->threads->count();
	}
	
	public function getPostsString()
	{
		$posts = $this->countPosts();
		$postText = 'post';
		if($posts == 0 || $posts > 1)
			$postText .= 's';
		
		return sprintf('%d %s', $posts, $postText);
	}

    public function friends()
    {
        $friends = \App\Models\Friendship::where(function($query) {
            $query->where(['receiver_id' => $this->id]);
            $query->orWhere(['requester_id' => $this->id]);
        })->where('status', '1')->get();

        return $friends;
    }

    public function friendRequests()
    {
        return \App\Models\Friendship::where('status', '0')
            ->where('receiver_id', '=', $this->id)
            ->get();
    }

    public function discordLinked()
    {
        if ($this->discord_id) {
            return true;
        }

        return false;
    }
	
	public function isBooster()
	{
		return (boolean) $this->booster;
	}

    public function isVerifiedHoster()
    {
        return false;
    }

    public function isAdmin()
    {
        return $this->admin == 1;
    }

    public function isModerator()
    {
        return $this->admin == 2;
    }

    public function isEventStaff()
    {
        return false;
    }

    public function isStaff()
    {
        // we are not giving event staff perms
        return ($this->admin == 1 || $this->admin == 2);
    }
	
	public function ownsItem($itemId)
	{
		return OwnedItems::where(['user_id' => $this->id, 'item_id' => $itemId])->exists();
	}
	
	public function canPurchaseItem($item)
	{
		// Match through all the possible scenarios
		$val = match(true) 
		{
			// Does the user own the item? Is it a limited unique?
			$this->ownsItem($item->id) && !$item->isLimitedUnique() => false,
			
			// Is the user an admin?
			$this->isAdmin() => true,
			
			// Is the item onsale? Is it approved?
			(!$item->isForSale() || !$item->isApproved()) => false,
			
			// Is the item boosters only? Is the user a booster?
			($item->isBoostersOnly() && !$this->isBooster()) => false,
			
			// Normally limiteds are former non-limiteds, so let's check.
			($item->isLimited()) => false,
			// If the item is a limited unique, has all stock been sold?
			($item->isLimitedUnique() && $item->sales >= $item->stock_circulating) => false,
			
			// Can the user afford the item?
			!$item->canAfford($this->money) => false,
			
			// Otherwise...
			default => true,
		};
		
		// Now it is up to the gods of fate...
		return $val;
	}
	
	public function canPurchaseResale($resale)
	{
		// Get the item from the resale
		$item = $resale->item;
		
		// Match through all the possible scenarios
		$val = match(true) 
		{
			// Is the user the reseller?
			$resale->user_id == $this->id => false,
			
			// Is the user an admin?
			$this->isAdmin() => true,
			
			// Is the item onsale? Is it approved?
			(!$item->approved) => false,
			
			// Is the item boosters only? Is the user a booster?
			($item->isBoostersOnly() && !$this->isBooster()) => false,
			
			// Can the user afford the item?
			!$resale->canAfford($this->money) => false,
			
			// Otherwise...
			default => true,
		};
		
		// Now it is up to the gods of fate...
		return $val;
	}
	
	public function canSellItem($item)
	{
		// Match through all the possible scenarios
		$val = match(true) 
		{
			// Is the item a limited?
			(!$item->isResellable()) => false,
			
			// Is the item approved?
			(!$item->isApproved()) => false,
			
			// Otherwise...
			default => true,
		};
		
		// Now it is up to the gods of fate...
		return $val;
	}
	
	public function getLimitedFromInventory($itemId)
	{
		// Get the item from the user's inventory (this contains info like the serial)
		return OwnedItems::where("item_id", $itemId)
						 ->where("user_id", $this->id)
						 ->get();
	}
	
	public function addToInventory($item, $serial = false)
	{
		// Is it a normal purchase?
		if(!$serial)
		{
			// Increment item sales
			$item->sales++;
			
			// Update the item
			$item->save();
		}
		
		// Put it in the user's inventory
		OwnedItems::create([
            'user_id' => $this->id,
            'item_id' => $item->id,
            'wearing' => false,
			'serial' => ($serial) ? $serial : $item->sales,
        ]);
	}
	
	public function incurFees($item)
	{
		// Charge the user for the value of the item/resale
		$this->money = $this->money - $item->price;
        $this->save();
		
		// The creator will in turn receive that amount
        $item->user->money = $item->user->money + $item->price;
        $item->user->save();
	}
	
	public function buyItem($item)
	{
		// Check if the user can purchase the item
		if(!$this->canPurchaseItem($item))
			return back()->with('error', 'You can not afford this item.'); // Ratelimit got the user ();

		$resale = Reseller::find($item->id);
		if($item->isLimited() || $item->isLimitedUnique()) {
			if ($this->ownsItem($item->id) && $this->getLimitedFromInventory($item->id)->count+1 == 3) {
				return false;
			}
			if ($item->isLimited() && $item->isResellable() && !is_null($resale)) {
				return abort(403);
			} 
		}
		// Add the item to the user's inventory
		$this->addToInventory($item);
		
		// Incur the fees and returns
        $this->incurFees($item);
		
		// Log the sale
		Sale::create([
			"purchaser_id"   => $this->id,
			"seller_id"      => $item->creator,
			"product_id"     => $item->id,
			"purchase_price" => $item->price,
			"total_price"    => $item->price
		]);
	}
	
	public function buyResale($resale)
	{
		// Check if the user can purchase the item
		if(!$this->canPurchaseResale($resale))
			return false;
        
		// Incur the fees and returns
        $this->incurFees($resale);
        
		/* Add the item to the user's inventory
		   The second argument allows us to insert a serial no. */
		$this->addToInventory($resale->item, $resale->serial);
		
		
		// Decrement the amount of private sellers and update the item's RAP
		$rap = $resale->item->getRAP();
		$resale->item->private_sellers--;
		if ($rap > $resale->item->price) {
			$change = floor(abs(($rap - $resale->item->price) / 10));
			$resale->item->rap = $rap - $change;
			$resale->item->save();
            $resale->delete();
		} elseif($rap < $resale->item->price) {
			$change = floor(abs(($resale->item->price - $rap) / 10));
			$resale->item->rap = $rap + $change;
			$resale->item->save();
            $resale->delete();
		} else {
            $resale->item->save();  
            $resale->delete();
        }
		
		// Log the sale
		Sale::create([
			"purchaser_id"   => $this->id,
			"seller_id"      => $resale->item->creator,
			"product_id"     => $resale->item->id,
			"purchase_price" => $resale->price,
			"total_price"    => $resale->price
		]);

		// Log it in the admin log if the user is an admin.
		if ($this->isAdmin()) {
			AdminLog::log($this, sprintf('Purchased a resale from %s for %s. Item: "%s". (USER ID: %s) (ITEM ID: %s) (SERIAL: %s)', $resale->user->username, $resale->price, $resale->item->name, $resale->user->id, $resale->item->id, $resale->serial));
		}
	}
}