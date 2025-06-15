@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Pending Student Approvals</h2>
    @if($pendingStudents->isEmpty())
        <div class="text-gray-600">No students pending approval.</div>
    @else
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr>
                    <th class="px-4 py-2">Club</th>
                    <th class="px-4 py-2">Student</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingStudents as $entry)
                    <tr>
                        <td class="border px-4 py-2">{{ $entry->club->name }}</td>
                        <td class="border px-4 py-2">{{ $entry->student->name }}</td>
                        <td class="border px-4 py-2">{{ $entry->student->email }}</td>
                        <td class="border px-4 py-2">
                            <form action="{{ route('admin.clubs.approve-student', [$entry->club->id, $entry->student->id]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">Approve</button>
                            </form>
                            <form action="{{ route('admin.clubs.reject-student', [$entry->club->id, $entry->student->id]) }}" method="POST" class="inline ml-2">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
