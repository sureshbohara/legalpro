<?php
namespace App\Services;

use App\Models\TimeEntry;
use Illuminate\Database\Eloquent\Builder;

class TimeEntryService {


    public function getTimeEntries(array $filters = []) {
        return TimeEntry::with(['case:id,case_number,title', 'user:id,name', 'task:id,title'])
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->where('description', 'like', '%' . $filters['search'] . '%');
            })
            ->when(!empty($filters['user_id']), function (Builder $q) use ($filters) {
                if ($filters['user_id'] === 'me') {
                    $q->where('user_id', auth()->id());
                } else {
                    $q->where('user_id', $filters['user_id']);
                }
            })
            ->when(!empty($filters['case_id']), function (Builder $q) use ($filters) {
                $q->where('case_id', $filters['case_id']);
            })
            ->when(!empty($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(!empty($filters['is_billable']), function (Builder $q) use ($filters) {
                $q->where('is_billable', $filters['is_billable']);
            })
            ->when(!empty($filters['date_from']), function (Builder $q) use ($filters) {
                $q->where('work_date', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function (Builder $q) use ($filters) {
                $q->where('work_date', '<=', $filters['date_to']);
            })
            ->orderBy('work_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function createTimeEntry(array $data): TimeEntry {
        $data['user_id'] = auth()->id();
        if ($data['is_billable'] ?? true) {
            $hours = $data['duration_minutes'] / 60;
            $data['total_amount'] = $hours * ($data['billable_rate'] ?? 0);
        } else {
            $data['total_amount'] = 0;
        }

        return TimeEntry::create($data);
    }

    public function updateTimeEntry(TimeEntry $entry, array $data): TimeEntry {
        if ($data['is_billable'] ?? $entry->is_billable) {
            $hours = $data['duration_minutes'] / 60;
            $data['total_amount'] = $hours * ($data['billable_rate'] ?? $entry->billable_rate);
        } else {
            $data['total_amount'] = 0;
        }

        $entry->update($data);
        return $entry->fresh(['case', 'user', 'task']);
    }

    public function deleteTimeEntry(TimeEntry $entry): bool {
        return $entry->delete();
    }


    public function getSummary(array $filters = []): array {
        $query = TimeEntry::query()
            ->when(!empty($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']))
            ->when(!empty($filters['case_id']), fn($q) => $q->where('case_id', $filters['case_id']))
            ->when(!empty($filters['date_from']), fn($q) => $q->where('work_date', '>=', $filters['date_from']))
            ->when(!empty($filters['date_to']), fn($q) => $q->where('work_date', '<=', $filters['date_to']));

        return [
            'total_entries' => (clone $query)->count(),
            'total_minutes' => (clone $query)->sum('duration_minutes'),
            'total_billable_minutes' => (clone $query)->where('is_billable', true)->sum('duration_minutes'),
            'total_amount' => (clone $query)->where('is_billable', true)->sum('total_amount'),
        ];
    }
    
}