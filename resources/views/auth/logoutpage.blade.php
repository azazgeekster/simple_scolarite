@extends('auth.layouts.app')

@section('title')
Student | Logged out
@endsection

@section('content')

<div class=" flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
    <!Checkmark Icon -->
    <div class="flex justify-center">
      <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
      </svg>
    </div>

    <!-- Logout Confirmation Message -->
    <h1 class="text-2xl font-bold text-gray-800 mt-4">You have been logged out</h1>
    <p class="text-gray-600 mt-2">Thank you for using our service. We hope to see you again soon!</p>

    <!-- Login Button -->
    <div class="mt-6">
      <a href="{{ route('student.loginform') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
        Log Back In
      </a>
    </div>

    <!-- Homepage Button -->
    <div class="mt-4">
      <!-- <a href="{{ route('student.login') }}" class="text-sm text-gray-600 hover:text-gray-800">Return to Homepage</a> -->
    </div>
  </div>
  </div>
@endsection
