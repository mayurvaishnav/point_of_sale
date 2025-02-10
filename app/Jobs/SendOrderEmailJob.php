<?php

namespace App\Jobs;

use App\Mail\OrderProductsMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipient = 'admin@example.com'; // Change to the actual recipient
        Mail::to($recipient)->send(new OrderProductsMail());
    }
}
