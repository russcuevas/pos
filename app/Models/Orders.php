<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'products_id',
        'custom_entry',
        'custom_price',
        'customer_id',
        'customer_name',
        'customer_phone',
        'address',
        'quantity',
        'discount_price',
        'total_price',
        'payment_amount',
        'change_amount',
        'payment_method',
        'order_type',
        'order_status',
        'delivery_fee',
        'cashier_id',
        'admin_id',
    ];
}
