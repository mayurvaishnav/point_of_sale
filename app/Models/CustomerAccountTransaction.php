<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAccountTransaction extends Model
{
    protected $fillable = [
        "customer_account_id",
        "customer_id",
        "order_id",
        "note",
        "credit_amount",
        "paid_amount",
        "balance",
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
