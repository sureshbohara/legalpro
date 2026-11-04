<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI;

class DocumentController extends Controller {
    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService) {
        $this->documentService = $documentService;
    }


    public function index(Request $request) {
        $filters = $request->only('search', 'category', 'case_id', 'client_id');
        $documents = $this->documentService->getDocuments($filters);
        return response()->json(['success' => true, 'data' => $documents]);
    }


    public function show(Document $document) {
        return response()->json(['success' => true, 'document' => $document->load(['case', 'client', 'uploader'])]);
    }


    public function store(StoreDocumentRequest $request) {
        $document = $this->documentService->createDocument(
            $request->only('name', 'category', 'description', 'case_id', 'client_id', 'is_active'),
            $request->file('file')
        );
        return response()->json([
            'success' => true, 
            'message' => 'Document uploaded successfully', 
            'document' => $document->load(['case', 'client', 'uploader'])
        ], 201);
    }



    public function update(Request $request, Document $document) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240',
            'category' => 'required|in:evidence,contract,correspondence,general',
            'description' => 'nullable|string',
            'case_id' => 'nullable|exists:cases,id',
            'client_id' => 'nullable|exists:clients,id',
            'is_active' => 'nullable|boolean',
        ]);
        $file = $request->hasFile('file') ? $request->file('file') : null;
        $updated = $this->documentService->updateDocument($document, $validated, $file);
        
        return response()->json(['success' => true, 'message' => 'Document updated successfully', 'document' => $updated]);
    }

    public function destroy(Document $document) {
        $this->documentService->deleteDocument($document);
        return response()->json(['success' => true, 'message' => 'Document deleted successfully']);
    }

    public function download(Document $document) {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }
        
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function toggleActive(Request $request, Document $document) {
        try {
            $request->validate(['is_active' => 'required|boolean']);
            $updated = $this->documentService->toggleActive($document, $request->is_active);
            $message = $request->is_active ? 'Document activated' : 'Document archived';
            return response()->json(['success' => true, 'message' => $message, 'document' => $updated], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }


    public function getClientDocuments(Request $request) {
        $client = $request->user();
        $documents = \App\Models\Document::where('client_id', $client->id)
            ->where('is_active', true)
            ->with(['case:id,case_number,title'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['success' => true, 'data' => $documents]);
    }
    



    public function summarize(Request $request) {
        $request->validate([
            'text' => 'required|string|max:10000', 
            'language' => 'nullable|string|in:ne,en'
        ]);
        $language = $request->language === 'ne' ? 'नेपाली' : 'English';
        $systemPrompt = "You are an expert legal assistant. Summarize the provided legal document text into 5 concise, clear bullet points in {$language}. Focus strictly on: 1) Key parties involved, 2) Important dates/deadlines, 3) Core legal issues, 4) Court orders or decisions, and 5) Required next actions. Keep it professional and highly accurate.";
        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));
            $result = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $request->text],
                ],
                'temperature' => 0.3,
            ]);
            return response()->json([
                'success' => true,
                'summary' => $result->choices[0]->message->content,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate summary. Please check your OpenAI API key or try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
}