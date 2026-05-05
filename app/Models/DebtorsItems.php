<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtorsItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'debtor_id',
        'products_id',
        'custom_entry',
        'custom_price',
        'custom_cost',
        'quantity',
    ];
}
