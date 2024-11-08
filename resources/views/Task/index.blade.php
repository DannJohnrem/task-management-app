<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ __('Tasks') }}</span>

            <a href="{{ route('task.create') }}" class="px-4 py-2 mb-2 text-sm font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                Create Task
            </a>
        </div>
    </x-slot>

    <!-- Filters and Table Section -->
    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="mb-6 space-y-4">
            <div class="flex items-center gap-4 space-x-4">
                <div class="flex flex-col">
                    <label for="statusFilter" class="font-medium">Status:</label>
                    <select id="statusFilter" class="px-3 py-2 mt-1 border-gray-300 rounded-md form-select focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="to-do">To Do</option>
                        <option value="in-progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="orderBy" class="font-medium">Order By:</label>
                    <select id="orderBy" class="px-3 py-2 mt-1 border-gray-300 rounded-md form-select focus:ring-blue-500 focus:border-blue-500">
                        <option value="title">Title</option>
                        <option value="created_at">Date Created</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="orderDirection" class="font-medium">Direction:</label>
                    <select id="orderDirection" class="px-3 py-2 mt-1 border-gray-300 rounded-md form-select focus:ring-blue-500 focus:border-blue-500">
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Task Table -->
        <table id="tasksTable" class="min-w-full border-collapse table-auto">
            <thead>
                <tr class="text-left bg-gray-100">
                    <th class="px-4 py-2 font-medium border-b">Title</th>
                    <th class="px-4 py-2 font-medium border-b">Content</th>
                    <th class="px-4 py-2 font-medium border-b">Status</th>
                    <th class="px-4 py-2 font-medium border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTable will populate this dynamically -->
            </tbody>
        </table>
    </div>
</x-app-layout>
