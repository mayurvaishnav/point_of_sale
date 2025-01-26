<?php
namespace App\Models;

use Illuminate\Support\Facades\Facade;

class CartItem extends Facade {
    
    /**
     * The rowID of the cart item.
     *
     * @var string
     */
    public $rowId;

    /**
     * The ID of the cart item.
     *
     * @var int|string
     */
    public $id;

    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $quantity;

    /**
     * The name of the cart item.
     *
     * @var string
     */
    public $name;

    /**
     * The price without TAX of the cart item.
     *
     * @var float
     */
    public $price;

    /**
     * The tax rate for the cart item.
     *
     * @var int|float
     */
    public $taxRate = 0;

    public $tax = 0;

    public $discount = 0;

    public $discountPercent = 0;


}