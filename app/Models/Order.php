<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status' => OrderStatus::class,
    ];

    public function getStatusBadge() 
    {
        $status = $this->status;

        switch ($status) {
            case OrderStatus::PAID:
                $badgeClass = 'success';
                break;
            case OrderStatus::PARTIAL:
                $badgeClass = 'warning';
                break;
            case OrderStatus::DUE:
                $badgeClass = 'danger';
                break;
            case OrderStatus::LAYAWAY:
                $badgeClass = 'secondary';
                break;
            case OrderStatus::CANCELLED:
                $badgeClass = 'dark';
                break;
            default:
                $badgeClass = 'light';
                break;
        }

        return '<span class="badge badge-' . $badgeClass . '">' . $status->value . '</span>';
    }

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

    public function customerAccountTransactions() 
    {
        return $this->hasMany(CustomerAccountTransaction::class);
    }
}
