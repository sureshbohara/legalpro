<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
class PaymentController extends Controller {
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService) {
        $this->paymentService = $paymentService;
    }

    public function store(StorePaymentRequest $request, Invoice $invoice) {
        $payment = $this->paymentService->createPayment($invoice, $request->validated());
        
        return response()->json([
            'success' => true, 
            'message' => 'Payment recorded successfully', 
            'payment' => $payment
        ], 201);
    }

    public function destroy(Payment $payment) {
        $this->paymentService->deletePayment($payment);
        return response()->json(['success' => true, 'message' => 'Payment deleted successfully']);
    }


    public function searchByInvoice(Request $request) {
        $request->validate([
            'invoice_number' => 'required|string|min:3'
        ]);
        $invoice = Invoice::where('invoice_number', 'like', '%' . $request->invoice_number . '%')
            ->with(['client:id,name', 'payments.recorder:id,name'])
            ->first();
        if (!$invoice) {
            return response()->json([
                'success' => false, 
                'message' => 'No invoice found with this number'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'invoice' => $invoice,
                'payments' => $invoice->payments
            ]
        ]);
    }




}