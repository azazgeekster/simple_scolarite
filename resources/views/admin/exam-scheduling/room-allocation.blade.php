@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-2 border-emerald-200 dark:border-emerald-800 rounded-xl flex items-start animate-fade-in">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm font-medium text-emerald-800 dark:text-emerald-300 whitespace-pre-line">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl flex items-start animate-fade-in">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm font-medium text-red-800 dark:text-red-300 whitespace-pre-line">{{ session('error') }}</div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl flex items-start animate-fade-in">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">{{ session('info') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl animate-fade-in">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800 dark:text-red-300 mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Enhanced Header with Exam Metadata -->
            <div class="mb-8">
                <a href="{{ route('admin.exam-scheduling.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group mb-6">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Exam Schedule
                </a>

                <!-- Exam Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-white flex items-center mb-3">
                                    <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Room Allocation Management
                                </h1>
                                <p class="text-indigo-100 text-sm">Configure and optimize room assignments for this exam</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Module Info -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30">
                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Module</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1 truncate">{{ $exam->module->label ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        <span class="font-mono bg-white dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $exam->module->code ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Date Info -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Exam Date</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ $exam->exam_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $exam->exam_date->format('l') }}</p>
                                </div>
                            </div>

                            <!-- Time Info -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30">
                                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Duration</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                        {{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded font-medium">
                                            {{ $exam->start_time->diffInMinutes($exam->end_time) }} minutes
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Session Type Info -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br {{ $exam->session_type === 'normal' ? 'from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30' : 'from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30' }}">
                                        <svg class="w-6 h-6 {{ $exam->session_type === 'normal' ? 'text-purple-600 dark:text-purple-400' : 'text-amber-600 dark:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Session Type</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ ucfirst($exam->session_type) }}</p>
                                    <p class="text-xs mt-0.5">
                                        <span class="px-2 py-0.5 rounded font-medium {{ $exam->session_type === 'normal' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' }}">
                                            {{ $exam->semester }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Students -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transform transition-all hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalStudents }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Assigned Students -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transform transition-all hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Students Assigned</p>
                            <p class="mt-2 text-3xl font-bold {{ $assignedStudents >= $totalStudents ? 'text-indigo-600 dark:text-indigo-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $assignedStudents }}
                            </p>
                        </div>
                        <div class="p-3 {{ $assignedStudents >= $totalStudents ? 'bg-indigo-100 dark:bg-indigo-900/30' : 'bg-amber-100 dark:bg-amber-900/30' }} rounded-xl">
                            <svg class="w-8 h-8 {{ $assignedStudents >= $totalStudents ? 'text-indigo-600 dark:text-indigo-400' : 'text-amber-600 dark:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Remaining Students -->
                @php
                    $remaining = $totalStudents - $assignedStudents;
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transform transition-all hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Remaining</p>
                            <p class="mt-2 text-3xl font-bold {{ $remaining > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                {{ $remaining }}
                            </p>
                        </div>
                        <div class="p-3 {{ $remaining > 0 ? 'bg-rose-100 dark:bg-rose-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30' }} rounded-xl">
                            <svg class="w-8 h-8 {{ $remaining > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($remaining > 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Rooms Allocated -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transform transition-all hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rooms Used</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $exam->roomAllocations->count() }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI-Powered Insights & Recommendations -->
            <div class="mb-8 space-y-4">
                <!-- Status Banner -->
                @if($assignedStudents > 0 || $exam->total_allocated_seats > 0)
                    <div class="rounded-2xl border-2 p-6 shadow-lg
                        {{ $assignedStudents < $totalStudents ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800' :
                            'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800' }}">
                        <div class="flex items-center">
                            @if($assignedStudents < $totalStudents)
                                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-amber-900 dark:text-amber-200">Students Pending Assignment</h3>
                                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                                        <strong>{{ $assignedStudents }} of {{ $totalStudents }} students</strong> have been assigned to rooms.
                                        @if($exam->total_allocated_seats < $totalStudents)
                                            Allocate <strong>{{ $totalStudents - $exam->total_allocated_seats }} more seats</strong> and submit to assign remaining students.
                                        @else
                                            Click "Submit Room Allocation" to assign the remaining <strong>{{ $totalStudents - $assignedStudents }} students</strong>.
                                        @endif
                                    </p>
                                </div>
                            @else
                                <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-emerald-900 dark:text-emerald-200">All Students Assigned!</h3>
                                    <p class="text-sm text-emerald-700 dark:text-emerald-300 mt-1">
                                        All <strong>{{ $totalStudents }} students</strong> have been successfully assigned to rooms.
                                        @if($exam->total_allocated_seats > $totalStudents)
                                            You have <strong>{{ $exam->total_allocated_seats - $totalStudents }} extra seats</strong> for flexibility.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Smart Recommendations Panel -->
                @php
                    $remaining = $totalStudents - $exam->total_allocated_seats;
                    $avgRoomCapacity = $availableLocals->avg('capacity') ?? 40;
                    $estimatedRoomsNeeded = $remaining > 0 ? ceil($remaining / $avgRoomCapacity) : 0;
                    $totalCapacityAvailable = $availableLocals->where('id', '!=', null)->sum('capacity') - $exam->total_allocated_seats;
                    $underUtilizedRooms = $exam->roomAllocations->filter(function($alloc) {
                        return ($alloc->allocated_seats / $alloc->local->capacity) < 0.5;
                    });

                    // Check if rebalancing is needed: multiple rooms and last one is under-utilized
                    $needsRebalancing = false;
                    $lastRoomUtilization = 0;
                    if ($exam->roomAllocations->count() > 1 && $assignedStudents > 0) {
                        $allocations = $exam->roomAllocations->sortBy('id');
                        $lastAlloc = $allocations->last();
                        $studentsInLastRoom = $exam->convocations()->where('local_id', $lastAlloc->local_id)->count();
                        $lastRoomUtilization = ($studentsInLastRoom / $lastAlloc->local->capacity) * 100;
                        // Need rebalancing if last room is less than 40% utilized
                        $needsRebalancing = $lastRoomUtilization < 40 && $studentsInLastRoom > 0;
                    }
                @endphp

                @if($remaining > 0 || $underUtilizedRooms->isNotEmpty())
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border-2 border-indigo-200 dark:border-indigo-800 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-start">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl mr-4">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-200 mb-3">Smart Recommendations</h3>
                                <div class="space-y-2">
                                    @if($remaining > 0)
                                        <div class="flex items-start text-sm">
                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-indigo-800 dark:text-indigo-300">
                                                <strong>Estimated {{ $estimatedRoomsNeeded }} room(s) needed</strong> to accommodate remaining {{ $remaining }} students (avg. capacity: {{ number_format($avgRoomCapacity, 0) }})
                                            </span>
                                        </div>
                                    @endif

                                    @if($totalCapacityAvailable > 0)
                                        <div class="flex items-start text-sm">
                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-indigo-800 dark:text-indigo-300">
                                                <strong>{{ $totalCapacityAvailable }} total seats available</strong> across {{ $availableLocals->count() }} rooms
                                            </span>
                                        </div>
                                    @elseif($remaining > 0)
                                        <div class="flex items-start text-sm">
                                            <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-rose-800 dark:text-rose-300">
                                                <strong>Warning:</strong> Insufficient capacity! Need {{ $remaining - $totalCapacityAvailable }} more seats.
                                            </span>
                                        </div>
                                    @endif

                                    @if($underUtilizedRooms->isNotEmpty())
                                        <div class="flex items-start text-sm">
                                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-amber-800 dark:text-amber-300">
                                                <strong>{{ $underUtilizedRooms->count() }} room(s) under 50% utilization</strong> - consider consolidating for better efficiency
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Rebalancing Panel - Shows when multiple rooms exist and last one is under-utilized -->
                @if($needsRebalancing)
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border-2 border-amber-300 dark:border-amber-700 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-start">
                            <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl mr-4">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-amber-900 dark:text-amber-200 mb-2">Rebalance Room Distribution</h3>
                                <p class="text-sm text-amber-700 dark:text-amber-300 mb-4">
                                    The last room is only <strong>{{ number_format($lastRoomUtilization, 0) }}%</strong> utilized.
                                    You can redistribute students more evenly across all {{ $exam->roomAllocations->count() }} rooms.
                                </p>

                                <div class="flex flex-wrap gap-3">
                                    <!-- Auto Balance Button -->
                                    <form action="{{ route('admin.exam-scheduling.rebalance-rooms', $exam->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="mode" value="auto">
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Auto Balance
                                        </button>
                                    </form>

                                    <!-- Manual Balance Button -->
                                    <button type="button"
                                            onclick="toggleManualBalanceModal()"
                                            class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 border-2 border-amber-300 dark:border-amber-600 text-amber-700 dark:text-amber-400 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:bg-amber-50 dark:hover:bg-amber-900/20">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                        </svg>
                                        Manual Balance
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Balance Modal -->
                    <div id="manualBalanceModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                    Manual Room Balancing
                                </h3>
                            </div>

                            <form action="{{ route('admin.exam-scheduling.rebalance-rooms', $exam->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="mode" value="manual">

                                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Total students to distribute: <strong class="text-indigo-600 dark:text-indigo-400">{{ $assignedStudents }}</strong>
                                    </p>

                                    @foreach($exam->roomAllocations->sortBy('id') as $allocation)
                                        @php
                                            $studentsInRoom = $exam->convocations()->where('local_id', $allocation->local_id)->count();
                                        @endphp
                                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <span class="font-bold text-gray-900 dark:text-white">{{ $allocation->local->name }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">(max: {{ $allocation->local->capacity }})</span>
                                                </div>
                                                <span class="text-xs px-2 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-lg">
                                                    Current: {{ $studentsInRoom }}
                                                </span>
                                            </div>
                                            <input type="number"
                                                   name="allocations[{{ $allocation->local_id }}]"
                                                   value="{{ $studentsInRoom }}"
                                                   min="0"
                                                   max="{{ $allocation->local->capacity }}"
                                                   class="manual-balance-input w-full px-3 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-500 dark:bg-gray-800 dark:text-white text-sm font-semibold"
                                                   data-capacity="{{ $allocation->local->capacity }}"
                                                   onchange="validateManualBalance()">
                                        </div>
                                    @endforeach

                                    <div id="balanceValidation" class="hidden mt-4 p-3 rounded-lg text-sm">
                                        <!-- Validation message will appear here -->
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                                    <button type="button"
                                            onclick="toggleManualBalanceModal()"
                                            class="px-4 py-2 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            id="manualBalanceSubmit"
                                            class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        Apply Balance
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Current Allocations -->
                <div class="lg:col-span-2">
                    @if($exam->roomAllocations->isNotEmpty())
                        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    Allocated Rooms
                                </h2>
                                <p class="text-sm text-indigo-100 mt-1 flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-white/20 text-white font-medium mr-2">
                                        {{ $exam->roomAllocations->count() }}
                                    </span>
                                    rooms assigned for this exam
                                </p>
                            </div>

                            <div class="p-6 space-y-4">
                                @foreach($exam->roomAllocations as $index => $allocation)
                                    @php
                                        // Count actual students assigned to this room
                                        $studentsInRoom = $exam->convocations()->where('local_id', $allocation->local_id)->count();
                                        $utilization = ($studentsInRoom / $allocation->local->capacity) * 100;
                                        $utilizationColor = $utilization < 50 ? 'amber' : ($utilization < 80 ? 'blue' : 'emerald');
                                    @endphp
                                    <div class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-2xl p-6 hover:border-indigo-400 dark:hover:border-indigo-600 transition-all duration-300 hover:shadow-xl hover:scale-[1.02]">
                                        <!-- Room Number Badge -->
                                        <div class="absolute -top-3 -left-3 w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600 text-white font-bold text-sm shadow-lg">
                                            {{ $index + 1 }}
                                        </div>

                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 pr-4">
                                                <!-- Room Header -->
                                                <div class="flex items-center mb-4">
                                                    <div class="p-3 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-xl mr-4 group-hover:scale-110 transition-transform">
                                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $allocation->local->name }}</h3>
                                                        <div class="flex items-center gap-2">
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                                                {{ $allocation->local->code }}
                                                            </span>
                                                            @if($allocation->local->building)
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                                    </svg>
                                                                    {{ $allocation->local->building }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Stats Grid -->
                                                <div class="grid grid-cols-3 gap-3 mb-4">
                                                    <div class="bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl p-4 text-center">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Capacity</p>
                                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $allocation->local->capacity }}</p>
                                                    </div>
                                                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/30 dark:to-blue-900/30 border-2 border-indigo-200 dark:border-indigo-800 rounded-xl p-4 text-center">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400 uppercase tracking-wide">Assigned</p>
                                                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $studentsInRoom }}</p>
                                                    </div>
                                                    <div class="bg-gradient-to-br from-{{ $utilizationColor }}-50 to-{{ $utilizationColor }}-100 dark:from-{{ $utilizationColor }}-900/30 dark:to-{{ $utilizationColor }}-900/40 border-2 border-{{ $utilizationColor }}-200 dark:border-{{ $utilizationColor }}-800 rounded-xl p-4 text-center">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <svg class="w-5 h-5 text-{{ $utilizationColor }}-600 dark:text-{{ $utilizationColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-medium text-{{ $utilizationColor }}-700 dark:text-{{ $utilizationColor }}-400 uppercase tracking-wide">Usage</p>
                                                        <p class="text-2xl font-bold text-{{ $utilizationColor }}-700 dark:text-{{ $utilizationColor }}-400 mt-1">{{ number_format($utilization, 0) }}%</p>
                                                    </div>
                                                </div>

                                                <!-- Enhanced Progress Bar -->
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="font-medium text-gray-600 dark:text-gray-400">Student Assignment</span>
                                                        <span class="font-semibold {{ $utilization < 50 ? 'text-amber-600 dark:text-amber-400' : ($utilization < 80 ? 'text-blue-600 dark:text-blue-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                                            {{ $studentsInRoom }} / {{ $allocation->local->capacity }}
                                                        </span>
                                                    </div>
                                                    <div class="relative w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden shadow-inner">
                                                        <div class="h-full rounded-full transition-all duration-500 {{ $utilization < 50 ? 'bg-gradient-to-r from-amber-500 to-amber-600' : ($utilization < 80 ? 'bg-gradient-to-r from-blue-500 to-blue-600' : 'bg-gradient-to-r from-emerald-500 to-emerald-600') }}"
                                                            style="width: {{ min($utilization, 100) }}%">
                                                            <div class="h-full w-full bg-white/30 animate-pulse"></div>
                                                        </div>
                                                    </div>
                                                    @if($utilization < 50)
                                                        <p class="text-xs text-amber-600 dark:text-amber-400 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Under-utilized - Consider consolidating
                                                        </p>
                                                    @elseif($utilization >= 100)
                                                        <p class="text-xs text-emerald-600 dark:text-emerald-400 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Fully allocated
                                                        </p>
                                                    @else
                                                        <p class="text-xs text-blue-600 dark:text-blue-400 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Optimal utilization
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Remove Button -->
                                            <form action="{{ route('admin.exam-scheduling.allocate-rooms', $exam->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="remove_local_id" value="{{ $allocation->local->id }}">
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to remove this room allocation?')"
                                                    class="p-3 text-red-600 hover:text-white dark:text-red-400 dark:hover:text-white hover:bg-red-600 dark:hover:bg-red-600 border-2 border-red-200 hover:border-red-600 dark:border-red-800 dark:hover:border-red-600 rounded-xl transition-all duration-300 group/btn">
                                                    <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="text-center py-20 px-6">
                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 mb-6 animate-pulse">
                                    <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">No Rooms Allocated Yet</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                                    Get started by selecting rooms from the <strong class="text-indigo-600 dark:text-indigo-400">Available Rooms</strong> panel. You can manually allocate seats or use the <strong class="text-indigo-600 dark:text-indigo-400">Auto-Balance</strong> feature for smart distribution.
                                </p>
                                <div class="mt-6 inline-flex items-center px-4 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $totalStudents }} students waiting for room assignment
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Available Rooms Panel -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-8">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Available Rooms
                            </h2>
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-purple-100">Remaining Students:</span>
                                    <span id="remainingCount" class="font-bold text-white px-2.5 py-1 bg-white/20 rounded-lg">{{ $totalStudents - $exam->total_allocated_seats }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-purple-100">Rooms Selected:</span>
                                    <span id="selectedCount" class="font-bold text-white px-2.5 py-1 bg-white/20 rounded-lg">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Allocation Mode Toggle -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <button type="button" id="autoMode" class="allocation-mode-btn flex-1 px-3 py-2 text-xs font-semibold rounded-lg bg-purple-600 text-white transition-all">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Auto
                                </button>
                                <button type="button" id="manualMode" class="allocation-mode-btn flex-1 px-3 py-2 text-xs font-semibold rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 transition-all">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                    Manual
                                </button>
                            </div>
                        </div>

                        <form action="{{ route('admin.exam-scheduling.allocate-rooms', $exam->id) }}" method="POST" id="allocationForm">
                            @csrf

                            <div class="p-4 space-y-2 max-h-[calc(100vh-420px)] overflow-y-auto custom-scrollbar" id="roomsList">
                                @foreach($availableLocals as $local)
                                    @php
                                        $isAllocated = $exam->roomAllocations->where('local_id', $local->id)->isNotEmpty();
                                        $isAvailable = $local->isAvailable($exam->exam_date, $exam->start_time, $exam->end_time, $exam->id);
                                        $fitScore = 0; // For sorting
                                        $remaining = $totalStudents - $exam->total_allocated_seats;
                                        if ($remaining > 0) {
                                            // Perfect fit gets highest score
                                            if ($local->capacity == $remaining) $fitScore = 1000;
                                            // Slightly larger rooms
                                            elseif ($local->capacity > $remaining && $local->capacity <= $remaining * 1.2) $fitScore = 900;
                                            // Larger rooms
                                            elseif ($local->capacity > $remaining) $fitScore = 800 - abs($local->capacity - $remaining);
                                            // Smaller rooms that can contribute
                                            else $fitScore = 700 + $local->capacity;
                                        }
                                    @endphp
                                    <div class="room-card cursor-pointer transition-all duration-200 {{ $isAllocated ? 'opacity-50 pointer-events-none' : (!$isAvailable ? 'opacity-40 pointer-events-none' : '') }}"
                                         data-room-id="{{ $local->id }}"
                                         data-capacity="{{ $local->capacity }}"
                                         data-fit-score="{{ $fitScore }}"
                                         data-allocated="{{ $isAllocated ? 'true' : 'false' }}"
                                         data-available="{{ $isAvailable ? 'true' : 'false' }}">

                                        <div class="flex items-center gap-3 p-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-purple-400 dark:hover:border-purple-600 hover:shadow-md">
                                            <!-- Selection Checkbox -->
                                            <div class="flex-shrink-0">
                                                <div class="w-5 h-5 rounded border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center selection-indicator transition-all">
                                                    <svg class="w-3 h-3 text-white hidden checkmark" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Room Info -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $local->name }}</h4>
                                                    @if($isAllocated)
                                                        <span class="flex-shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400">
                                                            Used
                                                        </span>
                                                    @elseif(!$isAvailable)
                                                        <span class="flex-shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400">
                                                            Conflict
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                                    <span class="font-mono font-semibold">{{ $local->code }}</span>
                                                    @if($local->building)
                                                        <span>• {{ $local->building }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Capacity Badge -->
                                            <div class="flex-shrink-0">
                                                <div class="text-right">
                                                    <div class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $local->capacity }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">seats</div>
                                                </div>
                                            </div>

                                            <!-- Allocated Seats (hidden by default, shown when selected) -->
                                            <div class="allocated-seats-display hidden flex-shrink-0">
                                                <input type="number"
                                                       name="allocations[{{ $local->id }}]"
                                                       class="seat-input hidden"
                                                       min="1"
                                                       max="{{ $local->capacity }}"
                                                       value="{{ $local->capacity }}"
                                                       disabled>
                                                <div class="text-right">
                                                    <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400 allocated-count">0</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">allocated</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Manual Input (shown only in manual mode when selected) -->
                                        <div class="manual-input-container hidden mt-2 ml-8">
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Seats to Allocate</label>
                                            <div class="relative">
                                                <input type="number"
                                                       class="manual-seat-input block w-full pl-3 pr-16 py-2 text-sm font-semibold border-2 border-purple-300 dark:border-purple-700 focus:border-purple-500 focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white rounded-lg transition-all shadow-sm"
                                                       min="1"
                                                       max="{{ $local->capacity }}"
                                                       placeholder="Enter seats">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <span class="text-xs font-medium text-gray-400">/ {{ $local->capacity }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Smart Suggestions (shown when remaining is small) -->
                            <div id="smartSuggestion" class="hidden px-6 py-4 bg-amber-50 dark:bg-amber-900/20 border-t border-amber-200 dark:border-amber-800">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="text-sm text-amber-800 dark:text-amber-300">
                                        <p class="font-semibold mb-1" id="suggestionText"></p>
                                        <p class="text-xs" id="suggestionDetail"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-gray-50 to-indigo-50 dark:from-gray-900/50 dark:to-indigo-900/20 px-6 py-5 border-t-2 border-gray-200 dark:border-gray-700 space-y-3">
                                <button type="submit"
                                        id="saveButton"
                                        class="w-full inline-flex items-center justify-center px-5 py-3.5 border border-transparent rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Allocations
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar for available rooms panel */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgb(243 244 246);
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgb(31 41 55);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, rgb(147 51 234), rgb(79 70 229));
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, rgb(126 34 206), rgb(67 56 202));
            background-clip: content-box;
        }

        /* Smooth animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Room card selection styles */
        .room-card.selected > div:first-child {
            border-color: rgb(147 51 234) !important;
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.05), rgba(79, 70, 229, 0.05));
        }

        .dark .room-card.selected > div:first-child {
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(79, 70, 229, 0.1));
        }

        .room-card:not(.selected):hover > div:first-child {
            transform: translateX(2px);
        }

        /* Smooth transitions for all interactive elements */
        .room-card,
        .selection-indicator,
        .allocated-seats-display,
        .manual-input-container {
            transition: all 0.2s ease-out;
        }
    </style>

    <script>
        // Room allocation management
        const TOTAL_STUDENTS = {{ $totalStudents }};
        const CURRENTLY_ASSIGNED = {{ $assignedStudents }};
        let remainingStudents = TOTAL_STUDENTS - CURRENTLY_ASSIGNED;
        let selectedRooms = [];
        let allocationMode = 'auto'; // 'auto' or 'manual'

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            sortRoomsByFit();
            updateUI();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Mode toggle
            document.getElementById('autoMode').addEventListener('click', () => setMode('auto'));
            document.getElementById('manualMode').addEventListener('click', () => setMode('manual'));

            // Room selection
            document.querySelectorAll('.room-card').forEach(card => {
                if (card.dataset.allocated === 'false' && card.dataset.available === 'true') {
                    card.addEventListener('click', function(e) {
                        // Don't toggle if clicking on manual input
                        if (e.target.classList.contains('manual-seat-input')) return;
                        toggleRoomSelection(this);
                    });
                }
            });

            // Manual input changes
            document.querySelectorAll('.manual-seat-input').forEach(input => {
                input.addEventListener('input', function() {
                    const card = this.closest('.room-card');
                    const roomId = card.dataset.roomId;
                    const value = parseInt(this.value) || 0;
                    updateRoomAllocation(roomId, value);
                });
            });
        }

        function setMode(mode) {
            allocationMode = mode;

            // Update button styles
            if (mode === 'auto') {
                document.getElementById('autoMode').className = 'allocation-mode-btn flex-1 px-3 py-2 text-xs font-semibold rounded-lg bg-purple-600 text-white transition-all';
                document.getElementById('manualMode').className = 'allocation-mode-btn flex-1 px-3 py-2 text-xs font-semibold rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 transition-all';

                // Hide manual inputs
                document.querySelectorAll('.manual-input-container').forEach(el => el.classList.add('hidden'));
            } else {
                document.getElementById('autoMode').className = 'allocation-mode-btn flex-1 px-3 py-2 text-xs font-semibold rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 transition-all';
                document.getElementById('manualMode').className = 'allocation-mode-btn flex-1 px-3 py-2 text-xs font-semibold rounded-lg bg-purple-600 text-white transition-all';

                // Show manual inputs for selected rooms
                selectedRooms.forEach(roomId => {
                    const card = document.querySelector(`.room-card[data-room-id="${roomId}"]`);
                    card.querySelector('.manual-input-container').classList.remove('hidden');
                });
            }

            updateUI();
        }

        function toggleRoomSelection(card) {
            const roomId = card.dataset.roomId;
            const capacity = parseInt(card.dataset.capacity);
            const isSelected = selectedRooms.includes(roomId);

            if (isSelected) {
                // Deselect
                selectedRooms = selectedRooms.filter(id => id !== roomId);
                card.classList.remove('selected');
                card.querySelector('.selection-indicator').classList.remove('bg-purple-600', 'border-purple-600');
                card.querySelector('.selection-indicator').classList.add('border-gray-300', 'dark:border-gray-600');
                card.querySelector('.checkmark').classList.add('hidden');
                card.querySelector('.allocated-seats-display').classList.add('hidden');
                card.querySelector('.manual-input-container').classList.add('hidden');
                card.querySelector('.seat-input').value = 0;
                card.querySelector('.seat-input').disabled = true;
            } else {
                // Select
                if (allocationMode === 'auto') {
                    // Auto mode: allocate full capacity or remaining, whichever is smaller
                    const toAllocate = Math.min(capacity, remainingStudents);
                    if (toAllocate > 0) {
                        selectedRooms.push(roomId);
                        card.classList.add('selected');
                        card.querySelector('.selection-indicator').classList.add('bg-purple-600', 'border-purple-600');
                        card.querySelector('.selection-indicator').classList.remove('border-gray-300', 'dark:border-gray-600');
                        card.querySelector('.checkmark').classList.remove('hidden');
                        card.querySelector('.allocated-seats-display').classList.remove('hidden');
                        card.querySelector('.allocated-count').textContent = toAllocate;
                        card.querySelector('.seat-input').value = toAllocate;
                        card.querySelector('.seat-input').disabled = false;
                    } else {
                        showNotification('No more students remaining to allocate!', 'info');
                    }
                } else {
                    // Manual mode: just select, user will enter amount
                    selectedRooms.push(roomId);
                    card.classList.add('selected');
                    card.querySelector('.selection-indicator').classList.add('bg-purple-600', 'border-purple-600');
                    card.querySelector('.selection-indicator').classList.remove('border-gray-300', 'dark:border-gray-600');
                    card.querySelector('.checkmark').classList.remove('hidden');
                    card.querySelector('.manual-input-container').classList.remove('hidden');
                    card.querySelector('.seat-input').disabled = false;
                }
            }

            updateUI();
        }

        function updateRoomAllocation(roomId, value) {
            const card = document.querySelector(`.room-card[data-room-id="${roomId}"]`);
            const capacity = parseInt(card.dataset.capacity);
            const actualValue = Math.min(Math.max(value, 0), capacity);

            card.querySelector('.allocated-count').textContent = actualValue;
            card.querySelector('.seat-input').value = actualValue;
            card.querySelector('.allocated-seats-display').classList.remove('hidden');

            updateUI();
        }

        function updateUI() {
            // Calculate remaining
            let allocated = 0;
            selectedRooms.forEach(roomId => {
                const card = document.querySelector(`.room-card[data-room-id="${roomId}"]`);
                const input = card.querySelector('.seat-input');
                allocated += parseInt(input.value) || 0;
            });

            // Calculate remaining: Total students - Already assigned students - Newly selected seats
            remainingStudents = TOTAL_STUDENTS - CURRENTLY_ASSIGNED - allocated;

            // Update counters
            document.getElementById('remainingCount').textContent = remainingStudents;
            document.getElementById('selectedCount').textContent = selectedRooms.length;

            // Show smart suggestions
            showSmartSuggestions();

            // Enable/disable save button
            document.getElementById('saveButton').disabled = selectedRooms.length === 0;
        }

        function showSmartSuggestions() {
            const suggestionDiv = document.getElementById('smartSuggestion');
            const suggestionText = document.getElementById('suggestionText');
            const suggestionDetail = document.getElementById('suggestionDetail');

            if (remainingStudents > 0 && remainingStudents < 50) {
                // Find perfect fit rooms
                const availableRooms = Array.from(document.querySelectorAll('.room-card')).filter(card =>
                    card.dataset.allocated === 'false' &&
                    card.dataset.available === 'true' &&
                    !selectedRooms.includes(card.dataset.roomId)
                );

                const perfectFit = availableRooms.find(card => parseInt(card.dataset.capacity) === remainingStudents);
                const smallRooms = availableRooms.filter(card => {
                    const cap = parseInt(card.dataset.capacity);
                    return cap >= remainingStudents && cap <= remainingStudents * 1.3;
                });

                if (perfectFit) {
                    suggestionText.textContent = `Perfect match found!`;
                    suggestionDetail.textContent = `Room ${perfectFit.querySelector('h4').textContent} has exactly ${remainingStudents} seats.`;
                    suggestionDiv.classList.remove('hidden');
                } else if (smallRooms.length > 0) {
                    suggestionText.textContent = `${remainingStudents} students remaining`;
                    suggestionDetail.textContent = `Consider ${allocationMode === 'auto' ? 'selecting' : 'manually balancing'} smaller rooms to avoid waste.`;
                    suggestionDiv.classList.remove('hidden');
                } else {
                    suggestionDiv.classList.add('hidden');
                }
            } else {
                suggestionDiv.classList.add('hidden');
            }
        }

        function sortRoomsByFit() {
            const roomsList = document.getElementById('roomsList');
            const rooms = Array.from(roomsList.querySelectorAll('.room-card'));

            rooms.sort((a, b) => {
                const scoreA = parseInt(a.dataset.fitScore) || 0;
                const scoreB = parseInt(b.dataset.fitScore) || 0;
                return scoreB - scoreA; // Descending order (best fit first)
            });

            rooms.forEach(room => roomsList.appendChild(room));
        }

        // Enhanced notification function
        function showNotification(message, type = 'success') {
            const colors = {
                success: 'bg-gradient-to-r from-emerald-600 to-emerald-700',
                error: 'bg-gradient-to-r from-red-600 to-red-700',
                info: 'bg-gradient-to-r from-blue-600 to-blue-700'
            };

            const icons = {
                success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
                info: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-fade-in flex items-center space-x-3 max-w-md`;
            notification.innerHTML = `
                <div class="flex-shrink-0">${icons[type]}</div>
                <p class="font-medium">${message}</p>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                notification.style.transition = 'all 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Form validation before submit
        document.getElementById('allocationForm').addEventListener('submit', function(e) {
            if (selectedRooms.length === 0) {
                e.preventDefault();
                showNotification('Please select at least one room before saving.', 'error');
                return false;
            }

            // Check if manual mode has all inputs filled
            if (allocationMode === 'manual') {
                let hasEmptyInputs = false;
                selectedRooms.forEach(roomId => {
                    const card = document.querySelector(`.room-card[data-room-id="${roomId}"]`);
                    const manualInput = card.querySelector('.manual-seat-input');
                    if (!manualInput.value || parseInt(manualInput.value) <= 0) {
                        hasEmptyInputs = true;
                    }
                });

                if (hasEmptyInputs) {
                    e.preventDefault();
                    showNotification('Please enter seat count for all selected rooms in manual mode.', 'error');
                    return false;
                }
            }
        });

        // Manual Balance Modal Functions
        function toggleManualBalanceModal() {
            const modal = document.getElementById('manualBalanceModal');
            if (modal) {
                modal.classList.toggle('hidden');
                if (!modal.classList.contains('hidden')) {
                    validateManualBalance();
                }
            }
        }

        function validateManualBalance() {
            const inputs = document.querySelectorAll('.manual-balance-input');
            const validationDiv = document.getElementById('balanceValidation');
            const submitBtn = document.getElementById('manualBalanceSubmit');
            const totalStudents = {{ $assignedStudents }};

            let sum = 0;
            let hasCapacityError = false;

            inputs.forEach(input => {
                const value = parseInt(input.value) || 0;
                const capacity = parseInt(input.dataset.capacity);
                sum += value;

                if (value > capacity) {
                    hasCapacityError = true;
                    input.classList.add('border-red-500');
                    input.classList.remove('border-gray-300', 'dark:border-gray-600');
                } else {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-gray-300', 'dark:border-gray-600');
                }
            });

            if (validationDiv) {
                if (hasCapacityError) {
                    validationDiv.className = 'mt-4 p-3 rounded-lg text-sm bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800';
                    validationDiv.innerHTML = '<strong>Error:</strong> Some rooms exceed their capacity.';
                    validationDiv.classList.remove('hidden');
                    if (submitBtn) submitBtn.disabled = true;
                } else if (sum !== totalStudents) {
                    const diff = totalStudents - sum;
                    validationDiv.className = 'mt-4 p-3 rounded-lg text-sm bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800';
                    if (diff > 0) {
                        validationDiv.innerHTML = `<strong>Warning:</strong> ${diff} student(s) not assigned. Total: ${sum}/${totalStudents}`;
                    } else {
                        validationDiv.innerHTML = `<strong>Warning:</strong> ${Math.abs(diff)} extra seat(s) assigned. Total: ${sum}/${totalStudents}`;
                    }
                    validationDiv.classList.remove('hidden');
                    if (submitBtn) submitBtn.disabled = sum !== totalStudents;
                } else {
                    validationDiv.className = 'mt-4 p-3 rounded-lg text-sm bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800';
                    validationDiv.innerHTML = `<strong>Perfect!</strong> All ${totalStudents} students will be distributed.`;
                    validationDiv.classList.remove('hidden');
                    if (submitBtn) submitBtn.disabled = false;
                }
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('manualBalanceModal');
                if (modal && !modal.classList.contains('hidden')) {
                    toggleManualBalanceModal();
                }
            }
        });

        // Close modal on backdrop click
        const modal = document.getElementById('manualBalanceModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    toggleManualBalanceModal();
                }
            });
        }
    </script>
@endsection
