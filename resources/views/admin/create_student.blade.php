@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-2">Add Student</h2>
        <p class="mb-6 text-gray-600">Register a new student for your organization.</p>
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.users.create-student') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1" for="name">Name</label>
                <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label class="block font-semibold mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-semibold w-full">Add Student</button>
        </form>
    </div>
</div>
@endsection
