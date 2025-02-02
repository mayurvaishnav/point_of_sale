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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('tax_rate_id')->constrained('tax_rates')->onDelete('restrict');
            $table->string('code')->nullable();
            $table->string('garage')->nullable();
            $table->string('image')->nullable();
            $table->string('store')->nullable();
            $table->decimal('buying_price', 8, 2)->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('selling_price', 8, 2);
            $table->boolean('tax_included')->default(true);
            $table->boolean('stockable')->default(false);
            $table->integer('quantity')->nullable();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('auto_order_at_low_stock')->default(false);
            $table->integer('low_stock_threshold')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
