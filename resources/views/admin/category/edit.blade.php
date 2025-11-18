@extends('admin.component.layout')

@section('title', 'Chỉnh sửa Danh mục')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="card-title">Chỉnh sửa Danh mục: {{ $category->name }}</h1>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display success message -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Display error message -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.category.update', $category->category_id) }}" method="POST" id="editCategoryForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" maxlength="50"
                                           value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Thông tin danh mục</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <small class="text-muted">
                                                <strong>ID:</strong> {{ $category->category_id }}<br>
                                                <strong>Tên hiện tại:</strong> {{ $category->name }}<br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4" maxlength="1000"
                                      placeholder="Nhập mô tả cho danh mục...">{{ old('description', $category->description) }}</textarea>
                            <small class="form-text text-muted">Tối đa 1000 ký tự</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fa fa-save"></i> Cập nhật danh mục
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
    // Add loading state when form is submitted
    const form = document.getElementById('editCategoryForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        // Disable submit button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang cập nhật...';
    });

    // Character counter for description
    const descriptionInput = document.getElementById('description');
    const maxLength = 1000;

    // Create character counter element
    const counterElement = document.createElement('div');
    counterElement.className = 'text-muted small mt-1';
    counterElement.innerHTML = `<span id="charCount">${descriptionInput.value.length}</span>/${maxLength} ký tự`;
    descriptionInput.parentNode.appendChild(counterElement);

    const charCountElement = document.getElementById('charCount');

    descriptionInput.addEventListener('input', function() {
        const currentLength = this.value.length;
        charCountElement.textContent = currentLength;

        if (currentLength > maxLength * 0.9) {
            counterElement.classList.add('text-warning');
        } else {
            counterElement.classList.remove('text-warning');
        }

        if (currentLength >= maxLength) {
            counterElement.classList.add('text-danger');
            counterElement.classList.remove('text-warning');
        } else {
            counterElement.classList.remove('text-danger');
        }
    });

    // Hide alerts after 3 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 3000);
});
</script>

@endsection
