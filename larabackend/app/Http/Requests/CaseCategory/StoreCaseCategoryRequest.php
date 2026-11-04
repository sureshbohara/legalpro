<?php
namespace App\Http\Requests\CaseCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaseCategoryRequest extends FormRequest {
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'name' => 'required|string|unique:case_categories,name|max:255',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];
    }
    
}