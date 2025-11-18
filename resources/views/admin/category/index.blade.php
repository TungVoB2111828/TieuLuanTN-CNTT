@extends('admin.component.layout')

@section('title', 'Danh mục')
@section('content')

<div class="card-header d-flex justify-content-between align-items-center">
    <h1 class="card-title">Danh sách Danh mục</h1>
</div>

<a href="{{ route('admin.category.create') }}" class="btn btn-primary mb-3">
    <i class="fa fa-plus"></i> Thêm Danh mục
</a>

<div class="card-body mt-3">
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="categoryTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->category_id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.category.edit', $category->category_id) }}"
                                class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="deleteCategory({{ $category->category_id }})">
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
    setTimeout(function() {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000); // 3 giây
</script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#categoryTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
            },
            "order": [
                [0, "desc"]
            ]
        });
    });

    // Delete Category Function
    function deleteCategory(id) {
        if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
            $.ajax({
                url: '{{ route('admin.category.destroy', ':id') }}'.replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    } else {
                        toastr.error(response.message || 'Có lỗi xảy ra khi xóa danh mục!');
                    }
                },
                error: function(xhr) {
                    toastr.error('Có lỗi xảy ra khi xóa danh mục!');
                }
            });
        }
    }
</script>

@endsection
