@extends('auth.layouts.app')
@section('title')
    Account Activation Page | Student
@endsection
@section('content')
    <section class="bg-white dark:bg-gray-900 min-h-screen flex items-center justify-center px-4">
        <div class="container px-4 mx-auto">
            <div class="max-w-md mx-auto py-8">

                <x-flash-message type="message" />
                <x-flash-message type="error" />

                <form method="POST" action="{{ route('student.activate.send') }}"
                    class="flex flex-col bg-white shadow border-2 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-lg p-6">
                    <a href="{{ route('student.loginform') }}"
                        class="mb-2 flex items-center text-blue-900 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                        <!-- Arrow Icon -->
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>

                    @csrf
                    <div class="text-center mb-8">
                        <h2 class="text-3xl md:text-4xl font-extrabold mb-2 text-blue-900 dark:text-white">
                            {{ __("Activate Your Account") }}
                        </h2>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold text-blue-900 dark:text-gray-100" for="cne">CNE</label>
                        <input name="cne" class="w-full p-3 text-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
            border-2 border-gray-300 dark:border-gray-400 rounded-lg focus:border-gray-500 dark:focus:border-gray-400
            focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-500" type="text" placeholder="Your CNE" required>

                        @error('cne')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold text-blue-900 dark:text-gray-100" for="email">Email</label>
                        <input name="email" class="w-full p-3 text-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
            border-2 border-gray-300 dark:border-gray-400 rounded-lg focus:border-gray-500 dark:focus:border-gray-400
            focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-500" type="email" placeholder="Your Email" required>

                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        class="w-full py-3 px-6 text-lg font-semibold text-white bg-blue-800 hover:bg-blue-900 border-3 border-blue-900 rounded-lg transition dark:bg-blue-600 dark:hover:bg-blue-700">
                        Send Activation Email
                    </button>
                </form>

            </div>
        </div>
    </section>
@endsection