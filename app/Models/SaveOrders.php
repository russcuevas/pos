<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_save_order',
        'cashier_id',
        'admin_id',
        'product_id',
        'custom_entry',
        'custom_price',
        'quantity',
    ];
}
