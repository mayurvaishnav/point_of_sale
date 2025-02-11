<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index(Request $request)
    {
        $logDir = storage_path('logs');
        $files = File::files($logDir);

        // Extract only log filenames
        $logFiles = collect($files)->map(fn($file) => basename($file))->filter(fn($file) => str_ends_with($file, '.log'))->values();

        // Get selected date or default to latest log
        $selectedLog = $request->query('log', 'laravel.log');
        $logPath = storage_path("logs/{$selectedLog}");

        // Read log file
        $logs = File::exists($logPath) ? File::get($logPath) : 'No logs available';
        $logLines = explode("\n", trim($logs));

        return view('logs.index', compact('logFiles', 'selectedLog', 'logLines'));
    }
}
