@extends('auth.layouts.app')
@section('title')
    Student | Forgot Password
@endsection
@section('content')

    <section class="py-26 bg-white dark:bg-gray-900">
        <div class="container px-4 mx-auto">
            <div class="max-w-md mx-auto py-8">
                <x-flash-message type="status" />
                <form id="reset-form" method="POST" action="{{ route('password.email') }}"
                    class="bg-white shadow-lg border-2 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-lg p-8 mx-auto">
                    <a href="{{ route('student.loginform') }}"
                        class="mb-4 flex items-center text-blue-900 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>
                    @csrf
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-extrabold text-blue-900 dark:text-white">{{ __("Forgot Password") }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Enter your email to receive a password reset
                            link.</p>
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block mb-2 font-bold text-blue-900 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required
                            class="w-full p-3 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-lg font-semibold placeholder-gray-500 dark:placeholder-gray-400">
                        @error('email')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit"
                        class="w-full py-3 px-6 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded transition duration-200 dark:bg-blue-800 dark:hover:bg-blue-700">Send
                        Reset Link</button>
                </form>


            </div>
        </div>
    </section>
@endsection