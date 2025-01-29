<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('customer')->latest()->get();
        return view("orders.index", compact("orders"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {

        $cartItems = [];

        foreach ($order->orderDetails as $orderDetail) {
            $item = new CartItem();
            $item->id = $orderDetail->product->id;
            $item->name = $orderDetail->product->name;
            $item->quantity = $orderDetail->quantity;
            $item->price = $orderDetail->price;
            $item->taxRate = $orderDetail->tax_rate;
            $item->tax = $orderDetail->tax;
            $item->discount = $orderDetail->discount;
            $item->total = $orderDetail->total;

            $cartItems[$orderDetail->product->id] = $item;
        }

        $cart = new Cart($order->customer, $order, $cartItems);

        session()->put("cart", $cart);

        return redirect()->route("pos.index");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
