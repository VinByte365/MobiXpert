<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct($order, $pdf)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->view('emails.order_confirmation')
            ->subject('Order Confirmation #'.$this->order->order_id)
            ->attachData($this->pdf->output(), 'receipt.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
