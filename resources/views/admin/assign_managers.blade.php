@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-2">Assign Club Managers</h2>
        <p class="mb-6 text-gray-600">Assign one or more club managers to <span class="font-semibold">{{ $club->name }}</span>.</p>
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded border border-red-200">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.clubs.assign-managers', $club->id) }}" id="assignManagersForm">
            @csrf
            <!-- Hidden field to ensure manager_ids is always sent, even if empty -->
            <input type="hidden" name="manager_ids" value="">
            <input type="hidden" name="form_submitted" value="1">
            <div class="mb-6">
                <label class="block font-semibold mb-2">Available Club Managers:</label>
                <div class="grid grid-cols-1 gap-3">
                    @forelse($managers as $manager)
                        @php
                            $clubManager = \App\Models\ClubManager::where('user_id', $manager->id)
                                ->where('club_id', $club->id)
                                ->first();
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200 mb-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="manager_ids[]" value="{{ $manager->id }}" class="form-checkbox h-5 w-5 text-blue-600" {{ in_array($manager->id, $assigned) ? 'checked' : '' }}>
                                <span class="ml-3 text-gray-800">
                                    <span class="font-medium">{{ $manager->name }}</span> <span class="text-gray-500">({{ $manager->email }})</span>
                                    @if($clubManager && $clubManager->banned)
                                        <span class="ml-2 px-2 py-1 bg-red-200 text-red-800 text-xs rounded">Banned</span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <!-- Ban button without nested form - use JavaScript instead -->
                                <button type="button" onclick="banManager({{ $manager->id }}, {{ $club->id }})" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs font-semibold">Ban</button>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500">No club managers available.</div>
                    @endforelse
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">Save Assignments</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, setting up form listeners...');
    
    const form = document.getElementById('assignManagersForm');
    if (form) {
        console.log('Found form:', form.action);
        
        form.addEventListener('submit', function(e) {
            console.log('Form submission triggered!');
            
            const checkboxes = document.querySelectorAll('input[name="manager_ids[]"]:checked');
            console.log('Selected managers:', Array.from(checkboxes).map(cb => cb.value));
            
            // Let the form submit normally
            console.log('Allowing form to submit...');
        });
    } else {
        console.error('Form not found!');
    }
    
    // Also add a click listener to the submit button for extra debugging
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked!');
        });
    }
});

// Function to handle ban manager (moved outside the form)
function banManager(userId, clubId) {
    if (confirm('Are you sure you want to ban this manager?')) {
        // Create a temporary form for the ban action
        const banForm = document.createElement('form');
        banForm.method = 'POST';
        banForm.action = '{{ url("/api/ban-club-manager") }}';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        banForm.appendChild(csrfInput);
        
        // Add user ID
        const userInput = document.createElement('input');
        userInput.type = 'hidden';
        userInput.name = 'user_id';
        userInput.value = userId;
        banForm.appendChild(userInput);
        
        // Add club ID
        const clubInput = document.createElement('input');
        clubInput.type = 'hidden';
        clubInput.name = 'club_id';
        clubInput.value = clubId;
        banForm.appendChild(clubInput);
        
        // Submit the form
        document.body.appendChild(banForm);
        banForm.submit();
    }
}
</script>
@endsection
