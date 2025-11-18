<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function add($productId)
    {
        $exists = Favorite::where('user_id', Auth::id())
                          ->where('product_id', $productId)
                          ->exists();

        if (!$exists) {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào yêu thích!');
    }

    public function remove($productId)
    {
        Favorite::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();

        return redirect()->back()->with('success', 'Đã xóa khỏi yêu thích!');
    }
}
