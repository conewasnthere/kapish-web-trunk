<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'datastore',
        'keys',
        'pid'
    ];

}
