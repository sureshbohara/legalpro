<?php
namespace App\Http\Requests\CaseStatus;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaseStatusRequest extends FormRequest {
    
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'name' => 'required|string|unique:case_statuses,name|max:255',
            'color' => 'nullable|string|max:7',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];
    }
}