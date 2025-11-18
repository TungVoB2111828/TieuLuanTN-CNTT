@extends('admin.component.layout')

@section('title', 'Nhân viên')

@section('content')
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="card-title">Danh sách Nhân viên</h1>
    </div>

    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary mb-3">
        <i class="fa fa-plus"></i> Thêm Nhân viên
    </a>

    <div class="card-body mt-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="staffTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên nhân viên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @foreach ($staff as $member)
                        <tr>
                            <td>{{ $member->staff_id }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone ?? 'N/A' }}</td>
                            <td>{{ $member->address ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.staff.edit', $member->staff_id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteStaff('{{ $member->staff_id }}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('admin.component.script')

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#staffTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
                },
                "order": [
                    [0, "desc"]
                ]
            });
        });

        // Delete Staff Function
        function deleteStaff(id) {
            if (confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) {
                $.ajax({
                    url: "{{ route('admin.staff.destroy', ['id' => '__id__']) }}".replace('__id__', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Có lỗi xảy ra khi xóa nhân viên!');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi xóa nhân viên!');
                    }
                });
            }
        }
    </script>
@endsection
