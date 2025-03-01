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
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('status')->index();
            $table->date('order_date')->index();
            $table->string('invoice_number')->nullable();
            $table->integer('quantity');
            $table->decimal('total_before_tax', 8, 2)->nullable();
            $table->decimal('discount',8,2)->nullable();
            $table->decimal('tax',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->decimal('total_after_discount',8,2)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
