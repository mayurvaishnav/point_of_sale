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
        const { printerName, printData } = req.body;
        if (!printerName || !printData) {
            return res.status(400).json({ error: 'printerName or printData is required' });
        }

        console.log(printerName, printData);

        let newPrinter = new ThermalPrinter({
            type: PrinterTypes.EPSON, // Printer type: 'star' or 'epson'
            interface: printerName, // Replace with your printer's IP and port
            characterSet: 'PC437_USA', // Printer character set
            lineCharacter: "-", // Set character for lines
            options: {
                timeout: 5000 // Connection timeout (ms)
            }
        });

        newPrinter.alignCenter();
        newPrinter.println(printData);

        let executed = await newPrinter.execute();
        res.json({ message: 'Receipt printed successfully!' });
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

        console.log("Printing A4 PDF on printer:", printerName);

        const pdfBuffer = Buffer.from(pdfBase64, "base64");
        const tempFilePath = "/tmp/print-input.pdf"; // Use "C:/temp/print.pdf" on Windows

        fs.writeFileSync(tempFilePath, pdfBuffer);
        console.log("PDF file written to disk: " + tempFilePath);

        // Define options for the printer
        const options = {
            printer: printerName,  // Printer name received from request
            silent: true,         // If you want to hide the print dialog, set to true
            landscape: false,      // Set to true if the page should be in landscape
            color: false,          // Set to true for color printing
            fit: true,             // Fit the page to the paper size
        };
        // await printer.print(tempFilePath, options);

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
