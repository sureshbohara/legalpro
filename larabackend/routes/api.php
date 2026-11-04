<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ActivityLogController;
use App\Http\Controllers\Api\V1\CaseCategoryController;
use App\Http\Controllers\Api\V1\CaseStatusController;
use App\Http\Controllers\Api\V1\CaseController;
use App\Http\Controllers\Api\V1\HearingController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\TimeEntryController;
use App\Http\Controllers\Api\V1\GlobalSearchController;
use App\Http\Controllers\Api\V1\CalendarController;
use App\Http\Controllers\Api\V1\DocumentTemplateController;
use App\Http\Controllers\Api\V1\ClientAuthController;
use App\Http\Controllers\Api\V1\NotificationController;


Route::get('/settings', [SettingController::class, 'index']);
Route::post('/settings', [SettingController::class, 'update']);


Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/client/login', [ClientAuthController::class, 'login'])->middleware('throttle:5,1');


Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {




    // Auth & Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'changePassword']);

    Route::apiResource('users', UserController::class);
    Route::post('/users/{user}/status', [UserController::class, 'userStatus']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    
    // Custom Route for assigning permissions to a role
    Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions']);

    Route::apiResource('clients', ClientController::class);
    Route::post('/clients/{client}/status', [ClientController::class, 'clientsStatus']);
    Route::post('/clients/{client}/send-portal-invite', [ClientController::class, 'sendPortalInvite']);

    Route::get('/activity-logs', [ActivityLogController::class, 'index']);

    // Case Categories
    Route::apiResource('case-categories', CaseCategoryController::class);
    Route::post('/case-categories/{caseCategory}/status', [CaseCategoryController::class, 'caseCategoryStatus']);

    // Case Statuses
    Route::apiResource('case-statuses', CaseStatusController::class);
    Route::post('/case-statuses/{caseStatus}/status', [CaseStatusController::class, 'caseStatusStatus']);

    Route::apiResource('cases', CaseController::class);

    // Hearings
    Route::apiResource('hearings', HearingController::class);
    Route::post('/hearings/{hearing}/toggle-active', [HearingController::class, 'toggleActive']);
    Route::post('/hearings/{hearing}/send-reminder', [HearingController::class, 'sendReminder']);

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Tasks
    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{task}/toggle-active', [TaskController::class, 'toggleActive']);

    // Documents
    Route::apiResource('documents', DocumentController::class);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download']);
    Route::post('/documents/{document}/toggle-active', [DocumentController::class, 'toggleActive']);
    Route::post('/documents/summarize', [DocumentController::class, 'summarize']);

    // Invoices
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus']);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf']);
    Route::post('/invoices/{invoice}/send-reminder', [InvoiceController::class, 'sendReminder']);

    // Payments
    Route::get('/payments/by-invoice', [PaymentController::class, 'searchByInvoice']);
    Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);

    // Time Entries
    Route::get('/time-entries/approved', [TimeEntryController::class, 'getApprovedEntries']); 
    Route::post('/time-entries/{timeEntry}/status', [TimeEntryController::class, 'updateStatus']);
    Route::apiResource('time-entries', TimeEntryController::class);

    Route::get('/global-search', [GlobalSearchController::class, 'search']);


    // Document Templates
    Route::apiResource('document-templates', DocumentTemplateController::class);
    Route::post('/document-templates/{template}/fill', [DocumentTemplateController::class, 'fillTemplate']);


    // Calendar
    Route::get('/calendar/events', [CalendarController::class, 'getEvents']);



    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);


    // Client Portal Specific Routes
    Route::prefix('client')->group(function () {
        Route::post('/logout', [ClientAuthController::class, 'logout']);
        Route::get('/profile', [ClientAuthController::class, 'profile']);
        Route::get('/cases', [CaseController::class, 'getClientCases']);
        Route::get('/documents', [DocumentController::class, 'getClientDocuments']);
        Route::get('/invoices', [InvoiceController::class, 'getClientInvoices']);
    });
});