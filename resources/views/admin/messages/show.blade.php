@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.messages.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Détails du Message</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Envoyé le {{ $message->created_at->format('d/m/Y à H:i') }}
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
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        {{ $message->subject }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">De:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">
                                {{ $message->sender->name }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-600 dark:text-gray-400">À:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">
                                {{ $message->getRecipientDescription() }}
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
            <div class="prose dark:prose-invert max-w-none">
                <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $message->message }}</div>
            </div>
        </div>

        <!-- Recipients Count (for bulk messages) -->
        @if($message->recipient_type !== 'individual' && isset($recipientCount))
            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900">
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Ce message a été envoyé à <strong>{{ $recipientCount }}</strong> étudiant(s)</span>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900">
            <div class="flex justify-between items-center">
                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-600 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer
                    </button>
                </form>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Retour à la liste
                    </a>
                    <a href="{{ route('admin.messages.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nouveau Message
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
