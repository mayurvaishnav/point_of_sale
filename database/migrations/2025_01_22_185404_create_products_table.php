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
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('tax_rate_id')->nullable()->constrained('tax_rates')->onDelete('set null');
            $table->string('code')->nullable();
            $table->string('garage')->nullable();
            $table->string('image')->nullable();
            $table->integer('store')->nullable();
            $table->decimal('buying_price', 8, 2)->nullable();
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->boolean('include_tax')->default(true);
            $table->boolean('stockable')->default(false);
            $table->integer('quantity')->nullable();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
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
