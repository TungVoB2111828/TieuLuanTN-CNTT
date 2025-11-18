<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        $staff = session('logged_in_user');
        return view('staff.dashboard', compact('staff'));
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('message', 'Đã đăng xuất.');
    }
}
