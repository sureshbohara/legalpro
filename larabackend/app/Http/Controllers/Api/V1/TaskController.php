<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Exception;

class TaskController extends Controller {
    protected TaskService $taskService;

    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'status', 'priority', 'assigned_to', 'case_id', 'overdue');
        $tasks = $this->taskService->getTasks($filters);
        return response()->json(['success' => true, 'data' => $tasks]);
    }


    public function show(Task $task) {
        return response()->json(['success' => true, 'task' => $task->load(['case', 'assignee', 'creator'])]);
    }


    public function store(StoreTaskRequest $request) {
        $task = $this->taskService->createTask($request->validated());
        return response()->json([
            'success' => true, 
            'message' => 'Task created successfully', 
            'task' => $task->load(['case', 'assignee', 'creator'])
        ], 201);
    }


    public function update(UpdateTaskRequest $request, Task $task) {
        $updated = $this->taskService->updateTask($task, $request->validated());
        return response()->json(['success' => true, 'message' => 'Task updated successfully', 'task' => $updated]);
    }


    public function destroy(Task $task) {
        $this->taskService->deleteTask($task);
        return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
    }


    public function toggleActive(Request $request, Task $task) {
        try {
            $request->validate(['is_active' => 'required|boolean']);
            $updated = $this->taskService->toggleActive($task, $request->is_active);
            $message = $request->is_active ? 'Task activated' : 'Task archived';
            return response()->json(['success' => true, 'message' => $message, 'task' => $updated], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }
    
}