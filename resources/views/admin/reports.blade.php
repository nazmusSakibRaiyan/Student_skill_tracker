<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Student Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    @include('components.navigation')
    
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reports & Analytics</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Access system reports, analytics, and monitoring tools
                        </p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-3 py-1 rounded-full text-sm font-medium">
                        Master Admin Only
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Users</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-gray-500">+{{ $stats['new_users_this_month'] }} this month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Clubs</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['total_clubs'] }}</p>
                        <p class="text-xs text-gray-500">+{{ $stats['new_clubs_this_month'] }} this month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Events</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_events'] }}</p>
                        <p class="text-xs text-gray-500">+{{ $stats['new_events_this_month'] }} this month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Enrollments</h3>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['total_enrollments'] }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['completed_enrollments'] }} completed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- System Monitoring -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="bg-red-100 dark:bg-red-900 p-2 rounded-lg">
                            <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">System Monitoring</h3>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Monitor system health, logs, and application performance</p>
                    <div class="space-y-3">
                        <a href="{{ route('admin.system-logs') }}" class="block w-full bg-red-600 text-white text-center py-2 px-4 rounded-md hover:bg-red-700 transition-colors font-medium">
                            📊 System Logs & Activity
                        </a>
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            📈 Performance Metrics (Coming Soon)
                        </button>
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            🔍 Error Tracking (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>

            <!-- User Analytics -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">User Analytics</h3>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Analyze user activity, registrations, and engagement</p>
                    <div class="space-y-3">
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            👥 User Activity Report (Coming Soon)
                        </button>
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            📊 Registration Trends (Coming Soon)
                        </button>
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            🎯 Engagement Metrics (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Club & Event Reports -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Club & Event Reports</h3>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Track club performance and event participation</p>
                    <div class="space-y-3">
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            🏛️ Club Performance (Coming Soon)
                        </button>
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            📅 Event Analytics (Coming Soon)
                        </button>
                        <button class="block w-full bg-gray-100 text-gray-600 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition-colors" disabled>
                            📈 Enrollment Statistics (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- User Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Statistics</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Registered Users</dt>
                            <dd class="text-sm font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">New Users This Month</dt>
                            <dd class="text-sm font-bold text-green-600">{{ $stats['new_users_this_month'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">New Users Last Month</dt>
                            <dd class="text-sm font-bold text-gray-600">{{ $stats['new_users_last_month'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                            <dd class="text-sm font-bold text-blue-600">{{ $stats['verified_users'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Verification</dt>
                            <dd class="text-sm font-bold text-yellow-600">{{ $stats['unverified_users'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Activity Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Activity Statistics</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Event Enrollments</dt>
                            <dd class="text-sm font-bold text-gray-900 dark:text-white">{{ $stats['total_enrollments'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Events</dt>
                            <dd class="text-sm font-bold text-green-600">{{ $stats['completed_enrollments'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">New Enrollments This Month</dt>
                            <dd class="text-sm font-bold text-blue-600">{{ $stats['new_enrollments_this_month'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Clubs</dt>
                            <dd class="text-sm font-bold text-purple-600">{{ $stats['total_clubs'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Events</dt>
                            <dd class="text-sm font-bold text-orange-600">{{ $stats['total_events'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Access</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                    🏠 Admin Dashboard
                </a>
                <a href="{{ route('users.index') }}" class="bg-green-600 text-white text-center py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                    👥 User Management
                </a>
                <a href="{{ route('clubs.index') }}" class="bg-purple-600 text-white text-center py-2 px-4 rounded-md hover:bg-purple-700 transition-colors">
                    🏛️ Club Management
                </a>
                <a href="{{ route('admin.system-logs') }}" class="bg-red-600 text-white text-center py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                    📊 System Logs
                </a>
            </div>
        </div>
    </div>
</body>
</html>
