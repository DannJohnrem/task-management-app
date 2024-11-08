<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = Auth::user();
                if (!$user) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                $tasks = Task::where('user_id', $user->id);

                // Apply filters if they are set
                if ($request->filled('status')) {
                    $tasks->where('status', $request->status);
                }

                // Filter by search query if provided
                if ($request->filled('search')) {
                    $tasks->where('title', 'like', '%' . $request->search . '%');
                }

                // Apply sorting if order_by and direction are set
                if ($request->filled('order_by')) {
                    $tasks->orderBy($request->order_by, $request->direction ?? 'asc');
                    $tasks->orderBy('created_at', 'desc');
                }

                // Get the total count of tasks for pagination
                $totalTasks = $tasks->count();

                // Paginate the tasks for DataTables
                $tasks = $tasks->skip($request->start)
                    ->take($request->length)
                    ->get();

                // Add action column and style the status as a pill
                $tasks->transform(function ($task) {
                    // Styling the status as a pill
                    $statusClass = match ($task->status) {
                        'to-do' => 'bg-gray-200 text-gray-800',
                        'in-progress' => 'bg-yellow-200 text-yellow-800',
                        'done' => 'bg-green-200 text-green-800',
                        default => 'bg-blue-200 text-blue-800',
                    };

                    // Format status as a pill
                    $task->status = '<span class="px-3 py-1 text-sm font-semibold leading-tight rounded-full ' . $statusClass . '">' . ucfirst($task->status) . '</span>';

                    // Add action buttons for edit and delete
                    $task->action = '
                        <button class="text-blue-500 edit-btn hover:text-blue-700" data-task="' . $task->id . '">Edit</button>
                        <button class="ml-2 text-red-500 delete-btn hover:text-red-700" data-task="' . $task->id . '">Delete</button>';

                    return $task;
                });

                return response()->json([
                    'draw' => (int) $request->draw,
                    'recordsTotal' => $totalTasks,
                    'recordsFiltered' => $totalTasks,
                    'data' => $tasks
                ]);
            } catch (\Exception $e) {
                Log::error('Error processing DataTables AJAX request: ' . $e->getMessage());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
        }

        return view('task.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        // Prepare the data for mass assignment
        $data = $request->only(['title', 'content', 'status', 'is_draft']);
        $data['user_id'] = Auth::id();

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('task_images', 'public');
        }

        Task::create($data);

        return response()->json(['message' => 'Task created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        // Prepare the data for mass assignment
        $data = $request->only(['title', 'content', 'status', 'is_draft']);

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($task->image && Storage::disk('public')->exists($task->image)) {
                Storage::disk('public')->delete($task->image);
            }
            $data['image'] = $request->file('image')->store('task_images', 'public');
        }

        // Update the task
        $task->update($data);

        return response()->json(['message' => 'Task updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->json(['message' => 'Task moved to trash.']);
    }
}
