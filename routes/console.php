<?php

use App\Jobs\SendOrderEmailJob;
use App\Models\ScheduledJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyTenSeconds();

Schedule::call(function () {
    $jobs = ScheduledJob::where('is_active', true)->get();

    foreach ($jobs as $job) {
        switch ($job->frequency) {
            case 'daily':
                Schedule::job(new SendOrderEmailJob($job))->dailyAt($job->execution_time);
                break;
            case 'weekly':
                Schedule::job(new SendOrderEmailJob($job))->weeklyOn(1, $job->execution_time); // Runs every Monday
                break;
            case 'monthly':
                Schedule::job(new SendOrderEmailJob($job))->monthlyOn(1, $job->execution_time); // Runs on 1st of the month
                break;
        }
    }
})->everyMinute();
