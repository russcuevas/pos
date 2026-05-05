<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtorsTransactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'debtor_id',
        'payment_amount',
        'payment_type',
        'note',
    ];
}
