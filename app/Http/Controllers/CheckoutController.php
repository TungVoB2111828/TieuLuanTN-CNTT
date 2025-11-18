<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Mail\InvoiceMail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'required|email',
            'address'         => 'required|string|max:500',
            'payment_method'  => 'required|in:cod,vnpay',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $total += $product->price * $item['quantity'];
            }
        }
        $total += 30000; // Phí vận chuyển

        // Lưu tổng tiền vào session
        session()->put('total_amount', $total);

        // Xử lý người dùng
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->save();
        } else {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'phone'    => $request->phone,
                    'address'  => $request->address,
                    'password' => Hash::make('12345678'),
                ]);
            }
        }

        if ($request->payment_method == 'cod') {
            // Tạo đơn hàng ngay
            $invoice = Invoice::create([
                'user_id'        => $user->user_id,
                'payment_status' => 'pending',
                'order_status'   => 'pending',
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

            $details = InvoiceDetail::where('invoice_id', $invoice->invoice_id)->with('product')->get();
            Mail::to($user->email)->send(new InvoiceMail($invoice, $details));

            session()->forget('cart');
            session()->forget('total_amount');

            return redirect()->route('home')->with('success', 'Đặt hàng thành công! Hóa đơn đã gửi về email.');
        }

        if ($request->payment_method == 'vnpay') {
            // Lưu thông tin tạm vào session để dùng khi callback
            session()->put('checkout_user_id', $user->user_id);
            session()->put('checkout_cart', $cart);
            session()->put('checkout_total', $total);

            // Trả về view auto submit để redirect sang VNPay
            return view('vnpay.auto_submit', [
                'amount' => $total,
                'email'  => $user->email,
            ]);
        }

        return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
    }

}

