<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của user hiện tại
    public function index()
    {
        $user = Auth::user();

        $invoices = Invoice::with(['details.product'])
                    ->where('user_id', $user->user_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('orders.index', compact('invoices'));
    }

    // Hủy đơn không cần điều kiện
    public function cancel(Request $request, Invoice $invoice)
    {
        // Cập nhật trạng thái thành 'cancelled' ngay lập tức
        $invoice->order_status = 'cancelled';
        $invoice->save();

        return response()->json([
            'success' => true,
            'invoice_id' => $invoice->invoice_id,
            'created_at' => $invoice->created_at->format('d/m/Y'),
            'total' => number_format($invoice->total, 0, ',', '.') . ' VNĐ',
            'payment_status_text' => match($invoice->payment_status) {
                'completed' => 'Đã thanh toán',
                'pending' => 'Chờ thanh toán',
                'failed' => 'Thất bại',
                'refunded' => 'Đã hoàn tiền',
                default => $invoice->payment_status,
            }
        ]);
    }
}
