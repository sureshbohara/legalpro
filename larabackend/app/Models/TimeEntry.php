<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TimeEntry extends Model {
    use HasFactory, LogsActivity;

    protected $fillable = [
        'case_id', 'user_id', 'task_id',
        'description', 'work_date', 'start_time', 'end_time', 'duration_minutes',
        'billable_rate', 'total_amount', 'is_billable', 'status', 'is_active'
    ];

    protected $casts = [
        'work_date' => 'date',
        'billable_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_billable' => 'boolean',
        'is_active' => 'boolean',
    ];

  
    public function case() { return $this->belongsTo(CaseModel::class, 'case_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function task() { return $this->belongsTo(Task::class); }

    
    public function getFormattedDurationAttribute() {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }

   
    public static function calculateDuration($startTime, $endTime) {
        if (!$startTime || !$endTime) return 0;
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        return max(0, ($end - $start) / 60);
    }
}