<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\User;
use App\Mail\InvoiceMail;
class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'vnpay') {
            return $this->vnpay_payment($request);
        }

        return redirect()->route('home')->with('success', 'Đặt hàng thành công (COD)');
    }

    public function vnpay_payment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "GC5D1BPG";
        $vnp_HashSecret = "57SLGFSYCL93QRKR6SUQ0CH5ISNQ5A0B";

        // Lấy tổng tiền từ session
        $cart_total = session('total_amount');
        if (!$cart_total) {
            return redirect()->route('home')->with('error', 'Không tìm thấy số tiền để thanh toán.');
        }

        $amount = $cart_total * 100; // VNPay yêu cầu đơn vị là VND * 100
        $invoiceId = $request->input('invoice_id'); // Lấy invoice_id từ form

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $request->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toán đơn hàng #" . $invoiceId,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl . '?invoice_id=' . $invoiceId,
            "vnp_TxnRef" => $invoiceId,
            "vnp_BankCode" => "NCB",
        ];

        ksort($inputData);

        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        $hashData = rtrim($hashData, '&');

        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $paymentUrl = $vnp_Url . '?' . http_build_query($inputData) . '&vnp_SecureHash=' . $vnp_SecureHash;

        return redirect($paymentUrl);
    }

    public function vnpayReturn(Request $request)
    {
        if ($request->vnp_ResponseCode == '00') {
            $userId = session('checkout_user_id');
            $cart = session('checkout_cart');
            $total = session('checkout_total');

            if (!$userId || !$cart || !$total) {
                return redirect()->route('home')->with('error', 'Không tìm thấy thông tin đơn hàng.');
            }

            // Tạo đơn hàng
            $invoice = Invoice::create([
                'user_id'        => $userId,
                'payment_status' => 'paid',
                'order_status'   => 'processing',
                'total'          => $total,
                'created_at'     => now(),
            ]);

            foreach ($cart as $id => $item) {
                $product = Product::find($id);
                if ($product) {
                    InvoiceDetail::create([
                        'invoice_id' => $invoice->invoice_id,
                        'product_id' => $product->product_id,
                        'quantity'   => $item['quantity'],
                        'price'      => $product->price,
                    ]);
                }
            }

            // Gửi mail hóa đơn
            $user = User::find($userId);
            if ($user) {
                $details = InvoiceDetail::where('invoice_id', $invoice->invoice_id)->with('product')->get();
                Mail::to($user->email)->send(new InvoiceMail($invoice, $details));
            }

            // Xóa session
            session()->forget('cart');
            session()->forget('total_amount');
            session()->forget('checkout_cart');
            session()->forget('checkout_user_id');
            session()->forget('checkout_total');

            return redirect()->route('home')->with('success', 'Thanh toán VNPay thành công, đơn hàng đã được tạo!');
        }

        return redirect()->route('home')->with('error', 'Thanh toán thất bại hoặc bị hủy! Đơn hàng chưa được tạo.');
    }
}