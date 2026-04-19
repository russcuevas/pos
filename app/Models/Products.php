<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'product_description',
        'product_image',
        'selling_price',
        'supplier_price',
        'quantity',
        'whole_sale_qty',
        'whole_sale_price',
        'is_show',
    ];
}
