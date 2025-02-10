<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\UsbPrintConnector;
use Mike42\Escpos\PrintConnectors\SerialPrintConnector;
use Mike42\Escpos\PrintConnectors\BluetoothPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

class PrintController extends Controller
{
    public function receipt($orderId)
    {
        $order = Order::with(['customer', 'orderDetails', 'orderpayments'])->find($orderId);

        // return view('orders.receipt', compact('order'));

        dd($this->formatReceiptText($order));

        try {
            $connector = new DummyPrintConnector();
            // $connector = new WindowsPrintConnector("POS-80");
            // $connector = new NetworkPrintConnector("192.168.1.100", 9100);
            $printer = new Printer($connector);

            $printer->text($this->formatReceiptText($order));

            $printer->cut();
            $printer->close();

        } catch (Exception $e) {

            // if
            return response()->json(['error' => 'Printing failed: ' . $e->getMessage()]);
        }

        return view('orders.receipt', compact('order'));
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
        $receiptText .= "\n--------------------------------\n";

        // Receipt info
        $receiptText .= str_pad("Receipt No: " . $order->invoice_number, 40, " ", STR_PAD_RIGHT) . "\n";
        $receiptText .= str_pad("Date: " . $order->created_at->format('d-m-Y h:i A'), 40, " ", STR_PAD_RIGHT) . "\n";
        $receiptText .= str_pad("Customer: " . ($order->customer ? $order->customer->name : "Walk-in Customer"), 40, " ", STR_PAD_RIGHT) . "\n";
        $receiptText .= "\n--------------------------------\n";

        // Item table headers
        $receiptText .= str_pad("Item", 25, " ", STR_PAD_RIGHT);
        $receiptText .= str_pad("Total", 15, " ", STR_PAD_LEFT) . "\n";
        $receiptText .= "--------------------------------\n";

        // Order details
        foreach ($order->orderDetails as $detail) {
            $receiptText .= str_pad($detail->product_name . " x" . $detail->quantity, 25, " ", STR_PAD_RIGHT);
            $receiptText .= str_pad(config('app.currency_symbol') . number_format($detail->total, 2), 15, " ", STR_PAD_LEFT) . "\n";
        }

        $receiptText .= "--------------------------------\n";

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
        $receiptText .= "--------------------------------\n";

        // Footer
        $receiptText .= "\nThank you for your purchase!\n";
        $receiptText .= "--------------------------------\n";
        $receiptText .= str_pad("Terms & Conditions", 40, " ", STR_PAD_BOTH) . "\n";
        $receiptText .= "No refund without a valid receipt\n";
        $receiptText .= "Please retain this receipt as proof of\n";
        $receiptText .= "purchase\n";

        return $receiptText;
    }

    // switch ($connectionType) {
    //     case 'usb':
    //         $connector = new UsbPrintConnector(0x1234, 0x5678);  // Vendor ID and Product ID for USB
    //         break;
    //     case 'network':
    //         $connector = new NetworkPrintConnector("192.168.1.100", 9100);  // IP and port for network
    //         break;
    //     case 'serial':
    //         $connector = new SerialPrintConnector("/dev/ttyS0");  // Serial port
    //         break;
    //     case 'bluetooth':
    //         $connector = new BluetoothPrintConnector("00:11:22:33:44:55");  // Bluetooth MAC address
    //         break;
    //     default:
    //         $connector = new DummyPrintConnector();  // For testing or placeholder
    //         break;
    // }
}
