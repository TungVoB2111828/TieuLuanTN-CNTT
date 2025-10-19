@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">Theo dõi đơn hàng</h2>

    @php
        $tabs = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Chờ lấy hàng',
            'shipping' => 'Chờ giao hàng',
            'completed' => 'Thành công',
        ];
    @endphp

    <div class="mb-4 flex space-x-4 border-b">
        @foreach($tabs as $status => $label)
            <button onclick="showTab('{{ $status }}')"
                class="py-2 px-4 hover:text-blue-600 font-medium"
                id="tab-{{ $status }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @foreach($tabs as $status => $label)
        <div id="content-{{ $status }}" class="tab-content hidden">
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="text-lg font-semibold mb-3">{{ $label }}</h3>

                @php
                    $filtered = $invoices->filter(function($inv) use ($status) {
                        if ($status === 'pending') {
                            return $inv->payment_status === 'pending' && $inv->order_status === 'pending';
                        }
                        return $inv->payment_status === 'paid' && $inv->order_status === $status;
                    });
                @endphp

                @forelse($filtered as $invoice)
                    <div class="flex justify-between border-b py-2 text-sm">
                        <div>
                            <div>Mã đơn: <strong>#{{ $invoice->invoice_id }}</strong></div>
                            <div>Tên đơn: <strong>{{ $invoice->invoice_name }}</strong></div>
                            <div>Ngày đặt: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div>Tổng: <strong>{{ number_format($invoice->total, 0, ',', '.') }} VNĐ</strong></div>
                            <div>Thanh toán: {{ ucfirst($invoice->payment_status) }}</div>
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
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.add('hidden'));
        document.getElementById('content-' + status).classList.remove('hidden');

        const buttons = document.querySelectorAll('button[id^="tab-"]');
        buttons.forEach(btn => btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600'));
        document.getElementById('tab-' + status).classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    }

    document.addEventListener('DOMContentLoaded', () => {
        showTab('pending');
    });
</script>

@endsection

