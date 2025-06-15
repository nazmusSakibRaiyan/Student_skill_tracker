@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Update Club Info</h2>
    <form action="{{ route('club-manager.club.update', $club->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-medium">Club Name</label>
            <input type="text" name="name" value="{{ old('name', $club->name) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="3">{{ old('description', $club->description) }}</textarea>
        </div>
        <div>
            <label class="block font-medium">Logo</label>
            @if($club->logo)
                <img src="{{ asset('storage/' . $club->logo) }}" alt="Club Logo" class="h-16 mb-2">
            @endif
            <input type="file" name="logo" accept="image/png,image/jpeg,image/jpg" class="block">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Club</button>
    </form>
</div>
@endsection
