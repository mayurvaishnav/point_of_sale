<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade {
    
    public ?Customer $customer;

    public ?int $order_id;

    /**
     * @var CartItem[]
     */
    public array $cartItems;
    public CartItem $total;

    public function __construct(?Customer $customer = null, ?int $order_id = null, ?array $cartItems = null)
    {
        $this->customer = $customer;
        $this->order_id = $order_id;
        $this->cartItems = $cartItems ?? [];
    }

    public function update(Customer $customer, ?int $order_id, array $cartItems)
    {
        $this->customer = $customer;
        $this->order_id = $order_id;

        foreach ($cartItems as $cartItem) {
            if (isset($this->cartItems[$cartItem->id])) {
                // If the item is already in the cart, add the quantity
                $this->cartItems[$cartItem->id]->quantity += $cartItem->quantity;
                $this->cartItems[$cartItem->id]->tax += $cartItem->tax;
                $this->cartItems[$cartItem->id]->discount += $cartItem->discount;
                $this->cartItems[$cartItem->id]->total += $cartItem->total;
            } else {
                // If the item is not in the cart, add it
                $this->cartItems[$cartItem->id] = $cartItem;
            }
        }

        $this->total = $this->getTotal();
    }

    public function getTotal(): CartItem
    {
        $total = 0.0;
        $totalTax = 0.0;
        $totalDiscount = 0.0;

        foreach ($this->cartItems as $cartItem) {
            $total += $cartItem->total;
            $totalTax += $cartItem->tax * $cartItem->quantity;
            $totalDiscount += $cartItem->discount * $cartItem->quantity;
        }

        $cart = new CartItem();
        $cart->name = 'Total';
        $cart->total = $total;
        $cart->discount = $totalDiscount;
        $cart->tax = $totalTax;
        
        return $cart;
    }

    public function removeItem($id)
    {
        if (isset($this->cartItems[$id])) {
            unset($this->cartItems[$id]);
            $this->total = $this->getTotal();
        }
    }

}