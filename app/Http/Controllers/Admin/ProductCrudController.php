<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['staff', 'category'])->get();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }
    /**
     * Display the admin management page.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,category_id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable'
            ]);

            $data = $request->only(['name', 'description', 'price', 'stock_quantity', 'category_id']);
            $data['staff_id'] = Auth::id() ?? 1; // Default to staff_id = 1 if not authenticated
            $data['status'] = $request->has('status') && $request->input('status') ? 1 : 0;
            $data['created_at'] = now();

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();

                // Store image in storage/app/public/products
                $path = $image->storeAs('products', $imageName, 'public');
                $data['image_url'] = $path;
            }

            Product::create($data);

            return redirect()->back()
                         ->with('success', 'Sản phẩm đã được thêm thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                         ->withErrors($e->errors())
                         ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                         ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['staff', 'category'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:50',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,category_id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable'
            ]);

            $data = $request->only(['name', 'description', 'price', 'stock_quantity', 'category_id']);
            $data['status'] = $request->has('status') && $request->input('status') ? 1 : 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                    Storage::disk('public')->delete($product->image_url);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();

                // Store new image in storage/app/public/products
                $path = $image->storeAs('products', $imageName, 'public');
                $data['image_url'] = $path;
            }

            $product->update($data);

            return redirect()->back()
                         ->with('success', 'Sản phẩm đã được cập nhật thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                         ->withErrors($e->errors())
                         ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                         ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete image file
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product data for editing.
     */
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            $categories = Category::all();
            return view('admin.products.edit', compact('product', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Không tìm thấy sản phẩm!');
        }
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $products = Product::with(['staff', 'category'])
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Filter products by category.
     */
    public function category($categoryId)
    {
        $products = Product::with(['staff', 'category'])
            ->where('category_id', $categoryId)
            ->paginate(10);

        return view('products.index', compact('products'));
    }
}