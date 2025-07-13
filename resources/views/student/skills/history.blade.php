@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Skill Points History</h1>
                <p class="text-gray-600">Track all your skill point awards and achievements</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('student.skills.index') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    ← Back to Skills
                </a>
                <a href="{{ route('student.skills.achievements') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    View Achievements
                </a>
            </div>
        </div>

        <!-- History List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($history->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($history as $record)
                        <div class="p-6 hover:bg-gray-50 transition duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <!-- Point Badge -->
                                    <div class="flex-shrink-0">
                                        @if($record->points_awarded > 0)
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-bold text-sm">+{{ $record->points_awarded }}</span>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                <span class="text-red-600 font-bold text-sm">{{ $record->points_awarded }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ $record->studentSkill->skillCategory->name }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $record->studentSkill->club->name }}
                                            </span>
                                        </div>
                                        
                                        @if($record->reason)
                                            <p class="text-gray-600 mb-2">{{ $record->reason }}</p>
                                        @endif
                                        
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span>Awarded by: {{ $record->assignedBy->name ?? 'System' }}</span>
                                            <span>•</span>
                                            <span>{{ $record->awarded_at ? $record->awarded_at->format('M j, Y \a\t g:i A') : $record->created_at->format('M j, Y \a\t g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Type Badge (if needed) -->
                                @if($record->action_type)
                                    <div class="flex-shrink-0 ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($record->action_type === 'award') bg-green-100 text-green-800
                                            @elseif($record->action_type === 'deduct') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($record->action_type) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Notes (if any) -->
                            @if($record->notes)
                                <div class="mt-3 pl-16">
                                    <p class="text-sm text-gray-500 italic">Note: {{ $record->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $history->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No History Yet</h3>
                    <p class="text-gray-500 mb-6">You haven't earned any skill points yet. Join clubs and participate in activities to start earning points!</p>
                    <a href="{{ route('student.skills.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        View My Skills
                    </a>
                </div>
            @endif
        </div>

        <!-- Stats Summary (if there's history) -->
        @if($history->count() > 0)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Records</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $history->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Points This Month</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $history->where('created_at', '>=', now()->startOfMonth())->sum('points_awarded') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 rounded-full p-3 mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Last Activity</p>
                            <p class="text-2xl font-bold text-gray-900">
                                @if($history->first())
                                    {{ $history->first()->created_at->diffForHumans() }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
