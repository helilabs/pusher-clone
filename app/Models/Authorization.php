<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class Authorization extends Model{

    public $fillable = [
        'session_id', 'channel'
    ];

}