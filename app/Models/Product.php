<?php

namespace App\Models;

use App\Helpers\ProductImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'image_url',
        'price',
        'discount_percentage',
        'stock',
        'status',
    ];

    protected $casts = [
        'price' => 'float',
        'discount_percentage' => 'float',
        'stock' => 'integer',
    ];

    /**
     * Check if product has a discount applied.
     */
    public function hasDiscount(): bool
    {
        return $this->discount_percentage > 0;
    }

    /**
     * Get the final discounted price of the product.
     */
    public function finalPrice(): float
    {
        if ($this->hasDiscount()) {
            return round($this->price * (1 - ($this->discount_percentage / 100)), 2);
        }
        return (float) $this->price;
    }

    /**
     * Returns the best display image for this product.
     * Priority: admin-set image_url → keyword match → generic fallback.
     */
    public function displayImage(): string
    {
        return ProductImageHelper::resolve($this->product_name, $this->image_url);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }

    public function hasEnoughStock(int $quantity): bool {
        return $this->status === 'Active' && $this->stock >= $quantity;
    }
}
