@extends('auth.layouts.app')


@section('title')
    Student | Reset Password
@endsection

@section('content')
    <section class="py-26 bg-white dark:bg-gray-900">
        <div class="container px-4 mx-auto">
            <div class="max-w-md mx-auto py-8">


                <x-flash-message type="status" />
                <x-flash-message type="error" />


                <form method="POST" action="{{ route('password.update') }}"
                    class="bg-white shadow-lg border-2 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-lg p-8 w-96 mx-auto">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __("Reset Password") }}</h2>
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block mb-2 font-bold text-gray-900 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required
                            class="w-full p-3 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-lg font-semibold placeholder-gray-500 dark:placeholder-gray-400"
                            value="{{ $email ?? old('email') }}">
                        @error('email')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block mb-2 font-bold text-gray-900 dark:text-gray-300">New
                            Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter new password" required
                            class="w-full p-3 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-lg font-semibold placeholder-gray-500 dark:placeholder-gray-400">
                        @error('password')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block mb-2 font-bold text-gray-900 dark:text-gray-300">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirm new password" required
                            class="w-full p-3 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-lg font-semibold placeholder-gray-500 dark:placeholder-gray-400">
                    </div>
                    <button type="submit"
                        class="w-full py-3 px-6 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded transition duration-200 dark:bg-blue-800 dark:hover:bg-blue-700">Reset
                        Password</button>
                </form>



            </div>
        </div>
    </section>
@endsection