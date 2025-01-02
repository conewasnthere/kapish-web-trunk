<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'body'
    ];
    
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }
}
