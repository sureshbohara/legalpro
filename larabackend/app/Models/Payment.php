<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Payment extends Model {
    use LogsActivity;

    protected $fillable = [
        'invoice_id', 'payment_date', 'amount', 
        'payment_method', 'reference_number', 'notes', 'recorded_by'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function recorder() {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}