@php
    // $pendingStudents should be available from the controller, otherwise fetch here
    if (!isset($pendingStudents)) {
        $pendingStudents = \DB::table('club_student')
            ->where('status', 'pending')
            ->join('clubs', 'club_student.club_id', '=', 'clubs.id')
            ->join('users', 'club_student.user_id', '=', 'users.id')
            ->select('club_student.club_id', 'club_student.user_id', 'clubs.name as club_name', 'users.name as student_name', 'users.email as student_email')
            ->get()
            ->map(function($row) {
                $row->club = \App\Models\Club::find($row->club_id);
                $row->student = \App\Models\User::find($row->user_id);
                return $row;
            });
    }
@endphp
@if($pendingStudents->isEmpty())
    <div class="text-gray-600">No students pending approval.</div>
@else
    <table class="min-w-full bg-white border rounded mb-4">
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
