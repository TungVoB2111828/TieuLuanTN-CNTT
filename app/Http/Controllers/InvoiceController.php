<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function sendInvoiceEmail($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        $details = $invoice->details; // hoặc quan hệ tương ứng
        $user = $invoice->user; // hoặc lấy email từ đâu đó

        if (!$user || !$user->email) {
            // Xử lý trường hợp email không có
            return redirect()->back()->with('error', 'Không tìm thấy email người nhận.');
        }

        Mail::to($user->email)->send(new \App\Mail\InvoiceMail($invoice, $details));

        return redirect()->back()->with('success', 'Gửi email thành công!');
    }
}

