<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRange extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'price_range_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'range_name',
        'min_price',
        'max_price',
    ];

    /**
     * Get the products for the price range.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'price_range_id', 'price_range_id');
    }
}
