<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'total_amount',
        'status',
        'shipping_address',
        'billing_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Get the order lines for the order.
     */
    public function orderLines()
    {
        return $this->hasMany(OrderLine::class, 'order_id', 'order_id');
    }

    /**
     * Get the products in this order.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_lines', 'order_id', 'product_id')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}