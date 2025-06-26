@extends('layouts.app')

@section('title', $task->title . ' - Task Manager')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-list-check mr-2"></i>
                            Tasks
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                            <span class="text-gray-500">{{ $task->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mt-4 flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <div class="mt-2 flex items-center space-x-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            <i class="fas {{ $task->status === 'completed' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ ucfirst($task->status) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Project: <a href="{{ route('projects.show', $task->project) }}"
                                class="text-blue-600 hover:text-blue-900">{{ $task->project->name }}</a>
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button onclick="toggleTaskStatus({{ $task->id }})"
                        class="bg-{{ $task->status === 'completed' ? 'yellow' : 'green' }}-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-{{ $task->status === 'completed' ? 'yellow' : 'green' }}-700 flex items-center">
                        <i class="fas {{ $task->status === 'completed' ? 'fa-undo' : 'fa-check' }} mr-2"></i>
                        Mark as {{ $task->status === 'completed' ? 'Pending' : 'Completed' }}
                    </button>
                    <a href="{{ route('tasks.edit', $task) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Task
                    </a>
                    <button onclick="deleteTask({{ $task->id }})"
                        class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Task
                    </button>
                </div>
            </div>
        </div>

        <!-- Task Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Task Details</h3>
                    </div>
                    <div class="p-6">
                        @if ($task->description)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                <div class="text-gray-900 whitespace-pre-wrap">{{ $task->description }}</div>
                            </div>
                        @else
                            <div class="mb-6">
                                <p class="text-gray-500 italic">No description provided for this task.</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Project</h4>
                                <a href="{{ route('projects.show', $task->project) }}"
                                    class="text-blue-600 hover:text-blue-900 font-medium">
                                    {{ $task->project->name }}
                                </a>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    <i
                                        class="fas {{ $task->status === 'completed' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                    {{ ucfirst($task->status) }}
                                </span>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Due Date</h4>
                                @if ($task->due_date)
                                    <span class="text-gray-900">{{ $task->due_date->format('F j, Y') }}</span>
                                    @if ($task->due_date->isPast() && $task->status === 'pending')
                                        <span
                                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Overdue
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-500">No due date set</span>
                                @endif
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Created</h4>
                                <span class="text-gray-900">{{ $task->created_at->format('F j, Y \a\t g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button onclick="toggleTaskStatus({{ $task->id }})"
                            class="w-full bg-{{ $task->status === 'completed' ? 'yellow' : 'green' }}-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-{{ $task->status === 'completed' ? 'yellow' : 'green' }}-700 flex items-center justify-center">
                            <i class="fas {{ $task->status === 'completed' ? 'fa-undo' : 'fa-check' }} mr-2"></i>
                            Mark as {{ $task->status === 'completed' ? 'Pending' : 'Completed' }}
                        </button>

                        <a href="{{ route('tasks.edit', $task) }}"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Task
                        </a>

                        <a href="{{ route('tasks.create', ['project_id' => $task->project_id]) }}"
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Another Task
                        </a>
                    </div>
                </div>

                <!-- Project Info -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Project Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $task->project->name }}</h4>
                            @if ($task->project->description)
                                <p class="text-sm text-gray-600">{{ Str::limit($task->project->description, 100) }}</p>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Tasks:</span>
                                <span class="font-medium">{{ $task->project->tasks->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Completed:</span>
                                <span
                                    class="font-medium">{{ $task->project->tasks->where('status', 'completed')->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Progress:</span>
                                <span class="font-medium">
                                    {{ $task->project->tasks->count() > 0 ? round(($task->project->tasks->where('status', 'completed')->count() / $task->project->tasks->count()) * 100) : 0 }}%
                                </span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('projects.show', $task->project) }}"
                                class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-folder mr-2"></i>
                                View Project
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
                            location.reload();
                        }
                    },
                    error: function() {
                        showNotification('Error updating task status. Please try again.', 'error');
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
                                window.location.href = '/tasks';
                            }
                        },
                        error: function() {
                            showNotification('Error deleting task. Please try again.', 'error');
                        }
                    });
                }
            }

            function showNotification(message, type) {
                const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
                    'bg-red-100 border-red-400 text-red-700';
                const notification = $(`
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
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
