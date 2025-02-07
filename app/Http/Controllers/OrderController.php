<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
         $this->middleware('permission:order-edit', ['only' => ['edit','updateCustomer']]);
         $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['orderPayments', 'customerAccountTransactions', 'customer.customerAccountTransactions'])->latest()->get();
        return view("orders.index", compact("orders"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $customers = Customer::all();
        $order->load(['customer', 'orderDetails', 'orderpayments']);

        return view('orders.show', compact('order', 'customers'));
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

        $cart = new Cart($order->customer, $order, $cartItems, 0);

        session()->put("cart", $cart);

        return redirect()->route("pos.index");
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCustomer(Request $request, Order $order)
    {
        $request->validate([
            "customer_id" => "nullable|exists:customers,id",
        ]);
        $order->customer_id = $request->customer_id;
        $order->save();

        return redirect()->route("orders.show", $order);
    }

    /**
     * Download invoice.
     */
    public function downloadInvoice(Order $order)
    {
        $order->load(['customer', 'orderDetails', 'orderpayments']);$customerAccountBalance = 0;

        if ($order->customer) {
            $customerAccountBalance = $order->customer->customerAccountTransactions->last()->balance ?? 0;
        }


        // return view('orders.invoice', compact('order', 'customerAccountBalance'));

        $pdf = Pdf::loadView('orders.invoice', compact('order', 'customerAccountBalance'));
        return $pdf->download($order->invoice_number .'.pdf');
    }

    /**
     * Email invoice.
     */
    public function emailInvoice(Request $request, Order $order)
    {
        $request->validate([
            'email'=> 'nullable|email',
        ]);

        $order->load(['customer', 'orderDetails', 'orderpayments']);
        
        $customerAccountBalance = 0;
        $customerEmail = $request->email;

        if ($order->customer) {
            $customerAccountBalance = $order->customer->customerAccountTransactions->last()->balance ?? 0;
            $customerEmail = $order->customer->email;
        }

        if($customerEmail == null) {
            return redirect()->back()->with('error', 'Customer email is required');
        }

        Mail::to([$customerEmail, env('MAIL_CC_ADDRESS')])->send(new OrderInvoiceMail($order, $customerAccountBalance));

        return redirect()->back()->with('success', 'Invoice sent successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if (!$order->canBeDeleted()) {
            return redirect()->back()->with('error', 'This order cannot be deleted');
        }

        $order->customerAccountTransactions()->delete();
        $order->orderPayments()->delete();
        $order->orderDetails()->delete();
        $order->delete();
        return redirect()->back()->with('success','Order deleted successfully');
    }
}
