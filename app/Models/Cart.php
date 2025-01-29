<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade {
    
    public ?Customer $customer;

    public ?Order $order;

    /**
     * @var CartItem[]
     */
    public array $cartItems;
    public CartItem $total;

    public function __construct(?Customer $customer = null, ?Order $order = null, ?array $cartItems = null)
    {
        $this->customer = $customer;
        $this->order = $order;
        $this->cartItems = $cartItems ?? [];
    }

    public function update(?Customer $customer, ?Order $order, array $cartItems)
    {
        $this->customer = $customer;
        $this->order = $order;

        foreach ($cartItems as $cartItem) {
            if (isset($this->cartItems[$cartItem->id])) {
                // If the item is already in the cart, add the quantity
                $this->cartItems[$cartItem->id]->quantity += $cartItem->quantity;
            } else {
                // If the item is not in the cart, add it
                $this->cartItems[$cartItem->id] = $cartItem;
            }
        }

    }

    public function getTotal(): array
    {
        $cart = [];
        $cart['name'] = 'Total';
        $cart['total'] = 0;
        $cart['tax'] = 0;
        $cart['subTotal'] = 0;
        $cart['discount'] = 0;

        foreach ($this->cartItems as $cartItem) {
            $cart['total'] += $cartItem->total;
            $cart['tax'] += $cartItem->tax;
            $cart['subTotal'] += $cartItem->subTotal;
            $cart['discount'] += $cartItem->discount;
        }

        $cart['totalAfterDiscount'] = $cart['total'] - $cart['discount'];
        
        return $cart;
    }

    public function getTotalCart(): CartItem
    {
        $quantity = 0;
        $total = 0;
        $tax = 0;
        $subTotal = 0;
        $discount = 0;
        $totalAfterDiscount = 0;

        foreach ($this->cartItems as $cartItem) {
            $quantity += $cartItem->quantity;
            $total += $cartItem->total;
            $tax += $cartItem->tax;
            $subTotal += $cartItem->subTotal;
            $discount += $cartItem->discount;
            $totalAfterDiscount += $cartItem->totalAfterDiscount;
        }

        $cartItemTotal = new CartItem(
            null,
            'Total',
            $quantity,
            0,
            formateCurrency($discount),
            0,
            formateCurrency($total),
            formateCurrency($totalAfterDiscount),
            formateCurrency($tax),
            formateCurrency($subTotal)
        );
        
        return $cartItemTotal;
    }

    public function removeItem($id)
    {
        if (isset($this->cartItems[$id])) {
            unset($this->cartItems[$id]);
        }
    }

}