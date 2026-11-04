<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hearing\StoreHearingRequest;
use App\Http\Requests\Hearing\UpdateHearingRequest;
use App\Models\Hearing;
use App\Models\CaseModel;
use App\Models\Task;
use App\Services\HearingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\HearingReminderMail;
use Carbon\Carbon;

class HearingController extends Controller {
    protected HearingService $hearingService;

    public function __construct(HearingService $hearingService) {
        $this->hearingService = $hearingService;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'status');
        $hearings = $this->hearingService->getHearings($filters);
        return response()->json(['success' => true, 'data' => $hearings]);
    }

    public function show(Hearing $hearing) {
        return response()->json(['success' => true, 'hearing' => $hearing->load('case')]);
    }

    public function store(StoreHearingRequest $request) {
        $hearing = $this->hearingService->createHearing($request->validated());
        $this->syncHearingPrepTask($hearing);

        return response()->json([
            'success' => true, 
            'message' => 'Hearing scheduled and preparation task auto-assigned!', 
            'hearing' => $hearing->load('case')
        ], 201);
    }

    public function update(UpdateHearingRequest $request, Hearing $hearing) {
        $updated = $this->hearingService->updateHearing($hearing, $request->validated());
        $this->syncHearingPrepTask($updated);

        return response()->json([
            'success' => true, 
            'message' => 'Hearing updated successfully', 
            'hearing' => $updated
        ]);
    }

    public function destroy(Hearing $hearing) {
        $this->hearingService->deleteHearing($hearing);
        return response()->json(['success' => true, 'message' => 'Hearing deleted successfully']);
    }

    public function toggleActive(Request $request, Hearing $hearing) {
        try {
            $request->validate(['is_active' => 'required|boolean']);
            $updated = $this->hearingService->toggleStatus($hearing, $request->is_active);
            $message = $request->is_active ? 'Hearing activated' : 'Hearing archived';

            return response()->json(['success' => true, 'message' => $message, 'hearing' => $updated], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    public function sendReminder(Hearing $hearing) {
        try {
            if ($hearing->case && $hearing->case->client && $hearing->case->client->email) {
                Mail::to($hearing->case->client->email)->send(new HearingReminderMail($hearing));
            }
            return response()->json(['success' => true, 'message' => 'Hearing reminder sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }

    private function syncHearingPrepTask(Hearing $hearing): void {
        $case = $hearing->case ?? CaseModel::find($hearing->case_id);
        if (!$case || !$case->assigned_lawyer_id) {
            return;
        }

        $deadline = Carbon::parse($hearing->hearing_date)->subDay()->format('Y-m-d');
        $taskTitle = '⚖️ Hearing Prep: ' . ($case->case_number ?? 'Unknown Case');
        $taskData = [
            'title' => $taskTitle,
            'description' => 'Prepare drafts and documents for the court date (' . $hearing->hearing_date . '). Court: ' . $hearing->court_name,
            'priority' => 'high',
            'status' => 'to_do',
            'case_id' => $case->id,
            'assigned_to' => $case->assigned_lawyer_id, 
            'deadline' => $deadline, 
            'is_active' => true,
        ];
        $existingTask = Task::where('case_id', $case->id)
                            ->where('title', 'like', '⚖️ Hearing Prep:%')
                            ->first();

        if ($existingTask) {
            $existingTask->update($taskData); 
        } else {
            Task::create($taskData); 
        }
    }
}