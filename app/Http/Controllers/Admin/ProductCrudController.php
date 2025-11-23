<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Thêm dòng này để dùng Rule

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
                
                // Quy tắc mới: 'image' là tên file có sẵn (string), 'image_file' là file upload (image)
                'image' => 'nullable|string|max:255',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                
                'status' => 'nullable'
            ]);

            $data = $request->only(['name', 'description', 'price', 'stock_quantity', 'category_id']);
            $data['staff_id'] = Auth::id() ?? 1; // Default to staff_id = 1 if not authenticated
            $data['status'] = $request->has('status') && $request->input('status') ? 1 : 0;
            $data['created_at'] = now();
            
            $imagePath = null;

            // 1. Xử lý trường hợp có upload file (image_file)
            if ($request->hasFile('image_file')) {
                $image = $request->file('image_file');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Lưu vào thư mục 'images'
                $path = $image->storeAs('images', $imageName, 'public');
                $imagePath = $path;
                
            // 2. Xử lý trường hợp nhập tên file có sẵn (image)
            } elseif ($request->filled('image')) {
                $fileName = $request->input('image');
                
                // Kiểm tra file có tồn tại trong storage/app/public/images không
                if (Storage::disk('public')->exists("images/{$fileName}")) {
                    $imagePath = "images/{$fileName}"; // Lưu đường dẫn tương đối: images/ten_file.jpg
                } else {
                    return redirect()->back()->with('error', "Lỗi: Không tìm thấy file ảnh '{$fileName}' trong thư mục /storage/images/")->withInput();
                }
            }
            
            $data['image_url'] = $imagePath;

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
                
                // Quy tắc mới cho update
                'image' => 'nullable|string|max:255',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                
                'status' => 'nullable'
            ]);

            $data = $request->only(['name', 'description', 'price', 'stock_quantity', 'category_id']);
            $data['status'] = $request->has('status') && $request->input('status') ? 1 : 0;
            
            $oldImagePath = $product->image_url;
            $newImagePath = null; // Biến này lưu đường dẫn mới

            // 1. Xử lý trường hợp có upload file (ưu tiên 1)
            if ($request->hasFile('image_file')) {
                // Xóa ảnh cũ (chỉ xóa nếu ảnh cũ là file upload, không phải file insert thủ công)
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }

                $image = $request->file('image_file');
                $imageName = time() . '_' . $image->getClientOriginalName();

                // Lưu ảnh mới vào thư mục 'images'
                $path = $image->storeAs('images', $imageName, 'public');
                $newImagePath = $path;
                
            // 2. Xử lý trường hợp nhập tên file có sẵn (image)
            } elseif ($request->filled('image')) {
                $fileName = $request->input('image');
                
                // Kiểm tra file có tồn tại không
                if (Storage::disk('public')->exists("images/{$fileName}")) {
                    $newImagePath = "images/{$fileName}"; // Đường dẫn mới là tên file đã nhập
                } else {
                     return redirect()->back()->with('error', "Lỗi: Không tìm thấy file ảnh '{$fileName}' trong thư mục /storage/images/")->withInput();
                }
            } else {
                // Nếu cả hai input đều rỗng, giữ nguyên ảnh cũ (hoặc null)
                $newImagePath = $oldImagePath; 
            }
            
            $data['image_url'] = $newImagePath;

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