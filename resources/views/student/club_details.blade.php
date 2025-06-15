@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Club Details</h2>
    <div class="bg-white rounded shadow p-6">
        <div class="flex items-center mb-4">
            @if($club->logo)
                <img src="{{ asset('storage/' . $club->logo) }}" alt="Club Logo" class="h-16 w-16 rounded mr-4">
            @else
                <div class="h-16 w-16 bg-gray-200 rounded mr-4 flex items-center justify-center text-2xl text-gray-500">
                    {{ strtoupper(substr($club->name,0,1)) }}
                </div>
            @endif
            <div>
                <h3 class="text-xl font-semibold text-gray-900">{{ $club->name }}</h3>
            </div>
        </div>
        <div class="mb-4">
            <h4 class="font-medium text-gray-800 mb-1">Description</h4>
            <p class="text-gray-700">{{ $club->description ?? 'No description provided.' }}</p>
        </div>
    </div>
    <a href="{{ route('student.dashboard') }}" class="inline-block mt-6 text-blue-600 hover:underline">&larr; Back to My Clubs</a>
</div>
@endsection
