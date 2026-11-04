<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(){
        $settings = $this->settingService->getSettings();
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }


    public function update(UpdateSettingRequest $request){
        $setting = $this->settingService->getSettings();
        $updated = $this->settingService->updateSettings(
            $setting, 
            $request->validated(), 
            $request
        );
        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
            'data' => $updated
        ]);
    }
}