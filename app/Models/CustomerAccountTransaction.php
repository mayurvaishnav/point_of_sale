<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAccountTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "customer_account_id",
        "customer_id",
        "order_id",
        "note",
        "credit_amount",
        "paid_amount",
        "balance",
    ];

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
