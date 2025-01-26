<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Product;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $cartItems = Cart::getCart();


        return view("pos.index", compact("products","cartItems"));
    }

    public function pay(Request $request) {
        $cart = Cart::getCart();
    }
}
