<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if ($product->quantity < 1) {
            throw new \Exception("Product out of stock");
        }

        $cartItem = new CartItem();
        $cartItem->id = $product->id;
        $cartItem->name = $product->name;
        $cartItem->quantity = 1;
        $cartItem->price = $product->selling_price;
        $cartItem->taxRate = $product->tax;
        $cartItem->tax = $product->tax;

        Cart::add($cartItem);

        return redirect()->route('pos.index');
    }

    public function removeFromCart($id)
    {
        Cart::removeItem($id);

        return redirect()->route('pos.index');
    }

    public function empty()
    {
        Cart::clearCart();

        return redirect()->route('pos.index');
    }
}
