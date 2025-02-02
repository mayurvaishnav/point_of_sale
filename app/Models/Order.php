<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case CUSTOMER_CREDIT = 'customer_credit';
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
        'paid_method',
        'net_sales',
        'discount',
        'tax',
        'total',
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
}
