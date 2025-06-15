@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Add Students to Club</h2>
    <form id="add-student-form" action="{{ route('club-manager.club.add-student', $club->id) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block font-medium">Search Students by Name or Email</label>
            <input type="text" id="student-search" class="w-full border rounded px-3 py-2" placeholder="Type name or email...">
            <ul id="search-results" class="border rounded bg-white mt-1 hidden"></ul>
        </div>
        <div id="selected-students-list" class="mb-2"></div>
        <input type="hidden" name="user_ids" id="selected-student-ids">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded" disabled id="add-student-btn">Add Students</button>
    </form>
    @if(session('success'))
        <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mt-4 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('student-search');
    const resultsList = document.getElementById('search-results');
    const selectedStudentIds = document.getElementById('selected-student-ids');
    const selectedStudentsList = document.getElementById('selected-students-list');
    const addBtn = document.getElementById('add-student-btn');
    let timeout = null;
    let selected = [];
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();
        if (query.length < 2) {
            resultsList.innerHTML = '';
            resultsList.classList.add('hidden');
            return;
        }
        timeout = setTimeout(() => {
            fetch(`{{ route('club-manager.club.search-students', $club->id) }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    resultsList.innerHTML = '';
                    if (data.length === 0) {
                        resultsList.innerHTML = '<li class="p-2 text-gray-500">No students found</li>';
                        resultsList.classList.remove('hidden');
                        return;
                    }
                    data.forEach(student => {
                        if (selected.find(s => s.id === student.id)) return;
                        const li = document.createElement('li');
                        li.textContent = `${student.name} (${student.email})`;
                        li.className = 'p-2 hover:bg-blue-100 cursor-pointer';
                        li.onclick = () => {
                            selected.push(student);
                            updateSelectedList();
                            searchInput.value = '';
                            resultsList.innerHTML = '';
                            resultsList.classList.add('hidden');
                        };
                        resultsList.appendChild(li);
                    });
                    resultsList.classList.remove('hidden');
                });
        }, 300);
    });
    function updateSelectedList() {
        selectedStudentsList.innerHTML = '';
        if (selected.length === 0) {
            addBtn.disabled = true;
            selectedStudentIds.value = '';
            return;
        }
        selected.forEach((student, idx) => {
            const div = document.createElement('div');
            div.className = 'inline-block bg-blue-100 text-blue-800 rounded px-2 py-1 m-1';
            div.textContent = `${student.name} (${student.email})`;
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'ml-2 text-red-600';
            removeBtn.textContent = '×';
            removeBtn.onclick = () => {
                selected.splice(idx, 1);
                updateSelectedList();
            };
            div.appendChild(removeBtn);
            selectedStudentsList.appendChild(div);
        });
        selectedStudentIds.value = selected.map(s => s.id).join(',');
        addBtn.disabled = false;
    }
});
</script>
@endpush
