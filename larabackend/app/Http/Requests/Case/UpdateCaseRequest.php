<?php
namespace App\Http\Requests\Case;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCaseRequest extends FormRequest {
    public function authorize(): bool { return true; }
    
    public function rules(): array {
        $caseId = $this->route('case')?->id;

        return [
            'case_number' => 'required|string|unique:cases,case_number,' . $caseId . '|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'category_id' => 'required|exists:case_categories,id',
            'status_id' => 'required|exists:case_statuses,id',
            'assigned_lawyer_id' => 'nullable|exists:users,id',
            'opening_date' => 'nullable|date',
            'closing_date' => 'nullable|date|after_or_equal:opening_date',
            'status' => 'nullable|boolean',
        ];
    }
}