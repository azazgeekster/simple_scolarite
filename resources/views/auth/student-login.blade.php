@extends("auth.layouts.app")

@section('title', 'Student Login')

@section("content")
<div class="relative min-h-screen flex items-center justify-center px-4 py-10 bg-gradient-to-br from-indigo-100 via-white to-blue-100 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800 transition-colors duration-300">

    <!-- Animated Blobs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-40 right-0 w-96 h-96 bg-blue-400 dark:bg-blue-800 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Login Card -->
    <div class="relative w-full max-w-md bg-white/80 dark:bg-gray-800/80 backdrop-blur-2xl border border-gray-200/50 dark:border-gray-700/50 shadow-2xl rounded-3xl p-8 z-10">

        <!-- Logo & Title -->
        <div class="text-center mb-8">
            
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome Back 👋</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Access your student portal</p>
        </div>

        <!-- Flash Messages -->
        <x-flash-message type="message"/>
        <x-flash-message type="error"/>

        <!-- Login Form -->
        <form method="POST" action="{{ route('student.login') }}" x-data="{ loading: false }" @submit="loading = true" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input type="email" name="email" id="email" required autocomplete="email"
                        value="{{ old('email') }}" placeholder="you@example.com"
                        class="w-full pl-11 pr-4 py-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                </div>
                @error('email')
                <p class="text-xs text-red-600 dark:text-red-400 mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-11 pr-4 py-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                </div>
                @error('password')
                <p class="text-xs text-red-600 dark:text-red-400 mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Options -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2 text-gray-600 dark:text-gray-300 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 cursor-pointer">
                    <span class="group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition-colors">Forgot?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="loading"
    class="w-full py-3.5 text-sm font-semibold text-white rounded-xl bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed">
    <svg x-show="loading" x-cloak class="inline animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
    </svg>
    <span x-text="loading ? 'Signing in...' : 'Sign in'">Sign in</span>
</button>

            <!-- Divider -->
            <div class="relative flex items-center my-6">
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                <span class="flex-shrink px-4 text-xs font-medium text-gray-500 dark:text-gray-400">OR CONTINUE WITH</span>
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
            </div>

            <!-- Google Sign In -->
            <a href="{{ route('auth.google.redirect') }}"
                class="block w-full py-3.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                <div class="flex items-center justify-center gap-3">
                    <svg class="h-5 w-5" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.94 0 7.07 1.62 9.24 3.32l6.79-6.79C36.45 2.6 30.69 0 24 0 14.75 0 6.57 5.19 2.44 12.78l7.99 6.19C12.56 12.5 17.9 9.5 24 9.5z"/>
                        <path fill="#34A853" d="M46.1 24.5c0-1.44-.13-2.83-.38-4.16H24v7.88h12.36c-.53 2.84-2.14 5.26-4.57 6.87l7.04 5.48C43.36 36.16 46.1 30.78 46.1 24.5z"/>
                        <path fill="#FBBC05" d="M10.43 28.97a13.9 13.9 0 01-.72-4.47c0-1.55.25-3.06.7-4.47l-8.01-6.2A23.91 23.91 0 000 24.5c0 3.8.91 7.38 2.49 10.53l7.94-6.06z"/>
                        <path fill="#4285F4" d="M24 47.9c6.51 0 12-2.16 15.98-5.9l-7.04-5.47c-1.96 1.32-4.46 2.09-8.94 2.09-6.08 0-11.44-3.98-13.33-9.43l-7.99 6.06C6.61 42.69 14.75 47.9 24 47.9z"/>
                    </svg>
                    <span>Sign in with Google</span>
                </div>
            </a>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm">
            <p class="text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="{{ route('student.activate.form') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">Create one</a>
            </p>
            <p class="mt-3 text-xs text-gray-500 dark:text-gray-500">© {{ date('Y') }} University Portal. All rights reserved.</p>
        </div>
    </div>
</div>

@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">

<style>
    @keyframes blob {
      0%, 100% { transform: translate(0, 0) scale(1); }
      33% { transform: translate(30px, -20px) scale(1.1); }
      66% { transform: translate(-20px, 10px) scale(0.9); }
    }
    .animate-blob { animation: blob 8s infinite ease-in-out; }
    .animation-delay-2000 { animation-delay: 2s; }

    [x-cloak] { display: none !important; }
    </style>
@endpush

@push('js')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
@endpush