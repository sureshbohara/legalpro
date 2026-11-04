<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingService
{
   
    public function getSettings(): Setting
    {
        return Setting::getInstance();
    }


    public function updateSettings(Setting $setting, array $data, $request): Setting
    {
 
        if ($request->hasFile('logo')) {
            $this->deleteOldFile($setting->logo);
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $this->deleteOldFile($setting->favicon);
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }


        if (!empty($data['mail_password']) && $data['mail_password'] === '********') {
            unset($data['mail_password']); 
        }

    
        $setting->update($data);


        if (array_key_exists('mail_host', $data) || array_key_exists('mail_port', $data)) {
            
        }

        return $setting->fresh();
    }


    private function deleteOldFile(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}