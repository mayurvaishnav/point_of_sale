@extends('layouts.adminlte-app')

@section('custom_content')
<div class="container">
    <h2>Application Logs</h2>
    <!-- Dropdown for selecting log file -->
    <form method="GET" action="{{ route('logs.index') }}">
        <label>Select Date:</label>
        <select name="log" onchange="this.form.submit()">
            @foreach($logFiles as $file)
                <option value="{{ $file }}" {{ $selectedLog == $file ? 'selected' : '' }}>
                    {{ str_replace(['laravel-', '.log'], '', $file) ?: 'Latest' }}
                </option>
            @endforeach
        </select>
    </form>

    <div style="max-height: 700px; overflow-y: auto; background: #f8f9fa; padding: 10px; border: 1px solid #ddd;">
        @foreach(array_reverse($logLines) as $line)
            <p style="font-family: monospace; margin: 0; padding: 2px 0;">{{ $line }}</p>
        @endforeach
    </div>
</div>
@endsection
