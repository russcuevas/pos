<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'beginning_balance',
        'opening_time',
    ];

    public function cashier()
    {
        return $this->belongsTo(Cashiers::class);
    }
}
