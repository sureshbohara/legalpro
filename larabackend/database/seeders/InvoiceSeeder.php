<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\CaseModel;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();
        $cases = CaseModel::all();

        if ($clients->isEmpty()) {
            $this->command->error('⚠️ Please seed Clients first!');
            return;
        }

        $invoices = [
            [
                'invoice_number' => 'INV-2024-001',
                'invoice_date' => Carbon::now()->subDays(30)->toDateString(),
                'due_date' => Carbon::now()->subDays(15)->toDateString(), // Overdue
                'status' => 'overdue',
                'tax_rate' => 13,
                'discount' => 0,
                'items' => [
                    ['description' => 'Legal Consultation (5 hours)', 'quantity' => 5, 'rate' => 2500],
                    ['description' => 'Document Drafting', 'quantity' => 1, 'rate' => 5000],
                ]
            ],
            [
                'invoice_number' => 'INV-2024-002',
                'invoice_date' => Carbon::now()->subDays(10)->toDateString(),
                'due_date' => Carbon::now()->addDays(5)->toDateString(),
                'status' => 'partial',
                'tax_rate' => 13,
                'discount' => 1000,
                'items' => [
                    ['description' => 'Court Hearing Representation', 'quantity' => 1, 'rate' => 15000],
                ]
            ],
            [
                'invoice_number' => 'INV-2024-003',
                'invoice_date' => Carbon::now()->subDays(5)->toDateString(),
                'due_date' => Carbon::now()->addDays(10)->toDateString(),
                'status' => 'paid',
                'tax_rate' => 13,
                'discount' => 0,
                'items' => [
                    ['description' => 'Contract Review', 'quantity' => 2, 'rate' => 3000],
                ]
            ],
        ];

        foreach ($invoices as $index => $invData) {
            $client = $clients[$index % $clients->count()];
            $case = $cases->isNotEmpty() ? $cases[$index % $cases->count()] : null;

            $subtotal = 0;
            foreach ($invData['items'] as $item) {
                $subtotal += ($item['quantity'] * $item['rate']);
            }
            
            $taxAmount = ($subtotal * $invData['tax_rate']) / 100;
            $totalAmount = $subtotal + $taxAmount - $invData['discount'];
            
     
            $paidAmount = $invData['status'] === 'paid' ? $totalAmount : ($invData['status'] === 'partial' ? ($totalAmount / 2) : 0);

            $invoice = Invoice::updateOrCreate(
                ['invoice_number' => $invData['invoice_number']],
                [
                    'client_id' => $client->id,
                    'case_id' => $case?->id,
                    'invoice_date' => $invData['invoice_date'],
                    'due_date' => $invData['due_date'],
                    'subtotal' => $subtotal,
                    'tax_rate' => $invData['tax_rate'],
                    'tax_amount' => $taxAmount,
                    'discount' => $invData['discount'],
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'status' => $invData['status'],
                    'created_by' => 1,
                ]
            );


            foreach ($invData['items'] as $item) {
                InvoiceItem::updateOrCreate(
                    ['invoice_id' => $invoice->id, 'description' => $item['description']],
                    [
                        'quantity' => $item['quantity'],
                        'rate' => $item['rate'],
                        'amount' => $item['quantity'] * $item['rate'],
                    ]
                );
            }
        }

        $this->command->info('✅ 3 Invoices with Items seeded successfully!');
    }
}