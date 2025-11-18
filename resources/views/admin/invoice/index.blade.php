@extends('admin.component.layout')

@section('title', 'Hóa đơn')
@section('content')

<div class="card-header d-flex justify-content-between align-items-center m-4">
    <h1 class="card-title">Danh sách Hóa đơn</h1>
</div>



<div class="card-body mt-3">
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="invoiceTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái thanh toán</th>
                    <th>Trạng thái đơn hàng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info">
                        {{ session('info') }}
                    </div>
                @endif
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_id }}</td>
                        <td>{{ $invoice->user->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->user->email ?? 'N/A' }}</td>
                        <td>{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td class="text-end">{{ number_format($invoice->total) }}đ</td>
                        <td>
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
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <a href="{{ route('admin.invoice.edit', $invoice->invoice_id) }}"
                                class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                <i class="fa fa-edit"></i>
                            </a>


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
        $('#invoiceTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
            },
            "order": [
                [0, "desc"]
            ],
            "columnDefs": [
                {
                    "targets": [4], // Cột tổng tiền
                    "className": "text-end"
                },
                {
                    "targets": [7], // Cột thao tác
                    "orderable": false
                }
            ]
        });
    });



    // Filter functions
    function filterByStatus(status) {
        var table = $('#invoiceTable').DataTable();
        if (status === 'all') {
            table.search('').draw();
        } else {
            table.search(status).draw();
        }
    }

    // Add filter buttons
    $(document).ready(function() {
        var filterHtml = `
            <div class="mx-3">
                <label class="form-label">Lọc theo trạng thái:</label>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterByStatus('all')">Tất cả</button>
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterByStatus('Đang xử lý')">Chờ xử lý</button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterByStatus('completed')">Đã thanh toán</button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="filterByStatus('delivered')">Đã giao hàng</button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterByStatus('cancelled')">Đã hủy</button>
                </div>
            </div>
        `;
        $('.card-body').prepend(filterHtml);
    });
</script>

@endsection
