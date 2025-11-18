<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'cmt_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'create_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'create_at' => 'datetime',
    ];

    /**
     * Get the user that made the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the product that was commented on.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}