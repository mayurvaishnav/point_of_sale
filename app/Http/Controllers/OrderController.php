<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Pest\ArchPresets\Custom;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:order-list', ['only' => ['index']]);
         $this->middleware('permission:order-create', ['only' => ['create','store']]);
         $this->middleware('permission:order-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }
    
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
        $order->load(['customer', 'orderDetails', 'orderpayments']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $cartItems = [];

        $idForNull = -1;

        foreach ($order->orderDetails as $orderDetail) {
            $item = new CartItem(
                $orderDetail->product_id ?? $idForNull--,
                $orderDetail->product_name,
                $orderDetail->quantity,
                $orderDetail->unit_price,
                $orderDetail->discount,
                $orderDetail->tax_rate,
            );

            $cartItems[$item->id] = $item;
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
