<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $request->validate([
            "start_date"=> "required|date",
            "end_date"=> "required|date",
        ]);

        $orders = $request->orders;
        $sales = [];
        
        return view('reports.sales');
    }
    
    public function customer()
    {
        return view('reports.customer');
    }
}