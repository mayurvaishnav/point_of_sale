<?php

namespace App\Http\Controllers;

use App\Mail\CustomerAccountStatementMail;
use App\Models\CustomerAccount;
use App\Models\CustomerAccountTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        Log::info("CustomerAccountController index method called by user: ". Auth::id());
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
        Log::info("CustomerAccountController details method called by user: ". Auth::id() . " for customerAccount Id:" . $customerAccount->id);
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

        Log::info("CustomerAccountController addPayment method called by user: ". Auth::id() . " for customerAccount Id:" . $customerAccount->id . " with parameters:" . json_encode($request->all()));

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
     * Store a newly created payment in storage.
     */
    public function sendEmail(Request $request, CustomerAccount $customerAccount)
    {
        $request->validate([
            // 'from_last_payment' => 'nullable|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        Log::info("CustomerAccountController sendEmail method called by user: ". Auth::id() . " for customerAccount Id:" . $customerAccount->id . " with parameters:" . json_encode($request->all()));

        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $customerAccount->load(['customer', 'transactions' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $query->orderBy('created_at', 'asc');
        }]);

        Mail::to($customerAccount->customer->email)
            ->send(new CustomerAccountStatementMail($customerAccount));

        // return view('customer_accounts.account-statement', compact('customerAccount'));


        return Redirect::back()->with('success', 'Paymnet added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deletePayment(CustomerAccount $customerAccount, CustomerAccountTransaction $customerAccountTransaction)
    {
        Log::info("CustomerAccountController deletePayment method called by user: ". Auth::id() . " for customerAccount Id:" . $customerAccount->id);
        if ($customerAccountTransaction->paid_amount != null && $customerAccount->transactions()->latest()->first() != $customerAccountTransaction) {
            return redirect()
                ->route('customer-accounts.details', $customerAccount)
                ->with('error', 'You can only delete the last payment added');
        }

        CustomerAccountTransaction::destroy($customerAccountTransaction->id);

        return redirect()->route('customer-accounts.details', $customerAccount)->with('success', 'Payment have been deleted!');
    }
}
