<?php

namespace App\Http\Requests\DocumentTemplate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentTemplateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }
}