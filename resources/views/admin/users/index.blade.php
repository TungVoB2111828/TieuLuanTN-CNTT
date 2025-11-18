@extends('admin.component.layout')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Danh sách tài khoản người dùng</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>SĐT</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc muốn xóa user này?')">
                                    <i class="fa fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Không có user nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
