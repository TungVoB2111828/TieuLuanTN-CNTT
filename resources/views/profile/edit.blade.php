@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ')

@section('header')
    <h2 class="h4 fw-bold text-dark mb-0">
        {{ __('Chỉnh sửa Hồ sơ') }}
    </h2>
@endsection

@section('content')
    <div class="row g-4">

        {{-- Menu điều hướng --}}
        <div class="col-12">
            <div class="bg-white p-3 rounded shadow-sm d-flex gap-3">
                <a href="{{ route('profile.edit.info') }}"
                   class="{{ ($section ?? '') === 'info' ? 'fw-bold text-primary text-decoration-underline' : 'text-primary' }}">
                    Chỉnh sửa thông tin
                </a>
                <a href="{{ route('profile.edit.password') }}"
                   class="{{ ($section ?? '') === 'password' ? 'fw-bold text-primary text-decoration-underline' : 'text-primary' }}">
                    Đổi mật khẩu
                </a>
                <a href="{{ route('profile.edit.delete') }}"
                   class="{{ ($section ?? '') === 'delete' ? 'fw-bold text-danger text-decoration-underline' : 'text-danger' }}">
                    Xóa tài khoản
                </a>
            </div>
        </div>

        {{-- Form: Cập nhật thông tin --}}
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="mb-3 text-dark">Thông tin Hồ sơ</h5>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Form: Đổi mật khẩu --}}
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="mb-3 text-dark">Đổi Mật khẩu</h5>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Form: Xóa tài khoản --}}
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="mb-3 text-dark">Xóa Tài khoản</h5>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
