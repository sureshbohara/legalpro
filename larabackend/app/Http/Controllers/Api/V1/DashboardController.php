<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CaseModel;
use App\Models\Client;
use App\Models\Hearing;
use App\Models\ActivityLog;
use App\Models\CaseCategory;
use App\Models\CaseStatus;
use App\Models\Invoice; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache; 

class DashboardController extends Controller 
{
   
    public function stats() 
    {
        try {
            $stats = Cache::remember('dashboard_stats_v1', 120, function () {
                
           
                $totalActiveCases = CaseModel::where('status', true)->count();
                $totalClients = Client::where('status', true)->count();
                $upcomingHearings = Hearing::where('status', 'scheduled')
                    ->where('is_active', true)
                    ->where('hearing_date', '>=', Carbon::today())
                    ->count();

               
                $totalRevenue = (float) (Invoice::sum('paid_amount') ?? 0);
                $totalOutstanding = (float) (Invoice::whereNotIn('status', ['paid', 'cancelled'])
                    ->get()
                    ->sum(fn($inv) => $inv->total_amount - $inv->paid_amount) ?? 0);
                $pendingInvoicesCount = Invoice::whereIn('status', ['sent', 'partial', 'overdue'])->count();

              
                $recentInvoices = Invoice::with(['client:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(fn($inv) => [
                        'id' => $inv->id,
                        'invoice_number' => $inv->invoice_number,
                        'client_name' => $inv->client?->name ?? 'Unknown Client',
                        'total_amount' => (float) $inv->total_amount, 
                        'paid_amount' => (float) $inv->paid_amount,
                        'status' => $inv->status,
                        'created_at' => $inv->created_at->diffForHumans(),
                    ]);

              
                $casesByCategory = CaseCategory::where('status', true)
                    ->withCount(['cases' => fn($q) => $q->where('status', true)])
                    ->get()
                    ->map(fn($category) => [
                        'name' => $category->name,
                        'value' => (int) $category->cases_count,
                        'color' => $category->color ?? '#94a3b8',
                    ]);

       
                $casesByStatus = CaseStatus::where('status', true)
                    ->withCount(['cases' => fn($q) => $q->where('status', true)])
                    ->get()
                    ->map(fn($status) => [
                        'name' => $status->name,
                        'value' => (int) $status->cases_count,
                        'color' => $status->color ?? '#94a3b8',
                    ]);

          
                $nextHearings = Hearing::with(['case:id,case_number,title'])
                    ->where('status', 'scheduled')
                    ->where('is_active', true)
                    ->whereBetween('hearing_date', [Carbon::today(), Carbon::today()->addDays(7)])
                    ->orderBy('hearing_date', 'asc')
                    ->limit(5)
                    ->get()
                    ->map(fn($hearing) => [
                        'id' => $hearing->id,
                        'case_number' => $hearing->case?->case_number ?? 'N/A',
                        'case_title' => $hearing->case?->title ?? 'Untitled Case',
                        'court_name' => $hearing->court_name,
                        'hearing_date' => $hearing->hearing_date->format('Y-m-d'),
                        'hearing_time' => $hearing->hearing_time,
                    ]);

           
                $recentActivities = ActivityLog::with(['causer:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->limit(6)
                    ->get()
                    ->map(fn($log) => [
                        'id' => $log->id,
                        'description' => $log->description,
                        'user_name' => $log->causer?->name ?? 'System',
                        'created_at' => $log->created_at->diffForHumans(),
                    ]);

                return [
                    'total_active_cases' => (int) $totalActiveCases,
                    'total_clients' => (int) $totalClients,
                    'upcoming_hearings' => (int) $upcomingHearings,
                    'total_revenue' => $totalRevenue,
                    'total_outstanding' => $totalOutstanding,
                    'pending_invoices_count' => (int) $pendingInvoicesCount,
                    'recent_invoices' => $recentInvoices,
                    'cases_by_category' => $casesByCategory,
                    'cases_by_status' => $casesByStatus,
                    'next_hearings' => $nextHearings,
                    'recent_activities' => $recentActivities,
                ];
            });

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            \Log::error('Dashboard Stats Error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to load dashboard statistics.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}