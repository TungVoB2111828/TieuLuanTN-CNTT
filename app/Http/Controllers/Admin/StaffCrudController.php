<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class StaffCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = Staff::orderBy('staff_id', 'desc')->get();
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:staff,email',
            'password' => 'required|string|min:6|max:255',
            'phone' => 'nullable|string|max:10|regex:/^[0-9]+$/',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Tên nhân viên là bắt buộc.',
            'name.max' => 'Tên nhân viên không được vượt quá 50 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 50 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự.',
            'phone.regex' => 'Số điện thoại chỉ được chứa số.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        try {
            Staff::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return redirect()->route('admin.staff.index')
                ->with('success', 'Thêm nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm nhân viên: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('staff', 'email')->ignore($staff->staff_id, 'staff_id')
            ],
            'password' => 'nullable|string|min:6|max:255',
            'phone' => 'nullable|string|max:10|regex:/^[0-9]+$/',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Tên nhân viên là bắt buộc.',
            'name.max' => 'Tên nhân viên không được vượt quá 50 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 50 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự.',
            'phone.regex' => 'Số điện thoại chỉ được chứa số.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $staff->update($updateData);

            return redirect()->route('admin.staff.index')
                ->with('success', 'Cập nhật nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật nhân viên: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $staff = Staff::findOrFail($id);
            $staff->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa nhân viên thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa nhân viên: ' . $e->getMessage()
            ], 500);
        }
    }
}
