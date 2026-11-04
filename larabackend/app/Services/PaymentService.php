<?php
namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;

class PaymentService {
    public function createPayment(Invoice $invoice, array $data): Payment {
        $data['invoice_id'] = $invoice->id;
        $data['recorded_by'] = auth()->id();

        $payment = Payment::create($data);

        $newPaidAmount = $invoice->paid_amount + $data['amount'];
        $invoice->update(['paid_amount' => $newPaidAmount]);

        if ($newPaidAmount >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'partial']);
        }

        return $payment->load('recorder');
    }

    public function deletePayment(Payment $payment): bool {
        $invoice = $payment->invoice;
        
        $newPaidAmount = max(0, $invoice->paid_amount - $payment->amount);
        $invoice->update(['paid_amount' => $newPaidAmount]);


        if ($newPaidAmount == 0) {
            $invoice->update(['status' => 'sent']);
        } elseif ($newPaidAmount < $invoice->total_amount) {
            $invoice->update(['status' => 'partial']);
        }

        return $payment->delete();
    }
}