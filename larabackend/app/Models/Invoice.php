<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Invoice extends Model {
    use HasFactory, LogsActivity;

    protected $fillable = [
        'invoice_number', 'invoice_date', 'due_date',
        'client_id', 'case_id', 'created_by',
        'subtotal', 'tax_rate', 'tax_amount', 'discount', 'total_amount', 'paid_amount',
        'status', 'notes', 'is_active'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function case() {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }


    public function getOutstandingAmountAttribute() {
        return $this->total_amount - $this->paid_amount;
    }
    
}