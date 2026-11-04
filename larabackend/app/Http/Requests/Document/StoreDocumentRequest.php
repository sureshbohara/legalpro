<?php
namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest {
    public function authorize(): bool { return true; }
    
    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'category' => 'required|in:evidence,contract,correspondence,general',
            'description' => 'nullable|string',
            'case_id' => 'nullable|exists:cases,id',
            'client_id' => 'nullable|exists:clients,id',
            'is_active' => 'nullable|boolean',
        ];
    }
}