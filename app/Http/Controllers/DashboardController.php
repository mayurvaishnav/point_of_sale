<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();

        // Generate full 7-day date ranges for this week and last week
        $datesThisWeek = collect();
        $datesLastWeek = collect();
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $datesThisWeek->push($date->toDateString());
        }
        for ($date = $startOfLastWeek->copy(); $date->lte($endOfLastWeek); $date->addDay()) {
            $datesLastWeek->push($date->toDateString());
        }

         // Fetch sales data for this and last week
        $salesData = Order::selectRaw('DATE(order_date) as date, SUM(total_after_discount) as total, COUNT(id) as order_count, SUM(discount) as discount')
            ->whereBetween('order_date', [$startOfLastWeek, $endOfWeek])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // dd($salesData, $startOfWeek, $endOfWeek, $startOfLastWeek, $endOfLastWeek);

        $salesDataThisWeek = $salesData->whereBetween('date', [$startOfWeek, $endOfWeek]);
        $salesDataLastWeek = $salesData->whereBetween('date', [$startOfLastWeek, $endOfLastWeek]);

        $salesThisWeek = $salesData->whereBetween('date', [$startOfWeek, $endOfWeek])->pluck('total', 'date')->toArray();
        $totalsThisWeek = $datesThisWeek->map(fn($date) => $salesThisWeek[$date] ?? 0)->toArray();
        $totalsThisWeekOrderCount = $salesDataThisWeek->pluck('order_count')->sum();
        $totalsThisWeekDiscount = $salesDataThisWeek->pluck('discount')->sum();
        $totalsThisWeekSales = $salesDataThisWeek->pluck('total')->sum();
        $totalsThisWeekTax = $salesDataThisWeek->pluck('tax')->sum();

        $salesLastWeek = $salesData->whereBetween('date', [$startOfLastWeek, $endOfLastWeek])->pluck('total', 'date')->toArray();
        $totalsLastWeek = $datesLastWeek->map(fn($date) => $salesLastWeek[$date] ?? 0)->toArray();
        $totalsLastWeekOrderCount = $salesDataLastWeek->pluck('order_count')->sum();
        $totalsLastWeekDiscount = $salesDataLastWeek->pluck('discount')->sum();
        $totalsLastWeekSales = $salesDataLastWeek->pluck('total')->sum();
        $totalsLastWeekTax = $salesDataLastWeek->pluck('tax')->sum();

        // dd($datesThisWeek, $totalsThisWeek, $datesLastWeek, $totalsLastWeek);
    
        return view('dashboard', compact(
            'datesThisWeek',
            'totalsThisWeek',
            'totalsThisWeekOrderCount',
            'totalsThisWeekDiscount',
            'totalsThisWeekSales',
            'totalsThisWeekTax',
            'datesLastWeek',
            'totalsLastWeek',
            'totalsLastWeekOrderCount',
            'totalsLastWeekDiscount',
            'totalsLastWeekSales',
            'totalsLastWeekTax',
        ));
    }
}
