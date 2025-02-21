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
        // $currencySymbol = config('app.currency_symbol');
        $receiptText = "";

        // $receiptText .= "\n\n"; // Add some space before printing

        // Store header info
        $receiptText .= sprintf("%-42s\n", "Bowes Tyres and Auto Centre"); // Center alignment using padding
        $receiptText .= sprintf("%-42s\n", "Timahoe Road, Portlaoise");
        $receiptText .= sprintf("%-42s\n", "Phone: 057 8665075");
        $receiptText .= sprintf("%-42s\n", "VAT No: IE397032GH");
        $receiptText .= "------------------------------------------\n";

        // Receipt info
        $receiptText .= sprintf("%-20s %22s\n", "Receipt No: " . $order->invoice_number, "");
        $receiptText .= sprintf("%-20s %22s\n", "Status: " . $order->status->value, "");
        $receiptText .= sprintf("%-20s %22s\n", "Date: " . $order->created_at->format('d-m-Y h:i A'), "");
        $receiptText .= sprintf("%-20s %22s\n", "Customer: " . ($order->customer ? $order->customer->name : "Walk-in Customer"), "");
        $receiptText .= "------------------------------------------\n";

        // Item table headers
        // $receiptText .= str_pad("Item", 25, " ", STR_PAD_RIGHT);
        // $receiptText .= str_pad("Total", 15, " ", STR_PAD_LEFT) . "";
        // $receiptText .= "\n------------------------------------------\n";

        // // Order details
        // foreach ($order->orderDetails as $detail) {
        //     $itemText = $detail->product_name . " x" . $detail->quantity;
        //     $totalText = number_format($detail->total, 2);
            
        //     // Split long item names into multiple lines of 25 characters
        //     $wrappedLines = str_split($itemText, 25);
        
        //     // Print first line with total aligned properly
        //     $receiptText .= sprintf("%-25s %15s", $wrappedLines[0], $totalText);
        
        //     // Print remaining lines, if any, but without a total to maintain alignment
        //     for ($i = 1; $i < count($wrappedLines); $i++) {
        //         $receiptText .= sprintf("%-25s", $wrappedLines[$i]);
        //     }
        // }

        // $receiptText .= "\n------------------------------------------\n";

        // // Totals
        // $receiptText .= sprintf("%25s %15s", "Subtotal:", number_format($order->total_before_tax, 2));
        // $receiptText .= sprintf("%25s %15s", "Vat:", number_format($order->tax, 2));

        // if ($order->discount != 0) {
        //     $receiptText .= sprintf("%25s %15s", "Discount:", number_format($order->discount, 2));
        // }
        
        // $receiptText .= sprintf("%25s %15s", "Total:", number_format($order->total_after_discount, 2));
        // $receiptText .= "\n------------------------------------------\n";

        // Footer
        $receiptText .= "Thank you for your purchase!\n";
        $receiptText .= "------------------------------------------\n";
        $receiptText .= sprintf("%-42s\n", "Terms & Conditions");
        $receiptText .= "No refund without a valid receipt\n";
        $receiptText .= "Please retain this receipt as proof of\n";
        $receiptText .= "purchase\n\n\n";

        return $receiptText;
    }
}
