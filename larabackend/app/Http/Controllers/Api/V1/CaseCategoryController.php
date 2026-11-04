<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseCategory\StoreCaseCategoryRequest;
use App\Http\Requests\CaseCategory\UpdateCaseCategoryRequest;
use App\Models\CaseCategory;
use App\Services\CaseCategoryService;
use Illuminate\Http\Request;
use Exception;

class CaseCategoryController extends Controller {
    protected CaseCategoryService $service;

    public function __construct(CaseCategoryService $service) {
        $this->service = $service;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'status');
        $categories = $this->service->getCategories($filters);
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function store(StoreCaseCategoryRequest $request) {
        $category = $this->service->createCategory($request->validated());
        return response()->json(['success' => true, 'message' => 'Category created successfully', 'category' => $category], 201);
    }


    public function update(UpdateCaseCategoryRequest $request, CaseCategory $caseCategory) {
        $updated = $this->service->updateCategory($caseCategory, $request->validated());
        return response()->json(['success' => true, 'message' => 'Category updated successfully', 'category' => $updated]);
    }


    public function destroy(CaseCategory $caseCategory) {
        $this->service->deleteCategory($caseCategory);
        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }


    public function caseCategoryStatus(Request $request, CaseCategory $caseCategory){
        try {
            $request->validate(['status' => 'required|in:0,1']);
            $caseCategory->update(['status' => $request->status]);
            $message = $request->status == 1 ? 'Category activated successfully' : 'Category deactivated successfully';
            return response()->json([
                'success' => true,
                'message' => $message,
                'category' => $caseCategory->only(['id', 'name', 'status'])
            ], 200);
        } catch (\Exception $e) { 
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    
}