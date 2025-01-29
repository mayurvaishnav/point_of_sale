<?php

namespace App\Http\Controllers;

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
        $customer = Customer::find($request->customer_id);
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

        // dd($cartItem, $customer, $product);

        CartService::addCartItem($customer , null, [$cartItem]);

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
