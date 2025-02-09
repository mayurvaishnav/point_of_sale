<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartService;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $cart = CartService::getCart();
        $cartItem = null;

        if ($request->product_id != null) {
            $product = Product::with('taxRate')->find($request->product_id);
            $requestedQuantity = ($cart->cartItems[$request->product_id]->quantity ?? 0 ) + 1;
            // dd($requestedQuantity);

            if ($product->stockable == true && ($product->quantity < 1 || $product->quantity < $requestedQuantity)) {
                return response()->json(['errors' => ['quantity' => ["Product {$product->name} has only {$product->quantity} in stock"]]], 422);
            }

            $cartItem = new CartItem(
                $product->id,
                $product->name,
                1,
                $product->selling_price,
                0,
                $product->taxRate->value,
            );
        } else {
            $keys = array_keys($cart->cartItems);
            $id = !empty($keys) ? min($keys) : 0;
            $id = $id >= 0 ? 0 : $id;
            $cartItem = new CartItem(
                $id - 1,
                $request->product_name,
                1,
                $request->customer_price,
                0,
                $request->tax_rate,
            );
        }

        CartService::addCartItem($cart->customer , $cart->order, [$cartItem]);

        if ($request->wantsJson()) {
            // dd(CartService::getCart());
            return response()->json(CartService::getCart());
        }

        return redirect()->route('pos.index');
    }

    public function updateCustomer(Request $request)
    {
        $id = $request->customer_id;

        $customer = Customer::find($id);

        CartService::updateCustomer($customer);

        return response()->json(CartService::getCart());
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price'=> 'required|numeric|min:0',
            'name'=> 'required|string',
        ]);
        $id = $request->item_id;
        $quantity = $request->quantity;
        $price = $request->price;
        $name = $request->name;

        CartService::update($id, $name, $quantity, $price);

        return response()->json(CartService::getCart());
    }

    public function removeFromCart(Request $request)
    {
        $id = $request->cart_item_id;
        CartService::removeItem($id);

        return response()->json(CartService::getCart());
    }

    public function empty()
    {
        CartService::clearCart();

        return response()->json(CartService::getCart());
    }
}
