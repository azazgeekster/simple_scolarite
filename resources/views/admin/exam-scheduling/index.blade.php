@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-start animate-fade-in">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Header with Quick Stats -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Exam Scheduling Dashboard
                        </h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-4">
                            <span class="flex items-center">
                                <span class="inline-block w-2 h-2 bg-indigo-600 rounded-full mr-2"></span>
                                <strong>{{ $stats['total'] }}</strong>&nbsp;Total
                            </span>
                            <span class="flex items-center">
                                <span class="inline-block w-2 h-2 bg-emerald-600 rounded-full mr-2"></span>
                                <strong>{{ $stats['allocated'] }}</strong>&nbsp;Allocated
                            </span>
                            <span class="flex items-center">
                                <span class="inline-block w-2 h-2 bg-amber-600 rounded-full mr-2"></span>
                                <strong>{{ $stats['not_allocated'] }}</strong>&nbsp;Pending
                            </span>
                            <span class="flex items-center">
                                <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                <strong>{{ $stats['upcoming'] }}</strong>&nbsp;Upcoming
                            </span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.exam-scheduling.create') }}"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Schedule New Exam
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Table with Embedded Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Live Search & Quick Filters -->
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Live Search -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <input type="text" id="liveSearch" placeholder="Search modules..."
                                    class="block w-full pl-10 pr-4 py-2.5 text-sm border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm"
                                    value="{{ request('search') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Period Filter -->
                        <div>
                            <select id="periodFilter" class="select2-filter block w-full px-3 py-2.5 text-sm border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm">
                                <option value="">All Periods</option>
                                @foreach($examPeriods as $period)
                                    <option value="{{ $period->id }}" {{ request('exam_period_id') == $period->id ? 'selected' : '' }}>
                                        {{ $period->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filiere Filter -->
                        <div>
                            <select id="filiereFilter" class="select2-filter block w-full px-3 py-2.5 text-sm border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm">
                                <option value="">All Filières</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->label_fr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Session Type Filter -->
                        <div>
                            <select id="sessionFilter" class="block w-full px-3 py-2.5 text-sm border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm">
                                <option value="">All Sessions</option>
                                <option value="normal" {{ request('session_type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="rattrapage" {{ request('session_type') == 'rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                            </select>
                        </div>

                        <!-- Allocation Filter -->
                        <div>
                            <select id="allocationFilter" class="block w-full px-3 py-2.5 text-sm border-2 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl transition-all shadow-sm">
                                <option value="">All Status</option>
                                <option value="allocated" {{ request('allocation_status') == 'allocated' ? 'selected' : '' }}>✓ Allocated</option>
                                <option value="not_allocated" {{ request('allocation_status') == 'not_allocated' ? 'selected' : '' }}>⚠ Not Allocated</option>
                            </select>
                        </div>
                    </div>

                    <!-- Results Count & Reset -->
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <span id="resultsCount">Showing <strong>{{ $exams->count() }}</strong> of <strong>{{ $exams->total() }}</strong> exams</span>
                        </div>
                        @if(request()->hasAny(['search', 'exam_period_id', 'filiere_id', 'session_type', 'allocation_status']))
                            <a href="{{ route('admin.exam-scheduling.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/30 hover:bg-indigo-200 dark:hover:bg-indigo-900/50 rounded-lg transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>

                @if($exams->isEmpty())
                    <div class="text-center py-16 px-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-100 dark:bg-indigo-900/30 mb-6">
                            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">No exams found</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            @if(request()->hasAny(['search', 'exam_period_id', 'filiere_id', 'session_type', 'allocation_status']))
                                Try adjusting your filters to see more results.
                            @else
                                Get started by scheduling your first exam.
                            @endif
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('admin.exam-scheduling.create') }}"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 transition-all hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Schedule New Exam
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Module & Filière
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Exam Details
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Session
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Students
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Allocation
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($exams as $exam)
                                    <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-colors">
                                        <!-- Module & Filière -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30">
                                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                                        {{ $exam->module->label ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $exam->module->code ?? 'N/A' }}</span>
                                                    </p>
                                                    <div class="mt-1">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                                            {{ $exam->module->filiere->label_fr ?? 'N/A' }}
                                                        </span>
                                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                            {{ $exam->semester }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Exam Details -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm font-medium text-gray-900 dark:text-white">
                                                    <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $exam->exam_date->format('d M Y') }}
                                                </div>
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                    <svg class="w-3 h-3 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }}
                                                    <span class="ml-2 px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-xs">
                                                        {{ $exam->start_time->diffInMinutes($exam->end_time) }}min
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Session Type -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold {{ $exam->session_type === 'normal' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                                @if($exam->session_type === 'normal')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                {{ ucfirst($exam->session_type) }}
                                            </span>
                                        </td>

                                        <!-- Students -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $exam->total_students ?? 0 }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Students</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Allocation Status -->
                                        <td class="px-6 py-4">
                                            @if($exam->roomAllocations->isNotEmpty())
                                                @php
                                                    $allocated = $exam->total_allocated_seats;
                                                    $total = $exam->total_students ?? 0;
                                                    $percentage = $total > 0 ? ($allocated / $total) * 100 : 0;
                                                @endphp
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md font-medium {{ $percentage >= 100 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $exam->roomAllocations->count() }} rooms
                                                        </span>
                                                        <span class="text-gray-600 dark:text-gray-400 font-semibold">{{ number_format($percentage, 0) }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="h-2 rounded-full transition-all {{ $percentage >= 100 ? 'bg-emerald-500' : ($percentage >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}"
                                                            style="width: {{ min($percentage, 100) }}%"></div>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $allocated }} / {{ $total }} seats
                                                    </p>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Not Allocated
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center gap-2">
                                                <!-- Manage Rooms Button -->
                                                <a href="{{ route('admin.exam-scheduling.room-allocation', $exam->id) }}"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 transition-all hover:scale-105 shadow-sm hover:shadow-md"
                                                    title="Manage room allocations">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    Rooms
                                                </a>

                                                @if($exam->roomAllocations->isNotEmpty())
                                                    <!-- Download PV Button -->
                                                    <a href="{{ route('admin.exam-scheduling.download-pv', $exam->id) }}"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-all hover:scale-105 shadow-sm hover:shadow-md"
                                                        title="Download PV (Procès-Verbal)">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        PV
                                                    </a>
                                                @endif

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.exam-scheduling.destroy', $exam->id) }}" method="POST" class="inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this exam? This action cannot be undone.\n\nModule: {{ $exam->module->label }}\nDate: {{ $exam->exam_date->format('d/m/Y') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 transition-all hover:scale-105 shadow-sm hover:shadow-md"
                                                        title="Delete exam">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($exams->hasPages())
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $exams->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 on filter dropdowns
            $('.select2-filter').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select...',
                allowClear: true
            });

            // Live filtering function
            function applyFilters() {
                const params = new URLSearchParams();

                const search = $('#liveSearch').val();
                const period = $('#periodFilter').val();
                const filiere = $('#filiereFilter').val();
                const session = $('#sessionFilter').val();
                const allocation = $('#allocationFilter').val();

                if (search) params.append('search', search);
                if (period) params.append('exam_period_id', period);
                if (filiere) params.append('filiere_id', filiere);
                if (session) params.append('session_type', session);
                if (allocation) params.append('allocation_status', allocation);

                window.location.href = '{{ route('admin.exam-scheduling.index') }}' + (params.toString() ? '?' + params.toString() : '');
            }

            // Debounce function for live search
            let searchTimeout;
            $('#liveSearch').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(applyFilters, 500);
            });

            // Apply filters on change
            $('#periodFilter, #filiereFilter, #sessionFilter, #allocationFilter').on('change', applyFilters);
        });
    </script>

    <style>
        /* Custom Select2 dark mode styles */
        .dark .select2-container--bootstrap-5 .select2-selection {
            background-color: rgb(55 65 81);
            border-color: rgb(75 85 99);
            color: white;
        }
        .dark .select2-container--bootstrap-5 .select2-selection__placeholder {
            color: rgb(156 163 175);
        }
        .dark .select2-dropdown {
            background-color: rgb(31 41 55);
            border-color: rgb(75 85 99);
        }
        .dark .select2-container--bootstrap-5 .select2-results__option {
            color: white;
        }
        .dark .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: rgb(99 102 241);
        }

        /* Fade-in animation */
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
@endsection
