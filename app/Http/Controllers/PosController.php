<?php

namespace App\Http\Controllers;

use App\Models\CartService;
use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        $cart = CartService::getCart();
        $customers = Customer::all();

        $categories = $products->pluck('category')->unique();

        // session()->forget('cart');
        // dd($products, $cart, $customers, $categories);

        return view("pos.index", compact("products","cart", "customers", "categories"));
    }

    public function pay(Request $request) {

        $rules = [
            'payment_method' => 'required'
        ];

        $validatedData = $request->validate($rules);

        $cart = CartService::getCart();
        $customer_id = $cart->customer->id;
        $customer = Customer::find($customer_id);

        // dd($cart, $validatedData);

        //validate the cart

        DB::beginTransaction();

        try {

            $order = Order::create([
                'customer_id' => $customer_id,
                'user_id' => Auth::user()->id,
                'status' => 'paid',
                'order_date' => now()->toDateString(),
                'paid_method' => $validatedData['payment_method'], 
                'net_sales' => $cart->getTotal()->total,
                'discount' => 0,
                'tax' => $cart->total->tax,
                'total' => $cart->total->total
            ]);

            $order->invoice_number = "BAC-" . Carbon::parse($order->order_date)->format('Ymd') . "-" . $order->id;
            $order->save();

            foreach ($cart->cartItems as $cartItem) {
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

                $product = Product::find($cartItem->id);
                $product->quantity -= $cartItem->quantity;
                $product->save();
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
            CartService::clearCart();

            return redirect()->route('pos.index')->with('success', 'Order has been placed successfully.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pos.index')->with('error', 'Failed to place order. Please try again.');
        }
        
    }

    public function save(Request $request) {

        $cart = CartService::getCart();
        $customer_id = $cart->customer->id;
        $customer = Customer::find($customer_id);

        // dd($cart, $validatedData);

        //validate the cart

        DB::beginTransaction();

        try {

            $order = Order::create([
                'customer_id' => $customer_id,
                'user_id' => Auth::user()->id,
                'status' => 'pending',
                'order_date' => now()->toDateString(),
                'net_sales' => 100,
                'discount' => 0,
                'tax' => 0,
                'total' => 1233
            ]);

            $order->invoice_number = "BAC-" . Carbon::parse($order->order_date)->format('Ymd') . "-" . $order->id;
            $order->save();

            foreach ($cart->cartItems as $cartItem) {
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

                $product = Product::find($cartItem->id);
                $product->quantity -= $cartItem->quantity;
                $product->save();
            }

            DB::commit();

            // Clear the cart
            CartService::clearCart();

            return redirect()->route('pos.index')->with('success', 'Order has been saved for later use.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pos.index')->with('error', 'Failed to place order. Please try again.');
        }
        
    }
}
