<?php
namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest {

    public function authorize(): bool { return true; }
    
    public function rules(): array {
        
        $invoiceId = $this->route('invoice')?->id;
        return [
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoiceId . '|max:50',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'client_id' => 'required|exists:clients,id',
            'case_id' => 'nullable|exists:cases,id',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
        ];
    }
}