<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập cho user
    public function showUserLoginForm()
    {
        return view('auth.login_user');
    }

    // Xử lý đăng nhập user
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            session(['role' => 'user']);
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Sai thông tin đăng nhập.',
        ])->onlyInput('email');
    }

    // Hiển thị form đăng nhập cho staff
    public function showStaffLoginForm()
    {
        return view('auth.login_staff');
    }

    // Xử lý đăng nhập staff
    public function loginStaff(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();
            session(['role' => 'staff']);
            return redirect()->route('admin.home.index');
        }

        return back()->withErrors([
            'email' => 'Sai thông tin đăng nhập.',
        ])->onlyInput('email');
    }

    // Đăng xuất chung
    public function logout(Request $request)
    {
        // Xác định guard đang đăng nhập
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
            $redirectTo = route('login.staff');
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $redirectTo = route('home');
        } else {
            $redirectTo = '/'; // fallback nếu không guard nào đăng nhập
        }

        // Huỷ phiên làm việc
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectTo);
    }
}
