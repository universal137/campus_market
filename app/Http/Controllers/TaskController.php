<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with('user')->latest();

        $status = $request->input('status');
        $allowedStatuses = ['open', 'completed'];
        if (! in_array($status, $allowedStatuses, true)) {
            $status = null;
        }
        if ($status !== null) {
            $query->where('status', $status);
        }

        $keyword = trim((string) $request->input('q'));
        if ($keyword !== '') {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            })
            ->orderByRaw("CASE 
                WHEN title LIKE ? THEN 1 
                WHEN content LIKE ? THEN 2 
                ELSE 3 
            END", ["%{$keyword}%", "%{$keyword}%"]);
        }

        $tasks = $query->paginate(8)->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $status,
                'q' => $keyword,
            ],
        ]);
    }

    public function show(Task $task): View
    {
        $task->load('user');

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    public function store(Request $request)
    {
        // Use authenticated user if available, otherwise create/find by email
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            $validated = $request->validate([
                'publisher_name' => ['required', 'string', 'max:50'],
                'publisher_email' => ['required', 'email', 'max:255'],
            ]);

            $user = User::firstOrCreate(
                ['email' => $validated['publisher_email']],
                [
                    'name' => $validated['publisher_name'],
                    'password' => bcrypt('campus-demo'),
                ]
            );
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'content' => ['required', 'string', 'max:2000'],
            'reward' => ['nullable', 'string', 'max:60'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $reward = $validated['reward'] ?? null;
        if (is_string($reward)) {
            $reward = trim($reward);
        }
        if ($reward === '') {
            $reward = null;
        }

        Task::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'reward' => $reward ?? '面议',
            'status' => 'open',
            'latitude' => $request->input('lat'),
            'longitude' => $request->input('lng'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '互助任务已发布，耐心等待同学来接单吧！'
        ]);
    }

    public function markAsComplete(Task $task): \Illuminate\Http\JsonResponse
    {
        // Check if user owns the task
        if (auth()->id() !== $task->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update(['status' => 'completed']);

        return response()->json([
            'status' => 'success',
            'message' => '任务已标记为已完成'
        ]);
    }

    public function destroy(Task $task): \Illuminate\Http\JsonResponse
    {
        // Check if user owns the task
        if (auth()->id() !== $task->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => '任务已删除'
        ]);
    }
}

