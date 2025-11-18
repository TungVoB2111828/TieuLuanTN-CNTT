<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang chỉnh sửa profile, mặc định mở mục 'info'
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'section' => 'info', // Mặc định mở phần chỉnh sửa thông tin
        ]);
    }

    /**
     * Mục chỉnh sửa thông tin user
     */
    public function editInfo(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'section' => 'info',
        ]);
    }

    /**
     * Mục chỉnh sửa mật khẩu
     */
    public function editPassword(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'section' => 'password',
        ]);
    }

    /**
     * Mục xóa tài khoản
     */
    public function editDelete(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'section' => 'delete',
        ]);
    }

    /**
     * Cập nhật thông tin profile
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Xóa tài khoản user
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('login.user');
    }
}
