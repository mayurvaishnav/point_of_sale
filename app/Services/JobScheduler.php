<?php

namespace App\Services;

use App\Jobs\AutoOrderEmailJob;
use App\Models\ScheduledJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

class JobScheduler
{
    public function scheduleJobs()
    {
        $jobs = ScheduledJob::where('is_active', true)->get();

        Log::info('In scheduleJobs()... with jobs: ' . $jobs->count());

        foreach ($jobs as $job) {
            $emailClass = $this->getEmailJobClass($job);
            if ($emailClass != null) {
                $this->scheduleJob($job, $emailClass);
            }
        }
    }

    private function getEmailJobClass($job) {
        if($job->job_name == 'daily_order_email') {
            return AutoOrderEmailJob::class;
        // } else if($job->job_name == 'monthly_customer_account_statement') {
        //     return AutoOrderEmailJob::class;
        } else {
            return null;
        }
    }

    private function scheduleJob($job, $jobClass)
    {
        Log::info("Scheduling job: {$job->job_name} with frequency: {$job->frequency} at {$job->execution_time}");
        switch ($job->frequency) {
            case 'daily':
                Schedule::job(new $jobClass($job))->dailyAt($job->execution_time);
                break;
            case 'weekly':
                Schedule::job(new $jobClass($job))->weeklyOn(1, $job->execution_time); // Runs every Monday
                break;
            case 'monthly':
                Schedule::job(new $jobClass($job))->monthlyOn(1, $job->execution_time); // Runs on 1st of the month
                break;
        }
    }
}