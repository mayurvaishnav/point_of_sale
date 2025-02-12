<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Enums\PaymentStatus;
use App\Models\CartService;
use App\Models\Customer;
use App\Models\CustomerAccountTransaction;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

        return view("pos.index", compact("products","cart", "customers", "categories"));
    }

    public function processPayment(Request $request) {

        $rules = [
            'payment_method' => 'required|string|in:CASH,CREDIT_CARD,CUSTOMER_ACCOUNT',
            'amount_paid' => 'required|numeric',
            'discount_amount'=> 'nullable|numeric',
            'note'=> 'nullable|string|max:10000',
        ];

        $validator = Validator::make($request->all(), $rules);

        CartService::updateDiscount($request->discount_amount ?? 0);

        $cart = CartService::getCart();
        $customer_id = $cart->customer->id ?? null;

        //validate the cart
        $validator->after(function ($validator) use ($cart, $request, $customer_id) {
            // Check if cart is empty
            if (count($cart->cartItems) == 0) {
                $validator->errors()->add('cart', 'Cart is empty.');
            }

            // Check if any product is out of stock
            foreach ($cart->cartItems as $item) {
                $product = Product::find($item->id);
                if ($product && $product->stockable && $product->quantity < $item->quantity) {
                    $validator->errors()->add('quantity', "Product {$product->name} is out of stock.");
                }
            }

            // Check if customer is present for CUSTOMER_ACCONT payment method
            if ($request->payment_method == PaymentMethods::CUSTOMER_ACCOUNT->value && !$customer_id) {
                $validator->errors()->add('customer', 'Customer must be selected for customer account payment method.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Log::info('PosController processPayment method called by user: ' . Auth::id() . ' with parameters: ' . json_encode($request->all()));

        DB::beginTransaction();

        try {

            $orderData = [
                'customer_id' => $customer_id,
                'user_id' => Auth::user()->id,
                'status' => $this->getOrderStatus($request->amount_paid, $cart->getTotalCart()->totalAfterDiscount),
                'order_date' => now()->toDateString(),
                'quantity'=> $cart->getTotalCart()->quantity,
                'discount' => $cart->getTotalCart()->discount,
                'tax' => $cart->getTotalCart()->tax,
                'total_before_tax'=> $cart->getTotalCart()->subTotal,
                'total' => $cart->getTotalCart()->total,
                'total_after_discount'=> $cart->getTotalCart()->totalAfterDiscount,
                'note'=> $request->note,
            ];

            $order = Order::updateOrCreate(
                ['id' => $cart->order->id ?? null],
                $orderData
            );

            if (!$order->invoice_number) {
                $order->invoice_number = "BAC-" . Carbon::parse($order->order_date)->format('Ymd') . "-" . $order->id;
                $order->save();
            }

            // Delete existing order details
            OrderDetail::where('order_id', $order->id)->delete();

            foreach ($cart->cartItems as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->id > 0 ? $cartItem->id : null,
                    'product_name' => $cartItem->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'discount' => $cartItem->discount,
                    'tax_rate'=> $cartItem->taxRate,
                    'tax' => $cartItem->tax,
                    'total' => $cartItem->total,
                    'total_before_tax' => $cartItem->subTotal,
                    'total_after_discount' => $cartItem->totalAfterDiscount
                ]);

                $product = Product::find($cartItem->id);
                if($product && $product->stockable) {
                    $product->quantity -= $cartItem->quantity;
                    $product->save();
                }
            }

            // Delete existing order payments if any
            $order->orderPayments()->delete();

            // Save the payment
            $order->orderPayments()->create([
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'amount_due' => $cart->getTotalCart()->totalAfterDiscount - $request->amount_paid,
                'payment_status' => $this->getPaymentStatus($request->amount_paid, $cart->getTotalCart()->totalAfterDiscount)
            ]);
            

            // Delete existing customer account transaction if any
            $order->customerAccountTransactions()->delete();

            // Deduct the amount from the customer's credit
            if ($request->payment_method == PaymentMethods::CUSTOMER_ACCOUNT->value) {
                $customer = Customer::find($customer_id);

                if($customer->customerAccounts()->count() == 0) {
                    $customer->customerAccounts()->create([
                        'description' => 'Customer account for ' . $customer->name
                    ]);
                }

                $previousBalance = $customer->customerAccountTransactions()->latest()->first()->balance ?? 0;
                
                // Deduct the amount from the customer's credit
                CustomerAccountTransaction::create([
                    'customer_account_id'=> $customer->customerAccounts()->first()->id,
                    'customer_id' => $customer_id,
                    'order_id' => $order->id,
                    'note' => 'Purchage - ' . $order->invoice_number,
                    'credit_amount' => $order->total_after_discount,
                    'balance' => $previousBalance - $order->total_after_discount
                ]);

            }

            DB::commit();

            // Clear the cart
            CartService::clearCart();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => 'Order has been placed successfully.',
                    'payment_method' => $request->payment_method,
                    'cart' => CartService::getCart(),
                    'order_id' => $order->id,
                    'customer_id' => $customer_id
                ]);
            }

            return redirect()->route('pos.index')->with('success', 'Order has been placed successfully.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Something went wrong. Please try again. ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('pos.index')->with('error', 'Failed to place order. Please try again.');
        }
        
    }

    private function getOrderStatus($paidAmount, $totalAmount) {
        if ($paidAmount >= $totalAmount) {
            return OrderStatus::PAID;
        } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
            return OrderStatus::PARTIAL;
        } else {
            return OrderStatus::DUE;
        }
    }

    private function getPaymentStatus($paidAmount, $totalAmount) {
        if ($paidAmount >= $totalAmount) {
            return PaymentStatus::PAID;
        } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
            return PaymentStatus::PARTIAL;
        } else {
            return PaymentStatus::DUE;
        }
    }

    public function save(Request $request) {

        $cart = CartService::getCart();
        $customer_id = $cart->customer->id ?? null;

        Log::info('PosController save method called by user: ' . Auth::id());

        DB::beginTransaction();
        try {

            $orderData = [
                'customer_id' => $customer_id,
                'user_id' => Auth::user()->id,
                'status' => OrderStatus::LAYAWAY,
                'order_date' => now()->toDateString(),
                'quantity'=> $cart->getTotalCart()->quantity,
                'discount' => 0,
                'tax' => $cart->getTotalCart()->tax,
                'total_before_tax'=> $cart->getTotalCart()->subTotal,
                'total' => $cart->getTotalCart()->total,
                'total_after_discount'=> $cart->getTotalCart()->totalAfterDiscount,
            ];

            $order = Order::updateOrCreate(
                ['id' => $cart->order->id ?? null],
                $orderData
            );

            if (!$order->invoice_number) {
                $order->invoice_number = "BAC-" . Carbon::parse($order->order_date)->format('Ymd') . "-" . $order->id;
                $order->save();
            }

            // Delete existing order details
            OrderDetail::where('order_id', $order->id)->delete();

            foreach ($cart->cartItems as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->id > 0 ? $cartItem->id : null,
                    'product_name' => $cartItem->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'discount' => $cartItem->discount,
                    'tax_rate'=> $cartItem->taxRate,
                    'tax' => $cartItem->tax,
                    'total' => $cartItem->total,
                    'total_before_tax' => $cartItem->subTotal,
                    'total_after_discount' => $cartItem->totalAfterDiscount
                ]);
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
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Something went wrong. Please try again.'
                ], 500);
            }
            return redirect()->route('pos.index')->with('error', 'Failed to place order. Please try again.');
        }
        
    }
}
