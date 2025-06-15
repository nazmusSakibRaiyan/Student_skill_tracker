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
                        
                        @if(!auth()->user()->hasVerifiedEmail())
                            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded">
                                <strong>Email not verified.</strong> Please <a href="{{ route('verification.notice') }}" class="underline">verify your email</a> to access all features.
                            </div>
                        @endif
                        
                        <!-- Profile Picture Upload (Students only) -->
                        @if($user->isStudent())
                        <form method="POST" action="{{ route('profile.picture.update') }}" enctype="multipart/form-data" class="mb-6">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <div>
                                    <img src="{{ $user->profile_picture_url }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Change Profile Picture</label>
                                    <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG, or PNG. Max 2MB.</p>
                                    @error('profile_picture')
                                        <span class="text-red-600 text-xs">{{ $message }}</span>
                                    @enderror
                                    @if(session('success'))
                                        <span class="text-green-600 text-xs">{{ session('success') }}</span>
                                    @endif
                                </div>
                                <div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none">Upload</button>
                                </div>
                            </div>
                        </form>
                        @endif
                        
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
                                        <dd class="text-sm text-gray-900 flex items-center">
                                            {{ $user->email }}
                                            @if($user->hasVerifiedEmail())
                                                <span class="ml-2 inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Verified
                                                </span>
                                            @else
                                                <span class="ml-2 inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <a href="{{ route('verification.notice') }}" class="hover:underline">Unverified</a>
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Role</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($user->role)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
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
