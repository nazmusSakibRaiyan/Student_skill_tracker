@extends('layouts.app')

@section('title', 'Manage Attendance - ' . $event->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $event->name }}</h1>
                <p class="mt-2 text-gray-600">{{ $event->club->name }} • {{ $event->start_date->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('club-manager.attendance.qr-code', $event) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    QR Code
                </a>
                <a href="{{ route('club-manager.attendance.export', $event) }}" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </a>
                <a href="{{ route('club-manager.attendance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-medium text-gray-500">Total Enrolled</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ $attendanceStats['total_enrolled'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-medium text-gray-500">Present</dt>
                    <dd class="text-2xl font-bold text-green-900">{{ $attendanceStats['present'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-medium text-gray-500">Late</dt>
                    <dd class="text-2xl font-bold text-yellow-900">{{ $attendanceStats['late'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-medium text-gray-500">Absent</dt>
                    <dd class="text-2xl font-bold text-red-900">{{ $attendanceStats['absent'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-medium text-gray-500">Attendance Rate</dt>
                    <dd class="text-2xl font-bold text-indigo-900">{{ $attendanceStats['attendance_rate'] }}%</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Actions</h3>
        <div class="flex flex-wrap gap-2">
            <button onclick="markAllAs('present')" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Mark All Present
            </button>
            <button onclick="markAllAs('absent')" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                Mark All Absent
            </button>
            <button onclick="clearAll()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Clear All
            </button>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Enrolled Students</h3>
        </div>

        @if($enrolledStudents->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($enrolledStudents as $enrollment)
                            <tr class="hover:bg-gray-50" data-user-id="{{ $enrollment->user->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($enrollment->user->profile_picture)
                                                <img class="h-10 w-10 rounded-full" src="{{ $enrollment->user->profile_picture_url }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">{{ substr($enrollment->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $enrollment->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($enrollment->attendance)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $enrollment->attendance->getStatusBadgeClass() }}">
                                            {{ ucfirst($enrollment->attendance->status) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Not Marked
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($enrollment->attendance)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $enrollment->attendance->getMethodBadgeClass() }}">
                                            {{ ucfirst(str_replace('_', ' ', $enrollment->attendance->check_in_method)) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $enrollment->attendance->notes ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <button onclick="markAttendance({{ $enrollment->user->id }}, 'present')" class="text-green-600 hover:text-green-900">Present</button>
                                        <button onclick="markAttendance({{ $enrollment->user->id }}, 'late')" class="text-yellow-600 hover:text-yellow-900">Late</button>
                                        <button onclick="markAttendance({{ $enrollment->user->id }}, 'absent')" class="text-red-600 hover:text-red-900">Absent</button>
                                        <button onclick="showNotesModal({{ $enrollment->user->id }}, '{{ addslashes($enrollment->user->name) }}', '{{ addslashes($enrollment->attendance->notes ?? '') }}')" class="text-indigo-600 hover:text-indigo-900">Notes</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No enrolled students</h3>
                <p class="mt-1 text-sm text-gray-500">No students are enrolled in this event.</p>
            </div>
        @endif
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Notes</h3>
            <form id="notesForm">
                <input type="hidden" id="notesUserId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student:</label>
                    <p id="notesStudentName" class="text-sm text-gray-900"></p>
                </div>
                <div class="mb-4">
                    <label for="notesText" class="block text-sm font-medium text-gray-700 mb-2">Notes:</label>
                    <textarea id="notesText" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter notes..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeNotesModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Save Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAttendance(userId, status) {
    fetch(`{{ route('club-manager.attendance.mark', $event) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id: userId,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to mark attendance'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark attendance');
    });
}

function markAllAs(status) {
    const attendances = [];
    document.querySelectorAll('tr[data-user-id]').forEach(row => {
        const userId = row.getAttribute('data-user-id');
        attendances.push({
            user_id: parseInt(userId),
            status: status
        });
    });

    if (attendances.length === 0) {
        alert('No students to mark');
        return;
    }

    if (!confirm(`Mark all students as ${status}?`)) {
        return;
    }

    fetch(`{{ route('club-manager.attendance.bulk-mark', $event) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            attendances: attendances
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to mark bulk attendance'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark bulk attendance');
    });
}

function clearAll() {
    if (!confirm('Clear all attendance marks? This action cannot be undone.')) {
        return;
    }
    // Implement clear all functionality if needed
    alert('Clear all functionality would be implemented here');
}

function showNotesModal(userId, studentName, currentNotes) {
    document.getElementById('notesUserId').value = userId;
    document.getElementById('notesStudentName').textContent = studentName;
    document.getElementById('notesText').value = currentNotes;
    document.getElementById('notesModal').classList.remove('hidden');
}

function closeNotesModal() {
    document.getElementById('notesModal').classList.add('hidden');
}

document.getElementById('notesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('notesUserId').value;
    const notes = document.getElementById('notesText').value;
    
    fetch(`{{ route('club-manager.attendance.mark', $event) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id: parseInt(userId),
            status: 'present', // Default to present when adding notes
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeNotesModal();
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to save notes'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save notes');
    });
});
</script>
@endpush
@endsection
