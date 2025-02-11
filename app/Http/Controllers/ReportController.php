<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAccountTransaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:report-sales', ['only' => ['sales']]);
         $this->middleware('permission:report-customer', ['only' => ['customer']]);
    }
    public function sales(Request $request)
    {
        $startDate = now()->toDateString();
        $endDate = now()->toDateString();
        
        $request->validate([
            "start_date"=> "nullable|date",
            "end_date"=> "nullable|date",
        ]);
        
        if($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        }

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();


        $orders = Order::whereBetween('order_date', [$startDate, $endDate])
            ->with(['orderPayments', 'orderDetails.product.category'])->get();

        $total = [
            "total_before_tax" => formateCurrency($orders->sum('total_before_tax')),
            "total" => formateCurrency($orders->sum('total_after_discount')),
            "discount" => formateCurrency($orders->sum('discount')),
            "tax" => formateCurrency($orders->sum('tax')),
            "order_count" => $orders->count(),
        ];

        $paymentSummary = $orders->flatMap->orderPayments->groupBy('payment_method')->map(function($paymentsByMethod) {
            return [
                'payment_method'=> $paymentsByMethod->first()->payment_method,
                "total" => formateCurrency($paymentsByMethod->sum('amount_paid')),
                "count" => $paymentsByMethod->count(),
            ];
        });

        $productSummary = $orders->flatMap->orderDetails->groupBy('product_name')->map(function($productByName) {
            return [
                'name'=> $productByName->first()->product_name,
                'quantity' => $productByName->sum('quantity'),
                'tax' => formateCurrency($productByName->sum('tax')),
                'total' => formateCurrency($productByName->sum('total_after_discount')),
            ];
        });

        $taxSummary = $orders->flatMap->orderDetails->groupBy('tax_rate')->map(function($productByTax) {
            return [
                'tax_rate'=> $productByTax->first()->tax_rate . '% VAT',
                'quantity' => $productByTax->sum('quantity'),
                'total' => formateCurrency($productByTax->sum('total_after_discount')),
            ];
        });

        $customerAccountSummary = CustomerAccountTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('paid_amount')
            ->with('customer')
            ->get()
            ->groupBy('customer_id')
            ->map(function($customerId) {
            return [
                'name' => $customerId->first()->customer->name,
                'total' => $customerId->sum('paid_amount'),
                'count' => $customerId->count(),
            ];
        });

        $categorySummary = $orders->flatMap->orderDetails->groupBy('product_id')->map(function($orderDetailsByProductId) {
            $product = $orderDetailsByProductId->first()->product;
            $categoryName = $product ? $product->category->name : 'No Category Selected';
        
            return [
                'name' => $categoryName,
                'quantity' => $orderDetailsByProductId->sum('quantity'),
                'total' => formateCurrency($orderDetailsByProductId->sum('total_after_discount')),
            ];
        })->values()->groupBy('name')->map(function($categoryGroup) {
            return [
                'name' => $categoryGroup->first()['name'],
                'quantity' => $categoryGroup->sum('quantity'),
                'total' => formateCurrency($categoryGroup->sum('total')),
            ];
        })->values();

        return view('reports.sales', compact(
            'total', 
            'paymentSummary',
            'productSummary',
            'customerAccountSummary',
            'taxSummary',
            'categorySummary',
            'startDate',
            'endDate',
        ));
    }
    
    public function customer(Request $request)
    {
        $request->validate([
            "start_date"=> "nullable|date",
            "end_date"=> "nullable|date",
            "customer_id"=> "nullable|exists:customers,id",
        ]);

        $customers = Customer::all();
        
        if(!$request->has(['start_date', 'end_date', 'customer_id'])) {
            return view('reports.customer', ['orders' => [], 'customers' => $customers]);
        }
        
        $currentCustomer = $customers->find($request->customer_id);

        $orders = Order::whereBetween('order_date', [$request->start_date, $request->end_date])
            ->where('customer_id', $request->customer_id)
            ->with(['customer'])
            ->get();
        
        return view('reports.customer', compact('orders', 'customers', 'currentCustomer'));
    }
}