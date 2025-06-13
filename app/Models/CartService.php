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
        $cart->refreshTotals();
        return $cart;
    }

    public static function addCartItem(?Customer $customer, ?Order $order, array $cartItems) {
        $cart = self::getCart();

        $cart->update($customer, $order, $cartItems);

        session()->put('cart', $cart);
    }

    public static function updateCustomer($customer) {
        $cart = self::getCart();
        $cart->customer = $customer;
        session()->put('cart', $cart);
    }

    public static function updateOrderNote($orderNote) {
        $cart = self::getCart();
        $cart->orderNote = $orderNote;
        session()->put('cart', $cart);
    }

    public static function updateDiscount($discount) {
        $cart = self::getCart();
        $cart->discount = $discount;
        session()->put('cart', $cart);
    }

    public static function update($itemId, $name, $quantity, $price) {
        $cart = self::getCart();
        $cartItem = new CartItem($itemId, $name, $quantity, $price);
        $cart->updateItem($itemId, $cartItem);
        session()->put('cart', $cart);
    }

    public static function updatePrice($itemId, $price) {
        $cart = self::getCart();
        if (isset($cart[$itemId])) {
            $cart[$itemId]->price = $price;
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