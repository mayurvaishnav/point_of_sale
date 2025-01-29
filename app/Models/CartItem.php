<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class CartItem {

    public ?int $id;
    public string $name;
    public int $quantity;
    public float $price;
    public float $taxRate;
    public float $discount;
    public float $total;
    public float $totalAfterDiscount;
    public float $tax;
    public float $subTotal;

    public function __construct(
        ?int $id, 
        ?string $name = '', 
        ?int $quantity = 1, 
        ?float $price = 0, 
        ?float $discount = 0, 
        ?float $taxRate = 0,
        ?float $total = null,
        ?float $totalAfterDiscount = null,
        ?float $tax = null,
        ?float $subTotal = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->discount = $discount;
        $this->taxRate = $taxRate;
        $this->total = $total ?? formateCurrency($this->price * $this->quantity);
        $this->totalAfterDiscount = $totalAfterDiscount ?? formateCurrency($this->total - $this->discount);
        $this->tax = $tax ?? ($this->totalAfterDiscount * formateCurrency($this->taxRate/100));
        $this->subTotal = $subTotal ?? formateCurrency($this->totalAfterDiscount - $this->tax);
    }

}