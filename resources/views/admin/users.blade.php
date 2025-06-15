<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Student Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-purple-600 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-white">User Management</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('profile') }}" class="text-white hover:text-purple-200">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-purple-800 text-white px-3 py-1 rounded text-sm hover:bg-purple-900">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">User Management System</h2>
                        
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-purple-800">Permission Verified</h3>
                                    <p class="text-sm text-purple-700 mt-1">You have permission to manage users in the system.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- User Management Functions -->
                            <div class="lg:col-span-2">
                                <div class="bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">User Management Functions</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-2">Create New User</h4>
                                            <p class="text-sm text-gray-600 mb-3">Add new users to the system</p>
                                            <a href="{{ route('admin.users.create-club-manager') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 text-sm mb-2 text-center">Add Club Manager</a>
                                            <a href="{{ route('admin.users.create-student') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 text-sm text-center">Add Student</a>
                                        </div>
                                        
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-2">Bulk Import</h4>
                                            <p class="text-sm text-gray-600 mb-3">Import multiple users from CSV</p>
                                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 text-sm">
                                                Import Users
                                            </button>
                                        </div>
                                        
                                        <div class="p-4 border border-gray-200 rounded-lg mb-6">
                                            <h4 class="font-medium text-gray-900 mb-2">Pending Student Approvals</h4>
                                            <p class="text-sm text-gray-600 mb-3">Approve or reject students added to clubs by managers.</p>
                                            <div class="overflow-x-auto">
                                                @include('admin.partials.pending_students_table')
                                            </div>
                                        </div>
                                        
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-2">Role Assignment</h4>
                                            <p class="text-sm text-gray-600 mb-3">Assign and manage user roles</p>
                                            <a href="#pending-approvals" class="w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700 text-sm block text-center">Manage Roles</a>
                                        </div>
                                        
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-2">User Reports</h4>
                                            <p class="text-sm text-gray-600 mb-3">Generate user activity reports</p>
                                            <button class="w-full bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 text-sm">
                                                View Reports
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Stats -->
                            <div class="space-y-6">
                                <div class="bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">User Statistics</h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Total Users</span>
                                            <span class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Distribution</h3>
                                    <div class="space-y-3">
                                        <div>
                                            <div class="flex justify-between text-sm mb-1">
                                                <span>Students</span>
                                                <span>35 (74%)</span>
                                            </div>
                                            <div class="bg-green-200 rounded-full h-2">
                                                <div class="bg-green-600 h-2 rounded-full" style="width: 74%"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-between text-sm mb-1">
                                                <span>Club Managers</span>
                                                <span>10 (21%)</span>
                                            </div>
                                            <div class="bg-blue-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: 21%"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-between text-sm mb-1">
                                                <span>Admins</span>
                                                <span>2 (5%)</span>
                                            </div>
                                            <div class="bg-red-200 rounded-full h-2">
                                                <div class="bg-red-600 h-2 rounded-full" style="width: 5%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add Club Manager and Student Buttons -->
                        {{-- Removed bottom Add Club Manager and Add Student buttons as requested --}}
                        
                        <!-- Pending Student Approvals Link -->
                        <div class="mb-6">
                            <a href="{{ route('admin.clubs.pending-students') }}" class="inline-block bg-yellow-500 text-white px-4 py-2 rounded shadow hover:bg-yellow-600 font-semibold">
                                Pending Student Approvals
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
