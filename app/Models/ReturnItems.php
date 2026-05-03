<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_item_id',
        'quantity',
        'refund_amount',
        'refund_source',
        'admin_id',
        'cashier_id',
    ];

    public function orderItem()
    {
        return $this->belongsTo(Orders::class, 'order_item_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admins::class, 'admin_id');
    }

    public function cashier()
    {
        return $this->belongsTo(Cashiers::class, 'cashier_id');
    }
}
