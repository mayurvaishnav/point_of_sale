<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $cartItems = Cart::getCart();


        return view("pos.index", compact("products","cartItems"));
    }

    public function pay(Request $request) {

        $rules = [
            'payment_method' => 'required'
        ];

        $validatedData = $request->validate($rules);

        $cart = Cart::getCart();
        $customer_id = 1;
        $customer = Customer::find($customer_id);

        // dd($cart, $validatedData);

        //validate the cart

        DB::beginTransaction();

        try {

            $order = Order::create([
                'customer_id' => $customer_id,
                'user_id' => Auth::user()->id,
                'status' => 'pending',
                'order_date' => now(),
                'invoice_number' => "123",
                'paid_method' => $validatedData['payment_method'], 
                'net_sales' => 100,
                'discount' => 0,
                'tax' => 0,
                'total' => 1233
            ]);

            foreach ($cart as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->id,
                    'product_name' => $cartItem->name,
                    'quantity' => $cartItem->quantity,
                    'unit_cost' => $cartItem->price,
                    'net_sales' => $cartItem->price * $cartItem->quantity,
                    'discount' => $cartItem->discount,
                    'tax' => $cartItem->tax,
                    'total' => $cartItem->price
                ]);
            }

            $previousBalance = $customer->customerCredits()->latest()->first()->balance ?? 0;

            if ($validatedData['payment_method'] == 'customer_credit') {
                // Deduct the amount from the customer's credit
                CustomerCredit::create([
                    'customer_id' => $customer_id,
                    'order_id' => $order->id,
                    'note' => 'Order payment for ' . $order->invoice_number,
                    'credit_amount' => $order->total,
                    'balance' => $previousBalance - $order->total
                ]);

            }

            DB::commit();

            // Clear the cart
            Cart::clearCart();

            return redirect()->route('pos.index')->with('success', 'Order has been placed successfully.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pos.index')->with('error', 'Failed to place order. Please try again.');
        }
        
    }
}
