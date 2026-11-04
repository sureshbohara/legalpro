<?php
namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable {
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice) {
        $this->invoice = $invoice->load(['client']);
    }

    public function build() {
        return $this->subject('💰 Invoice Reminder: ' . $this->invoice->invoice_number)
                    ->view('emails.invoice_reminder');
    }
}