<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CustomerCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get customers who have credits
        $customers = Customer::whereHas('customerCredits')
            ->with(['customerCredits' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        return view('customer_credits.index', compact('customers'));
    }

    /**
     * Display the listing of all the credit history.
     */
    public function details(Customer $customer)
    {
        $customerCredits = $customer->customerCredits()->with('order')->latest()->get();

        return view('customer_credits.details', compact('customer', 'customerCredits'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function addPayment(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        $previousBalance = $customer->customerCredits()->latest()->first()->balance ?? 0;

        $customer->customerCredits()->create([
            'paid_amount' => $request->amount,
            'note' => $request->note,
            'balance' => $previousBalance + $request->amount,
        ]);

        return Redirect::back()->with('success', 'Paymnet added successfully for ' . $customer->name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deletePayment(Customer $customer, CustomerCredit $customerCredit)
    {
        if ($customerCredit->paid_amount != null && $customer->customerCredits()->latest()->first() != $customerCredit) {
            return redirect()
                ->route('customer-credits.details', $customer)
                ->with('error', 'You can only delete the last payment added');
        }

        CustomerCredit::destroy($customerCredit->id);

        return redirect()->route('customer-credits.details', $customer)->with('success', 'Payment have been deleted!');
    }
}
