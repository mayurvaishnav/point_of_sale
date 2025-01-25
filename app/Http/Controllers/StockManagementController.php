<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class StockManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("stocks.index", [
            "products" => Product::with(['category', 'supplier'])->get()
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function add(Request $request, $productId)
    {
        $rules = [
            'buying_price' => 'numeric|min:0',
            'quantity' => 'integer|min:0',
        ];

        $validatedData = $request->validate($rules);

        $product = Product::find($productId);
        $product->quantity += $validatedData['quantity'];
        $product->buying_price = $validatedData['buying_price'];
        $product->save();

        return redirect()->route('stocks.index')->with('success', 'Stock succesfully add to Product: ' . $product->name);
    }
    /**
     * Display a listing of the resource.
     */
    public function adject(Request $request, $productId)
    {
        $rules = [
            'quantity' => 'integer|min:0',
        ];

        $validatedData = $request->validate($rules);

        $product = Product::find($productId);
        $product->quantity = $validatedData['quantity'];
        $product->save();
        
        return redirect()->route('stocks.index')->with('success', 'Stock succesfully adjected for Product: ' . $product->name);
    }
}
