<?php

namespace App\Jobs;

use App\Mail\AutoReOrderProductsMail;
use App\Models\Product;
use App\Models\ScheduledJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AutoOrderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ScheduledJob $scheduledJob;

    /**
     * Create a new job instance.
     */
    public function __construct(ScheduledJob $scheduledJob)
    {
        $this->scheduledJob = $scheduledJob;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $productsToReorder = $this->findProductsToReorder();
        if ($productsToReorder->isEmpty()) {
            return;
        }

        $goupedBySupplier = $productsToReorder->groupBy('supplier_id');

        foreach ($goupedBySupplier as $supplierId => $products) {
            $supplierEmail = $products->first()->supplier->email;
            Mail::to($supplierEmail)
                ->send(new AutoReOrderProductsMail($this->scheduledJob, $productsToReorder));
        }
        
    }

    private function findProductsToReorder()
    {
        return Product::where('auto_order_at_low_stock', true)
            ->where('quantity', '<', 'low_stock_threshold')
            ->with('supplier')
            ->get();
    }
}
