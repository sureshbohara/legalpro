<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            // General Info
            'system_name' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'extra_email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:50',
            'extra_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'opening_hr' => 'nullable|string|max:100',
            'work_hours' => 'nullable|string|max:100',

            // Social Media
            'facebook' => 'nullable|url|max:191',
            'twitter' => 'nullable|url|max:191',
            'linkedin' => 'nullable|url|max:191',
            'instagram' => 'nullable|url|max:191',
            'youtube' => 'nullable|url|max:191',

            // API & Info
            'open_api_key' => 'nullable|string',
            'info1' => 'nullable|string',
            'info2' => 'nullable|string',

            // Media (Images)
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:1024',

            // SMTP Configuration
            'mail_transport' => 'nullable|string|max:50',
            'mail_host' => 'nullable|string|max:191',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string|max:191',
            'mail_password' => 'nullable|string|max:191',
            'mail_encryption' => 'nullable|string|in:tls,ssl,null',
            'mail_from' => 'nullable|email|max:191',
            'mail_from_name' => 'nullable|string|max:191',
            'smtp_check' => 'nullable|boolean',
        ];
    }
}