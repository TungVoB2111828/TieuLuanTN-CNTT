@extends('admin.component.layout')

@section('title', 'Hóa đơn')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="card-title">Chi tiết Hóa đơn #{{ $invoice->invoice_id }}</h1>
                    <a href="{{ route('admin.invoice.index') }}" class="btn btn-secondary">
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

                    <div class="row">
                        <!-- Thông tin khách hàng -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fa fa-user"></i> Thông tin khách hàng</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Tên:</strong></td>
                                            <td>{{ $invoice->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $invoice->user->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số điện thoại:</strong></td>
                                            <td>{{ $invoice->user->phone ?? 'Chưa có' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Địa chỉ:</strong></td>
                                            <td>{{ $invoice->user->address ?? 'Chưa có' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin hóa đơn -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fa fa-file-text"></i> Thông tin hóa đơn</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Mã hóa đơn:</strong></td>
                                            <td>#{{ $invoice->invoice_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày tạo:</strong></td>
                                            <td>{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tổng tiền:</strong></td>
                                            <td class="text-success"><strong>{{ number_format($invoice->total) }}đ</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Khách hàng ID:</strong></td>
                                            <td>{{ $invoice->user_id }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết sản phẩm -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-shopping-cart"></i> Chi tiết sản phẩm</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>STT</th>
                                            <th>Sản phẩm</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp
                                        @foreach ($invoice->details as $index => $detail)
                                            @php
                                                $subtotal = $detail->price * $detail->quantity;
                                                $total += $subtotal;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $detail->product->name ?? 'Sản phẩm không tồn tại' }}</strong>
                                                    @if($detail->product)
                                                        <br><small class="text-muted">ID: {{ $detail->product->product_id }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-end">{{ number_format($detail->price) }}đ</td>
                                                <td class="text-center">{{ $detail->quantity }}</td>
                                                <td class="text-end">{{ number_format($subtotal) }}đ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                            <td class="text-end"><strong class="text-success">{{ number_format($total) }}đ</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Form cập nhật trạng thái -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-edit"></i> Cập nhật trạng thái hóa đơn</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.invoice.update', $invoice->invoice_id) }}" method="POST" id="updateInvoiceForm">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="payment_status" class="form-label">Trạng thái thanh toán <span class="text-danger">*</span></label>
                                            <select class="form-control @error('payment_status') is-invalid @enderror"
                                                    id="payment_status" name="payment_status" required>
                                                @foreach($paymentStatuses as $key => $value)
                                                    <option value="{{ $key }}" {{ $invoice->payment_status == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('payment_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order_status" class="form-label">Trạng thái đơn hàng <span class="text-danger">*</span></label>
                                            <select class="form-control @error('order_status') is-invalid @enderror"
                                                    id="order_status" name="order_status" required>
                                                @foreach($orderStatuses as $key => $value)
                                                    <option value="{{ $key }}" {{ $invoice->order_status == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('order_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Trạng thái hiện tại -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><i class="fa fa-info-circle"></i> Trạng thái hiện tại:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Thanh toán:</strong>
                                            @switch($invoice->payment_status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Đã thanh toán</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">Thất bại</span>
                                                    @break
                                                @case('refunded')
                                                    <span class="badge bg-info">Đã hoàn tiền</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $invoice->payment_status }}</span>
                                            @endswitch
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Đơn hàng:</strong>
                                            @switch($invoice->order_status)
                                                @case('new')
                                                    <span class="badge bg-primary">Đơn hàng mới</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-warning">Đang xử lý</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info">Đang chuẩn bị</span>
                                                    @break
                                                @case('shipped')
                                                    <span class="badge bg-secondary">Đã giao vận</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge bg-success">Đã giao hàng</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $invoice->order_status }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.invoice.index') }}" class="btn btn-secondary me-2">
                                        <i class="fa fa-times"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fa fa-save"></i> Cập nhật trạng thái
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state when form is submitted
    const form = document.getElementById('updateInvoiceForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        // Disable submit button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang cập nhật...';
    });

    // Status change notifications
    const paymentSelect = document.getElementById('payment_status');
    const orderSelect = document.getElementById('order_status');

    paymentSelect.addEventListener('change', function() {
        const value = this.value;
        if (value === 'refunded') {
            if (!confirm('Bạn có chắc chắn muốn đánh dấu đơn hàng này là "Đã hoàn tiền"?')) {
                this.value = '{{ $invoice->payment_status }}'; // Reset to original
            }
        }
    });

    orderSelect.addEventListener('change', function() {
        const value = this.value;
        if (value === 'cancelled') {
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này? Thao tác này có thể ảnh hưởng đến kho hàng.')) {
                this.value = '{{ $invoice->order_status }}'; // Reset to original
            }
        } else if (value === 'delivered') {

</script>          //
@endsection
