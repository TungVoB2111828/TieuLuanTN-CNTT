<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('category_id', 'desc')->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.max' => 'Tên danh mục không được vượt quá 50 ký tự.',
        ]);

        try {
            Category::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.category.index')
                ->with('success', 'Thêm danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm danh mục: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.max' => 'Tên danh mục không được vượt quá 50 ký tự.',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            $category->update($updateData);

            return redirect()->route('admin.category.index')
                ->with('success', 'Cập nhật danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật danh mục: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa danh mục: ' . $e->getMessage()
            ], 500);
        }
    }
}
