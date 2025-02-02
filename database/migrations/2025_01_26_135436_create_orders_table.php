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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('pending');
            $table->date('order_date')->default(now())->index();
            $table->string('invoice_number')->nullable();
            $table->string('paid_method')->nullable();
            $table->decimal('net_sales', 8, 2)->nullable();
            $table->decimal('discount',8,2)->nullable();
            $table->decimal('tax',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
