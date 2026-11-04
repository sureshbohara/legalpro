<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Mail\InvoiceReminderMail;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller {
    protected InvoiceService $invoiceService;


    public function __construct(InvoiceService $invoiceService) {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'status', 'client_id');
        $invoices = $this->invoiceService->getInvoices($filters);
        return response()->json(['success' => true, 'data' => $invoices]);
    }


    public function show(Invoice $invoice) {
        return response()->json([
            'success' => true, 
            'invoice' => $invoice->load(['client', 'case', 'creator', 'items', 'payments'])
        ]);
    }


    public function store(StoreInvoiceRequest $request) {
        $invoice = $this->invoiceService->createInvoice(
            $request->only('invoice_number', 'invoice_date', 'due_date', 'client_id', 'case_id', 'tax_rate', 'discount', 'notes'),
            $request->items,
            $request->input('time_entry_ids', []) 
        );
        return response()->json([
            'success' => true, 
            'message' => 'Invoice created successfully', 
            'invoice' => $invoice
        ], 201);
    }


    public function update(UpdateInvoiceRequest $request, Invoice $invoice) {
        $updated = $this->invoiceService->updateInvoice(
            $invoice,
            $request->only('invoice_number', 'invoice_date', 'due_date', 'client_id', 'case_id', 'tax_rate', 'discount', 'notes'),
            $request->items
        );
        
        return response()->json(['success' => true, 'message' => 'Invoice updated successfully', 'invoice' => $updated]);
    }


    public function destroy(Invoice $invoice) {
        $this->invoiceService->deleteInvoice($invoice);
        return response()->json(['success' => true, 'message' => 'Invoice deleted successfully']);
    }


    public function updateStatus(Request $request, Invoice $invoice) {
        try {
            $request->validate(['status' => 'required|in:draft,sent,partial,paid,overdue,cancelled']);
            $updated = $this->invoiceService->updateStatus($invoice, $request->status);
            return response()->json(['success' => true, 'message' => 'Status updated', 'invoice' => $updated]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }


    public function downloadPdf(Invoice $invoice) {
        $invoice->load(['client', 'case', 'items']);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download("Invoice_{$invoice->invoice_number}.pdf");
    }


    public function sendReminder(Invoice $invoice) {
        try {
            if ($invoice->client && $invoice->client->email) {
                Mail::to($invoice->client->email)->send(new InvoiceReminderMail($invoice));
            }
            return response()->json(['success' => true, 'message' => 'Invoice reminder sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }



    public function getClientInvoices(Request $request) {
    $client = $request->user();
    $invoices = \App\Models\Invoice::where('client_id', $client->id)
        ->with(['case:id,case_number,title'])
        ->orderBy('invoice_date', 'desc')
        ->get();
    return response()->json(['success' => true, 'data' => $invoices]);
   }



    
}