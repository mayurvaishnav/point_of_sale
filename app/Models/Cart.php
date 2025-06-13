<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade {
    
    public ?Customer $customer;

    public ?Order $order;
    public ?string $orderNote;
    public ?float $discount = 0;

    /**
     * @var CartItem[]
     */
    public array $cartItems;
    public CartItem $total;

    public function __construct(?Customer $customer = null, ?Order $order = null, ?array $cartItems = null, ?float $discount = null, ?string $orderNote = null)
    {
        $this->customer = $customer;
        $this->order = $order;
        $this->cartItems = $cartItems ?? [];
        $this->discount = $discount ?? 0;
        $this->orderNote = $orderNote;
    }

    public function update(?Customer $customer, ?Order $order, array $cartItems)
    {
        $this->customer = $customer;
        $this->order = $order;

        foreach ($cartItems as $cartItem) {
            if (isset($this->cartItems[$cartItem->id])) {
                // If the item is already in the cart, add the quantity
                $this->cartItems[$cartItem->id]->quantity += $cartItem->quantity;
                $this->cartItems[$cartItem->id]->refreshTotals();
            } else {
                // If the item is not in the cart, add it
                $this->cartItems[$cartItem->id] = $cartItem;
            }
        }

    }

    public function updateItem($id, CartItem $cartItem)
    {
        if (isset($this->cartItems[$id])) {
            $this->cartItems[$id]->name = $cartItem->name;
            $this->cartItems[$id]->quantity = $cartItem->quantity;
            $this->cartItems[$id]->price = $cartItem->price;
            $this->cartItems[$id]->discount = $cartItem->discount;
            $this->cartItems[$id]->refreshTotals();
        }
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

        // dd(formateCurrency($total));

        $cartItemTotal = new CartItem(
            null,
            'Total',
            $quantity,
            0,
            formateCurrency($this->discount),
            0,
            formateCurrency($total),
            formateCurrency($totalAfterDiscount - $this->discount),
            formateCurrency($tax),
            formateCurrency($subTotal)
        );

        $this->total = $cartItemTotal;
        
        return $cartItemTotal;
    }

    public function refreshTotals()
    {
        $this->getTotalCart();
    }

    public function removeItem($id)
    {
        if (isset($this->cartItems[$id])) {
            unset($this->cartItems[$id]);
        }
    }

}