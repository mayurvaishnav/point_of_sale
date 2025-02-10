@extends('layouts.adminlte-app')

@section('title', 'Dashboard')

@section('custom_content_header')
    <h1>Welcome back, {{ Auth::user()->name }}</h1>
@stop

@section('custom_css')
@stop

@section('custom_content')
@can('report-sales')
    
    <p><strong>This week</strong> vs <strong>Last week</strong></p>
    <div class="row">
        <! -- Order Count -->
        <div class="col-md-4">
            <div class="small-box bg-light">
                <div class="inner">
                    <h5>Order Count</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h3>{{$totalsThisWeekOrderCount}}</h3>
                            <p>This week</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <h3>
                                    {{ $totalsLastWeekOrderCount }} 
                                    <span style="font-size: 0.8em; vertical-align: bottom;">
                                        @php
                                            $percentageChange = $totalsLastWeekOrderCount != 0 ? (($totalsThisWeekOrderCount - $totalsLastWeekOrderCount) / $totalsLastWeekOrderCount) * 100 : 0;
                                        @endphp
                                        <span style="font-size: 0.8em; vertical-align: bottom; color: {{ $percentageChange >= 0 ? 'green' : 'red' }};">
                                            {{ number_format($percentageChange, 2) }}%
                                            <i class="fas fa-arrow-{{ $percentageChange >= 0 ? 'up' : 'down' }}"></i>
                                        </span>
                                    </span>
                                </h3>
                            </div>
                            <p>Last week</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="col-md-4">
            <div class="small-box bg-light">
                <div class="inner">
                    <h5>Total sales</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h3>{{ config('app.currency_symbol') }} {{ $totalsThisWeekSales }}</h3>
                            <p>This week</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <h3>
                                    {{ config('app.currency_symbol') }} {{ $totalsLastWeekSales }} 
                                    <span style="font-size: 0.8em; vertical-align: bottom;">
                                        @php
                                            $percentageChange = $totalsLastWeekSales != 0 ? (($totalsThisWeekSales - $totalsLastWeekSales) / $totalsLastWeekSales) * 100 : 0;
                                        @endphp
                                        <span style="font-size: 0.8em; vertical-align: bottom; color: {{ $percentageChange >= 0 ? 'green' : 'red' }};">
                                            {{ number_format($percentageChange, 2) }}%
                                            <i class="fas fa-arrow-{{ $percentageChange >= 0 ? 'up' : 'down' }}"></i>
                                        </span>
                                    </span>
                                </h3>
                            </div>
                            <p>Last week</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount -->
        <div class="col-md-4">
            <div class="small-box bg-light">
                <div class="inner">
                    <h5>Discounts</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h3>{{ config('app.currency_symbol') }} {{ $totalsThisWeekDiscount }}</h3>
                            <p>This week</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <h3>
                                    {{ config('app.currency_symbol') }} {{ $totalsLastWeekDiscount }} 
                                    <span style="font-size: 0.8em; vertical-align: bottom;">
                                        @php
                                            $percentageChange = $totalsLastWeekDiscount != 0 ? (($totalsThisWeekDiscount - $totalsLastWeekDiscount) / $totalsLastWeekDiscount) * 100 : 0;
                                        @endphp
                                        <span style="font-size: 0.8em; vertical-align: bottom; color: {{ $percentageChange <= 0 ? 'green' : 'red' }};">
                                            {{ number_format($percentageChange, 2) }}%
                                            <i class="fas fa-arrow-{{ $percentageChange < 0 ? 'up' : 'down' }}"></i>
                                        </span>
                                    </span>
                                </h3>
                            </div>
                            <p>Last week</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
@endcan
@stop



@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($datesThisWeek),
                    datasets: [
                        {
                            label: 'This Week',
                            data: @json($totalsThisWeek),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            fill: true,
                        },
                        {
                            label: 'Last Week',
                            data: @json($totalsLastWeek),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            fill: true,
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'yyyy-MM-dd',
                                displayFormats: {
                                    day: 'yyyy-MM-dd'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@stop