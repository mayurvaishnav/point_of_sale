<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:customer-list', ['only' => ['index']]);
         $this->middleware('permission:customer-create', ['only' => ['create','store']]);
         $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info("CustomerController index method called by user: ". Auth::id());
        return view("customers.index", [
            "customers" => Customer::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info("CustomerController create method called by user: ". Auth::id());
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
            'phone' => 'required|string|max:20|unique:customers,phone',
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'brand'=> 'nullable|string|max:50',
            'model'=> 'nullable|string|max:50',
            'registration_no'=> 'nullable|string|max:50',
        ];

        $validatedData = $request->validate($rules);

        Log::info("CustomerController store method called by user: ". Auth::id() . " with parameters:" . json_encode( $request->all()));

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
        Log::info("CustomerController edit method called by user: ". Auth::id() . " for customer Id:" . $customer->id);
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
            'phone' => 'required|string|max:20|unique:customers,phone,'.$customer->id,
            'address' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:100',
            'brand'=> 'nullable|string|max:50',
            'model'=> 'nullable|string|max:50',
            'registration_no'=> 'nullable|string|max:50',
        ];

        $validatedData = $request->validate($rules);

        Log::info("CustomerController update method called by user: ". Auth::id() . " for customer Id:" . $customer->id . " with parameters:" . json_encode( $request->all()));

        Customer::where('id', $customer->id)->update($validatedData);

        return redirect()->route('customers.index')->with('success', 'Customer have been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        Log::info("CustomerController destroy method called by user: ". Auth::id() . " for customer Id:" . $customer->id);
        Customer::destroy($customer->id);

        return redirect()->route('customers.index')->with('success', 'Customer have been deleted!');
    }
}
