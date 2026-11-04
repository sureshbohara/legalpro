<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
   
    public function getNotifications(?int $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => (string) $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'link' => $notification->link,
                    'is_read' => $notification->is_read,
                    'time' => $notification->created_at->diffForHumans(),
                    'created_at' => $notification->created_at->toIso8601String(),
                ];
            });
    }

  
    public function getUnreadCount(?int $userId = null): int
    {
        $userId = $userId ?? Auth::id();
        return Notification::forUser($userId)->unread()->count();
    }


    public function markAsRead(int $notificationId, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();
        
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
            return true;
        }
        return false;
    }


    public function markAllAsRead(?int $userId = null): int
    {
        $userId = $userId ?? Auth::id();
        
        return Notification::forUser($userId)
            ->unread()
            ->update(['is_read' => true]);
    }

  
    public function delete(int $notificationId, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();
        
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }


    public function create(int $userId, string $title, string $message, string $type = 'system', ?string $link = null): Notification
    {
        return Notification::notify($userId, $title, $message, $type, $link);
    }
}