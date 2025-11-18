@extends('admin.component.layout')

@section('title', 'Sản phẩm')
@section('content')


    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="card-title">Danh sách Sản phẩm</h1>

    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
        <i class="fa fa-plus"></i> Thêm Sản phẩm
    </a>
    <div class="card-body mt-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="productsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->product_id }}</td>
                            <td>
                                @if ($product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Không có hình</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>
                                <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->status ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td>{{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product->product_id) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="deleteProduct({{ $product->product_id }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>


    @include('admin.component.script')
  
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#productsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
                },
                "order": [
                    [0, "desc"]
                ]
            });
        });

        // Delete Product Function
        function deleteProduct(id) {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                $.ajax({
                    url: '{{ route('admin.products.destroy', ':id') }}'.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Có lỗi xảy ra khi xóa sản phẩm!');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi xóa sản phẩm!');
                    }
                });
            }
        }
    </script>

@endsection
