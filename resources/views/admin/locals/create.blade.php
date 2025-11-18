@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.locals.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Rooms
                </a>
                <div class="mt-4">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Room
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Create a new examination room for student allocations
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Room Information</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Enter the details for the new room</p>
                </div>

                <form action="{{ route('admin.locals.store') }}" method="POST">
                    @csrf

                    <div class="p-8 space-y-6">
                        <!-- Code & Name Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    Room Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="code" name="code" value="{{ old('code') }}" required
                                    class="block w-full px-4 py-3 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white rounded-xl transition-all shadow-sm"
                                    placeholder="e.g., A101">
                                @error('code')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    Room Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="block w-full px-4 py-3 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white rounded-xl transition-all shadow-sm"
                                    placeholder="e.g., Salle A101">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Building & Type Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Building -->
                            <div>
                                <label for="building" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    Building
                                </label>
                                <input type="text" id="building" name="building" value="{{ old('building') }}"
                                    class="block w-full px-4 py-3 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white rounded-xl transition-all shadow-sm"
                                    placeholder="e.g., Building A">
                                @error('building')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    Room Type
                                </label>
                                <div class="relative">
                                    <select id="type" name="type"
                                        class="block w-full pl-4 pr-10 py-3 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white rounded-xl transition-all shadow-sm hover:border-indigo-400">
                                        <option value="">Select Type</option>
                                        <option value="amphi" {{ old('type') === 'amphi' ? 'selected' : '' }}>Amphi</option>
                                        <option value="salle" {{ old('type') === 'salle' ? 'selected' : '' }}>Salle</option>
                                        <option value="labo" {{ old('type') === 'labo' ? 'selected' : '' }}>Labo</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Capacity <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" required min="1"
                                    class="block w-full pl-12 pr-4 py-3 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white rounded-xl transition-all shadow-sm"
                                    placeholder="e.g., 50">
                            </div>
                            @error('capacity')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border-2 border-indigo-200 dark:border-indigo-800">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="is_active" class="ml-3 block text-sm font-medium text-gray-900 dark:text-white">
                                Active (available for allocation)
                            </label>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Notes
                            </label>
                            <textarea id="notes" name="notes" rows="4"
                                class="block w-full px-4 py-3 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white rounded-xl transition-all shadow-sm"
                                placeholder="Additional information about this room">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 px-8 py-6 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <a href="{{ route('admin.locals.index') }}"
                            class="inline-flex items-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:scale-105 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
