<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
	
	protected $fillable = [
		"purchaser_id",
		"seller_id",
		"product_id",
		"quantity",
		"purchase_price",
		"discount",
		"total_price",
		"marketplace_fee",
	];
	
	public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
	
	public function purchaser()
    {
        return $this->belongsTo(User::class, 'purchaser_id');
    }
	
	public function item()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }
}
