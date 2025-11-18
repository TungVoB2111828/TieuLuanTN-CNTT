@if ($ordersList->isEmpty())
    <p class="text-gray-500">Không có đơn hàng nào trong mục này.</p>
@else
    <div class="space-y-3">
        @foreach ($ordersList as $order)
            <div class="border rounded-lg p-4 flex justify-between text-sm bg-white shadow-sm">
                <div>
                    <div>Mã đơn: <strong>#{{ $order->id }}</strong></div>
                    <div>Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</div>

                    <div class="mt-2 space-y-2">
                        @foreach ($order->products as $product)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('images/no-image.jpg') }}"
                                     alt="{{ $product->name }}"
                                     class="w-16 h-16 object-cover rounded-md border" />
                                <a href="{{ route('products.show', $product->id) }}"
                                   class="text-blue-600 hover:underline">
                                    {{ $product->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-right">
                    <div>Tổng: <strong>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong></div>
                    <div>Thanh toán: {{ ucfirst($order->payment_status) }}</div>
                </div>
            </div>
        @endforeach
    </div>

@endif

