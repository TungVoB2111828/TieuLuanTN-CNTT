<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply filters
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // ✅ chỉnh lại paginate thành 20 (5 cột × 4 hàng)
        $products = $query->paginate(20);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display products by category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category($id)
    {
        $category = Category::findOrFail($id);

        // ✅ chỉnh paginate thành 20
        $products = Product::where('Category_id', $id)->paginate(20);
        $categories = Category::all();

        return view('products.category', compact('products', 'category', 'categories'));
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        // ✅ Ghi nhận điểm nếu người dùng đã đăng nhập
        if (Auth::check()) {
            $userId = Auth::id();
            $categoryId = $product->category_id;

            $favorite = Favorite::where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->first();

            if ($favorite) {
                Favorite::where('user_id', $userId)
                    ->where('category_id', $categoryId)
                    ->increment('score', 1);
            } else {
                Favorite::create([
                    'user_id' => $userId,
                    'category_id' => $categoryId,
                    'score' => 1
                ]);
            }
        }

        $relatedProducts = Product::where('Category_id', $product->Category_id)
                                ->where('Product_id', '!=', $id)
                                ->take(4)
                                ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Search for products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        // ✅ chỉnh paginate thành 20
        $products = Product::where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->paginate(20);

        return view('products.search', compact('products', 'query'));
    }
}
