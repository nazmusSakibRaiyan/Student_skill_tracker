@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 bg-white rounded-xl shadow-xl px-10">
    <h2 class="text-2xl font-bold mb-4 text-pink-700">Manage Events for {{ $club->name }}</h2>
    <div id="club-manager-events" data-club-id="{{ $club->id }}"></div>
    <a href="{{ route('club-manager.dashboard') }}" class="inline-block mt-6 text-pink-600 hover:underline">&larr; Back to Dashboard</a>
</div>
@endsection
