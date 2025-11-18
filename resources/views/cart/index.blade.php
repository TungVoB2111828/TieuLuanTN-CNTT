@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<script>
    function updateCartQuantity(productId, action) {
        $.ajax({
            url: "{{ route('cart.update') }}",
            type: "POST",
            data: {
                product_id: productId,
                action: action,
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Cập nhật giỏ hàng thất bại');
                }
            },
            error: function() {
                alert('Lỗi kết nối server');
            }
        });
    }

    function confirmRemove(productId) {
        if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
            updateCartQuantity(productId, 'remove');
        }
    }
</script>
<div class="container mx-auto px-4 py-6 max-w-6xl">
    <h1 class="text-2xl font-bold mb-6">🛒 Giỏ hàng</h1>

    @if(count($products) > 0)
    <div class="flex flex-col lg:flex-row gap-6 justify-center items-start">
        <div class="w-full lg:w-2/3 bg-white shadow-md rounded-lg p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left text-sm font-semibold text-gray-700">
                            <th class="py-3">Sản phẩm</th>
                            <th class="py-3">Giá</th>
                            <th class="py-3">Số lượng</th>
                            <th class="py-3">Tổng</th>
                            <th class="py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($products as $id => $item)
                        <tr>
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item['product']->image_url ? asset('storage/' . $item['product']->image_url) : asset('images/no-image.jpg') }}"
                                         alt="{{ $item['product']->name }}"
                                         class="w-16 h-16 object-cover rounded-md border" />
                                    <a href="{{ route('products.show', $item['product']->product_id) }}"
                                       class="text-blue-600 hover:underline">
                                        {{ $item['product']->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-4">{{ number_format($item['product']->price, 0, ',', '.') }} VNĐ</td>
                            <td class="py-4">
                                <div class="flex items-center border rounded w-24">
                                    <button type="button" class="px-2 py-1" onclick="updateCartQuantity('{{ $id }}', 'decrease')">-</button>
                                    <input type="text" class="w-10 text-center border-l border-r" value="{{ $item['quantity'] }}" readonly>
                                    <button type="button" class="px-2 py-1" onclick="updateCartQuantity('{{ $id }}', 'increase')">+</button>
                                </div>
                            </td>
                            <td class="py-4">{{ number_format($item['product']->price * $item['quantity'], 0, ',', '.') }} VNĐ</td>
                            <td class="py-4">
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="confirmRemove('{{ $id }}')" title="Xóa sản phẩm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{ route('home') }}" class="text-blue-600 hover:underline">
                    ← Tiếp tục mua sắm
                </a>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline">🗑 Xóa giỏ hàng</button>
                </form>
            </div>
        </div>

        <div class="w-full lg:w-1/3 bg-white shadow-md rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4">Tóm tắt đơn hàng</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                </div>
                <div class="flex justify-between">
                    <span>Phí vận chuyển:</span>
                    <span>{{ number_format(20000, 0, ',', '.') }} VNĐ</span>
                </div>
                <hr>
                <div class="flex justify-between font-bold text-base">
                    <span>Tổng cộng:</span>
                    <span>{{ number_format($total + 20000, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>
            <a href="{{ route('checkout') }}"
                class="block mt-6 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded">
                Tiến hành thanh toán
            </a>
        </div>
    </div>
    @else
    <div class="text-center py-20">
        <i class="fas fa-shopping-cart fa-3x text-gray-400 mb-4"></i>
        <h3 class="text-xl font-semibold">Giỏ hàng của bạn đang trống</h3>
        <p class="text-gray-500 mb-4">Hãy thêm sản phẩm vào giỏ hàng của bạn.</p>
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tiếp tục mua sắm
        </a>
    </div>
    @endif
</div>

@endsection
