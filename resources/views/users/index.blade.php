@extends('layouts.app')

@section('title', 'Tasks - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">

            <a href="{{ route('tasks.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Users
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4"
                id="filter-form">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search Tasks</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="Search by task title...">
                </div>





            </form>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if ($users->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                DOB</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                PHOTO</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tasks-tbody">
                        @foreach ($users as $index => $user)
                            <tr class="task-row {{ $index % 2 == 0 ? 'bg-blue-50' : 'bg-green-50' }}"
                                data-task-id="{{ $user->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">

                    <h3 class="mt-2 text-sm font-medium text-gray-900">No User found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if (request()->hasAny(['search', 'status', 'project_id']))
                            Try adjusting your filters or search terms.
                        @else
                        @endif
                    </p>
                    <div class="mt-6">


                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-submit form on filter change
            $('#search, #status, #project_id').on('change keyup', function() {
                if ($(this).attr('id') === 'search') {
                    // Debounce search input
                    clearTimeout(window.searchTimeout);
                    window.searchTimeout = setTimeout(() => {
                        $('#filter-form').submit();
                    }, 500);
                } else {
                    $('#filter-form').submit();
                }
            });

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

                            showNotification('Task status updated successfully!', 'success');
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
                                $(`.task-row[data-task-id="${taskId}"]`).fadeOut(300, function() {
                                    $(this).remove();

                                    // Update row colors
                                    updateRowColors();

                                    // Check if no tasks left on this page
                                    if ($('.task-row').length === 0) {
                                        location.reload();
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
