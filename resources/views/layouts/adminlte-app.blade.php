@extends('adminlte::page')

@section('adminlte_css_pre')
    {{-- @laravelPWA --}}
@endsection

@section('content_header')
    @yield('custom_content_header')
@stop

@section('css')
    @yield('custom_css')

    <style>
        .select2-container .select2-selection--single {
            padding-left: 10px !important;
            padding: 0;
            height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 10px !important;
            padding: 0;
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            padding-left: 10px !important;
            padding: 0;
            height: 38px;
        }

        .select2 {
            width: 100% !important;
        }
    </style>

    <script>

    
    </script>
@stop

@section('content')
    @include('layouts.alerts')
    @yield('custom_content')

@endsection

@section('js')
    
    <script src="{{ asset('js/qz-tray.js') }}"></script>

    <script>
        $(document).ready(function() {

            // Cashdrawer open button
            $('#openCashDrawer').on('click', function (e) { 
                e.preventDefault(); 
                console.log("Inside function open cash drawer...");
                let data = [
                    "\x1B\x40",  // Initialize printer
                    "\x1B\x70\x00\x19\xFA" // ESC/POS: Open cash drawer
                ];
                completeReciptPrintJob(data);
            });

            // Reload page button
            $('#reloadPageButton').on('click', function(e) {
                e.preventDefault();
                location.reload();
            });

            // Auto reload after 5 minutes of inactivity
            let inactivityTime = 5 * 60 * 1000; // 5 minutes
            let timeout;
            function resetTimer() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    location.reload();
                }, inactivityTime);
            }
            // Detect user activity (mouse move, key press, scroll, click)
            $(document).on('mousemove keydown scroll click', resetTimer);
            resetTimer();

            // DataTables
            $('.datatable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "lengthMenu": [10, 25, 50, 100, 200],
                "pageLength": 50,
                "order": []
            });
        });

        // QZ Tray setup and Print
        async function completePrintJob(printerName, printData) {
            try {
                // Ensure QZ Tray is connected
                if (!qz.websocket.isActive()) {
                    console.log("QZ Tray is not connected. Connecting...");
                    await qz.websocket.connect();
                    console.log("QZ Tray connected.");
                }

                // localhost:8181 QZ interface
                let config = qz.configs.create(printerName);

                await qz.print(config, printData);
                console.log("Data printed successfully!");
            } catch (err) {
                console.error("Error printing data:", err);
            }
        }

        // Print receipt data
        function completeReciptPrintJob(data) {
            // "SLK-T32EB"
            let printerName = "{{ env('THERMAL_PRINTER_NAME') }}"; // Printer name
            completePrintJob(printerName, data);
        }

        // Print A4: Invoice
        function completeA4PrintJob(data) {
            let printerName = "{{ env('A4_PRINTER_NAME') }}"; // Printer name
            completePrintJob(printerName, data);
        }

        // Print Receipt
        function printReceiptByOrderId(orderId) {

            $.ajax({
                url: "{{ route('print.receipt', '') }}/" + orderId,
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function (response) {
                    let receiptData = response.receipt.split("\n"); // Split by newline for line-by-line printing
                    receiptData.unshift("\x1B\x40", "\x1B\x70\x00\x19\xFA"); // Initialize & Open Cash Drawer
                    receiptData.push("\x1D\x56\x41\x03"); // Cut paper
                    completeReciptPrintJob(receiptData);
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong, Please refresh and try again from orders page.',
                    });
                }
            });
        }



        // Print A4 invoice
        function printA4ByOrderId(orderId) {
            const url = `{{ route('orders.downloadInvoice', ':orderId') }}`.replace(':orderId', orderId);
            $.ajax({
                url: url,
                method: 'GET',
                data: { _token: "{{ csrf_token() }}" },
                xhrFields: { responseType: 'blob' },
                success: function (response) {
                    // Convert the response into a Blob
                    var pdfBlob = new Blob([response], { type: 'application/pdf' });
                    // Send the PDF blob to the QZ Tray printer
                    completeA4PrintJob(pdfBlob);
                    console.log('PDF sent to printer successfully!');

                    // reload the page
                    // location.reload();
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong, Please try again printing from orders page.',
                    });
                }
            });
        }
    </script>
    @yield('custom_js')
@endsection