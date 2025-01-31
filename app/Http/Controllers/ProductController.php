<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("products.index", [
            "products" => Product::with(['category', 'supplier'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'code' => 'nullable|string|max:255',
            'garage' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store' => 'nullable|integer',
            'tax_rate' => 'nullable|integer',
            'buying_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        // dd($validatedData);

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
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('products.edit', compact('product','categories','suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'code' => 'nullable|string|max:255',
            'garage' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store' => 'nullable|integer',
            'tax_rate' => 'nullable|integer',
            'buying_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        Product::where('id', $product->id)->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Product have been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Product::destroy($product->id);

        return redirect()->route('products.index')->with('success', 'Product have been deleted!');
    }
}
