<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class CartItem extends Facade {

    public $id;
    public $quantity;
    public $name;
    public $price;
    public $taxRate;
    public $tax;
    public $discount;
    public $discountPercent;
    Public $total;

}