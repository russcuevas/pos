<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersCarts extends Model
{
    use HasFactory;

    protected $fillable = [
        'customers_id',
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
