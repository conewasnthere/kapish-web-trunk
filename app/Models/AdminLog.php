<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'action',
        'show_danger'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // Log an action from an administrator (should ONLY be used for administrators)
    public static function log($user, $action, $dangerous = false)
    {
        // Create the log
        return self::create([
            'user_id' => $user->id,
            'ip' => $user->last_ip, // Internal purposes only
            'action' => $action,
			'show_danger' => $dangerous
        ]);
    }
}
