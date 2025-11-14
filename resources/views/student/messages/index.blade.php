@extends('student.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mes Messages</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            @if($unreadCount > 0)
                Vous avez <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $unreadCount }}</span> message(s) non lu(s)
            @else
                Tous vos messages ont été lus
            @endif
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        @if($messages->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($messages as $message)
                    <a href="{{ route('student.messages.show', $message->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Read/Unread Indicator -->
                                <div class="flex-shrink-0 mt-1">
                                    @if(!$message->is_read)
                                        <div class="w-3 h-3 bg-blue-500 rounded-full" title="Non lu"></div>
                                    @else
                                        <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full" title="Lu"></div>
                                    @endif
                                </div>

                                <!-- Message Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="text-base font-semibold {{ !$message->is_read ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
                                            {{ $message->subject }}
                                        </h3>
                                        <div class="flex items-center space-x-2 ml-4">
                                            <!-- Priority Badge -->
                                            @if($message->isUrgent() || $message->priority === 'high')
                                                <span class="px-2 py-1 text-xs font-medium rounded {{ $message->getPriorityBadgeClass() }}">
                                                    @if($message->priority === 'urgent')
                                                        Urgent
                                                    @else
                                                        Important
                                                    @endif
                                                </span>
                                            @endif
                                            <!-- Category Badge -->
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
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        {{ Str::limit($message->message, 150) }}
                                    </p>

                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        De: {{ $message->sender->name }}
                                        <span class="mx-2">•</span>
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <!-- Arrow -->
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
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
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Vous n'avez reçu aucun message pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection
