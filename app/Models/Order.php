<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case CUSTOMER_ACCONT = 'customer_account';
}

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'status',
        'order_date',
        'invoice_number',
        'quantity',
        'total_before_tax',
        'discount',
        'tax',
        'total',
        'total_after_discount',
    ];

    protected $casts = [
        'paid_method' => PaymentMethod::class,
    ];

    /**
     * Get the customer that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails() 
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderPayments() 
    {
        return $this->hasMany(OrderPayment::class);
    }
}
