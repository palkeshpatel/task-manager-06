@extends('layouts.app')

@section('title', $project->name . ' - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-folder mr-2"></i>
                            Projects
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                            <span class="text-gray-500">{{ $project->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mt-4 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                    @if ($project->description)
                        <p class="mt-2 text-gray-600">{{ $project->description }}</p>
                    @endif
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('projects.edit', $project) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Project
                    </a>
                    <button onclick="deleteProject({{ $project->id }})"
                        class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Project
                    </button>
                </div>
            </div>
        </div>

        <!-- Project Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-list-check text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $project->tasks->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $project->tasks->where('status', 'pending')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $project->tasks->where('status', 'completed')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-percentage text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Progress</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $project->tasks->count() > 0 ? round(($project->tasks->where('status', 'completed')->count() / $project->tasks->count()) * 100) : 0 }}%
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Project Progress</h3>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-blue-500 h-4 rounded-full flex items-center justify-center text-white text-xs font-medium"
                    style="width: {{ $project->tasks->count() > 0 ? ($project->tasks->where('status', 'completed')->count() / $project->tasks->count()) * 100 : 0 }}%">
                    {{ $project->tasks->count() > 0 ? round(($project->tasks->where('status', 'completed')->count() / $project->tasks->count()) * 100) : 0 }}%
                </div>
            </div>
        </div>

        <!-- Tasks Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Tasks</h3>
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}"
                    class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add Task
                </a>
            </div>

            @if ($project->tasks->count() > 0)
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tasks-tbody">
                            @foreach ($project->tasks as $index => $task)
                                <tr class="task-row {{ $index % 2 == 0 ? 'bg-blue-50' : 'bg-green-50' }}"
                                    data-task-id="{{ $task->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                            @if ($task->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="toggleTaskStatus({{ $task->id }})"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer hover:opacity-80 task-status-badge
                                           {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            <i
                                                class="fas {{ $task->status === 'completed' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                            {{ ucfirst($task->status) }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $task->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('tasks.edit', $task) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteTask({{ $task->id }})"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-list-check text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new task for this project.</p>
                    <div class="mt-6">
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>
                            New Task
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleTaskStatus(taskId) {
                $.ajax({
                    url: `/tasks/${taskId}/toggle-status`,
                    type: 'PATCH',
                    success: function(response) {
                        if (response.success) {
                            const task = response.task;
                            const row = $(`.task-row[data-task-id="${taskId}"]`);
                            const statusBadge = row.find('.task-status-badge');

                            // Update status badge
                            if (task.status === 'completed') {
                                statusBadge.removeClass('bg-yellow-100 text-yellow-800')
                                    .addClass('bg-green-100 text-green-800')
                                    .html('<i class="fas fa-check-circle mr-1"></i>Completed');
                            } else {
                                statusBadge.removeClass('bg-green-100 text-green-800')
                                    .addClass('bg-yellow-100 text-yellow-800')
                                    .html('<i class="fas fa-clock mr-1"></i>Pending');
                            }

                            // Refresh page to update statistics
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        }
                    },
                    error: function() {
                        alert('Error updating task status. Please try again.');
                    }
                });
            }

            function deleteTask(taskId) {
                if (confirm('Are you sure you want to delete this task?')) {
                    $.ajax({
                        url: `/tasks/${taskId}`,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                $(`.task-row[data-task-id="${taskId}"]`).fadeOut(300, function() {
                                    $(this).remove();

                                    // Check if no tasks left
                                    if ($('.task-row').length === 0) {
                                        location.reload();
                                    } else {
                                        // Update row colors
                                        updateRowColors();
                                    }
                                });

                                showNotification('Task deleted successfully!', 'success');
                            }
                        },
                        error: function() {
                            showNotification('Error deleting task. Please try again.', 'error');
                        }
                    });
                }
            }

            function deleteProject(projectId) {
                if (confirm('Are you sure you want to delete this project? This will also delete all associated tasks.')) {
                    $.ajax({
                        url: `/projects/${projectId}`,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                window.location.href = '/projects';
                            }
                        },
                        error: function() {
                            showNotification('Error deleting project. Please try again.', 'error');
                        }
                    });
                }
            }

            function updateRowColors() {
                $('.task-row').each(function(index) {
                    $(this).removeClass('bg-blue-50 bg-green-50');
                    $(this).addClass(index % 2 == 0 ? 'bg-blue-50' : 'bg-green-50');
                });
            }

            function showNotification(message, type) {
                const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
                    'bg-red-100 border-red-400 text-red-700';
                const notification = $(`
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="${bgColor} px-4 py-3 rounded relative" style="display: none;">
                <span class="block sm:inline">${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="$(this).parent().parent().fadeOut()">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        </div>
    `);

                $('main').prepend(notification);
                notification.find('div').fadeIn();

                setTimeout(() => {
                    notification.fadeOut();
                }, 5000);
            }
        </script>
    @endpush
@endsection
