@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $skillCategory->name }}</h1>
                <p class="text-gray-600">{{ $club->name }} • {{ $skillCategory->description }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('student.skills.club', $club) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    ← Back to Club
                </a>
            </div>
        </div>

        <!-- Progress Overview -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Current Level -->
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center"
                         style="background-color: {{ $skillCategory->color }}20; color: {{ $skillCategory->color }}">
                        <span class="text-2xl font-bold">{{ $studentSkill ? $studentSkill->level : 0 }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">Current Level</h3>
                </div>

                <!-- Total Points -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 mb-1">
                        {{ $studentSkill ? number_format($studentSkill->total_points) : 0 }}
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">Total Points</h3>
                    <p class="text-xs text-gray-400">of {{ number_format($skillCategory->max_points) }} max</p>
                </div>

                <!-- Progress Percentage -->
                <div class="text-center">
                    <div class="text-3xl font-bold mb-1" style="color: {{ $skillCategory->color }}">
                        {{ $studentSkill ? number_format($studentSkill->progress_percentage, 1) : 0 }}%
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">Progress</h3>
                </div>

                <!-- Rank -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600 mb-1">
                        @if($userRank && $userRank <= $categoryLeaders->count())
                            #{{ $userRank }}
                        @else
                            -
                        @endif
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">Category Rank</h3>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full transition-all duration-500" 
                         style="width: {{ $studentSkill ? $studentSkill->progress_percentage : 0 }}%; background-color: {{ $skillCategory->color }}"
                         title="{{ $studentSkill ? $studentSkill->progress_percentage : 0 }}% complete"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Point History -->
            <div class="lg:col-span-2">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Point History</h3>
                
                @if($history->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($history as $record)
                                <div class="p-6 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-4">
                                            <!-- Point Badge -->
                                            <div class="flex-shrink-0">
                                                @if($record->points_awarded > 0)
                                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                        <span class="text-green-600 font-bold text-sm">+{{ $record->points_awarded }}</span>
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                        <span class="text-red-600 font-bold text-sm">{{ $record->points_awarded }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-1 min-w-0">
                                                @if($record->reason)
                                                    <p class="text-gray-900 mb-1">{{ $record->reason }}</p>
                                                @endif
                                                
                                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                    <span>Awarded by: {{ $record->assignedBy->name ?? 'System' }}</span>
                                                    <span>•</span>
                                                    <span>{{ $record->awarded_at ? $record->awarded_at->format('M j, Y \a\t g:i A') : $record->created_at->format('M j, Y \a\t g:i A') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes (if any) -->
                                    @if($record->notes)
                                        <div class="mt-3 pl-14">
                                            <p class="text-sm text-gray-500 italic">Note: {{ $record->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($history->hasPages())
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                {{ $history->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📝</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No History Yet</h3>
                        <p class="text-gray-500">You haven't earned any points in this category yet. Start participating in activities to earn your first points!</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Category Leaderboard -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Category Leaders</h3>
                    
                    @if($categoryLeaders->count() > 0)
                        <div class="space-y-3">
                            @foreach($categoryLeaders as $index => $leader)
                                <div class="flex items-center justify-between p-2 rounded-lg 
                                    {{ $leader['user']->id === auth()->id() ? 'bg-blue-50 border border-blue-200' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold mr-3
                                            @if($index === 0) bg-yellow-100 text-yellow-800
                                            @elseif($index === 1) bg-gray-100 text-gray-800  
                                            @elseif($index === 2) bg-orange-100 text-orange-800
                                            @else bg-gray-50 text-gray-600
                                            @endif">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $leader['user']->name }}
                                                @if($leader['user']->id === auth()->id())
                                                    <span class="text-xs text-blue-600">(You)</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">Level {{ $leader['level'] }}</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-700">
                                        {{ number_format($leader['total_points']) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No one has earned points in this category yet</p>
                    @endif
                </div>

                <!-- Category Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Category Details</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Maximum Points</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($skillCategory->max_points) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Category Type</span>
                            <span class="text-sm font-semibold text-gray-900">{{ ucfirst($skillCategory->category_type ?? 'General') }}</span>
                        </div>
                        
                        @if($skillCategory->description)
                            <div class="pt-3 border-t border-gray-100">
                                <span class="text-sm text-gray-600 block mb-1">Description</span>
                                <p class="text-sm text-gray-900">{{ $skillCategory->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('student.skills.club', $club) }}" 
                           class="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                            View All Club Skills
                        </a>
                        
                        <a href="{{ route('student.skills.index') }}" 
                           class="block w-full text-center bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
