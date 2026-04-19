<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class Customers extends Authenticable
{
    use Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'phone_number',
        'address',
        'is_verified'
    ];

    protected $hidden = [
        'password',
    ];
}
