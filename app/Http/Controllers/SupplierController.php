<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:supplier-list', ['only' => ['index']]);
         $this->middleware('permission:supplier-create', ['only' => ['create','store']]);
         $this->middleware('permission:supplier-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('SupplierController index method called by user: ' . Auth::id());
        return view("suppliers.index", [
            "suppliers" => Supplier::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info('SupplierController create method called by user: ' . Auth::id());
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
            'phone' => 'required|string|max:20|unique:suppliers,phone',
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        Log::info('SupplierController store method called by user: ' . Auth::id() . ' with parameters: ' . json_encode($validatedData));

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
        Log::info('SupplierController edit method called by user: ' . Auth::id() . ' for supplier ID: ' . $supplier->id);
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
            'phone' => 'required|string|max:20|unique:suppliers,phone,'.$supplier->id,
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        Log::info('SupplierController update method called by user: ' . Auth::id() . 
            ' for supplier ID: ' . $supplier->id . 'with parameters: ' . json_encode($validatedData));

        Supplier::where('id', $supplier->id)->update($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Supplier have been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        Log::info('SupplierController delete method called by user: ' . Auth::id() . ' for supplier ID: ' . $supplier->id);
        Supplier::destroy($supplier->id);

        return redirect()->route('suppliers.index')->with('success', 'Supplier have been deleted!');
    }
}
