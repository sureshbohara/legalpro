<?php
namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest {
    public function authorize(): bool { return true; }
    
    public function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:to_do,in_progress,under_review,done,cancelled',
            'case_id' => 'nullable|exists:cases,id',
            'assigned_to' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ];
    }
}