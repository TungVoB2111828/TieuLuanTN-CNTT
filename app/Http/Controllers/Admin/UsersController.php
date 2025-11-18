<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    // Hiển thị danh sách user
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:users,email,' . $user->user_id . ',user_id',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:10',
        ]);

        $user->update($request->only(['name','email','address','phone']));

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thông tin user thành công');
    }

    // Xóa user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa user thành công');
    }
}
