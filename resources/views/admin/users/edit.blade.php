@extends('admin.component.layout')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa thông tin người dùng</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">SĐT</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>

            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
