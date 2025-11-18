@component('mail::message')
# Hóa đơn đặt hàng

Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi. Dưới đây là thông tin đơn hàng:

**Mã hóa đơn:** {{ $invoice->invoice_id }}  
**Ngày đặt:** {{ $invoice->created_at->format('d/m/Y H:i') }}  
**Trạng thái thanh toán:** {{ $invoice->payment_status }}  
**Trạng thái đơn hàng:** {{ $invoice->order_status }}

| Sản phẩm         | Số lượng | Đơn giá       | Thành tiền     |
|------------------|:--------:|--------------:|---------------:|
@foreach ($details as $item)
| {{ $item->product ? $item->product->name : 'Sản phẩm không tồn tại' }} | {{ $item->quantity }} | {{ number_format($item->price, 0, ',', '.') }} VNĐ | {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ |
@endforeach

**Phí vận chuyển:** {{ number_format(30000, 0, ',', '.') }} VNĐ 

**Tổng cộng:** {{ number_format($invoice->total, 0, ',', '.') }} VNĐ

Cảm ơn bạn đã tin tưởng và ủng hộ!

@endcomponent
