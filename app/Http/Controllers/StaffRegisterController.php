<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StaffRegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('staff.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:staff,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Staff::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu ở đây
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        return redirect()->route('admin.home.index')->with('success', 'Staff registered successfully.');
    }
}
