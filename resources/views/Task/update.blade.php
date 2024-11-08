<x-app-layout>
    <x-slot name="header">
        {{ __('Update Task') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <!-- Update Task Form -->
        <form action="{{ route('task.update', $task->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Use PUT method for update -->
            <div class="mb-4">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-input" value="{{ old('title', $task->title) }}" required maxlength="100">
            </div>

            <div class="mb-4">
                <label for="content">Content:</label>
                <textarea id="content" name="content" class="form-input" required>{{ old('content', $task->content) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-input" required>
                    <option value="to-do" {{ old('status', $task->status) == 'to-do' ? 'selected' : '' }}>To Do</option>
                    <option value="in-progress" {{ old('status', $task->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="done" {{ old('status', $task->status) == 'done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="is_draft" class="block text-sm font-medium text-gray-700">Status:</label>
                <select id="is_draft" name="is_draft" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="1" {{ $task->is_draft ? 'selected' : '' }}>Draft</option>
                    <option value="0" {{ !$task->is_draft ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-input">
                @if ($task->image)
                    <div class="mt-2">
                        <img src="{{ Storage::url($task->image) }}" alt="Current Image" class="w-32 h-32">
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</x-app-layout>
