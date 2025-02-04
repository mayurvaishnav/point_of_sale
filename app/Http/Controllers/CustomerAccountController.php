<?php

namespace App\Http\Controllers;

use App\Models\CustomerAccount;
use App\Models\CustomerAccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CustomerAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:customer-account-list', ['only' => ['index']]);
         $this->middleware('permission:customer-account-details', ['only' => ['details']]);
         $this->middleware('permission:customer-account-add-payment', ['only' => ['addPayment']]);
         $this->middleware('permission:customer-account-delete-payment', ['only' => ['deletePayment']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerAccounts = CustomerAccount::with(['customer', 'transactions' => function ($query) {
            $query->latest()->limit(1);
        }])->get();

        return view('customer_accounts.index', compact('customerAccounts'));
    }

    /**
     * Display the listing of all the credit history.
     */
    public function details(CustomerAccount $customerAccount)
    {
        $customerAccount->load(['customer', 'transactions' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('customer_accounts.details', compact('customerAccount'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function addPayment(Request $request, CustomerAccount $customerAccount)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        $previousBalance = $customerAccount->transactions()->latest()->first()->balance ?? 0;

        $customerAccount->transactions()->create([
            'customer_id' => $customerAccount->customer_id,
            'paid_amount' => $request->amount,
            'note' => $request->note,
            'balance' => $previousBalance + $request->amount,
        ]);

        return Redirect::back()->with('success', 'Paymnet added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deletePayment(CustomerAccount $customerAccount, CustomerAccountTransaction $customerAccountTransaction)
    {
        if ($customerAccountTransaction->paid_amount != null && $customerAccount->transactions()->latest()->first() != $customerAccountTransaction) {
            return redirect()
                ->route('customer-accounts.details', $customerAccount)
                ->with('error', 'You can only delete the last payment added');
        }

        CustomerAccountTransaction::destroy($customerAccountTransaction->id);

        return redirect()->route('customer-accounts.details', $customerAccount)->with('success', 'Payment have been deleted!');
    }
}
