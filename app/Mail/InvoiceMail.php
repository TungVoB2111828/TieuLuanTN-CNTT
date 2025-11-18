<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $details;

    public function __construct($invoice, $details)
    {
        $this->invoice = $invoice;
        $this->details = $details;
    }

    public function build()
    {
        return $this->subject('Hóa đơn đặt hàng của bạn')
                    ->markdown('emails.invoice')
                    ->with([
                        'invoice' => $this->invoice,
                        'details' => $this->details,
                    ]);
    }

}
