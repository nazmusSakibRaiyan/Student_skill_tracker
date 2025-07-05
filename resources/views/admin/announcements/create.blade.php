<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement - Student Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-purple-600 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('admin.announcements.index') }}" class="text-white hover:text-purple-200 mr-4">
                            ← Back to Announcements
                        </a>
                        <h1 class="text-xl font-semibold text-white">Create Announcement</h1>
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
        <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Announcement</h2>
                        
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.announcements.store') }}" method="POST">
                            @csrf
                            
                            <!-- Title -->
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Announcement Title
                                </label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="Enter announcement title"
                                       required>
                            </div>

                            <!-- Message -->
                            <div class="mb-6">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Message
                                </label>
                                <textarea id="message" 
                                          name="message" 
                                          rows="5"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                          placeholder="Enter your announcement message"
                                          required>{{ old('message') }}</textarea>
                            </div>

                            <!-- Priority -->
                            <div class="mb-6">
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority Level
                                </label>
                                <select id="priority" 
                                        name="priority" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                        required>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="emergency" {{ old('priority') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                            </div>

                            <!-- Target Audience -->
                            <div class="mb-6">
                                <label for="target_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Target Audience
                                </label>
                                <select id="target_type" 
                                        name="target_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                        required
                                        onchange="toggleTargetOptions()">
                                    <option value="all" {{ old('target_type', 'all') === 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="role" {{ old('target_type') === 'role' ? 'selected' : '' }}>Specific Roles</option>
                                    <option value="club" {{ old('target_type') === 'club' ? 'selected' : '' }}>Specific Clubs</option>
                                    <option value="individual" {{ old('target_type') === 'individual' ? 'selected' : '' }}>Individual Users</option>
                                </select>
                            </div>

                            <!-- Role Targets -->
                            <div id="role-targets" class="mb-6 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Roles
                                </label>
                                <div class="space-y-2">
                                    @foreach($roles as $role)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="target_roles[]" 
                                                   value="{{ $role->name }}"
                                                   {{ in_array($role->name, old('target_roles', [])) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">{{ $role->display_name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Club Targets -->
                            <div id="club-targets" class="mb-6 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Clubs
                                </label>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @foreach($clubs as $club)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="target_clubs[]" 
                                                   value="{{ $club->id }}"
                                                   {{ in_array($club->id, old('target_clubs', [])) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">{{ $club->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Individual User Targets -->
                            <div id="user-targets" class="mb-6 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Users
                                </label>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @foreach($users as $user)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="target_users[]" 
                                                   value="{{ $user->id }}"
                                                   {{ in_array($user->id, old('target_users', [])) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $user->name }} ({{ $user->email }}) - {{ $user->role->display_name ?? 'No Role' }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Expiration Date -->
                            <div class="mb-6">
                                <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                                    Expiration Date (Optional)
                                </label>
                                <input type="datetime-local" 
                                       id="expires_at" 
                                       name="expires_at" 
                                       value="{{ old('expires_at') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                <p class="mt-1 text-sm text-gray-500">Leave empty for permanent announcement</p>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.announcements.index') }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700">
                                    Create Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTargetOptions() {
            const targetType = document.getElementById('target_type').value;
            const roleTargets = document.getElementById('role-targets');
            const clubTargets = document.getElementById('club-targets');
            const userTargets = document.getElementById('user-targets');

            // Hide all target option divs
            roleTargets.classList.add('hidden');
            clubTargets.classList.add('hidden');
            userTargets.classList.add('hidden');

            // Show relevant target options
            switch(targetType) {
                case 'role':
                    roleTargets.classList.remove('hidden');
                    break;
                case 'club':
                    clubTargets.classList.remove('hidden');
                    break;
                case 'individual':
                    userTargets.classList.remove('hidden');
                    break;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleTargetOptions();
        });
    </script>
</body>
</html>
