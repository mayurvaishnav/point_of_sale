import express from 'express';
import cors from 'cors';
import bodyParser from 'body-parser';
import fs from 'fs';
import { exec } from 'child_process';
import pkg from 'node-thermal-printer';
import printer from 'pdf-to-printer';

const app = express();
const port = 3000;
const { ThermalPrinter, PrinterTypes } = pkg;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Health check endpoint
app.get('/health-check', (req, res) => {
    res.status(200).json({ message: 'Print server is running!' });
});

// Endpoint to list all available printers
app.get('/printers', async (req, res) => {
    try {
        const printers = await printer.getPrinters();
        res.json({ printers });
    } catch (error) {
        console.error('Error fetching printers:', error);
        res.status(500).json({ error: 'Failed to retrieve printer list: ' + error });
    }
});

// Thermal receipt printing
app.post('/print/receipt', async (req, res) => {
    try {
        const { printerName, printData, order } = req.body;
        if (!printerName || !printData) {
            return res.status(400).json({ error: 'printerName or printData is required' });
        }

        let newPrinter = new ThermalPrinter({
            type: PrinterTypes.EPSON, // Printer type: 'star' or 'epson'
            interface: printerName,
            characterSet: 'PC437_USA',
            width: 41,
            lineCharacter: "-",
            options: {
                timeout: 5000 // Connection timeout (ms)
            }
        });

        const formattedDate = new Date(order.created_at).toLocaleString("en-GB", { 
            day: "2-digit", month: "2-digit", year: "numeric", 
            hour: "2-digit", minute: "2-digit", hour12: true 
        });

        // await newPrinter.init(); // Initialize the printer

        // Center header text
        newPrinter.alignCenter();
        newPrinter.println("Bowes Tyres and Auto Centre");
        newPrinter.println("Timahoe Road, Portlaoise");
        newPrinter.println("Phone: 057 8665075");
        newPrinter.println("VAT No: IE397032GH");
        newPrinter.drawLine(); // Prints a separator line

        // Align text to left for receipt details
        newPrinter.alignLeft();
        newPrinter.println(`Receipt No: ${order.invoice_number}`);
        newPrinter.println(`Status: ${order.status}`);
        newPrinter.println(`Date: ${formattedDate}`);
        newPrinter.println(`Customer: ${order.customer ? order.customer.name : "Walk-in Customer"}`);
        newPrinter.drawLine();

        // Print items table with fixed-width formatting
        newPrinter.tableCustom([
            { text: "Item", align: "LEFT", width: 0.7 },
            { text: "Total", align: "RIGHT", width: 0.3 }
        ]);
        newPrinter.drawLine();

        order.order_details.forEach(detail => {
            newPrinter.tableCustom([
                { text: `${detail.product_name} x${detail.quantity}`, align: "LEFT", width: 0.7 },
                { text: `${parseFloat(detail.total).toFixed(2)}`, align: "RIGHT", width: 0.3 }
            ]);
        });

        newPrinter.drawLine();

        // Print totals
        newPrinter.tableCustom([
            { text: "Subtotal:", align: "RIGHT", width: 0.7 },
            { text: parseFloat(order.total_before_tax).toFixed(2), align: "RIGHT", width: 0.3 }
        ]);
        newPrinter.tableCustom([
            { text: "VAT:", align: "RIGHT", width: 0.7 },
            { text: parseFloat(order.tax).toFixed(2), align: "RIGHT", width: 0.3 }
        ]);

        if (order.discount != 0) {
            newPrinter.tableCustom([
                { text: "Discount:", align: "RIGHT", width: 0.7 },
                { text: `-${parseFloat(order.discount).toFixed(2)}`, align: "RIGHT", width: 0.3 }
            ]);
        }

        newPrinter.tableCustom([
            { text: "Total:", align: "RIGHT", width: 0.7 },
            { text: parseFloat(order.total_after_discount).toFixed(2), align: "RIGHT", width: 0.3 }
        ]);

        newPrinter.drawLine();

        // Print footer
        newPrinter.alignCenter();
        newPrinter.println("Thank you for your purchase!");
        newPrinter.drawLine();
        newPrinter.println("Terms & Conditions");
        newPrinter.println("No refund without a valid receipt");
        newPrinter.println("Please retain this receipt as proof of purchase");
        newPrinter.println("\n");
        newPrinter.println("\n");

        await newPrinter.execute(); // Send print job to printer
        // await newPrinter.cut(); // Cut paper if supported
        await newPrinter.raw(Buffer.from([0x1D, 0x56, 0x00])); // ESC/POS cut command

        // await newPrinter.openCashDrawer();
        await newPrinter.raw(Buffer.from([0x1B, 0x70, 0x00, 0x19, 0xFA]));

        res.json({ message: 'Receipt printed successfully!' });
    } catch (error) {
        console.error('Receipt Print Error:', error);
        res.status(500).json({ error: 'Failed to print receipt: ' + error });
    }
});



// Thermal receipt printing
app.post('/open-cash-drawer', async (req, res) => {
    try {
        const { printerName, printData, order } = req.body;

        let newPrinter = new ThermalPrinter({
            type: PrinterTypes.EPSON,
            interface: printerName,
            characterSet: 'PC437_USA',
            lineCharacter: "-",
            options: {
                timeout: 5000 // Connection timeout (ms)
            }
        });

        // await newPrinter.openCashDrawer(); // Open cash drawer
        await newPrinter.raw(Buffer.from([0x1B, 0x70, 0x00, 0x19, 0xFA]));

        res.json({ message: 'Cash drawer opned successfully!' });
    } catch (error) {
        console.error('Receipt Print Error:', error);
        res.status(500).json({ error: 'Failed to print receipt: ' + error });
    }
});

// A4 PDF Printing
app.post("/print/a4", async (req, res) => {
    try {
        const { printerName, pdfBase64 } = req.body;
        if (!pdfBase64) {
            return res.status(400).json({ error: "No PDF data received" });
        }

        const pdfBuffer = Buffer.from(pdfBase64, "base64");
        const tempFilePath = "C:/temp/print.pdf"; // Use "C:/temp/print.pdf" on Windows "/tmp/print-input.pdf" for Mac/Linux

        fs.writeFileSync(tempFilePath, pdfBuffer);

        const options = {
            printer: printerName,  // Printer name received from request
            silent: true,         // If you want to hide the print dialog, set to true
            landscape: false,      // Set to true if the page should be in landscape
            color: false,          // Set to true for color printing
            fit: true,             // Fit the page to the paper size
        };
        
        await printer.print(tempFilePath, options);

        res.json({ message: "Printed A4 successfully" });

        // Cleanup after 50 seconds
        setTimeout(() => fs.unlinkSync(tempFilePath), 50000);
    } catch (error) {
        console.error("A4 Print Error:", error);
        res.status(500).json({ error: "Failed to print A4: " + error });
    }
});

// Start server
app.listen(port, () => {
    console.log(`Print server is running on http://localhost:${port}`);
});
