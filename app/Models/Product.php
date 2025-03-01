<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'supplier_id',
        'tax_rate_id',
        'is_active',
        'code',
        'garage',
        'image',
        'store',
        'buying_price',
        'price',
        'selling_price',
        'tax_included',
        'quantity',
        'description',
        'brand',
        'stockable',
        'auto_order_at_low_stock',
        'low_stock_threshold',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that owns the product.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the tax_rate of the product.
     */    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }
}
