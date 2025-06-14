<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    @include('components.navigation')
    
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Dashboard Header -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Welcome back, {{ auth()->user()->name }}! Manage your Student Skill Tracker system.
                        </p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-3 py-1 rounded-full text-sm font-medium">
                        Master Admin
                    </div>
                </div>
            </div>
        </div>        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('users.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM9 7a1 1 0 112 0 1 1 0 01-2 0zM2 18a6 6 0 1112 0H2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manage Users</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Create, edit, and manage user accounts</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('clubs.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manage Clubs</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Oversee all clubs and activities</p>
                    </div>
                </div>
            </a>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Skill Categories</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Define and manage skill types</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reports</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">View system analytics and reports</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- System Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">System Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Roles</dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Role::count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">System Permissions</dt>
                            <dd class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Permission::count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Admin Capabilities -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Admin Capabilities</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-1 mr-3">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Full system access</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-1 mr-3">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">User management privileges</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-1 mr-3">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Role and permission management</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-1 mr-3">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Club and skill oversight</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-1 mr-3">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">System analytics and reporting</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
