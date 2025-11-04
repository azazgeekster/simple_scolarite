@extends("student.layouts.app")

@section("title", "Mes Examens")

@section("main_content")
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Calendrier des Examens
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Consultez vos examens à venir et passés
            </p>
        </div>

        {{-- Upcoming Exams --}}
        @if($upcomingExams->isNotEmpty())
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-bold text-white">Examens à Venir</span>
                    <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $upcomingExams->count() }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach($upcomingExams as $exam)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 sm:px-6 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-white mb-1">
                                        {{ $exam->module->label }}
                                    </h3>
                                    <p class="text-blue-100 text-xs font-medium">
                                        {{ $exam->module->code }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                                        @if($exam->session_type === 'normal') bg-white/20 text-white
                                        @else bg-orange-500 text-white
                                        @endif">
                                        @if($exam->session_type === 'normal')
                                            Session Normale
                                        @else
                                            Rattrapage
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-4 sm:p-6">
                            <div class="space-y-3">
                                {{-- Date --}}
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Date</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($exam->exam_date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Time --}}
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Horaire</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Location --}}
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Salle</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $exam->local ?? 'À définir' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Semester --}}
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                        <span class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ $exam->semester }}</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Semestre</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            Semestre {{ substr($exam->semester, 1) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-6 flex gap-3">
                                <a href="{{ route('exams.show', $exam) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Détails
                                </a>
                                <a href="{{ route('exams.convocation', $exam) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Convocation
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Past Exams --}}
        @if($pastExams->isNotEmpty())
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    <span class="text-sm font-bold text-white">Examens Passés</span>
                    <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pastExams->count() }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{-- Desktop Table --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-750">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Module</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Horaire</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Salle</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Session</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($pastExams as $exam)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $exam->module->label }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $exam->module->code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($exam->exam_date)->locale('fr')->isoFormat('DD/MM/YYYY') }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $exam->local ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold
                                            @if($exam->session_type === 'normal') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                            @else bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400
                                            @endif">
                                            {{ $exam->session_type === 'normal' ? 'Normale' : 'Rattrapage' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('exams.show', $exam) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                            Voir
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="block lg:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($pastExams as $exam)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $exam->module->label }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->module->code }}</p>
                                </div>
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold
                                    @if($exam->session_type === 'normal') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                    @else bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400
                                    @endif">
                                    {{ $exam->session_type === 'normal' ? 'Normale' : 'Rattrapage' }}
                                </span>
                            </div>
                            <div class="space-y-1.5 text-xs text-gray-600 dark:text-gray-400 mb-3">
                                <p>📅 {{ \Carbon\Carbon::parse($exam->exam_date)->locale('fr')->isoFormat('DD/MM/YYYY') }}</p>
                                <p>🕐 {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</p>
                                <p>📍 {{ $exam->local ?? 'À définir' }}</p>
                            </div>
                            <a href="{{ route('exams.show', $exam) }}"
                               class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                Voir les détails
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Empty State --}}
        @if($upcomingExams->isEmpty() && $pastExams->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12">
            <div class="text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucun examen disponible</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Le calendrier des examens sera publié prochainement.
                </p>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
