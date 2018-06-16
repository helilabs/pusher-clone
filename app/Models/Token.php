<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class Token extends Model{

    public $fillable = [
        'app_id', 'secret'
    ];

}