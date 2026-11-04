<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Builder;

class InvoiceService {
    
    public function getInvoices(array $filters = []) {
        return Invoice::with(['client:id,name', 'case:id,case_number,title', 'creator:id,name'])
            ->withCount('items')
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->where('invoice_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('client', function ($q) use ($filters) {
                      $q->where('name', 'like', '%' . $filters['search'] . '%');
                  });
            })
            ->when(!empty($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(!empty($filters['client_id']), function (Builder $q) use ($filters) {
                $q->where('client_id', $filters['client_id']);
            })
            ->orderBy('invoice_date', 'desc')
            ->paginate(15);
    }

    public function createInvoice(array $data, array $items, array $timeEntryIds = []): Invoice {
        $processedItems = [];
        $subtotal = 0;
        
        foreach ($items as $item) {
            $amount = (float)$item['quantity'] * (float)$item['rate'];
            $processedItems[] = [
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'amount' => $amount,
            ];
            $subtotal += $amount;
        }

        $taxRate = (float)($data['tax_rate'] ?? 13);
        $discount = (float)($data['discount'] ?? 0);
        
        $taxAmount = ($subtotal * $taxRate) / 100;
        $totalAmount = $subtotal + $taxAmount - $discount;

        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $taxAmount;
        $data['total_amount'] = $totalAmount;
        $data['created_by'] = auth()->id();

        $invoice = Invoice::create($data);

        foreach ($processedItems as $item) {
            $invoice->items()->create($item);
        }

        if (!empty($timeEntryIds)) {
            \App\Models\TimeEntry::whereIn('id', $timeEntryIds)
                ->where('status', 'approved') 
                ->update(['status' => 'invoiced']);
        }
        return $invoice->load(['client', 'case', 'creator', 'items']);
    }


    public function updateInvoice(Invoice $invoice, array $data, array $items): Invoice {
        $processedItems = [];
        $subtotal = 0;
        
        foreach ($items as $item) {
            $amount = (float)$item['quantity'] * (float)$item['rate'];
            $processedItems[] = [
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'amount' => $amount,
            ];
            $subtotal += $amount;
        }

        $taxRate = (float)($data['tax_rate'] ?? 13);
        $discount = (float)($data['discount'] ?? 0);
        
        $taxAmount = ($subtotal * $taxRate) / 100;
        $totalAmount = $subtotal + $taxAmount - $discount;

        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $taxAmount;
        $data['total_amount'] = $totalAmount;

        $invoice->update($data);

        $invoice->items()->delete();
        foreach ($processedItems as $item) {
            $invoice->items()->create($item);
        }

        return $invoice->fresh(['client', 'case', 'creator', 'items']);
    }

    public function deleteInvoice(Invoice $invoice): bool {
        return $invoice->delete();
    }

    public function updateStatus(Invoice $invoice, string $status): Invoice {
        $invoice->update(['status' => $status]);
        return $invoice->fresh();
    }
}