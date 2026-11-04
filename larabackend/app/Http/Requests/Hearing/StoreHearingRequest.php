<?php
namespace App\Http\Requests\Hearing;

use Illuminate\Foundation\Http\FormRequest;

class StoreHearingRequest extends FormRequest {
    public function authorize(): bool { return true; }
    
    public function rules(): array {
        return [
            'case_id' => 'required|exists:cases,id',
            'hearing_date' => 'required|date',
            'hearing_time' => 'nullable|date_format:H:i',
            'court_name' => 'required|string|max:255',
            'judge_name' => 'nullable|string|max:255',
            'hearing_type' => 'required|string|max:100',
            'purpose' => 'nullable|string',
            'outcome' => 'nullable|string',
            'next_hearing_date' => 'nullable|date|after_or_equal:hearing_date',
            'status' => 'required|in:scheduled,completed,adjourned,cancelled',
            'is_active' => 'nullable|boolean',
        ];
    }
}