@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Messages Envoyés</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Gérer les messages envoyés aux étudiants
            </p>
        </div>
        <a href="{{ route('admin.messages.create') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nouveau Message
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Sujet</th>
                            <th scope="col" class="px-6 py-3">Destinataire</th>
                            <th scope="col" class="px-6 py-3">Priorité</th>
                            <th scope="col" class="px-6 py-3">Catégorie</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ Str::limit($message->subject, 50) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $message->getRecipientDescription() }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $message->getPriorityBadgeClass() }}">
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
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $message->getCategoryBadgeClass() }}">
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
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.messages.show', $message->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Voir">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun message</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par envoyer votre premier message aux étudiants.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.messages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nouveau Message
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
