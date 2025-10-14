@extends("auth.layouts.app")

@section('title')
    Login | Student
@endsection

@section("content")
<div class="min-h-screen flex items-center justify-center px-4 bg-gray-50 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-2xl p-6 sm:p-8 transition-all">

        <x-flash-message type="message"/>
        <x-flash-message type="error"/>

        <form method="POST" action="{{ route('student.login') }}" x-data="{ loading: false }" x-on:submit="loading = true" class="space-y-6">
            @csrf

            <div class="text-center">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-blue-900 dark:text-white tracking-tight">
                    sign in to your account
                </h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Student access only</p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com"
                    class="mt-1 w-full px-4 py-3 text-base rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required value="{{ old('email') }}">

                @error('email')
                <p class="text-sm mt-1 text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="your password"
                    class="mt-1 w-full px-4 py-3 text-base rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required>

                @error('password')
                <p class="text-sm mt-1 text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center text-sm">
                <label class="flex items-center text-gray-600 dark:text-gray-400">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 mr-2">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-700 dark:text-blue-300 hover:underline font-medium">
                    Forgot password?
                </a>
            </div>

            <button type="submit"
                :disabled="loading"
                class="w-full flex justify-center items-center px-4 py-3 text-white text-base font-semibold bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-xl transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-70 disabled:cursor-not-allowed">
                <svg x-show="loading" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <span x-text="loading ? 'Signing in...' : 'Sign in'"></span>
            </button>

            <div class="relative my-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white dark:bg-gray-800 px-3 text-gray-500 dark:text-gray-400">or</span>
                </div>
            </div>

            <a href="{{ route('auth.google.redirect') }}" class="block">
                <button type="button"
                    class="w-full flex items-center justify-center px-4 py-3 text-base font-semibold text-gray-800 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600 transition">
                    <svg class="h-5 w-5 mr-2" viewBox="0 0 48 48">
                        <path fill="#FBBC05" d="M9.83 24c0-1.52.25-2.99.7-4.36L2.62 13.6C1.08 16.73.21 20.26.21 24c0 3.74.87 7.26 2.41 10.39l7.9-6.04c-.45-1.37-.7-2.83-.7-4.35z"/>
                        <path fill="#EA4335" d="M23.71 10.13c3.31 0 6.31 1.17 8.65 3.09l6.84-6.83C35.04 2.77 29.7.53 23.71.53c-9.29 0-17.27 5.31-21.09 13.07l7.91 6.04c1.82-5.53 6.99-9.51 13.18-9.51z"/>
                        <path fill="#34A853" d="M23.71 37.87c-6.19 0-11.36-3.98-13.18-9.51l-7.9 6.04c3.82 7.76 11.8 13.07 21.08 13.07 5.74 0 11.21-2.04 15.3-5.82l-7.51-5.8c-2.12 1.33-4.78 2.01-7.79 2.01z"/>
                        <path fill="#4285F4" d="M46.15 24c0-1.39-.21-2.77-.53-4.08H23.71v8.13h12.61c-.54 2.9-2.17 5.37-4.8 7.01l7.5 5.8c4.32-3.98 6.68-9.87 6.68-16.86z"/>
                    </svg>
                    Sign in with google
                </button>
            </a>

            <div class="text-center mt-6">
                <a href="{{ route('student.activate.form') }}" class="text-blue-700 dark:text-blue-300 font-medium hover:underline">
                    I don't have an account
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
@endpush

@push('js')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
@endpush
