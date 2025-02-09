<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function receipt($orderId)
    {
        $order = Order::find($orderId);

        $order->load(['customer', 'orderDetails', 'orderpayments']);

        return view('orders.receipt', compact('order'));
    }
}
