<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'category_id';
    
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];
    
    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }

    /**
     * Get users who favorited this category.
     */
    public function favorite()
    {
        return $this->hasMany(Favorite::class, 'category_id', 'category_id');
    }

}
