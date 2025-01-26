<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class Cart {

    private $cartName = "cart";

    public static function getCart() {
        $cart = session()->get('cart');
        if (empty($cart)) {
            return [];
        }
        return $cart;
    }

    public static function add(CartItem $cartItem) {
        $cart = self::getCart();

        if (isset($cart[$cartItem->id])) {
            // If the item is already in the cart, add the quantity
            $cart[$cartItem->id]->quantity += $cartItem->quantity;
        } else {
            // If the item is not in the cart, add it
            $cart[$cartItem->id] = $cartItem;
        }
        session()->put('cart', $cart);
    }

    public function updateQuantity($itemId, $quantity) {
        $cart = self::getCart();
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
    }

    public function updateDiscount($itemId, $discount) {
        $cart = $this->getCart();
        if (isset($cart[$itemId])) {
            $cart[$itemId]['discount'] = $discount;
            session()->put('cart', $cart);
        }
    }

    public static function removeItem($itemId) {
        $cart = self::getCart();
        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session()->put('cart', $cart);
        }
    }

    public static function clearCart() {
        session()->forget('cart');
    }


}