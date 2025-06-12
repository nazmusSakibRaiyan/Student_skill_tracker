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
                                            <button class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 text-sm">
                                                Add User
                                            </button>
                                        </div>
                                        
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-2">Bulk Import</h4>
                                            <p class="text-sm text-gray-600 mb-3">Import multiple users from CSV</p>
                                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 text-sm">
                                                Import Users
                                            </button>
                                        </div>
                                        
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <h4 class="font-medium text-gray-900 mb-2">Role Assignment</h4>
                                            <p class="text-sm text-gray-600 mb-3">Assign and manage user roles</p>
                                            <button class="w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700 text-sm">
                                                Manage Roles
                                            </button>
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
                                
                                <!-- Sample User List -->
                                <div class="mt-6 bg-white border border-gray-200 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Users</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 bg-red-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white font-medium">MA</span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">Master Admin</div>
                                                                <div class="text-sm text-gray-500">admin@example.com</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            Master Admin
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            Active
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                                        <button class="text-red-600 hover:text-red-900">Delete</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white font-medium">CM</span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">Club Manager</div>
                                                                <div class="text-sm text-gray-500">manager@example.com</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Club Manager
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            Active
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                                        <button class="text-red-600 hover:text-red-900">Delete</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 bg-green-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white font-medium">SU</span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">Student User</div>
                                                                <div class="text-sm text-gray-500">student@example.com</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            Student
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            Active
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                                        <button class="text-red-600 hover:text-red-900">Delete</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                                            <span class="text-2xl font-bold text-gray-900">47</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Active Users</span>
                                            <span class="text-2xl font-bold text-green-600">42</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">New This Month</span>
                                            <span class="text-2xl font-bold text-blue-600">8</span>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
