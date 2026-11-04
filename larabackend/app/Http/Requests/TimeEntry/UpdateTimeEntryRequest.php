<?php
namespace App\Http\Requests\TimeEntry;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeEntryRequest extends FormRequest {
    public function authorize(): bool { return true; }
    
    public function rules(): array {
        return [
            'case_id' => 'nullable|exists:cases,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'required|string|max:500',
            'work_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1',
            'billable_rate' => 'nullable|numeric|min:0',
            'is_billable' => 'nullable|boolean',
            'status' => 'nullable|in:draft,submitted,approved,invoiced',
        ];
    }
}