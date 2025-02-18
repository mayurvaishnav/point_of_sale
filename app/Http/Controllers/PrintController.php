<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrintController extends Controller
{
    public function receipt($orderId)
    {
        Log::info("PrintController receipt method called by user: " . Auth::id() . " for order ID: " . $orderId);
        $order = Order::with(['customer', 'orderDetails', 'orderpayments'])->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    
        $formattedReceipt = $this->formatReceiptText($order);
    
        return response()->json(['receipt' => $formattedReceipt]);
    }

    private function formatReceiptText($order)
    {
        $receiptText = "";

        $receiptText .= "\n\n"; // Add some space before printing

        // Store header info
        $receiptText .= str_pad("Bowes Tyres and Auto Centre", 40, " ", STR_PAD_BOTH) . "\n";
        $receiptText .= str_pad("Timahoe Road, Portlaoise", 40, " ", STR_PAD_BOTH) . "\n";
        $receiptText .= str_pad("Phone: 057-8665075", 40, " ", STR_PAD_BOTH) . "\n";
        $receiptText .= str_pad("VAT No: IE397032GH", 40, " ", STR_PAD_BOTH) . "\n";
        $receiptText .= "\n------------------------------------------\n";

        // Receipt info
        $receiptText .= str_pad("Receipt No: " . $order->invoice_number, 40, " ", STR_PAD_RIGHT) . "\n";
        $receiptText .= str_pad("Status: " . $order->status->value, 40, " ", STR_PAD_RIGHT) . "\n";
        $receiptText .= str_pad("Date: " . $order->created_at->format('d-m-Y h:i A'), 40, " ", STR_PAD_RIGHT) . "\n";
        $receiptText .= str_pad("Customer: " . ($order->customer ? $order->customer->name : "Walk-in Customer"), 40, " ", STR_PAD_RIGHT) . "\n";
        $receiptText .= "\n------------------------------------------\n";

        // Item table headers
        $receiptText .= str_pad("Item", 25, " ", STR_PAD_RIGHT);
        $receiptText .= str_pad("Total", 15, " ", STR_PAD_LEFT) . "\n";
        $receiptText .= "------------------------------------------\n";

        // Order details
        foreach ($order->orderDetails as $detail) {
            $receiptText .= str_pad($detail->product_name . " x" . $detail->quantity, 25, " ", STR_PAD_RIGHT);
            $receiptText .= str_pad(config('app.currency_symbol') . number_format($detail->total, 2), 15, " ", STR_PAD_LEFT) . "\n";
        }

        $receiptText .= "------------------------------------------\n";

        // Totals
        $receiptText .= str_pad("Subtotal:", 25, " ", STR_PAD_RIGHT);
        $receiptText .= str_pad(config('app.currency_symbol') . number_format($order->total_before_tax, 2), 15, " ", STR_PAD_LEFT) . "\n";
        $receiptText .= str_pad("Vat:", 25, " ", STR_PAD_RIGHT);
        $receiptText .= str_pad(config('app.currency_symbol') . number_format($order->tax, 2), 15, " ", STR_PAD_LEFT) . "\n";

        if ($order->discount != 0) {
            $receiptText .= str_pad("Discount:", 25, " ", STR_PAD_RIGHT);
            $receiptText .= str_pad("-" . config('app.currency_symbol') . number_format($order->discount, 2), 15, " ", STR_PAD_LEFT) . "\n";
        }

        $receiptText .= str_pad("Total:", 25, " ", STR_PAD_RIGHT);
        $receiptText .= str_pad(config('app.currency_symbol') . number_format($order->total_after_discount, 2), 15, " ", STR_PAD_LEFT) . "\n";
        $receiptText .= "------------------------------------------\n";

        // Footer
        $receiptText .= "\nThank you for your purchase!\n";
        $receiptText .= "------------------------------------------\n";
        $receiptText .= str_pad("Terms & Conditions", 40, " ", STR_PAD_BOTH) . "\n";
        $receiptText .= "No refund without a valid receipt\n";
        $receiptText .= "Please retain this receipt as proof of\n";
        $receiptText .= "purchase\n";

        return $receiptText;
    }
}
