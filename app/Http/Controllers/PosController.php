<?php

namespace App\Http\Controllers;

use App\Models\CartService;
use App\Models\Customer;
use App\Models\CustomerAccountTransaction;
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

    public function processPayment(Request $request) {

        $rules = [
            'payment_method' => 'required|string|in:cash,credit_card,customer_credit',
            'amount_paid' => 'required|numeric'
        ];

        $validatedData = $request->validate($rules);

        $cart = CartService::getCart();
        $customer_id = $cart->customer->id ?? null;

        // dd($cart, $validatedData);

        //validate the cart

        // DB::beginTransaction();

        // try {

            $order = Order::create([
                'customer_id' => $customer_id,
                'user_id' => Auth::user()->id,
                'status' => 'paid',
                'order_date' => now()->toDateString(),
                'paid_method' => $validatedData['payment_method'], 
                'net_sales' => $cart->getTotalCart()->subTotal,
                'discount' => 0,
                'tax' => $cart->getTotalCart()->tax,
                'total' => $cart->getTotalCart()->total
            ]);

            $order->invoice_number = "BAC-" . Carbon::parse($order->order_date)->format('Ymd') . "-" . $order->id;
            $order->save();

            foreach ($cart->cartItems as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->id > 0 ? $cartItem->id : null,
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


            

            if ($validatedData['payment_method'] == 'customer_credit') {
                $customer = Customer::find($customer_id);

                if($customer->customerAccounts()->count() == 0) {
                    $customer->customerAccounts()->create([
                        'description' => 'Customer account for ' . $customer->name
                    ]);
                }

                $previousBalance = $customer->customerAccountTransactions()->latest()->first()->balance ?? 0;
                // dd($customer->customerAccounts()->first());
                
                // Deduct the amount from the customer's credit
                CustomerAccountTransaction::create([
                    'customer_account_id'=> $customer->customerAccounts()->first()->id,
                    'customer_id' => $customer_id,
                    'order_id' => $order->id,
                    'note' => 'Order payment for ' . $order->invoice_number,
                    'credit_amount' => $order->total,
                    'balance' => $previousBalance - $order->total
                ]);

            }

            // DB::commit();

            // Clear the cart
            CartService::clearCart();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => 'Order has been placed successfully.',
                    'payment_method' => $validatedData['payment_method']
                ]);
            }

            return redirect()->route('pos.index')->with('success', 'Order has been placed successfully.');
        
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->route('pos.index')->with('error', 'Failed to place order. Please try again.');
        // }
        
    }

    public function save(Request $request) {

        $cart = CartService::getCart();

        // dd($cart, $validatedData);

        //validate the cart

        DB::beginTransaction();

        try {

            $order = Order::create([
                'customer_id' => $cart->customer->id ?? null,
                'user_id' => Auth::user()->id,
                'status' => 'layaway',
                'order_date' => now()->toDateString(),
                'net_sales' => $cart->getTotalCart()->subTotal,
                'discount' => $cart->getTotalCart()->discount,
                'tax' => $cart->getTotalCart()->tax,
                'total' => $cart->getTotalCart()->total
            ]);

            $order->invoice_number = "BAC-" . Carbon::parse($order->order_date)->format('Ymd') . "-" . $order->id;
            $order->save();

            foreach ($cart->cartItems as $cartItem) {
                $productId = $cartItem->id > 0 ? $cartItem->id : null;
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
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
            
            if ($request->wantsJson()) {
                return response()->json(CartService::getCart());
            }

            return redirect()->route('pos.index')->with('success', 'Order has been saved for later use.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pos.index')->with('error', 'Failed to place order. Please try again.');
        }
        
    }
}
