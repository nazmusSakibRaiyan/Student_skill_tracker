@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Club Members</h1>
                <p class="text-gray-600">{{ $club->name }}</p>
            </div>
            <a href="{{ route('club-manager.skills.index', $club) }}" 
               class="text-blue-600 hover:text-blue-800 flex items-center">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <label for="category-filter" class="text-sm font-medium text-gray-700 mr-2">Filter by Category:</label>
                    <select id="category-filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center">
                    <label for="search" class="text-sm font-medium text-gray-700 mr-2">Search:</label>
                    <input type="text" id="search" placeholder="Search members..." 
                           class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <!-- Members Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($members as $member)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <!-- Member Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if($member->profile_picture)
                                    <img src="{{ asset('storage/' . $member->profile_picture) }}" 
                                         alt="{{ $member->name }}" 
                                         class="w-12 h-12 rounded-full object-cover mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-gray-600 font-semibold text-lg">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $member->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $totalPoints = $member->studentSkills->sum('total_points');
                                    $averageLevel = $member->studentSkills->avg('level') ?? 0;
                                @endphp
                                <p class="text-sm font-medium text-gray-900">{{ number_format($totalPoints) }} pts</p>
                                <p class="text-xs text-gray-500">Avg Level: {{ number_format($averageLevel, 1) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Skills Summary -->
                    <div class="p-4">
                        @if($member->studentSkills->count() > 0)
                            <div class="space-y-2">
                                @foreach($member->studentSkills->take(3) as $skill)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" 
                                                 style="background-color: {{ $skill->skillCategory->color ?? '#3B82F6' }}"></div>
                                            <span class="text-sm text-gray-700">{{ $skill->skillCategory->name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 mr-2">Lvl {{ $skill->level }}</span>
                                            <span class="text-xs text-gray-500">{{ $skill->total_points }} pts</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if($member->studentSkills->count() > 3)
                                    <p class="text-xs text-gray-500 text-center pt-2">
                                        +{{ $member->studentSkills->count() - 3 }} more skills
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No skills assigned yet</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-4 pb-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('club-manager.skills.member-profile', [$club, $member]) }}" 
                               class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-md text-sm hover:bg-blue-700 transition duration-200">
                                View Profile
                            </a>
                            <a href="{{ route('club-manager.skills.assign-form', [$club, $member]) }}" 
                               class="flex-1 bg-green-600 text-white text-center py-2 px-3 rounded-md text-sm hover:bg-green-700 transition duration-200">
                                Assign Points
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Members Found</h3>
                        <p class="text-gray-500">There are no approved members in this club yet.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
            <div class="mt-8">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category-filter');
    const searchInput = document.getElementById('search');
    const memberCards = document.querySelectorAll('.grid > div');

    function filterMembers() {
        const selectedCategory = categoryFilter.value;
        const searchTerm = searchInput.value.toLowerCase();

        memberCards.forEach(card => {
            const memberName = card.querySelector('h3').textContent.toLowerCase();
            const memberEmail = card.querySelector('.text-gray-600').textContent.toLowerCase();
            const skillElements = card.querySelectorAll('.text-gray-700');
            
            let hasCategory = !selectedCategory;
            if (selectedCategory) {
                skillElements.forEach(skill => {
                    if (skill.textContent.toLowerCase().includes(selectedCategory.toLowerCase())) {
                        hasCategory = true;
                    }
                });
            }

            const matchesSearch = memberName.includes(searchTerm) || memberEmail.includes(searchTerm);
            
            if (hasCategory && matchesSearch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    categoryFilter.addEventListener('change', filterMembers);
    searchInput.addEventListener('input', filterMembers);
});
</script>
@endsection
