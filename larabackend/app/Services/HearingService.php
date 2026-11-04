<?php
namespace App\Services;

use App\Models\Hearing;
use Illuminate\Database\Eloquent\Builder;

class HearingService {

    
    public function getHearings(array $filters = []) {
        return Hearing::with(['case:id,case_number,title'])
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->whereHas('case', function ($q) use ($filters) {
                    $q->where('case_number', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('title', 'like', '%' . $filters['search'] . '%');
                })->orWhere('court_name', 'like', '%' . $filters['search'] . '%');
            })
            ->when(!empty($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->orderBy('hearing_date', 'desc')
            ->paginate(15);
    }

    public function createHearing(array $data): Hearing {
        return Hearing::create($data);
    }

    public function updateHearing(Hearing $hearing, array $data): Hearing {
        $hearing->update($data);
        return $hearing->fresh(['case']);
    }

    public function deleteHearing(Hearing $hearing): bool {
        return $hearing->delete();
    }

    public function toggleStatus(Hearing $hearing, bool $isActive): Hearing {
        $hearing->update(['is_active' => $isActive]);
        return $hearing->fresh();
    }
}