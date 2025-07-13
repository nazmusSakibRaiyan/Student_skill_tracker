@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Member Profile</h1>
                <p class="text-gray-600">{{ $club->name }}</p>
            </div>
            <a href="{{ route('club-manager.skills.members', $club) }}" 
               class="text-blue-600 hover:text-blue-800 flex items-center">
                ← Back to Members
            </a>
        </div>

        <!-- Member Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center mb-6">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                         alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-full object-cover mr-6">
                @else
                    <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-2xl font-bold mr-6">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center mt-2">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">{{ $user->role->name ?? 'Student' }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-600">Total Points</h3>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($totalPoints) }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-green-600">Skills Count</h3>
                    <p class="text-2xl font-bold text-green-900">{{ $memberSkills->count() }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-purple-600">Average Level</h3>
                    <p class="text-2xl font-bold text-purple-900">{{ number_format($averageLevel, 1) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Skills Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Skills Overview</h3>
                @forelse($memberSkills as $skill)
                    <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-900">{{ $skill->skillCategory->name }}</h4>
                            <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-sm">
                                Level {{ $skill->level }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>{{ $skill->total_points }} points</span>
                            <span>{{ $skill->skillCategory->category_type }}</span>
                        </div>
                        <!-- Progress Bar -->
                        <div class="mt-2">
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" 
                                     style="width: {{ min(100, ($skill->total_points / 1000) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">📊</div>
                        <p class="text-gray-500">No skills assigned yet</p>
                    </div>
                @endforelse
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($recentHistory as $history)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                @if($history->points_awarded > 0)
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-600 text-sm">+</span>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <span class="text-red-600 text-sm">-</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $history->studentSkill->skillCategory->name }}
                                    </p>
                                    <span class="text-sm text-gray-500">
                                        {{ $history->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-600">
                                        @if($history->points_awarded > 0)
                                            <span class="text-green-600">+{{ $history->points_awarded }} points</span>
                                        @else
                                            <span class="text-red-600">{{ $history->points_awarded }} points</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        by {{ $history->assignedBy->name ?? 'System' }}
                                    </p>
                                </div>
                                @if($history->reason)
                                    <p class="text-xs text-gray-500 mt-1 italic">"{{ $history->reason }}"</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-6xl mb-4">📝</div>
                            <p class="text-gray-500">No activity recorded yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('club-manager.skills.assign-form', ['club' => $club, 'user' => $user]) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    Assign Skills
                </a>
                <a href="{{ route('club-manager.skills.members', $club) }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                    Back to Members
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
