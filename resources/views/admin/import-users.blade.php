<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Users - Student Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-purple-600 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('admin.users.index') }}" class="text-white hover:text-purple-200 mr-4">
                            ← Back to Users
                        </a>
                        <h1 class="text-xl font-semibold text-white">Import Users</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('profile') }}" class="text-white hover:text-purple-200">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-purple-800 text-white px-3 py-1 rounded text-sm hover:bg-purple-900">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Bulk Import Users</h2>
                        
                        <!-- Success/Error Messages -->
                        @if(session('success'))
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Instructions -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-medium text-blue-900 mb-2">Import Instructions</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Supported file format: CSV</li>
                                <li>• Maximum file size: 10MB</li>
                                <li>• Required columns: name, email, role</li>
                                <li>• Optional column: password (defaults to 'password123' if not provided)</li>
                                <li>• Valid roles: student, club_manager, master_admin</li>
                                <li>• Duplicate emails will be skipped</li>
                            </ul>
                            <div class="mt-3">
                                <a href="{{ route('admin.users.import.template') }}" 
                                   class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Template CSV
                                </a>
                            </div>
                        </div>

                        <!-- Upload Form -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <form action="{{ route('admin.users.import.process') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-6">
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Select File to Import
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                                    <span>Upload a file</span>
                                                    <input id="file" name="file" type="file" class="sr-only" accept=".csv" required>
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">CSV up to 10MB</p>
                                        </div>
                                    </div>
                                    @error('file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700">
                                        Import Users
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File input handling
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = document.querySelector('label[for="file"] span');
                label.textContent = fileName;
            }
        });
    </script>
</body>
</html>
