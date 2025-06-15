<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Manager Dashboard - Student Skill Tracker</title>
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
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Club Manager Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Welcome back, {{ auth()->user()->name }}! Manage your club and track student progress.
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                        Club Manager
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Clubs</h2>
                    @if($clubs->isEmpty())
                        <div class="text-gray-600">You are not assigned to any clubs yet.</div>
                    @else
                        <ul class="mb-6">
                            @foreach($clubs as $club)
                                <li class="mb-2 p-2 bg-gray-100 rounded flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($club->logo)
                                            <img src="{{ asset('storage/' . $club->logo) }}" alt="Club Logo" class="h-8 w-8 rounded mr-2">
                                        @else
                                            <div class="h-8 w-8 bg-gray-300 rounded mr-2 flex items-center justify-center text-lg text-gray-500">
                                                {{ strtoupper(substr($club->name,0,1)) }}
                                            </div>
                                        @endif
                                        <span>{{ $club->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <a href="{{ route('club-manager.club.edit', $club->id) }}" class="text-blue-600 hover:underline ml-4">Edit</a>
                                        <a href="{{ route('club-manager.club.add-student-form', $club->id) }}" class="text-green-600 hover:underline ml-2">Add Student</a>
                                    </div>
                                </li>
                                <li class="mb-6">
                                    <div class="bg-white p-6 border border-gray-200 rounded-lg mt-2">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Students</h3>
                                        <p class="text-gray-600 text-sm mb-4">Pending and approved students in this club</p>
                                        <table class="min-w-full bg-white border rounded mb-4">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-2">Name</th>
                                                    <th class="px-4 py-2">Email</th>
                                                    <th class="px-4 py-2">Status</th>
                                                    <th class="px-4 py-2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($club->students as $student)
                                                    <tr>
                                                        <td class="border px-4 py-2">{{ $student->name }}</td>
                                                        <td class="border px-4 py-2">{{ $student->email }}</td>
                                                        <td class="border px-4 py-2">
                                                            @if($student->pivot->status === 'pending')
                                                                <span class="text-yellow-600">Pending</span>
                                                            @else
                                                                <span class="text-green-600">Approved</span>
                                                            @endif
                                                        </td>
                                                        <td class="border px-4 py-2">
                                                            <form action="{{ route('club-manager.club.remove-student', [$club->id, $student->id]) }}" method="POST" onsubmit="return confirm('Remove this student from the club?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:underline">Remove</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Club Manager Access</h3>
                                <p class="text-sm text-blue-700 mt-1">You can manage your assigned club and track student progress.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Student Management</h3>
                            <p class="text-gray-600 text-sm mb-4">Manage students in your club</p>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>✓ View Student Profiles</li>
                                <li>✓ Track Student Progress</li>
                                <li>✓ Assign Skills to Students</li>
                                <li>✓ Generate Student Reports</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Skill Tracking</h3>
                            <p class="text-gray-600 text-sm mb-4">Monitor and update skill development</p>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>✓ Update Skill Levels</li>
                                <li>✓ Add Skill Assessments</li>
                                <li>✓ View Skill Progress</li>
                                <li>✓ Export Skill Reports</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Club Activities</h3>
                            <p class="text-gray-600 text-sm mb-4">Organize and track club activities</p>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>✓ Schedule Activities</li>
                                <li>✓ Track Attendance</li>
                                <li>✓ Manage Events</li>
                                <li>✓ Activity Reports</li>
                            </div>
                            
                            <div class="bg-white p-6 border border-gray-200 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Reports & Analytics</h3>
                                <p class="text-gray-600 text-sm mb-4">Club performance and progress reports</p>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>✓ Club Performance Reports</li>
                                    <li>✓ Student Progress Analytics</li>
                                    <li>✓ Skill Development Trends</li>
                                    <li>✓ Export Data</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="text-sm font-medium text-yellow-800">Note:</h4>
                        <p class="text-sm text-yellow-700 mt-1">Your access is limited to the club(s) you manage. Contact the admin for additional permissions or club assignments.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
