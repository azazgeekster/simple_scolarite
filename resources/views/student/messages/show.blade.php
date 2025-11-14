@extends('student.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('student.messages.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Message</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Reçu le {{ $message->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <!-- Message Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <!-- Subject with priority indicator -->
                    <div class="flex items-center space-x-3 mb-3">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $message->subject }}
                        </h2>
                        @if($message->isUrgent())
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">De:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">
                                {{ $message->sender->name }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Date:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">
                                {{ $message->created_at->format('d/m/Y à H:i') }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Priorité:</span>
                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded {{ $message->getPriorityBadgeClass() }}">
                                @switch($message->priority)
                                    @case('low')
                                        Basse
                                        @break
                                    @case('normal')
                                        Normale
                                        @break
                                    @case('high')
                                        Haute
                                        @break
                                    @case('urgent')
                                        Urgente
                                        @break
                                @endswitch
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Catégorie:</span>
                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded {{ $message->getCategoryBadgeClass() }}">
                                @switch($message->category)
                                    @case('general')
                                        Général
                                        @break
                                    @case('exam')
                                        Examen
                                        @break
                                    @case('grade')
                                        Note
                                        @break
                                    @case('administrative')
                                        Administratif
                                        @break
                                    @case('important')
                                        Important
                                        @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Body -->
        <div class="p-6">
            <!-- Alert for urgent messages -->
            @if($message->isUrgent())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:border-red-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm font-medium text-red-800 dark:text-red-400">
                            Message urgent - Veuillez lire attentivement
                        </span>
                    </div>
                </div>
            @endif

            <div class="prose dark:prose-invert max-w-none">
                <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $message->message }}</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900">
            <div class="flex justify-between items-center">
                <div>
                    @if($message->is_read)
                        <form action="{{ route('student.messages.mark-unread', $message->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                                </svg>
                                Marquer comme non lu
                            </button>
                        </form>
                    @endif
                </div>

                <a href="{{ route('student.messages.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Retour aux messages
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
