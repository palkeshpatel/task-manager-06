@extends('layouts.app')

@section('title', 'Create Task - Task Manager')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <span class="text-gray-500">Create Task</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Create New Task</h1>

        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6 p-6">
                @csrf

                <div>
                    <label for="project_id" class="block text-sm font-medium text-gray-700">Project *</label>
                    <select name="project_id" id="project_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('project_id') border-red-500 @enderror">
                        <option value="">Select a project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ old('project_id', request('project_id')) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Developer *</label>
                    <select name="user_id[]" id="user_id" required multiple
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('user_id') border-red-500 @enderror">
                        <option value="">Select a Developer</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', request('user_id')) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Task Title *</label>
                    <input type="text" name="title" id="title" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('title') border-red-500 @enderror"
                        placeholder="Enter task title" value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('description') border-red-500 @enderror"
                        placeholder="Describe the task (optional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" id="due_date"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('due_date') border-red-500 @enderror"
                        value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('tasks.index') }}"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-green-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-save mr-2"></i>
                        Create Task
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Project Creation -->
        @if ($projects->count() === 0)
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">No projects available</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>You need to create a project first before adding tasks.</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('projects.create') }}"
                                class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-md text-sm font-medium hover:bg-yellow-200">
                                <i class="fas fa-plus mr-2"></i>
                                Create Project
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
