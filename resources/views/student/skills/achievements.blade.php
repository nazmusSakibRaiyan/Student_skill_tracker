@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Achievements</h1>
                <p class="text-gray-600">Celebrate your skill milestones and accomplishments</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('student.skills.index') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    ← Back to Skills
                </a>
                <a href="{{ route('student.skills.history') }}" 
                   class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
                    View History
                </a>
            </div>
        </div>

        @if($skills->count() > 0)
            <!-- Achievement Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($skills as $skill)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Achievement Header -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $skill->skillCategory->name }}</h3>
                                <span class="text-2xl">
                                    @if($skill->level >= 5)
                                        🏆
                                    @elseif($skill->level >= 3)
                                        🥇
                                    @elseif($skill->level >= 2)
                                        🥈
                                    @else
                                        🥉
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $skill->club->name }}</p>
                        </div>

                        <!-- Progress Section -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">Level {{ $skill->level }}</span>
                                <span class="text-sm text-gray-500">{{ $skill->total_points }} points</span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                                     style="width: {{ $skill->progress_percentage }}%"></div>
                            </div>

                            <!-- Achievement Badges -->
                            <div class="space-y-2">
                                @if($skill->total_points >= 100)
                                    <div class="flex items-center text-sm">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <span class="text-gray-700">First 100 Points</span>
                                    </div>
                                @endif
                                
                                @if($skill->level >= 2)
                                    <div class="flex items-center text-sm">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        <span class="text-gray-700">Level {{ $skill->level }} Achieved</span>
                                    </div>
                                @endif
                                
                                @if($skill->total_points >= 500)
                                    <div class="flex items-center text-sm">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                        <span class="text-gray-700">500+ Points Milestone</span>
                                    </div>
                                @endif
                                
                                @if($skill->total_points >= 1000)
                                    <div class="flex items-center text-sm">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                        <span class="text-gray-700">Master Level (1000+ Points)</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Overall Achievement Summary -->
            <div class="mt-12 bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Achievement Summary</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $skills->count() }}</div>
                        <div class="text-sm text-gray-600">Skills Developed</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $skills->sum('total_points') }}</div>
                        <div class="text-sm text-gray-600">Total Points</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ number_format($skills->avg('level'), 1) }}</div>
                        <div class="text-sm text-gray-600">Average Level</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ $skills->where('level', '>=', 3)->count() }}</div>
                        <div class="text-sm text-gray-600">Advanced Skills</div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Achievements Yet</h3>
                <p class="text-gray-500 mb-6">Start participating in club activities and earning skill points to unlock achievements!</p>
                <a href="{{ route('student.skills.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View My Skills
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
