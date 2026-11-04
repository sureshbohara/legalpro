<?php
namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest {
    public function authorize(): bool { return true; }
    
    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,corporate',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
        
            'registration_number' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'website' => 'nullable|url|max:255',
            
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];
    }
}