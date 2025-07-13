@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Assign Skill Points</h1>
                <p class="text-gray-600">{{ $club->name }} - {{ $user->name }}</p>
            </div>
            <a href="{{ route('club-manager.skills.member-profile', [$club, $user]) }}" 
               class="text-blue-600 hover:text-blue-800 flex items-center">
                ← Back to Profile
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Member Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                         alt="{{ $user->name }}" 
                         class="w-16 h-16 rounded-full object-cover mr-4">
                @else
                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                        <span class="text-gray-600 font-bold text-xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    @php
                        $totalPoints = $currentSkills->sum('total_points');
                        $averageLevel = $currentSkills->avg('level') ?? 0;
                    @endphp
                    <p class="text-sm text-gray-500 mt-1">
                        Total Points: {{ number_format($totalPoints) }} | Average Level: {{ number_format($averageLevel, 1) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Current Skills Overview -->
        @if($currentSkills->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Current Skills</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($currentSkills as $skill)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" 
                                         style="background-color: {{ $skill->skillCategory->color ?? '#3B82F6' }}"></div>
                                    <span class="font-medium text-gray-900">{{ $skill->skillCategory->name }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Level {{ $skill->level }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $skill->progress_percentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500">{{ $skill->total_points }} points</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Assign Points Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Assign New Points</h3>
            
            <form action="{{ route('club-manager.skills.assign-points', [$club, $user]) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Selection -->
                    <div>
                        <label for="skill_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Skill Category <span class="text-red-500">*</span>
                        </label>
                        <select name="skill_category_id" id="skill_category_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="">Select a category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        data-color="{{ $category->color }}"
                                        data-description="{{ $category->description }}"
                                        {{ old('skill_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('skill_category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div id="category-description" class="text-sm text-gray-500 mt-1"></div>
                    </div>

                    <!-- Points -->
                    <div>
                        <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                            Points to Award <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="points" 
                               id="points" 
                               min="1" 
                               max="50" 
                               value="{{ old('points') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               required
                               placeholder="Enter points (1-50)">
                        @error('points')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Maximum 50 points per assignment</p>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mt-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Assignment <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              id="reason" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                              required
                              placeholder="Explain why you're awarding these points (e.g., excellent presentation, leadership in project, etc.)">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Points Preview -->
                <div id="points-preview" class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200" style="display: none;">
                    <h4 class="font-medium text-blue-900 mb-2">Points Assignment Preview</h4>
                    <div id="preview-content" class="text-sm text-blue-800"></div>
                </div>

                <!-- Submit -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('club-manager.skills.member-profile', [$club, $user]) }}" 
                       class="px-6 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        Assign Points
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('skill_category_id');
    const pointsInput = document.getElementById('points');
    const categoryDescription = document.getElementById('category-description');
    const pointsPreview = document.getElementById('points-preview');
    const previewContent = document.getElementById('preview-content');

    function updateCategoryDescription() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        if (selectedOption.value) {
            const description = selectedOption.dataset.description;
            categoryDescription.textContent = description || 'No description available';
        } else {
            categoryDescription.textContent = '';
        }
        updatePreview();
    }

    function updatePreview() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const points = pointsInput.value;
        
        if (selectedOption.value && points) {
            const categoryName = selectedOption.textContent;
            const currentSkill = @json($currentSkills);
            const currentPoints = currentSkill[selectedOption.value] ? currentSkill[selectedOption.value].total_points : 0;
            const currentLevel = currentSkill[selectedOption.value] ? currentSkill[selectedOption.value].level : 0;
            const newPoints = parseInt(currentPoints) + parseInt(points);
            const newLevel = Math.max(1, Math.floor(newPoints / 20) + 1);
            
            previewContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="font-medium">Category: ${categoryName}</p>
                        <p>Points to add: +${points}</p>
                    </div>
                    <div>
                        <p>Current: ${currentPoints} pts (Level ${currentLevel})</p>
                        <p>New Total: ${newPoints} pts (Level ${newLevel})</p>
                    </div>
                    <div>
                        <p class="text-green-600">Level change: ${newLevel > currentLevel ? '+' + (newLevel - currentLevel) : 'No change'}</p>
                    </div>
                </div>
            `;
            pointsPreview.style.display = 'block';
        } else {
            pointsPreview.style.display = 'none';
        }
    }

    categorySelect.addEventListener('change', updateCategoryDescription);
    pointsInput.addEventListener('input', updatePreview);
});
</script>
@endsection
