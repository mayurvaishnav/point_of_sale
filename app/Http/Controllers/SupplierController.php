<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("suppliers.index", [
            "suppliers" => Supplier::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:suppliers,email',
            'phone' => 'required|string|max:15|unique:suppliers,phone',
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        $supplier = Supplier::create($validatedData);

        if (!$supplier) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating supplier.');
        }
        return redirect()->route('suppliers.index')->with('success', 'Supplier have been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:suppliers,email,'.$supplier->id,
            'phone' => 'required|string|max:15|unique:suppliers,phone,'.$supplier->id,
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        Supplier::where('id', $supplier->id)->update($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Supplier have been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        Supplier::destroy($supplier->id);

        return redirect()->route('suppliers.index')->with('success', 'Supplier have been deleted!');
    }
}
