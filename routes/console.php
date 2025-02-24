<?php

use App\Jobs\AutoOrderEmailJob;
use App\Models\ScheduledJob;
use App\Services\JobScheduler;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyTenSeconds();

Schedule::call(function () {
    $scheduler = new JobScheduler();
    Log::info('Scheduling jobs...');

    $scheduler->scheduleDailyAutoOrderMail();
})->name('Send daily email to supplier for auto order')->dailyAt("12:20");

Schedule::command('backup:run')->dailyAt('00:00');
