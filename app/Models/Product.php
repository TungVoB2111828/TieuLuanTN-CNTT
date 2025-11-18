<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // Không sử dụng updated_at

    use HasFactory;

    protected $primaryKey = 'product_id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    protected $fillable = [
        'staff_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'image_url',
        'status',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /**
     * Get the staff that added the product.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }

    /**
     * Get all comments for the product.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id', 'product_id');
    }

    /**
     * Get invoice details containing this product.
     */
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'product_id', 'product_id');
    }

}
