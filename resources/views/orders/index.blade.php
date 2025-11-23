@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">Theo dõi đơn hàng</h2>

    @php
        $tabs = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Chờ lấy hàng',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Thành công',
            'cancelled' => 'Đã hủy',
        ];
    @endphp

    {{-- Thanh tab trạng thái --}}
    <div class="mb-4 flex space-x-4 border-b">
        @foreach($tabs as $status => $label)
            <button onclick="showTab('{{ $status }}')"
                class="py-2 px-4 hover:text-blue-600 font-medium"
                id="tab-{{ $status }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Nội dung từng tab --}}
    @foreach($tabs as $status => $label)
        <div id="content-{{ $status }}" class="tab-content hidden">
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="text-lg font-semibold mb-3">{{ $label }}</h3>

                @php
                    $filtered = $invoices->filter(fn($inv) => $inv->order_status === $status);
                @endphp

                @forelse($filtered as $invoice)
                    <div class="flex justify-between border-b py-2 text-sm invoice-row" id="invoice-{{ $invoice->invoice_id }}">
                        <div>
                            <div>Mã đơn: <strong>{{ $invoice->invoice_id }}</strong></div>
                            <div>Ngày đặt: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div>Tổng: <strong>{{ number_format($invoice->total, 0, ',', '.') }} VNĐ</strong></div>
                            <div>Thanh toán: 
                                @switch($invoice->payment_status)
                                    @case('completed') Đã thanh toán @break
                                    @case('pending') Chờ thanh toán @break
                                    @case('failed') Thất bại @break
                                    @case('refunded') Đã hoàn tiền @break
                                    @default {{ $invoice->payment_status }}
                                @endswitch
                            </div>
                            {{-- Nút Hủy chỉ hiện ở new & processing --}}
                            @if(in_array($invoice->order_status, ['pending', 'processing']))
                                <button class="mt-1 px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600"
                                    onclick="cancelInvoice('{{ $invoice->invoice_id }}')">
                                    Hủy đơn
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Không có đơn hàng.</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<script>
function showTab(status) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    document.getElementById('content-' + status).classList.remove('hidden');

    document.querySelectorAll('button[id^="tab-"]').forEach(btn => {
        btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
    });
    document.getElementById('tab-' + status).classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
}

// Mặc định mở tab Chờ xác nhận
document.addEventListener('DOMContentLoaded', () => {
    showTab('new');
});

// AJAX Hủy đơn
function cancelInvoice(invoiceId) {
    if(!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) return;

    fetch(`/orders/cancel/${invoiceId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Xóa row khỏi tab cũ
            const row = document.getElementById('invoice-' + invoiceId);
            if(row) row.remove();

            // Thêm row vào tab cancelled
            const cancelledTab = document.getElementById('content-cancelled').querySelector('.bg-white');
            const newRow = document.createElement('div');
            newRow.classList.add('flex','justify-between','border-b','py-2','text-sm','invoice-row');
            newRow.id = 'invoice-' + data.invoice_id;
            newRow.innerHTML = `
                <div>
                    <div>Mã đơn: <strong>${data.invoice_id}</strong></div>
                    <div>Ngày đặt: ${data.created_at}</div>
                </div>
                <div class="text-right">
                    <div>Tổng: <strong>${data.total}</strong></div>
                    <div>Thanh toán: ${data.payment_status_text}</div>
                </div>
            `;
            cancelledTab.appendChild(newRow);

            alert('Đơn hàng đã được hủy thành công.');
        } else {
            alert(data.message || 'Có lỗi xảy ra.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra, vui lòng thử lại.');
    });
}
</script>
@endsection
