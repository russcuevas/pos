<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class Cashiers extends Authenticable
{
    use Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}
