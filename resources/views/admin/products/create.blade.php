@extends('admin.component.layout')

@section('title', 'Thêm Sản phẩm')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center ">
                    <h1 class="card-title">Thêm Sản phẩm mới</h1>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary bg-primary">
                        <i class="fa fa-arrow-left "></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    {{-- Display validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Display success message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Display error message --}}
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" maxlength="50" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->category_id }}"
                                                {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="Nhập mô tả sản phẩm...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                                    <small class="form-text text-muted">Chỉ chấp nhận file: jpeg, png, jpg, gif. Kích thước tối đa: 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="imagePreview" class="mb-3" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                               {{ old('status', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            <span id="statusText">{{ old('status', '1') ? 'Hoạt động' : 'Không hoạt động' }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fa fa-save"></i> Lưu sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image when file is selected
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Toggle status text
    const statusCheckbox = document.getElementById('status');
    const statusText = document.getElementById('statusText');

    statusCheckbox.addEventListener('change', function() {
        if (this.checked) {
            statusText.textContent = 'Hoạt động';
        } else {
            statusText.textContent = 'Không hoạt động';
        }
    });

    // Format price input (optional - remove non-numeric characters)
    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', function() {
        let value = this.value;
        if (value) {
            // Remove non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            this.value = value;
        }
    });

    // Add loading state when form is submitted
    const form = document.getElementById('createProductForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        // Disable submit button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang lưu...';
    });
});
</script>

@endsection
