<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('user')
            ->orderBy('Invoice_id', 'desc')
            ->get();

        return view('admin.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Typically invoices are created by customers, not admin
        return redirect()->route('admin.invoice.index')
            ->with('info', 'Hóa đơn được tạo bởi khách hàng khi đặt hàng.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Invoices are typically created by the system when orders are placed
        return redirect()->route('admin.invoice.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with(['user', 'invoiceDetails.product'])
            ->findOrFail($id);

        return view('admin.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::with(['user', 'invoiceDetails.product'])
            ->findOrFail($id);

        $orderStatuses = [
            'new' => 'Đơn hàng mới',
            'pending' => 'Đang xử lý',
            'processing' => 'Đang chuẩn bị',
            'shipped' => 'Đã giao vận',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy'
        ];

        $paymentStatuses = [
            'pending' => 'Chờ thanh toán',
            'completed' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refunded' => 'Đã hoàn tiền'
        ];

        return view('admin.invoice.edit', compact('invoice', 'orderStatuses', 'paymentStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed,refunded',
            'order_status' => 'required|in:new,pending,processing,shipped,delivered,cancelled',
        ], [
            'payment_status.required' => 'Trạng thái thanh toán là bắt buộc.',
            'payment_status.in' => 'Trạng thái thanh toán không hợp lệ.',
            'order_status.required' => 'Trạng thái đơn hàng là bắt buộc.',
            'order_status.in' => 'Trạng thái đơn hàng không hợp lệ.',
        ]);

        try {
            $invoice->update([
                'payment_status' => $request->payment_status,
                'order_status' => $request->order_status,
            ]);

            return redirect()->route('admin.invoice.index')
                ->with('success', 'Cập nhật hóa đơn thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật hóa đơn: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($id);

            // Delete invoice details first
            InvoiceDetail::where('invoice_id', $id)->delete();

            // Delete invoice
            $invoice->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa hóa đơn thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa hóa đơn: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoice statistics
     */
    public function getStats()
    {
        $stats = [
            'total_invoices' => Invoice::count(),
            'pending_orders' => Invoice::where('order_status', 'pending')->count(),
            'completed_payments' => Invoice::where('payment_status', 'completed')->count(),
            'total_revenue' => Invoice::where('payment_status', 'completed')->sum('total'),
        ];

        return response()->json($stats);
    }
}
