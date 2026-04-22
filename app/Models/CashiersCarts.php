<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersCarts extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'cashier_id',
        'product_id',
        'custom_entry',
        'custom_price',
        'quantity'
    ];
}
