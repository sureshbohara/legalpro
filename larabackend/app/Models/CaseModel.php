<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class CaseModel extends Model {
    use HasFactory, LogsActivity;

    protected $table = 'cases';
    protected $fillable = [
        'case_number', 'title', 'description', 'client_id', 'category_id', 
        'status_id', 'assigned_lawyer_id', 'opening_date', 'closing_date', 'status'
    ];
    protected $casts = [
        'status' => 'boolean',
        'opening_date' => 'date',
        'closing_date' => 'date',
    ];

   
    public function client() { return $this->belongsTo(Client::class); }

    public function category() { return $this->belongsTo(CaseCategory::class, 'category_id'); }

    public function status() { return $this->belongsTo(CaseStatus::class, 'status_id'); }

    public function lawyer() { return $this->belongsTo(User::class, 'assigned_lawyer_id'); }
    
}