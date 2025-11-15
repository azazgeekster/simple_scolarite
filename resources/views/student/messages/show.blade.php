@extends('student.layouts.app')

@section('main_content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('student.messages.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            &larr; Retour aux messages
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        @if(!$message->is_read)
                            <span class="flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                        @endif
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $message->subject }}
                        </h1>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $message->getPriorityBadgeClass() }}">
                            Priorité: {{ ucfirst($message->priority) }}
                        </span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $message->getCategoryBadgeClass() }}">
                            {{ ucfirst($message->category) }}
                        </span>
                        @if($message->is_read)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                Lu
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Non lu
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Message Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Expéditeur</p>
                    <p class="text-sm text-gray-900 dark:text-white mt-1">
                        {{ $message->sender ? $message->sender->name : 'Administration' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Date d'envoi</p>
                    <p class="text-sm text-gray-900 dark:text-white mt-1">
                        {{ $message->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Type de message</p>
                    <p class="text-sm text-gray-900 dark:text-white mt-1">
                        {{ $message->getRecipientDescription() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="p-6">
            <div class="prose dark:prose-invert max-w-none">
                <p class="text-gray-900 dark:text-white whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
            </div>
        </div>

        <!-- Actions -->
        @if($message->recipient_id === auth('student')->id())
            <div class="border-t border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                <div class="flex justify-end">
                    @if($message->is_read)
                        <form action="{{ route('student.messages.mark-unread', $message) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                Marquer comme non lu
                            </button>
                        </form>
                    @else
                        <form action="{{ route('student.messages.mark-read', $message) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                                Marquer comme lu
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Priority Notice -->
    @if($message->priority === 'urgent' || $message->priority === 'high')
        <div class="mt-4 p-4 {{ $message->priority === 'urgent' ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700' : 'bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700' }} rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 {{ $message->priority === 'urgent' ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }} flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold {{ $message->priority === 'urgent' ? 'text-red-800 dark:text-red-300' : 'text-orange-800 dark:text-orange-300' }}">
                        {{ $message->priority === 'urgent' ? 'Message Urgent' : 'Message Important' }}
                    </h3>
                    <p class="text-xs {{ $message->priority === 'urgent' ? 'text-red-700 dark:text-red-400' : 'text-orange-700 dark:text-orange-400' }} mt-1">
                        Ce message nécessite votre attention immédiate. Veuillez en prendre connaissance et agir en conséquence si nécessaire.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
