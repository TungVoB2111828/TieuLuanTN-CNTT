<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $featuredProducts = Product::orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $newArrivals = Product::orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $products = Product::paginate(12); // tất cả sản phẩm, phân trang

        $recommendedProducts = collect();

        if (Auth::check()) {
            $user = Auth::user();

            $topCategoryId = $user->favorite()
                ->orderByDesc('score')
                ->pluck('category_id')
                ->first();

            if ($topCategoryId) {
                $recommendedProducts = Product::where('category_id', $topCategoryId)->get();
            }
        }

        return view('home', compact(
            'categories',
            'featuredProducts',
            'newArrivals',
            'products',
            'recommendedProducts'
        ));
    }

}

