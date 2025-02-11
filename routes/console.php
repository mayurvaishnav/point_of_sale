<?php

use App\Jobs\AutoOrderEmailJob;
use App\Models\ScheduledJob;
use App\Services\JobScheduler;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyTenSeconds();

Schedule::call(function () {
    $scheduler = new JobScheduler();
    $scheduler->scheduleJobs();
})->everyMinute();
