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
        // dd($request->all());
        $cart = CartService::getCart();
        $customer = Customer::find($request->customer_id);
        $cartItem = null;

        if ($request->product_id != null) {
            $product = Product::find($request->product_id);

            if ($product->quantity < 1) {
                throw new \Exception("Product out of stock");
            }

            $cartItem = new CartItem(
                $product->id,
                $product->name,
                1,
                $product->selling_price,
                0,
                $product->tax_rate,
            );
        } else {
            $keys = array_keys($cart->cartItems);
            $id = !empty($keys) ? min($keys) : 0;
            $cartItem = new CartItem(
                $id - 1,
                $request->product_name,
                1,
                $request->customer_price,
                0,
                $request->tax_rate,
            );
        }

        // dd($cartItem, $customer, $product);

        CartService::addCartItem($customer , null, [$cartItem]);

        if ($request->wantsJson()) {
            // dd(CartService::getCart());
            return response()->json(CartService::getCart());
        }

        return redirect()->route('pos.index');
    }

    public function updateQuantity(Request $request)
    {
        $id = $request->cart_item_id;
        $quantity = $request->quantity;

        CartService::updateQuantity($id, $quantity);

        return redirect()->route('pos.index');
    }

    public function updatePrice(Request $request)
    {
        $id = $request->cart_item_id;
        $price = $request->price;

        CartService::updatePrice($id, $price);

        return redirect()->route('pos.index');
    }

    public function removeFromCart(Request $request)
    {
        $id = $request->cart_item_id;
        CartService::removeItem($id);

        return redirect()->route('pos.index');
    }

    public function empty()
    {
        CartService::clearCart();

        return redirect()->route('pos.index');
    }
}
