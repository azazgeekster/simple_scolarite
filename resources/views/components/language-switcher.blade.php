<div class="relative" x-data="{ open: false }">
    <button @click="open = !open"
            class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        @php
            $currentLocale = app()->getLocale();
            $flags = [
                'en' => '🇬🇧',
                'fr' => '🇫🇷',
                'ar' => '🇲🇦'
            ];
        @endphp
        <span class="text-xl">{{ $flags[$currentLocale] ?? '🌐' }}</span>
        <span class="text-sm font-medium">{{ config("app.locale_names.$currentLocale") }}</span>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
        @foreach(config('app.available_locales') as $locale)
            <a href="{{ route('language.switch', $locale) }}"
               class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() == $locale ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }}">
                <span class="text-xl">{{ $flags[$locale] ?? '🌐' }}</span>
                <span class="font-medium">{{ config("app.locale_names.$locale") }}</span>
            </a>
        @endforeach
    </div>
</div>