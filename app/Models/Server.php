<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'creator',
        'visits',
        'ip',
        'loopback_ip',
        'port',
        'version',
        'friends_only',
        'maxplayers',
        'secret',
        'unlisted'
    ];

    protected $hidden = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'creator');
    }
}
