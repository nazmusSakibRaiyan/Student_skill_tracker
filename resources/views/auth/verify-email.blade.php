<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Student Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                    Verify Your Email
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    We've sent a verification link to your email address
                </p>
            </div>            <!-- Content -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                <!-- Registration Success Message -->
                @if (session('status') == 'registration-success')
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <strong>Registration Successful!</strong>
                        </div>
                        <p class="mt-2 text-sm">Welcome to Student Skill Tracker! Please verify your email to get started.</p>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <div class="text-center">
                    <div class="mb-6">
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            Before getting started, could you verify your email address by clicking on the link we just emailed to you?
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Your email: <strong class="text-gray-900 dark:text-white">{{ auth()->user()->email }}</strong>
                        </p>
                    </div>

                    <!-- Resend Verification Email -->
                    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                        @csrf
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Resend Verification Email
                        </button>
                    </form>

                    <!-- Check Email Instructions -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                            Haven't received the email?
                        </h3>
                        <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1 text-left">
                            <li>• Check your spam/junk folder</li>
                            <li>• Make sure the email address is correct</li>
                            <li>• Wait a few minutes and check again</li>
                            <li>• Click "Resend" to get a new verification email</li>
                        </ul>
                    </div>

                    <!-- Logout Option -->
                    <div class="text-center">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white underline">
                                Log out and use a different account
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Having trouble? Contact support at 
                    <a href="mailto:support@studentskilltracker.com" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                        support@studentskilltracker.com
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
