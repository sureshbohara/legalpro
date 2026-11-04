<?php
namespace App\Services;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class DocumentService {


    public function getDocuments(array $filters = []) {

        return Document::with(['case:id,case_number,title', 'client:id,name', 'uploader:id,name'])
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            })

            ->when(!empty($filters['category']), function (Builder $q) use ($filters) {
                $q->where('category', $filters['category']);
            })
            ->when(!empty($filters['case_id']), function (Builder $q) use ($filters) {
                $q->where('case_id', $filters['case_id']);
            })

            ->when(!empty($filters['client_id']), function (Builder $q) use ($filters) {
                $q->where('client_id', $filters['client_id']);
            })

            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function createDocument(array $data, $file): Document {
        $data['uploaded_by'] = auth()->id();
        $data['file_path'] = $file->store('documents', 'public');
        $data['file_name'] = $file->getClientOriginalName();
        $data['file_type'] = $file->getMimeType();
        $data['file_size'] = $file->getSize();
        return Document::create($data);
    }

    public function updateDocument(Document $document, array $data, $file = null): Document {
        if ($file) {
            Storage::disk('public')->delete($document->file_path);
            $data['file_path'] = $file->store('documents', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize();
        }
        
        $document->update($data);
        return $document->fresh(['case', 'client', 'uploader']);
    }

    public function deleteDocument(Document $document): bool {
        Storage::disk('public')->delete($document->file_path);
        return $document->delete();
    }

    public function toggleActive(Document $document, bool $isActive): Document {
        $document->update(['is_active' => $isActive]);
        return $document->fresh();
    }
}