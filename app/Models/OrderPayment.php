<?php

namespace App\Models;

use App\Enums\PaymentMethods;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrderPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount_paid',
        'amount_due',
        'payment_status',
    ];

    protected $casts = [
        'payment_status' => PaymentStatus::class,
        'payment_method' => PaymentMethods::class,
    ];

    public function getPaymnetStatusBadge() 
    {
        $status = $this->payment_status;

        switch ($status) {
            case PaymentStatus::PAID:
                $badgeClass = 'success';
                break;
            case PaymentStatus::PARTIAL:
                $badgeClass = 'warning';
                break;
            case PaymentStatus::DUE:
                $badgeClass = 'danger';
                break;
            default:
                $badgeClass = 'secondary';
                break;
        }

        return '<span class="badge badge-' . $badgeClass . '">' . $status->value . '</span>';
    }

    public function order() 
    {
        return $this->belongsTo(Order::class);
    }
}