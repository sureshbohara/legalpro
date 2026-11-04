<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseStatus\StoreCaseStatusRequest;
use App\Http\Requests\CaseStatus\UpdateCaseStatusRequest;
use App\Models\CaseStatus;
use App\Services\CaseStatusService;
use Illuminate\Http\Request;
use Exception;

class CaseStatusController extends Controller {
    protected CaseStatusService $service;

    public function __construct(CaseStatusService $service) {
        $this->service = $service;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'status');
        $statuses = $this->service->getStatuses($filters);
        return response()->json(['success' => true, 'data' => $statuses]);
    }

    public function store(StoreCaseStatusRequest $request) {
        $status = $this->service->createStatus($request->validated());
        return response()->json(['success' => true, 'message' => 'Status created successfully', 'status' => $status], 201);
    }

    public function update(UpdateCaseStatusRequest $request, CaseStatus $caseStatus) {
        $updated = $this->service->updateStatus($caseStatus, $request->validated());
        return response()->json(['success' => true, 'message' => 'Status updated successfully', 'status' => $updated]);
    }

    public function destroy(CaseStatus $caseStatus) {
        $this->service->deleteStatus($caseStatus);
        return response()->json(['success' => true, 'message' => 'Status deleted successfully']);
    }

    public function caseStatusStatus(Request $request, CaseStatus $caseStatus){
        try {
            $request->validate(['status' => 'required|in:0,1']);
            $caseStatus->update(['status' => $request->status]);
            $message = $request->status == 1 ? 'Status activated successfully' : 'Status deactivated successfully';
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $caseStatus->only(['id', 'name', 'status'])
            ], 200);
        } catch (\Exception $e) { 
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }


   
}