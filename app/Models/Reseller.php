<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\Item;
use App\Models\User;

class Reseller extends Model
{
    use HasFactory;
	
	protected $fillable = [
		"item_id",
		"user_id",
		"price",
		"serial" // We'll have this column for easy access
	];
	
	public function item()
	{
		return $this->belongsTo(Item::class, 'item_id');
	}
	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	
	public function canAfford()
	{
		return (Auth::user()->money > $this->price);
	}
}
