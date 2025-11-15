@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.messages.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            &larr; Retour aux messages
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ $message->subject }}
                    </h1>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $message->getPriorityBadgeClass() }}">
                            Priorité: {{ ucfirst($message->priority) }}
                        </span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $message->getCategoryBadgeClass() }}">
                            {{ ucfirst($message->category) }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('admin.messages.destroy', $message) }}"
                      method="POST"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700">
                        Supprimer
                    </button>
                </form>
            </div>

            <!-- Message Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Destinataire(s)</p>
                    <p class="text-sm text-gray-900 dark:text-white mt-1">
                        {{ $message->getRecipientDescription() }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Date d'envoi</p>
                    <p class="text-sm text-gray-900 dark:text-white mt-1">
                        {{ $message->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="p-6">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Message</h2>
            <div class="prose dark:prose-invert max-w-none">
                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $message->message }}</p>
            </div>
        </div>

        <!-- Additional Details -->
        @if($message->recipient_type === 'individual' && $message->recipient)
            <div class="border-t border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Détails de l'étudiant</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Nom</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $message->recipient->prenom }} {{ $message->recipient->nom }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Code Apogée</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $message->recipient->apogee }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $message->recipient->email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($message->recipient_type === 'filiere' && $message->filiere)
            <div class="border-t border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Détails de la filière</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $message->filiere->label_fr }}
                    </p>
                    @if($message->filiere->label_ar)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ $message->filiere->label_ar }}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
