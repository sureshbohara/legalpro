<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog; 
use Illuminate\Http\Request;

class ActivityLogController extends Controller {

    public function index(Request $request) {
        try {
            $logs = ActivityLog::with(['causer', 'subject'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
                
            return response()->json([
                'success' => true, 
                'data' => $logs
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to fetch logs: ' . $e->getMessage()
            ], 500);
        }
    }
}