<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("customers.index", [
            "customers" => Customer::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:customers,email',
            'phone' => 'required|string|max:15|unique:customers,phone',
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        $customer = Customer::create($validatedData);

        if (!$customer) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating customer.');
        }
        return redirect()->route('customers.index')->with('success', 'Customer have been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:customers,email,'.$customer->id,
            'phone' => 'required|string|max:15|unique:customers,phone,'.$customer->id,
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        Customer::where('id', $customer->id)->update($validatedData);

        return redirect()->route('customers.index')->with('success', 'Customer have been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        Customer::destroy($customer->id);

        return redirect()->route('customers.index')->with('success', 'Customer have been deleted!');
    }
}
