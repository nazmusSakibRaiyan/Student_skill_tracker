@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-2">Assign Club Managers</h2>
        <p class="mb-6 text-gray-600">Assign one or more club managers to <span class="font-semibold">{{ $club->name }}</span>.</p>
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.clubs.assign-managers', $club->id) }}">
            @csrf
            <div class="mb-6">
                <label class="block font-semibold mb-2">Available Club Managers:</label>
                <div class="grid grid-cols-1 gap-3">
                    @forelse($managers as $manager)
                        @php
                            $clubManager = \App\Models\ClubManager::where('user_id', $manager->id)
                                ->where('club_id', $club->id)
                                ->first();
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200 mb-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="manager_ids[]" value="{{ $manager->id }}" class="form-checkbox h-5 w-5 text-blue-600" {{ in_array($manager->id, $assigned) ? 'checked' : '' }}>
                                <span class="ml-3 text-gray-800">
                                    <span class="font-medium">{{ $manager->name }}</span> <span class="text-gray-500">({{ $manager->email }})</span>
                                    @if($clubManager && $clubManager->banned)
                                        <span class="ml-2 px-2 py-1 bg-red-200 text-red-800 text-xs rounded">Banned</span>
                                    @endif
                                </span>
                            </div>
                            <form method="POST" action="{{ url('/api/ban-club-manager') }}" onsubmit="return confirm('Are you sure you want to ban this manager?');">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $manager->id }}">
                                <input type="hidden" name="club_id" value="{{ $club->id }}">
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs font-semibold">Ban</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-gray-500">No club managers available.</div>
                    @endforelse
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">Save Assignments</button>
        </form>
    </div>
</div>
@endsection
