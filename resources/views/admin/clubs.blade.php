@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Clubs Management</h2>
        <a href="{{ route('clubs.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create New Club</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clubs as $club)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $club->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $club->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('clubs.edit', $club->id) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <a href="{{ route('admin.clubs.assign-managers', $club->id) }}" class="text-green-600 hover:underline mr-2">Assign Managers</a>
                        <form action="{{ route('clubs.destroy', $club->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No clubs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
