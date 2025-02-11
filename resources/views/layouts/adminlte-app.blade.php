@extends('adminlte::page')

@section('adminlte_css_pre')
    @laravelPWA
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
@stop

@section('content')
    @include('layouts.alerts')
    @yield('custom_content')

@endsection

@section('js')
    @yield('custom_js')

    <script>
        $(document).ready(function() {

            // Cashdrawer open button
            $('#openCashDrawer').on('click', function(e) {
                e.preventDefault();
                
            })

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
    </script>

@endsection