<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = session('logged_in_user');
        return view('user.dashboard', compact('user'));
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('message', 'Đã đăng xuất.');
    }
}
