<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'payment_method',
        'amount',
        'category',
        'note'
    ];
}
