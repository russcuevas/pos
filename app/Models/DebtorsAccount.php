<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtorsAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_contact',
        'customer_address',
        'balance',
    ];

    public function transactions()
    {
        return $this->hasMany(DebtorsTransactions::class, 'debtor_id');
    }

    public function items()
    {
        return $this->hasMany(DebtorsItems::class, 'debtor_id');
    }
}
