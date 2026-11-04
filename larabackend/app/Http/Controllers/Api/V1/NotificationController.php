<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService){
        $this->notificationService = $notificationService;
    }

   
    public function index(Request $request){
        $notifications = $this->notificationService->getNotifications();
        $unreadCount = $this->notificationService->getUnreadCount();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

  
    public function markAsRead(Request $request, Notification $notification){
        $success = $this->notificationService->markAsRead($notification->id);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notification marked as read' : 'Notification not found',
        ]);
    }


    public function markAllAsRead(){
        $count = $this->notificationService->markAllAsRead();

        return response()->json([
            'success' => true,
            'message' => "{$count} notifications marked as read",
            'count' => $count,
        ]);
    }

 
    public function destroy(Notification $notification){
        $success = $this->notificationService->delete($notification->id);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notification deleted' : 'Notification not found',
        ]);
    }


    public function unreadCount(){
        return response()->json([
            'success' => true,
            'count' => $this->notificationService->getUnreadCount(),
        ]);
    }
}