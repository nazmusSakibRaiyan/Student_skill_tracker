@php
    $unreadAnnouncements = auth()->user()->unreadAnnouncements();
@endphp

@if($unreadAnnouncements->count() > 0)
    <div class="mb-6 space-y-4">
        @foreach($unreadAnnouncements as $announcement)
            <div class="announcement-banner relative rounded-lg p-4 border-l-4 
                @if($announcement->priority === 'emergency') 
                    bg-red-50 border-red-500 dark:bg-red-900/20 dark:border-red-400
                @elseif($announcement->priority === 'high') 
                    bg-orange-50 border-orange-500 dark:bg-orange-900/20 dark:border-orange-400
                @elseif($announcement->priority === 'medium') 
                    bg-yellow-50 border-yellow-500 dark:bg-yellow-900/20 dark:border-yellow-400
                @else 
                    bg-blue-50 border-blue-500 dark:bg-blue-900/20 dark:border-blue-400
                @endif
            " data-announcement-id="{{ $announcement->id }}">
                
                <!-- Close Button -->
                <button type="button" 
                        class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        onclick="markAnnouncementAsRead({{ $announcement->id }})">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <!-- Priority Badge -->
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        @if($announcement->priority === 'emergency')
                            <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @elseif($announcement->priority === 'high')
                            <div class="flex items-center justify-center w-8 h-8 bg-orange-100 rounded-full">
                                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @else
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Announcement Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-1">
                            <h4 class="text-sm font-semibold 
                                @if($announcement->priority === 'emergency') text-red-800 dark:text-red-200
                                @elseif($announcement->priority === 'high') text-orange-800 dark:text-orange-200
                                @elseif($announcement->priority === 'medium') text-yellow-800 dark:text-yellow-200
                                @else text-blue-800 dark:text-blue-200
                                @endif">
                                {{ $announcement->title }}
                            </h4>
                            
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if($announcement->priority === 'emergency') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @elseif($announcement->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                @elseif($announcement->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                @else bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                @endif">
                                @if($announcement->priority === 'emergency')
                                    🚨 EMERGENCY
                                @else
                                    {{ strtoupper($announcement->priority) }}
                                @endif
                            </span>
                        </div>

                        <p class="text-sm font-medium leading-relaxed announcement-message">
                            {{ $announcement->message }}
                        </p>

                        <div class="mt-2 flex items-center justify-between text-xs 
                            @if($announcement->priority === 'emergency') text-red-600 dark:text-red-400
                            @elseif($announcement->priority === 'high') text-orange-600 dark:text-orange-400
                            @elseif($announcement->priority === 'medium') text-yellow-600 dark:text-yellow-400
                            @else text-blue-600 dark:text-blue-400
                            @endif">
                            <span>Posted {{ $announcement->created_at->diffForHumans() }}</span>
                            @if($announcement->expires_at)
                                <span>Expires {{ $announcement->expires_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function markAnnouncementAsRead(announcementId) {
            fetch(`/announcements/${announcementId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const banner = document.querySelector(`[data-announcement-id="${announcementId}"]`);
                    if (banner) {
                        banner.style.opacity = '0';
                        banner.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            banner.remove();
                        }, 300);
                    }
                }
            })
            .catch(error => {
                console.error('Error marking announcement as read:', error);
            });
        }
    </script>

    <style>
        .announcement-banner {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .announcement-message {
            color: #8B7355 !important; /* Light creamish brown for better readability */
        }
        
        @media (prefers-color-scheme: dark) {
            .announcement-message {
                color: #D4C4A8 !important; /* Lighter cream for dark mode */
            }
        }
    </style>
@endif
