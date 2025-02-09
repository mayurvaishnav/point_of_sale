<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
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
        'note',
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

    public function canEditCustomer() 
    {
        if($this->orderPayments->first() && $this->orderPayments->first()->payment_method == PaymentMethods::CUSTOMER_ACCOUNT) {
            return false;
        }

        return true;
    }

    public function canBeDeleted() 
    {
        if($this->orderPayments->first() && $this->orderPayments->first()->payment_method == PaymentMethods::CUSTOMER_ACCOUNT) {
            $orderTransactionId = $this->customerAccountTransactions->first()->id ?? null;

            $customerLastTransactionId = $this->customer->customerAccountTransactions->last()->id ?? null;
            if($orderTransactionId != $customerLastTransactionId) {
                return false;
            }
            return true;
        }

        return true;
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
