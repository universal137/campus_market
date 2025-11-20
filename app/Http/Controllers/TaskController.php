<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with('user')->latest();

        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }

        $keyword = trim((string) $request->input('q'));
        if ($keyword !== '') {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        $tasks = $query->paginate(8)->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $status ?: null,
                'q' => $keyword ?: null,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'publisher_name' => ['required', 'string', 'max:50'],
            'publisher_email' => ['required', 'email', 'max:255'],
            'title' => ['required', 'string', 'max:120'],
            'content' => ['required', 'string', 'max:2000'],
            'reward' => ['nullable', 'string', 'max:60'],
        ]);

        $user = User::firstOrCreate(
            ['email' => $validated['publisher_email']],
            [
                'name' => $validated['publisher_name'],
                'password' => bcrypt('campus-demo'),
            ]
        );

        Task::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'reward' => $validated['reward'] ?: '面议',
            'status' => 'open',
        ]);

        return back()->with('success', '互助任务已发布，耐心等待同学来接单吧！');
    }
}

