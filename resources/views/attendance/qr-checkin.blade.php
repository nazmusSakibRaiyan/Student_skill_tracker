@extends('layouts.app')

@section('title', 'Check-in - ' . $event->name)

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            @if($event->logo)
                <img class="mx-auto h-16 w-16 rounded-full" src="{{ asset('storage/' . $event->logo) }}" alt="{{ $event->name }}">
            @else
                <div class="mx-auto h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Check-in to Event</h2>
            <h3 class="mt-2 text-xl text-gray-600">{{ $event->name }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $event->club->name }}</p>
            <p class="text-sm text-gray-500">{{ $event->start_date->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form id="checkinForm" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Enter your email address">
                    </div>
                </div>

                <div>
                    <button type="submit" id="submitBtn" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Check In</span>
                        <svg id="loadingSpinner" class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Success/Error Messages -->
            <div id="messageContainer" class="mt-4 hidden">
                <div id="successMessage" class="hidden rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Check-in Successful!</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p id="successText"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="errorMessage" class="hidden rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Check-in Failed</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p id="errorText"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Event Information</span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <div class="text-sm text-gray-600">
                        <p><strong>Type:</strong> {{ ucfirst($event->event_type) }}</p>
                        @if($event->venue_link)
                            <p class="mt-1">
                                <strong>Venue:</strong> 
                                <a href="{{ $event->venue_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                    View Location
                                </a>
                            </p>
                        @endif
                        <p class="mt-1"><strong>Date:</strong> {{ $event->start_date->format('M d, Y') }}</p>
                        <p><strong>Time:</strong> {{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('checkinForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const messageContainer = document.getElementById('messageContainer');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.textContent = 'Checking in...';
    loadingSpinner.classList.remove('hidden');
    
    // Hide previous messages
    messageContainer.classList.add('hidden');
    successMessage.classList.add('hidden');
    errorMessage.classList.add('hidden');
    
    // Submit check-in
    fetch('{{ route('attendance.qr-process', ['qrCode' => $event->qr_code]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        messageContainer.classList.remove('hidden');
        
        if (data.success) {
            successMessage.classList.remove('hidden');
            document.getElementById('successText').innerHTML = `
                Welcome <strong>${data.user}</strong>!<br>
                You have successfully checked in to <strong>${data.event}</strong><br>
                Check-in time: ${new Date(data.checked_in_at).toLocaleString()}
            `;
            
            // Disable form after successful check-in
            document.getElementById('email').disabled = true;
            submitText.textContent = 'Checked In';
            submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            submitBtn.classList.add('bg-green-600');
        } else {
            errorMessage.classList.remove('hidden');
            document.getElementById('errorText').textContent = data.error || 'An error occurred during check-in';
            
            // Reset form state
            submitBtn.disabled = false;
            submitText.textContent = 'Check In';
            loadingSpinner.classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageContainer.classList.remove('hidden');
        errorMessage.classList.remove('hidden');
        document.getElementById('errorText').textContent = 'Network error. Please try again.';
        
        // Reset form state
        submitBtn.disabled = false;
        submitText.textContent = 'Check In';
        loadingSpinner.classList.add('hidden');
    });
});
</script>
@endpush
@endsection
