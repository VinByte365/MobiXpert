<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'brand_id',
        'price_range_id',
        'image_path'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function priceRange()
    {
        return $this->belongsTo(PriceRange::class, 'price_range_id', 'price_range_id');
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }
    
    /**
     * Get the order lines for the product.
     */
    public function orderLines()
    {
        return $this->hasMany(OrderLine::class, 'product_id', 'product_id');
    }
}