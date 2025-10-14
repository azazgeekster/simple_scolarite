@extends('auth.layouts.app')

@section('title')
    Student | Set Password For First Time
@endsection

@section('content')
    <section class="py-26 bg-white dark:bg-gray-900">
        <div class="container px-4 mx-auto">
            <div class="max-w-md mx-auto py-8">
                <x-flash-message type="status" />
                <x-flash-message type="error" />

                <form method="POST" action="{{ route('student.activate.complete', $token) }}"
                    class="bg-white shadow-lg border-2 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-lg p-8 mx-auto">
                    @csrf
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __("Set Your Password") }}</h2>
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block mb-2 font-bold text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="**********" required
                            class="w-full p-3 rounded border-2 border-blue-600 bg-white dark:bg-gray-700 dark:border-blue-600 text-lg font-semibold placeholder-gray-500 dark:placeholder-gray-400">
                        @error('password')<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block mb-2 font-bold text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="**********" required
                            class="w-full p-3 rounded border-2 border-blue-600 bg-white dark:bg-gray-700 dark:border-blue-600 text-lg font-semibold placeholder-gray-500 dark:placeholder-gray-400">
                    </div>
                    <button type="submit"
                        class="w-full py-3 px-6 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded transition duration-200 dark:bg-blue-600 dark:hover:bg-blue-700">Activate
                        Account</button>
                </form>

            </div>
        </div>
    </section>
@endsection