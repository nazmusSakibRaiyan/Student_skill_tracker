<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Student Skill Tracker</title>
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
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Welcome back, {{ auth()->user()->name }}! Track your skills and progress.
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium">
                        Student
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Profile Card -->
        @if(auth()->user()->isStudent())
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6 flex items-center space-x-6">
                <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border">
                <div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</div>
                    <div class="mt-1 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        {{ auth()->user()->role ? auth()->user()->role->display_name : 'Student' }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Learning Journey</h2>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Welcome to Your Dashboard</h3>
                                <p class="text-sm text-green-700 mt-1">Track your skill development and view your progress over time.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Total Events Enrolled -->
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $totalEnrollments }}</h3>
                                    <p class="text-sm text-gray-600">Events Enrolled</p>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Events -->
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $completedEvents }}</h3>
                                    <p class="text-sm text-gray-600">Events Completed</p>
                                </div>
                            </div>
                        </div>

                        <!-- Active Enrollments -->
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-yellow-100 p-3 rounded-lg">
                                    <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $totalEnrollments - $completedEvents }}</h3>
                                    <p class="text-sm text-gray-600">Active Enrollments</p>
                                </div>
                            </div>
                        </div>

                        <!-- Club Count -->
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-3 rounded-lg">
                                    <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM9 7a1 1 0 112 0 1 1 0 01-2 0zM2 18a6 6 0 1112 0H2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    @php $clubCount = auth()->user()->clubs->count(); @endphp
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $clubCount }}</h3>
                                    <p class="text-sm text-gray-600">Joined Clubs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">My Skills</h3>
                            <p class="text-gray-600 text-sm mb-4">View and track your skill development</p>
                            <div class="space-y-2">
                                <div class="bg-blue-100 p-3 rounded">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium">Programming</span>
                                        <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded">Beginner</span>
                                    </div>
                                    <div class="mt-2 bg-blue-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: 30%"></div>
                                    </div>
                                </div>
                                <div class="bg-green-100 p-3 rounded">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium">Design</span>
                                        <span class="text-xs bg-green-600 text-white px-2 py-1 rounded">Intermediate</span>
                                    </div>
                                    <div class="mt-2 bg-green-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">My Clubs</h3>
                            <p class="text-gray-600 text-sm mb-4">Clubs you're participating in</p>
                            <div class="space-y-2">
                                @php $clubs = auth()->user()->clubs; @endphp
                                @if($clubs->isEmpty())
                                    <div class="text-gray-500">You are not assigned to any clubs yet.</div>
                                @else
                                    @foreach($clubs as $club)
                                        <a href="{{ route('student.club-details', $club->id) }}" class="block">
                                            <div class="flex items-center p-2 bg-gray-50 rounded justify-between hover:bg-blue-100 transition">
                                                <div class="flex items-center">
                                                    @if($club->logo)
                                                        <img src="{{ asset('storage/' . $club->logo) }}" alt="Club Logo" class="h-8 w-8 rounded mr-2">
                                                    @else
                                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-2">
                                                            {{ strtoupper(substr($club->name,0,1)) }}
                                                        </div>
                                                    @endif
                                                    <p class="text-sm font-medium text-gray-900">{{ $club->name }}</p>
                                                </div>
                                                @if($club->pivot->status === 'pending')
                                                    <span class="text-yellow-600 text-sm">Pending Approval</span>
                                                @else
                                                    <span class="text-green-600 text-sm">Approved</span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Recent Activities</h3>
                            <p class="text-gray-600 text-sm mb-4">Your latest event enrollments and completions</p>
                            <div class="space-y-2">
                                @if($recentActivities && $recentActivities->count() > 0)
                                    @foreach($recentActivities as $activity)
                                        <div class="text-sm border-l-4 pl-3 py-2 
                                            @if($activity->status === 'completed') border-green-500 bg-green-50
                                            @elseif($activity->status === 'enrolled') border-blue-500 bg-blue-50
                                            @else border-gray-500 bg-gray-50
                                            @endif">
                                            <p class="font-medium text-gray-900">
                                                @if($activity->status === 'completed')
                                                    ✅ Completed {{ $activity->event->name }}
                                                @elseif($activity->status === 'enrolled')
                                                    📝 Enrolled in {{ $activity->event->name }}
                                                @else
                                                    ❌ Cancelled {{ $activity->event->name }}
                                                @endif
                                            </p>
                                            <p class="text-gray-500 text-xs">
                                                {{ $activity->event->club->name }} • 
                                                @if($activity->status === 'completed' && $activity->completed_at)
                                                    Completed {{ $activity->completed_at->diffForHumans() }}
                                                @else
                                                    {{ $activity->enrolled_at->diffForHumans() }}
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-sm text-gray-500 text-center py-4">
                                        <div class="text-2xl mb-2">📚</div>
                                        <p>No event activities yet.</p>
                                        <p class="text-xs">Join a club and enroll in events to see your activities here!</p>
                                    </div>
                                @endif
                                
                                @if($totalEnrollments > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>Total Events Enrolled: <span class="font-semibold text-gray-900">{{ $totalEnrollments }}</span></span>
                                            <span>Completed: <span class="font-semibold text-green-600">{{ $completedEvents }}</span></span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Goal Tracking</h3>
                            <p class="text-gray-600 text-sm mb-4">Your learning goals and progress</p>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span>Complete Web Development Course</span>
                                        <span>3/5</span>
                                    </div>
                                    <div class="mt-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: 60%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span>Master Design Tools</span>
                                        <span>2/4</span>
                                    </div>
                                    <div class="mt-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: 50%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Achievements</h3>
                            <p class="text-gray-600 text-sm mb-4">Your earned badges and certificates</p>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-700">First Login</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-700">Skill Tracker</p>
                                </div>
                                <div class="text-center opacity-50">
                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500">Expert</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Actions</h3>
                            <p class="text-gray-600 text-sm mb-4">Common tasks and actions</p>
                            <div class="space-y-2">
                                <button class="w-full text-left p-2 text-sm bg-green-50 text-green-700 rounded hover:bg-green-100 transition-colors">
                                    Update My Skills
                                </button>
                                <button class="w-full text-left p-2 text-sm bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition-colors">
                                    View My Progress
                                </button>
                                <button class="w-full text-left p-2 text-sm bg-purple-50 text-purple-700 rounded hover:bg-purple-100 transition-colors">
                                    Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
