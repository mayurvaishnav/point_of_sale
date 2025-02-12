<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:stock-management-list', ['only' => ['index']]);
         $this->middleware('permission:stock-management-add-sctock', ['only' => ['add']]);
         $this->middleware('permission:stock-management-adject', ['only' => ['adject']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('StockManagementController index method called by user: ' . Auth::id());
        $products = Product::where('stockable', true)->with(['category', 'supplier'])->get();
        return view("stocks.index", compact("products"));
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

        Log::info('StockManagementController add method called by user: ' . Auth::id() . 
            ' for product ID: ' . $productId . ' with paramaters: ' . json_encode($validatedData));

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

        Log::info('StockManagementController adject method called by user: ' . Auth::id() . 
            ' for product ID: ' . $productId . ' with paramaters: ' . json_encode($validatedData));

        $product = Product::find($productId);
        $product->quantity = $validatedData['quantity'];
        $product->save();
        
        return redirect()->route('stocks.index')->with('success', 'Stock succesfully adjected for Product: ' . $product->name);
    }
}
