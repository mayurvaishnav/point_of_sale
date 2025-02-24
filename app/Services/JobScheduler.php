<?php

namespace App\Services;

use App\Jobs\AutoOrderEmailJob;
use App\Mail\AutoReOrderProductsMail;
use App\Models\Product;
use App\Models\ScheduledJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

class JobScheduler
{
    public function scheduleDailyAutoOrderMail()
    {
        $scheduledJobs = ScheduledJob::where('job_name', 'daily_order_email')
            ->where('is_active', true)
            ->get();

        // foreach ($jobs as $job) {
        //     Log::info('In JobScheduler()...');
        //     AutoOrderEmailJob::dispatch($job);
        // }

        if ($scheduledJobs->count() > 0) {
            Log::info('No jobs found to schedule...');
            return;
        }

        $scheduledJob = $scheduledJobs->first();

        $productsToReorder = $this->findProductsToReorder();

        if ($productsToReorder->isEmpty()) {
            return;
        }

        $goupedBySupplier = $productsToReorder->groupBy('supplier_id');

        foreach ($goupedBySupplier as $supplierId => $products) {
            $supplierEmail = $products->first()->supplier->email;

            if (empty($supplierEmail)) {
                Log::error("Supplier with ID: $supplierId has no email address. Skipping...");
                continue;
            }

            $productNames = $products->map(function($product) {
                return $product->name;
            })->toArray();
            Log::info("Sending auto reorder email to supplier: $supplierEmail with Products: " . implode(', ', $productNames));
            Mail::to($supplierEmail)
                ->send(new AutoReOrderProductsMail($scheduledJob, $products));
        }

        Log::info('Done JobScheduler()...');
    }

    private function findProductsToReorder()
    {
        return Product::where('is_active', true)
            ->where('stockable', true)
            ->where('auto_order_at_low_stock', true)
            ->whereRaw('CAST(quantity AS UNSIGNED) < CAST(low_stock_threshold AS UNSIGNED)')
            ->with('supplier')
            ->get();
    }

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