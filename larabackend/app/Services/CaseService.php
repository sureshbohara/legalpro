<?php
namespace App\Services;

use App\Models\CaseModel;
use Illuminate\Database\Eloquent\Builder;

class CaseService {


    public function getCases(array $filters = []) {
        return CaseModel::with(['client:id,name', 'category:id,name,color', 'status:id,name,color', 'lawyer:id,name'])
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->where('case_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('title', 'like', '%' . $filters['search'] . '%');
            })
            ->when(!empty($filters['category_id']), function (Builder $q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            })
            ->when(!empty($filters['status_id']), function (Builder $q) use ($filters) {
                $q->where('status_id', $filters['status_id']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function createCase(array $data): CaseModel {
        return CaseModel::create($data);
    }

    public function updateCase(CaseModel $case, array $data): CaseModel {
        $case->update($data);
        return $case->fresh(['client', 'category', 'status', 'lawyer']);
    }

    public function deleteCase(CaseModel $case): bool {
        return $case->delete();
    }
    
}