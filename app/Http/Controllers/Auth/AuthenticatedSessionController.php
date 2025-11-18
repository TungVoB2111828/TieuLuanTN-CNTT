<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị form đăng nhập.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý yêu cầu đăng nhập.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required'],
        ]);

        // Kiểm tra thông tin đăng nhập
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Thông tin đăng nhập không chính xác.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Đăng xuất người dùng.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
