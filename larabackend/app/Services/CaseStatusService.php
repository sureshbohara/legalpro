<?php
namespace App\Services;

use App\Models\CaseStatus;
use Illuminate\Database\Eloquent\Builder;

class CaseStatusService {


    public function getStatuses(array $filters = []) {
        return CaseStatus::query()
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            })
            ->when(isset($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function createStatus(array $data): CaseStatus {
        if (!empty($data['is_default'])) {
            CaseStatus::where('is_default', true)->update(['is_default' => false]);
        }
        return CaseStatus::create($data);
    }


    public function updateStatus(CaseStatus $status, array $data): CaseStatus {
        if (!empty($data['is_default'])) {
            CaseStatus::where('is_default', true)->update(['is_default' => false]);
        }
        $status->update($data);
        return $status->fresh();
    }


    public function deleteStatus(CaseStatus $status): bool {
        return $status->delete();
    }
    
}