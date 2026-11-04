<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Task extends Model {
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title', 'description', 'priority', 'status',
        'case_id', 'assigned_to', 'created_by',
        'deadline', 'is_active'
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
    ];

    public function case() {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function assignee() {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}