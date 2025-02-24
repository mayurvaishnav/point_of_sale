<?php

namespace App\Jobs;

use App\Mail\AutoReOrderProductsMail;
use App\Models\Product;
use App\Models\ScheduledJob;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoOrderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

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
        Log::info('In AutoOrderEmailJob...');
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
            // Mail::to($supplierEmail)->send(new AutoReOrderProductsMail($this->scheduledJob, $products));
        }
        
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

    /**
     * Handle a job failure.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
    }
}
