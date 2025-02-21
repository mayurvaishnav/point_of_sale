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
    
    <script>
        $(document).ready(function() {

            // Cashdrawer open button
            $('#openCashDrawer').on('click', function (e) { 
                e.preventDefault(); 
                let data = [
                    "\x1B\x40",  // Initialize printer
                    "\x1B\x70\x00\x19\xFA" // ESC/POS: Open cash drawer
                ];
                $.ajax({
                    url: "http://localhost:3000/open-cash-drawer",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ 
                        printerName: "{{ env('THERMAL_PRINTER_NAME') }}",
                    }),
                    success: function (response) {
                        console.log(response.message);
                    },
                    error: function (error) {
                        console.error('Print error:', error);
                    }
                });
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

        async function completeReciptPrintJob(printData) {
            $.ajax({
                url: "http://localhost:3000/print/receipt",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({ 
                    printerName: "{{ env('THERMAL_PRINTER_NAME') }}",
                    printData: printData,
                    order: printData.order
                }),
                success: function (response) {
                    console.log(response.message);

                    // reload the page
                    location.reload();
                },
                error: function (error) {
                    console.error('Print error:', error);
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Error',
                    //     text: 'Failed to print. Please check the local print server.',
                    // });
                }
            });
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
                    receiptData.unshift("\x1B\x40"); // Initialize printer
                    receiptData.unshift("\x1B\x70\x00\x19\xFA"); // Open cash drawer
                    receiptData.push("\x1D\x56\x41\x03"); // Cut paper

                    const printDataString = receiptData.join("\n");
                    completeReciptPrintJob(response);
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

        function completeA4PrintJob(base64data) {
            // Send the Base64 PDF to the local print server
            $.ajax({
                url: "http://localhost:3000/print/a4",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({ 
                    printerName: "{{ env('A4_PRINTER_NAME') }}",
                    pdfBase64: base64data
                 }),
                 success: function (response) {
                    console.log(response.message);

                    // reload the page
                    location.reload();
                },
                error: function (error) {
                    console.error('Print error:', error);
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Error',
                    //     text: 'Failed to print. Please check the local print server.',
                    // });
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

                    // Convert Blob to Base64
                    var reader = new FileReader();
                    reader.readAsDataURL(pdfBlob);
                    reader.onloadend = function () {
                        // Get the Base64 part (ignore metadata)
                        let base64data = reader.result.split(',')[1];

                        // Send the Base64 PDF to QZ Tray
                        completeA4PrintJob(base64data);
                    };

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