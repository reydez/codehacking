<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'is_active', 'photo_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
       return $this->belongsTo('App\Role');
    }

    public function  photo()
    {
        return $this->belongsTo('App\Photo');
    }

    public function  posts()
    {
        return $this->hasMany('App\Post');
    }

    public function isAdmin()
    {
        if($this->role->name == "administrator" && $this->is_active == 1)
        {
            return true;
        }
        return false;
    }
}
