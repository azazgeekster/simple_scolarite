@extends('student.layouts.app')

@section('main_content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mes Messages</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Consultez vos messages de l'administration
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('student.messages.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Statut
                </label>
                <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Tous les messages</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Non lus</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Lus</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Catégorie
                </label>
                <select name="category" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="all">Toutes les catégories</option>
                    <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>Général</option>
                    <option value="exam" {{ request('category') === 'exam' ? 'selected' : '' }}>Examen</option>
                    <option value="grade" {{ request('category') === 'grade' ? 'selected' : '' }}>Note</option>
                    <option value="administrative" {{ request('category') === 'administrative' ? 'selected' : '' }}>Administratif</option>
                    <option value="important" {{ request('category') === 'important' ? 'selected' : '' }}>Important</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Unread count badge -->
    @if($unreadCount > 0)
        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-300">
                Vous avez <span class="font-semibold">{{ $unreadCount }}</span> message(s) non lu(s)
            </p>
        </div>
    @endif

    <!-- Messages list -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        {{-- DEBUG: Messages count = {{ $messages->count() }} --}}
        @if($messages->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($messages as $message)
                    <a href="{{ route('student.messages.show', $message) }}"
                       class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ !$message->is_read ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        @if(!$message->is_read)
                                            <span class="flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-blue-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                            </span>
                                        @endif
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white {{ !$message->is_read ? 'font-bold' : '' }}">
                                            {{ $message->subject }}
                                        </h3>
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                        {{ Str::limit($message->message, 150) }}
                                    </p>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $message->getPriorityBadgeClass() }}">
                                            {{ ucfirst($message->priority) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $message->getCategoryBadgeClass() }}">
                                            {{ ucfirst($message->category) }}
                                        </span>
                                        @if($message->sender)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                De: {{ $message->sender->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ml-4 flex-shrink-0 text-right">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $message->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $message->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $messages->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun message</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Vous n'avez pas de messages pour le moment.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
