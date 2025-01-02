<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'requester_id',
        'status',
        'best_friends'
    ];

    public function requester()
    {
        return $this->belongsTo('App\Models\User', 'requester_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }

    public function areFriends()
    {
        return $this->status >= 1;
    }

    public function areBestFriends()
    {
        return $this->status >= 2;
    }
}
