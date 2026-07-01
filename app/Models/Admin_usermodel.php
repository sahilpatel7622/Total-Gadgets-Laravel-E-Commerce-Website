<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin_usermodel extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'Admin_user';
    protected $fillable = ['name','name', 'email', 'password'];

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
