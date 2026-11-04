<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentTemplate\StoreDocumentTemplateRequest;
use App\Http\Requests\DocumentTemplate\UpdateDocumentTemplateRequest;
use App\Models\DocumentTemplate;
use App\Services\DocumentTemplateService;
use Illuminate\Http\Request;

class DocumentTemplateController extends Controller {
    protected DocumentTemplateService $documentTemplateService;

    public function __construct(DocumentTemplateService $documentTemplateService) {
        $this->documentTemplateService = $documentTemplateService;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'category');
        $templates = $this->documentTemplateService->getTemplates($filters);
        
        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    public function store(StoreDocumentTemplateRequest $request) {
        $template = $this->documentTemplateService->createTemplate($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Template created successfully',
            'template' => $template
        ], 201);
    }

    public function show(DocumentTemplate $template) {
        return response()->json([
            'success' => true,
            'template' => $template->load('creator')
        ]);
    }

    public function update(UpdateDocumentTemplateRequest $request, DocumentTemplate $template) {
        $updated = $this->documentTemplateService->updateTemplate($template, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully',
            'template' => $updated
        ]);
    }

    public function destroy(DocumentTemplate $template) {
        $this->documentTemplateService->deleteTemplate($template);
        
        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully'
        ]);
    }

    public function fillTemplate(Request $request, DocumentTemplate $template) {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'case_id' => 'nullable|exists:cases,id',
        ]);

        $result = $this->documentTemplateService->fillTemplate(
            $template,
            $validated['client_id'],
            $validated['case_id'] ?? null
        );
        return response()->json([
            'success' => true,
            'message' => 'Document filled successfully',
            'data' => [
                'filled_content' => $result['filled_content'],
                'template_name' => $result['template_name']
            ]
        ]);
    }

}