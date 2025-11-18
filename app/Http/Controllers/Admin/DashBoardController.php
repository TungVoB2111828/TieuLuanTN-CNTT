<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy số liệu tổng quan
        $invoiceCount = DB::table('invoice')->count();
        $invoiceCompletedCount = DB::table('invoice')
                                    ->where('payment_status', 'completed')
                                    ->count();
        $productCount = DB::table('products')->count();

        // Thêm tổng số danh mục
        $categoryCount = DB::table('categories')->count();

        // Thêm tổng số nhân viên
        $staffCount = DB::table('staff')->count();

        return view('admin.home.index', [
            'invoiceCount' => $invoiceCount,
            'invoiceCompletedCount' => $invoiceCompletedCount,
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'staffCount' => $staffCount,
        ]);
    }

    public function getTimeData(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $toDate = $request->get('to_date', Carbon::now()->format('Y-m-d'));

        $timeData = DB::table('invoice')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as invoices'),
                DB::raw('SUM(total) as revenue')
            )
            ->whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate])
            ->where('payment_status', 'completed')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return response()->json($timeData);
    }

    public function getCustomerData()
    {
        $customerData = DB::table('users')
            ->join('invoice', 'users.user_id', '=', 'invoice.user_id')
            ->select(
                'users.name',
                DB::raw('COUNT(invoice.Invoice_id) as orders'),
                DB::raw('SUM(invoice.total) as total')
            )
            ->where('invoice.payment_status', 'completed')
            ->groupBy('users.user_id', 'users.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return response()->json($customerData);
    }

    public function getProductData()
    {
        $productData = DB::table('products')
            ->join('invoice_detail', 'products.product_id', '=', 'invoice_detail.product_id')
            ->join('invoice', 'invoice_detail.invoice_id', '=', 'invoice.Invoice_id')
            ->select(
                'products.name',
                DB::raw('SUM(invoice_detail.quantity) as quantity'),
                DB::raw('SUM(invoice_detail.quantity * invoice_detail.price) as revenue')
            )
            ->where('invoice.payment_status', 'completed')
            ->groupBy('products.product_id', 'products.name')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get();

        return response()->json($productData);
    }
}
