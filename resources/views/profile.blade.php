<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Student Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">Student Skill Tracker</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ $user->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
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
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">User Profile</h2>
                        
                        <!-- User Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Role</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($user->role)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($user->role->name === 'master_admin') bg-red-100 text-red-800
                                                    @elseif($user->role->name === 'club_manager') bg-blue-100 text-blue-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ $user->role->display_name }}
                                                </span>
                                            @else
                                                <span class="text-gray-500">No role assigned</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions</h3>
                                @if($user->role && $user->role->permissions->count() > 0)
                                    <div class="space-y-1 max-h-64 overflow-y-auto">
                                        @foreach($user->role->permissions as $permission)
                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                                {{ $permission->display_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">No permissions assigned</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Quick Access Links -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Access</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @if($user->isMasterAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="bg-red-600 text-white p-4 rounded-lg hover:bg-red-700 text-center">
                                        <div class="font-semibold">Admin Dashboard</div>
                                        <div class="text-sm opacity-90">Full system access</div>
                                    </a>
                                @endif
                                
                                @if($user->isClubManager() || $user->isMasterAdmin())
                                    <a href="{{ route('club-manager.dashboard') }}" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 text-center">
                                        <div class="font-semibold">Club Manager</div>
                                        <div class="text-sm opacity-90">Manage club activities</div>
                                    </a>
                                @endif
                                
                                <a href="{{ route('student.dashboard') }}" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 text-center">
                                    <div class="font-semibold">Student Dashboard</div>
                                    <div class="text-sm opacity-90">View your progress</div>
                                </a>
                                
                                @if($user->hasPermission('manage_users'))
                                    <a href="{{ route('users.index') }}" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 text-center">
                                        <div class="font-semibold">User Management</div>
                                        <div class="text-sm opacity-90">Manage system users</div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
