@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-start">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-start">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('admin.exam-scheduling.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Schedule
                </a>
                <div class="mt-4">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Schedule New Exam
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Create a new exam schedule with automatic module selection
                    </p>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form action="{{ route('admin.exam-scheduling.store') }}" method="POST" id="examScheduleForm">
                    @csrf

                    <!-- Progress Steps -->
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-semibold">
                                    1
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Basic Info</span>
                            </div>
                            <div class="flex-1 h-0.5 bg-gray-300 dark:bg-gray-600 mx-4"></div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 text-sm font-semibold">
                                    2
                                </div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Schedule</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <!-- Section 1: Exam Period & Quick Add -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Exam Period
                                </h3>
                                <a href="{{ route('admin.exam-periods.create') }}" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/30 hover:bg-indigo-200 dark:hover:bg-indigo-900/50 rounded-lg transition-all hover:scale-105">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Quick Add Period
                                </a>
                            </div>
                            <div class="relative">
                                <select id="exam_period_id" name="exam_period_id" required
                                    class="block w-full pl-4 pr-10 py-3.5 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm hover:border-indigo-400">
                                    <option value="">Select Exam Period</option>
                                    @foreach($examPeriods as $period)
                                        <option value="{{ $period->id }}" {{ old('exam_period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->label }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                @error('exam_period_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Section 2: Filiere & Semester -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Filière
                                    <span class="ml-1 text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="filiere_id" name="filiere_id" required
                                        class="block w-full pl-4 pr-10 py-3.5 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm hover:border-indigo-400">
                                        <option value="">Select Filière</option>
                                        @foreach($filieres as $filiere)
                                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                                {{ $filiere->label_fr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                @error('filiere_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                    Semester
                                    <span class="ml-1 text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="semester" name="semester" required
                                        class="block w-full pl-4 pr-10 py-3.5 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm hover:border-indigo-400">
                                        <option value="">Select Semester</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="S{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester S{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                @error('semester')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Section 3: Module (Auto-loaded) -->
                        <div>
                            <label class="flex items-center text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Module
                                <span class="ml-1 text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="module_id" name="module_id" required
                                    class="block w-full pl-4 pr-10 py-3.5 text-base border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm hover:border-indigo-400 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">Select Filière and Semester first</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg id="module-loading" class="hidden animate-spin h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg id="module-arrow" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('module_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Section 4: Session Type -->
                        <div>
                            <label class="flex items-center text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Session Type
                                <span class="ml-1 text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 cursor-pointer border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/20">
                                    <input type="radio" name="session_type" value="normal" {{ old('session_type') == 'normal' ? 'checked' : '' }} required
                                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Normal Session</span>
                                </label>
                                <label class="relative flex items-center p-4 cursor-pointer border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/20">
                                    <input type="radio" name="session_type" value="rattrapage" {{ old('session_type') == 'rattrapage' ? 'checked' : '' }} required
                                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Rattrapage</span>
                                </label>
                            </div>
                            @error('session_type')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Section 5: Date & Time -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <!-- EXAM DATE -->
                            <div>
                                <label for="exam_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Exam Date <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <!-- Calendar Icon -->
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                             fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round"
                                             stroke-linejoin="round" stroke-width="2"
                                             d="M6 2v2M14 2v2M4 6h12v10H4zM8 10h4"/></svg>
                                    </div>
                                    <input type="date" id="exam_date" name="exam_date" value="{{ old('exam_date') }}" required
                                        class="block w-full p-3.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                                               rounded-xl shadow-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                @error('exam_date')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- START TIME -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Start Time <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <!-- Clock Icon -->
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    </div>
                                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" min="08:00" max="18:00" required
                                        class="block w-full p-3.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                                               rounded-xl shadow-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                @error('start_time')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- END TIME -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    End Time <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <!-- Clock Icon -->
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    </div>
                                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" min="08:00" max="18:00" required
                                        class="block w-full p-3.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                                               rounded-xl shadow-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                @error('end_time')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-8 py-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <a href="{{ route('admin.exam-scheduling.index') }}"
                                class="inline-flex items-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:scale-105 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </a>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                <button type="submit" name="action" value="schedule_only"
                                    class="inline-flex items-center px-6 py-3 border-2 border-indigo-600 dark:border-indigo-500 rounded-xl text-sm font-medium text-indigo-700 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all hover:scale-105 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Schedule & Return
                                </button>

                                <button type="submit" name="action" value="schedule_and_new"
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Schedule & Add New
                                </button>

                                <button type="submit" name="action" value="schedule_and_allocate"
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Schedule & Allocate Rooms
                                </button>
                            </div>
                        </div>

                        <!-- Quick Tips -->
                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-800 dark:text-blue-300">
                                    <p class="font-semibold mb-1">Quick Tips:</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li><strong>Schedule & Return:</strong> Save and go back to exam list</li>
                                        <li><strong>Schedule & Add New:</strong> Save and quickly add another exam</li>
                                        <li><strong>Schedule & Allocate Rooms:</strong> Save and immediately allocate rooms for this exam</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Module loading functionality
        document.addEventListener('DOMContentLoaded', function() {
            const examPeriodSelect = document.getElementById('exam_period_id');
            const filiereSelect = document.getElementById('filiere_id');
            const semesterSelect = document.getElementById('semester');
            const moduleSelect = document.getElementById('module_id');
            const moduleLoading = document.getElementById('module-loading');
            const moduleArrow = document.getElementById('module-arrow');

            function loadModules() {
                const filiereId = filiereSelect.value;
                const semester = semesterSelect.value;
                const examPeriodId = examPeriodSelect.value;

                if (filiereId && semester) {
                    // Show loading state
                    moduleLoading.classList.remove('hidden');
                    moduleArrow.classList.add('hidden');
                    moduleSelect.disabled = true;
                    moduleSelect.innerHTML = '<option value="">Loading modules...</option>';

                    // Build URL with optional exam_period_id for duplicate prevention
                    let url = `/admin/api/modules?filiere_id=${filiereId}&semester=${semester}`;
                    if (examPeriodId) {
                        url += `&exam_period_id=${examPeriodId}`;
                    }

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                throw new Error(data.error);
                            }

                            moduleSelect.innerHTML = '<option value="">Select Module</option>';

                            if (data.length === 0) {
                                moduleSelect.innerHTML = '<option value="">No modules available</option>';
                            } else {
                                data.forEach(module => {
                                    const option = document.createElement('option');
                                    option.value = module.id;
                                    option.textContent = `${module.code} - ${module.label}`;
                                    moduleSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error loading modules:', error);
                            moduleSelect.innerHTML = '<option value="">Error loading modules. Please try again.</option>';
                        })
                        .finally(() => {
                            moduleLoading.classList.add('hidden');
                            moduleArrow.classList.remove('hidden');
                            moduleSelect.disabled = false;
                        });
                } else {
                    moduleSelect.innerHTML = '<option value="">Select Filière and Semester first</option>';
                    moduleSelect.disabled = true;
                }
            }

            // Reload modules when exam period changes (for duplicate filtering)
            examPeriodSelect.addEventListener('change', loadModules);
            filiereSelect.addEventListener('change', loadModules);
            semesterSelect.addEventListener('change', loadModules);

            // Load modules if values are pre-filled (from old input)
            if (filiereSelect.value && semesterSelect.value) {
                loadModules();
            }
        });
    </script>
@endsection
