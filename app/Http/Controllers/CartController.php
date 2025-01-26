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

        $cartItem = new CartItem();
        $cartItem->id = $product->id;
        $cartItem->name = $product->name;
        $cartItem->quantity = 1;
        $cartItem->price = $product->selling_price;
        $cartItem->taxRate = $product->tax_rate;
        $cartItem->tax = $product->tax;
        $cartItem->discount = $product->discount;
        $cartItem->total = $product->selling_price;

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
