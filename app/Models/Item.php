<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Reseller;
use App\Models\Sale;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'creator',
        'price',
		'original_price',
		'stock_circulating',
		'private_sellers',
        'onsale',
        'approved',
		'is_limited',
		'is_limitedu',
		'is_boosters_only',
        'type',
        'sales',
        'hatchtype',
        'hatchdate',
        'hatchname',
        'hatchdesc',
        'thumbnail_url'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'creator');
    }

    public function isXmlAsset()
    {
        return $this->type == "Hat" || $this->type == "Model" || $this->type == "Gear" || $this->type == "Package" || $this->type == "Head";
    }

    public function getContents()
    {
        if (Storage::disk('public')->exists('items/' . $this->id)) {
            return Storage::disk('public')->get('items/' . $this->id);
        } else {
            return false;
        }
    }
	
	public function getOverlay($useBig = false)
	{
		$size = ($useBig) ? "_big" : "";
		
		switch(true)
		{
			case $this->is_boosters_only:
				return "/images/overlays/overlay_bcOnly$size.png";
			case $this->is_limitedu:
				return "/images/overlays/overlay_limitedUnique$size.png";
			case $this->is_limited;
				return "/images/overlays/overlay_limited$size.png";
		}
	}
	
	public function amountRemaining()
	{
		return max(0, $this->stock_circulating - $this->sales);
	}
	
	public function isApproved()
	{
		return $this->approved;
	}
	
	public function isForSale()
	{
		return $this->onsale;
	}
	
	public function isBoostersOnly()
	{
		return $this->is_boosters_only;
	}
	
	public function isLimited()
	{
		return $this->is_limited;
	}
	
	public function isLimitedUnique()
	{
		return $this->is_limitedu;
	}
	
	public function isResellable()
	{
		return !$this->amountRemaining() && ($this->is_limited || $this->is_limitedu);
	}
	
	public function canAfford()
	{
		return (Auth::user()->money >= $this->price);
	}
	
	public function getRAP()
	{
		$rap = Sale::where("product_id", $this->id)->orderBy("created_at")->limit(50)->avg("total_price");

		if (!$rap) {
			$rap = 0;
		}

		return $rap;
	}
}
