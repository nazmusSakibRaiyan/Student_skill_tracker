@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Skills Dashboard</h1>
                <p class="text-gray-600">Track your skill progress across all clubs</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('student.skills.history') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    View History
                </a>
                <a href="{{ route('student.skills.achievements') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    Achievements
                </a>
            </div>
        </div>

        <!-- Overall Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Points</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPoints) }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Average Level</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($averageLevel, 1) }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Active Clubs</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalClubs }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Skill Categories</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalCategories }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Club Skills -->
        <div class="space-y-6">
            @forelse($clubs as $club)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Club Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $club->name }}</h2>
                                @php
                                    $clubSkills = $studentSkills->get($club->id, collect());
                                    $clubTotalPoints = $clubSkills->sum('total_points');
                                    $clubAvgLevel = $clubSkills->avg('level') ?? 0;
                                @endphp
                                <p class="text-sm text-gray-600">
                                    {{ number_format($clubTotalPoints) }} total points | 
                                    Average level: {{ number_format($clubAvgLevel, 1) }}
                                </p>
                            </div>
                            <a href="{{ route('student.skills.club', $club) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                                View Details
                            </a>
                        </div>
                    </div>

                    <!-- Club Skills Grid -->
                    <div class="p-6">
                        @if($club->skillCategories->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($club->skillCategories as $category)
                                    @php
                                        $skill = $clubSkills->where('skill_category_id', $category->id)->first();
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 rounded-full mr-2" 
                                                     style="background-color: {{ $category->color ?? '#3B82F6' }}"></div>
                                                <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                                            </div>
                                            @if($skill)
                                                <span class="text-sm font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                                    Level {{ $skill->level }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                    Not started
                                                </span>
                                            @endif
                                        </div>

                                        @if($skill)
                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                <div class="h-2 rounded-full" 
                                                     style="width: {{ $skill->progress_percentage }}%; background-color: {{ $category->color ?? '#3B82F6' }}"></div>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">{{ $skill->total_points }} points</span>
                                                <span class="text-gray-500">{{ number_format($skill->progress_percentage, 1) }}%</span>
                                            </div>
                                        @else
                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                <div class="h-2 rounded-full" style="width: 0%"></div>
                                            </div>
                                            <p class="text-sm text-gray-500">No points earned yet</p>
                                        @endif

                                        @if($skill)
                                            <a href="{{ route('student.skills.category', [$club, $category]) }}" 
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 mt-2">
                                                View Progress
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p>No skill categories available for this club</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Clubs Found</h3>
                    <p class="text-gray-500">You are not a member of any clubs yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Recent Activity -->
        @if($recentActivity->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h2>
                <div class="space-y-4">
                    @foreach($recentActivity as $activity)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full p-2 mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">
                                        +{{ $activity->points_awarded }} points in {{ $activity->studentSkill->skillCategory->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $activity->studentSkill->club->name }} - by {{ $activity->assignedBy->name }}
                                    </p>
                                    @if($activity->reason)
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->reason }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $activity->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('student.skills.history') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        View all activity →
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
