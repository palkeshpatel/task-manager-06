<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    @auth
        <!-- Navigation -->
        <nav class="bg-white shadow-lg border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold text-gray-900">

                                Task Manager
                            </h1>
                        </div>
                        <div class="hidden md:ml-6 md:flex md:space-x-8">
                            <a href="{{ route('dashboard') }}"
                                class="@if (request()->routeIs('dashboard')) border-blue-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">

                                Dashboard
                            </a>
                            <a href="/users">
                                Users
                            </a>
                            <a href="/projects"> Projects
                            </a>
                            <a href="/tasks">Tasks
                            </a>

                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                    <span
                                        class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="ml-2 text-gray-700">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <!-- Main Content -->
    <main class="@auth py-6 @endauth">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    x-data="{ show: true }" x-show="show">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                        <i class="fas fa-times cursor-pointer"></i>
                    </span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    x-data="{ show: true }" x-show="show">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                        <i class="fas fa-times cursor-pointer"></i>
                    </span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global AJAX error handler
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Validation errors:\n';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join(', ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
