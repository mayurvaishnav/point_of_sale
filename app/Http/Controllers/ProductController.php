<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:product-list', ['only' => ['index']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('ProductController index method called by user: ' . Auth::id());
        return view("products.index", [
            "products" => Product::with(['category', 'supplier'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info('ProductController create method called by user: ' . Auth::id());
        $categories = Category::all();
        $suppliers = Supplier::all();
        $taxRates = TaxRate::all();

        return view('products.create', compact('categories', 'suppliers', 'taxRates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'price'=> 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'tax_included'=> 'required|boolean',
            'stockable'=> 'required|boolean',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'code' => 'nullable|string|max:255',
            'garage' => 'nullable|string|max:255',
            'store'=> 'nullable|string|max:255',
            'buying_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => 'nullable|integer|min:0',
            'brand'=> 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'auto_order_at_low_stock'=> 'nullable|boolean',
            'low_stock_threshold'=> 'nullable|integer|min:0',
        ];

        $validatedData = $request->validate($rules);

        Log::info('ProductController store method called by user: ' . Auth::id() . ' with parameters: ' . json_encode($validatedData));

        $product = Product::create($validatedData);

        if (!$product) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating product.');
        }
        return redirect()->route('products.index')->with('success', 'Product have been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Log::info('ProductController edit method called by user: ' . Auth::id() . ' for product ID: ' . $product->id);
        $categories = Category::all();
        $suppliers = Supplier::all();
        $taxRates = TaxRate::all();

        return view('products.edit', compact('product','categories','suppliers', 'taxRates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'price'=> 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'tax_included'=> 'required|boolean',
            'stockable'=> 'required|boolean',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'code' => 'nullable|string|max:255',
            'garage' => 'nullable|string|max:255',
            'store'=> 'nullable|string|max:255',
            'buying_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => 'nullable|integer|min:0',
            'brand'=> 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'auto_order_at_low_stock'=> 'nullable|boolean',
            'low_stock_threshold'=> 'nullable|integer|min:0',
        ];

        $validatedData = $request->validate($rules);

        Log::info('ProductController update method called by user: ' . Auth::id() . ' for product ID: ' . $product->id . ' with parameters: ' . json_encode($validatedData));

        Product::where('id', $product->id)->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Product have been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Log::info('ProductController destroy method called by user: ' . Auth::id() . ' for product ID: ' . $product->id);
        Product::destroy($product->id);

        return redirect()->route('products.index')->with('success', 'Product have been deleted!');
    }
}
