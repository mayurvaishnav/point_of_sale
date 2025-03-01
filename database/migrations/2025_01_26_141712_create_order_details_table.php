<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->decimal('total_before_tax', 8, 2)->nullable();
            $table->decimal('discount',8,2)->nullable();
            $table->decimal('tax',8,2)->nullable();
            $table->decimal('tax_rate',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->decimal('total_after_discount',8,2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
