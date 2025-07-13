@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $club->name }} Skills</h1>
                <p class="text-gray-600">Track your skill progress within this club</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('student.skills.index') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    ← Back to All Skills
                </a>
            </div>
        </div>

        <!-- Club Overview Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if($club->logo)
                        <img src="{{ asset('storage/' . $club->logo) }}" alt="{{ $club->name }}" 
                             class="w-16 h-16 rounded-full object-cover mr-4">
                    @else
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mr-4">
                            {{ substr($club->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $club->name }}</h2>
                        @if($club->description)
                            <p class="text-gray-600 mt-1">{{ $club->description }}</p>
                        @endif
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="text-sm text-gray-500">{{ $categories->count() }} Categories</span>
                            <span class="text-sm text-gray-500">{{ $allMembers->count() }} Members</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $studentSkills->sum('total_points') }}
                    </div>
                    <div class="text-sm text-gray-500">Your Total Points</div>
                    @if($userPosition)
                        <div class="text-sm text-gray-600 mt-1">
                            Rank #{{ $userPosition }} of {{ $allMembers->count() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Skills Categories -->
            <div class="lg:col-span-2">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Skill Categories</h3>
                
                @if($categories->count() > 0)
                    <div class="space-y-6">
                        @foreach($categories as $category)
                            @php
                                $userSkill = $studentSkills->get($category->id);
                                $progress = $userSkill ? $userSkill->progress_percentage : 0;
                                $points = $userSkill ? $userSkill->total_points : 0;
                                $level = $userSkill ? $userSkill->level : 0;
                            @endphp
                            
                            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" 
                                             style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                                            @if($category->icon)
                                                <i class="fas fa-{{ $category->icon }} text-xl"></i>
                                            @else
                                                <span class="text-xl font-bold">{{ substr($category->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $category->description }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($level > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                                  style="background-color: {{ $category->color }}">
                                                Level {{ $level }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Not Started
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Progress Section -->
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700">Progress</span>
                                        <span class="text-sm text-gray-500">{{ $points }} / {{ $category->max_points }} points</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="h-3 rounded-full transition-all duration-300" 
                                             style="width: {{ $progress }}%; background-color: {{ $category->color }}"
                                             title="{{ $progress }}% complete"></div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500">
                                        @if($points > 0)
                                            {{ $progress }}% Complete
                                        @else
                                            Ready to start earning points
                                        @endif
                                    </div>
                                    <a href="{{ route('student.skills.category', [$club, $category]) }}" 
                                       class="text-sm font-medium hover:underline"
                                       style="color: {{ $category->color }}">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📂</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Skill Categories</h3>
                        <p class="text-gray-500">This club hasn't set up any skill categories yet.</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Club Leaderboard -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Club Leaderboard</h3>
                    
                    @if($allMembers->count() > 0)
                        <div class="space-y-3">
                            @foreach($allMembers->take(10) as $index => $member)
                                <div class="flex items-center justify-between p-2 rounded-lg 
                                    {{ $member['user']->id === auth()->id() ? 'bg-blue-50 border border-blue-200' : 'hover:bg-gray-50' }}">
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
                                                {{ $member['user']->name }}
                                                @if($member['user']->id === auth()->id())
                                                    <span class="text-xs text-blue-600">(You)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-700">
                                        {{ number_format($member['total_points']) }}
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($allMembers->count() > 10)
                                <div class="text-center text-sm text-gray-500 pt-2">
                                    ... and {{ $allMembers->count() - 10 }} more members
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No members yet</p>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Your Stats</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Points</span>
                            <span class="text-lg font-bold text-gray-900">{{ $studentSkills->sum('total_points') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Categories Started</span>
                            <span class="text-lg font-bold text-gray-900">{{ $studentSkills->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Average Level</span>
                            <span class="text-lg font-bold text-gray-900">
                                {{ $studentSkills->count() > 0 ? number_format($studentSkills->avg('level'), 1) : '0' }}
                            </span>
                        </div>
                        
                        @if($userPosition)
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                <span class="text-sm text-gray-600">Club Rank</span>
                                <span class="text-lg font-bold text-blue-600">#{{ $userPosition }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
