<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Case\StoreCaseRequest;
use App\Http\Requests\Case\UpdateCaseRequest;
use App\Models\CaseModel;
use App\Services\CaseService;
use Illuminate\Http\Request;

class CaseController extends Controller {
    protected CaseService $caseService;

    public function __construct(CaseService $caseService) {
        $this->caseService = $caseService;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'category_id', 'status_id');
        $cases = $this->caseService->getCases($filters);
        return response()->json(['success' => true, 'data' => $cases]);
    }


     public function show(CaseModel $case) {
        $case->load(['client', 'category', 'status', 'lawyer']);
        return response()->json(['success' => true, 'case' => $case]);
    }

    public function store(StoreCaseRequest $request) {
        $case = $this->caseService->createCase($request->validated());
        return response()->json(['success' => true, 'message' => 'Case created successfully', 'case' => $case->load(['client', 'category', 'status', 'lawyer'])], 201);
    }

    public function update(UpdateCaseRequest $request, CaseModel $case) {
        $updated = $this->caseService->updateCase($case, $request->validated());
        return response()->json(['success' => true, 'message' => 'Case updated successfully', 'case' => $updated]);
    }

    public function destroy(CaseModel $case) {
        $this->caseService->deleteCase($case);
        return response()->json(['success' => true, 'message' => 'Case deleted successfully']);
    }


     public function getClientCases(Request $request) {
        $client = $request->user();
        $cases = \App\Models\CaseModel::where('client_id', $client->id)
            ->with(['category:id,name,color', 'status:id,name,color', 'lawyer:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['success' => true, 'data' => $cases]);
    }


    
}