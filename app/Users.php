<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //
    public $timestamps = true;
    protected $hidden = [
        'password', 'remember_token',
    ];
}
