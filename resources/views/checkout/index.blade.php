@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Thanh toán</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Thông tin giao hàng -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Thông tin giao hàng</h2>
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    @php $user = auth()->user(); @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-1">Họ tên</label>
                            <input type="text" name="name" id="name"
                                class="w-full border rounded-lg px-3 py-2 @error('name') border-red-500 @enderror"
                                value="{{ $user->name ?? old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium mb-1">Số điện thoại</label>
                            <input type="text" name="phone" id="phone"
                                class="w-full border rounded-lg px-3 py-2 @error('phone') border-red-500 @enderror"
                                value="{{ $user->phone ?? old('phone') }}" required>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full border rounded-lg px-3 py-2 @error('email') border-red-500 @enderror"
                            value="{{ $user->email ?? old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium mb-1">Địa chỉ giao hàng</label>
                        <input type="text" name="address" id="address"
                            class="w-full border rounded-lg px-3 py-2 @error('address') border-red-500 @enderror"
                            value="{{ $user->address ?? old('address') }}" required>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="note" class="block text-sm font-medium mb-1">Ghi chú đơn hàng</label>
                        <textarea name="note" id="note" rows="3"
                            class="w-full border rounded-lg px-3 py-2">{{ old('note') }}</textarea>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="bg-gray-50 rounded-xl p-4 border mb-6">
                        <h2 class="text-md font-semibold mb-2">Phương thức thanh toán</h2>

                        <label class="flex items-center mb-2 space-x-2">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span>Thanh toán khi nhận hàng (COD)</span>
                        </label>

                    <!-- Đơn hàng -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Đơn hàng của bạn</h2>
                        @php
                            $cart = session()->get('cart', []);
                            $shippingFee = 30000;
                            $totalProducts = 0;
                        @endphp

                        <div class="space-y-2">
                            @foreach($cart as $id => $item)
                                @php 
                                    $product = App\Models\Product::find($id);
                                @endphp
                                @if($product)
                                    @php
                                        $subtotal = $product->price * $item['quantity'];
                                        $totalProducts += $subtotal;
                                    @endphp
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $product->name }} x {{ $item['quantity'] }}</span>
                                        <span>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="flex justify-between">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($totalProducts, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phí vận chuyển:</span>
                            <span>{{ number_format($shippingFee, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg mt-3">
                            <span>Tổng cộng:</span>
                            <span>{{ number_format($totalProducts + $shippingFee, 0, ',', '.') }} VNĐ</span>
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Đặt hàng
                        </button>
                        <input type="hidden" name="total_amount" value="{{ $totalProducts + $shippingFee }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection