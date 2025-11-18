<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id'; // tên cột khóa chính trong bảng
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all favorites for the user.
     */
    public function favorite()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'user_id');
    }

    /**
     * Get categories favorited by the user.
     */
    public function favoriteCategories()
    {
        return $this->belongsToMany(Category::class, 'favorites')->withPivot('score');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id', 'user_id');
    }
}

