@extends('admin.component.layout')

@section('title', 'Thêm Nhân viên')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="card-title">Thêm Nhân viên mới</h1>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary bg-primary">
                        <i class="fa fa-arrow-left"></i> Quay lại
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

                    <form action="{{ route('admin.staff.store') }}" method="POST" id="createStaffForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên nhân viên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" maxlength="50" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" maxlength="50" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" minlength="6" maxlength="255" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fa fa-eye" id="passwordIcon"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}" maxlength="10"
                                           pattern="[0-9]*" placeholder="0123456789">
                                    <small class="form-text text-muted">Chỉ nhập số, tối đa 10 ký tự</small>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" maxlength="255"
                                      placeholder="Nhập địa chỉ nhân viên...">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fa fa-save"></i> Lưu nhân viên
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
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'password') {
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        } else {
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        }
    });

    // Format phone input (only allow numbers)
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        let value = this.value;
        // Remove non-numeric characters
        value = value.replace(/[^0-9]/g, '');
        this.value = value;
    });

    // Add loading state when form is submitted
    const form = document.getElementById('createStaffForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        // Disable submit button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang lưu...';
    });

    // Email validation
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && !emailRegex.test(email)) {
            this.classList.add('is-invalid');
            if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.classList.add('invalid-feedback');
                feedback.textContent = 'Email không đúng định dạng';
                this.parentNode.appendChild(feedback);
            }
        } else {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback && feedback.textContent === 'Email không đúng định dạng') {
                feedback.remove();
            }
        }
    });
});
</script>

@endsection
