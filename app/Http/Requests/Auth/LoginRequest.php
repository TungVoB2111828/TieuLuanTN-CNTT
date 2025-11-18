<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $email = $this->input('email');
        $password = $this->input('password');
        $role = $this->input('role');

        if ($role === 'user') {
            $user = User::where('email', $email)->first();
        } elseif ($role === 'staff') {
            $user = DB::table('staff')->where('email', $email)->first();
        } else {
            throw ValidationException::withMessages([
                'role' => ['Vai trò không hợp lệ.'],
            ]);
        }

        if (!$user || !Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng.'],
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Đăng nhập người dùng nếu là user (sử dụng Auth)
        if ($role === 'user') {
            Auth::login($user); // Sử dụng model User
        }

        // Lưu thông tin user và vai trò vào session
        session([
            'logged_in_user' => $user,
            'role' => $role,
        ]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            event(new Lockout($this));

            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
