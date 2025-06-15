@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Reset Password</h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email Address</label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">New Password</label>
                <input id="password" type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password-confirm" class="block text-gray-700">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Reset Password</button>
        </form>
    </div>
</div>
@endsection
