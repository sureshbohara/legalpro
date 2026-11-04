<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Hearing extends Model {
    use HasFactory, LogsActivity;

    protected $fillable = [
        'case_id', 'hearing_date', 'hearing_time', 'court_name', 'judge_name',
        'hearing_type', 'purpose', 'outcome', 'next_hearing_date', 'status', 'is_active'
    ];

    protected $casts = [
        'hearing_date' => 'date',
        'next_hearing_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function case() {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }
}