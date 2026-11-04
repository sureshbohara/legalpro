<?php
namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TaskService {


     public function getTasks(array $filters = []) {
        $query = Task::query()->with(['case', 'assignee', 'creator']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }


        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['case_id'])) {
            $query->where('case_id', $filters['case_id']);
        }

        if (!empty($filters['overdue']) && $filters['overdue'] === 'true') {
            $query->where('deadline', '<', now()->toDateString())
                  ->where('status', '!=', 'done');
        }
        $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END");
        $query->orderBy('deadline', 'asc');

        return $query->paginate(15);
    }


    public function createTask(array $data): Task {
        $data['created_by'] = auth()->id();
        return Task::create($data);
    }


    public function updateTask(Task $task, array $data): Task {
        $task->update($data);
        return $task->fresh(['case', 'assignee', 'creator']);
    }


    public function deleteTask(Task $task): bool {
        return $task->delete();
    }

    public function toggleActive(Task $task, bool $isActive): Task {
        $task->update(['is_active' => $isActive]);
        return $task->fresh();
    }


}