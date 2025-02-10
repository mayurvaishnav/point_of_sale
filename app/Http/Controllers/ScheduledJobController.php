<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledJob;

class ScheduledJobController extends Controller
{
    function __construct()
    {
        //  $this->middleware('permission:job-list', ['only' => ['index']]);
        //  $this->middleware('permission:job-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:job-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:job-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $jobs = ScheduledJob::all();
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        ScheduledJob::create($request->validate([
            'job_name' => 'required|string',
            'email_subject' => 'required|string',
            'email_body' => 'required|string',
            'execution_time' => 'required|date_format:H:i',
            'frequency' => 'required|in:daily,weekly,monthly',
            'is_active' => 'boolean',
        ]));

        return redirect()->route('jobs.index')->with('success', 'Job added successfully!');
    }

    public function edit(ScheduledJob $job)
    {
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, ScheduledJob $job)
    {
        $job->update($request->validate([
            // 'job_name' => 'required|string',
            'email_subject' => 'required|string',
            'email_body' => 'required|string',
            'execution_time' => 'required|date_format:H:i',
            'frequency' => 'required|in:daily,weekly,monthly',
            'is_active' => 'boolean',
        ]));

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully!');
    }

    public function destroy(ScheduledJob $job)
    {
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully!');
    }
}
