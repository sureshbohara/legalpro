<?php
namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity {
    public static function bootLogsActivity() {

        static::created(function ($model) {
            self::logActivity($model, 'created');
        });

   
        static::updated(function ($model) {
            $changes = $model->getDirty();
            if (!empty($changes)) {
                $properties = [
                    'old' => $model->getOriginal(),
                    'new' => $changes,
                ];
                self::logActivity($model, 'updated', $properties);
            }
        });
        static::deleted(function ($model) {
            self::logActivity($model, 'deleted', ['old' => $model->getOriginal()]);
        });
    }

    protected static function logActivity($model, string $action, array $properties = []) {
        ActivityLog::create([
            'log_name' => strtolower(class_basename($model)), 
            'description' => ucfirst($action) . ' ' . class_basename($model), 
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}