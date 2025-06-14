@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Create New Club</h2>
    <form action="{{ route('clubs.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Club Name</label>
            <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create Club</button>
        </div>
    </form>
</div>
@endsection
