@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-8 text-gray-800">Thanh toán</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Khung thông tin giao hàng -->
        <div class="lg:col-span-2 space-y-6">

            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                @csrf
                @php $user = auth()->user(); @endphp

                <div class="bg-white rounded-2xl shadow-md p-6 border">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin giao hàng</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Họ tên -->
                        <div>
                            <label class="block text-sm mb-1 font-medium">Họ tên</label>
                            <input type="text" name="name"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
                                   value="{{ $user->name ?? old('name') }}" required>
                        </div>

                        <!-- Số điện thoại -->
                        <div>
                            <label class="block text-sm mb-1 font-medium">Số điện thoại</label>
                            <input type="text" name="phone"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
                                   value="{{ $user->phone ?? old('phone') }}" required>
                        </div>

                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <label class="block text-sm mb-1 font-medium">Email</label>
                        <input type="email" name="email"
                               class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
                               value="{{ $user->email ?? old('email') }}" required>
                    </div>

                    <!-- Địa chỉ -->
                    <div class="mt-4">
                        <label class="block text-sm mb-1 font-medium">Địa chỉ giao hàng</label>
                        <input type="text" name="address"
                               class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
                               value="{{ $user->address ?? old('address') }}" required>
                    </div>

                    <!-- Ghi chú -->
                    <div class="mt-4">
                        <label class="block text-sm mb-1 font-medium">Ghi chú đơn hàng</label>
                        <textarea name="note" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none">{{ old('note') }}</textarea>
                    </div>

                </div>

                <!-- Phương thức thanh toán -->
                <div class="bg-white rounded-2xl shadow-md p-6 border">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Phương thức thanh toán</h2>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="payment_method" value="cod" checked class="accent-blue-600">
                        <span class="text-gray-700">Thanh toán khi nhận hàng (COD)</span>
                    </label>
                </div>

        </div>

        <!-- Khung đơn hàng -->
        <div class="bg-white rounded-2xl shadow-md p-6 border h-fit">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Đơn hàng của bạn</h2>

            @php
                $cart = session()->get('cart', []);
                $shippingFee = 20000;
                $totalProducts = 0;
            @endphp

            <div class="space-y-3">
                @foreach ($cart as $id => $item)
                    @php $product = App\Models\Product::find($id); @endphp
                    @if ($product)
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

            <div class="flex justify-between text-gray-700">
                <span>Tạm tính:</span>
                <span>{{ number_format($totalProducts, 0, ',', '.') }} VNĐ</span>
            </div>

            <div class="flex justify-between text-gray-700 mt-2">
                <span>Phí vận chuyển:</span>
                <span>{{ number_format($shippingFee, 0, ',', '.') }} VNĐ</span>
            </div>

            <div class="flex justify-between font-bold text-lg text-gray-900 mt-4">
                <span>Tổng cộng:</span>
                <span>{{ number_format($totalProducts + $shippingFee, 0, ',', '.') }} VNĐ</span>
            </div>

            <button type="submit"
                    class="w-full mt-6 bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white font-semibold py-3 rounded-xl shadow">
                Đặt hàng
            </button>

            <input type="hidden" name="total_amount" value="{{ $totalProducts + $shippingFee }}">

        </div>

        </form>
    </div>

</div>
@endsection
