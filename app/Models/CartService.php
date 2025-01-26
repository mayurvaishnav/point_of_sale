<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class CartService {

    private $cartName = "cart";


    public static function getCart() {
        $cart = session()->get('cart');
        if ($cart == null) {
            return new Cart();
        }
        return $cart;
    }

    public static function addCartItem(Customer $customer, ?int $order_id, array $cartItems) {
        $cart = self::getCart();

        $cart->update($customer, $order_id, $cartItems);

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

    public static function removeItem($id) {
        $cart = self::getCart();

        $cart->removeItem($id);
    }

    public static function clearCart() {
        session()->forget('cart');
    }


}