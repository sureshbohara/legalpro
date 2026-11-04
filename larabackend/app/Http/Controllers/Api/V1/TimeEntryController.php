<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimeEntry\StoreTimeEntryRequest;
use App\Http\Requests\TimeEntry\UpdateTimeEntryRequest;
use App\Models\TimeEntry;
use App\Services\TimeEntryService;
use Illuminate\Http\Request;

class TimeEntryController extends Controller {
    protected TimeEntryService $timeEntryService;

    public function __construct(TimeEntryService $timeEntryService) {
        $this->timeEntryService = $timeEntryService;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'user_id', 'case_id', 'status', 'is_billable', 'date_from', 'date_to');
        $entries = $this->timeEntryService->getTimeEntries($filters);
        $summary = $this->timeEntryService->getSummary($filters);
        
        return response()->json([
            'success' => true, 
            'data' => $entries,
            'summary' => $summary
        ]);
    }

    public function show(TimeEntry $timeEntry) {
        return response()->json([
            'success' => true, 
            'entry' => $timeEntry->load(['case', 'user', 'task'])
        ]);
    }

    public function store(StoreTimeEntryRequest $request) {
        $entry = $this->timeEntryService->createTimeEntry($request->validated());
        return response()->json([
            'success' => true, 
            'message' => 'Time entry created successfully', 
            'entry' => $entry->load(['case', 'user', 'task'])
        ], 201);
    }

    public function update(UpdateTimeEntryRequest $request, TimeEntry $timeEntry) {
        if ($timeEntry->status === 'invoiced') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit invoiced time entry'
            ], 403);
        }

        $updated = $this->timeEntryService->updateTimeEntry($timeEntry, $request->validated());
        return response()->json([
            'success' => true, 
            'message' => 'Time entry updated successfully', 
            'entry' => $updated
        ]);
    }

    public function destroy(TimeEntry $timeEntry) {
        if ($timeEntry->status === 'invoiced') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete invoiced time entry'
            ], 403);
        }

        $this->timeEntryService->deleteTimeEntry($timeEntry);
        return response()->json(['success' => true, 'message' => 'Time entry deleted successfully']);
    }


    public function getApprovedEntries(Request $request) {
        $request->validate([
            'case_id' => 'nullable|exists:cases,id',
            'client_id' => 'nullable|exists:clients,id',
        ]);
        $query = TimeEntry::with(['case:id,case_number,title,client_id'])
            ->where('status', 'approved')
            ->where('is_billable', true);
        if ($request->case_id) {
            $query->where('case_id', $request->case_id);
        }
        if ($request->client_id) {
            $query->whereHas('case', function($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }
        $entries = $query->orderBy('work_date', 'desc')->get()->map(function($entry) {
            return [
                'id' => $entry->id,
                'description' => $entry->description,
                'work_date' => $entry->work_date->format('Y-m-d'),
                'duration_minutes' => $entry->duration_minutes,
                'duration_hours' => round($entry->duration_minutes / 60, 2),
                'billable_rate' => $entry->billable_rate,
                'total_amount' => $entry->total_amount,
                'case_number' => $entry->case?->case_number,
                'case_title' => $entry->case?->title,
            ];
        });

        return response()->json([
            'success' => true,
            'entries' => $entries
        ]);
    }

    public function updateStatus(Request $request, TimeEntry $timeEntry) {
        $request->validate([
            'status' => 'required|in:draft,submitted,approved,invoiced'
        ]);
        if ($timeEntry->status === 'invoiced') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change status of invoiced time entry'
            ], 403);
        }
        $timeEntry->update(['status' => $request->status]);
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'entry' => $timeEntry->fresh()
        ]);
    }
}