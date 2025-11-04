@extends("student.layouts.app")

@section("title", "Détails de l'Examen")

@section("main_content")
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('exams.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux examens
            </a>
        </div>

        {{-- Exam Header Card --}}
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                            @if($exam->session_type === 'normal') bg-white/20 text-white
                            @else bg-orange-500 text-white
                            @endif">
                            @if($exam->session_type === 'normal')
                                Session Normale
                            @else
                                Session de Rattrapage
                            @endif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 rounded-lg text-xs font-bold text-white">
                            {{ $exam->semester }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
                        {{ $exam->module->label }}
                    </h1>
                    <p class="text-blue-100 text-sm font-medium">
                        {{ $exam->module->code }}
                    </p>
                </div>

                @if($exam->exam_date >= now()->toDateString())
                <div class="flex-shrink-0">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                        <p class="text-xs text-blue-100 mb-1 text-center">Examen dans</p>
                        <p class="text-2xl font-bold text-white text-center">
                            {{ \Carbon\Carbon::parse($exam->exam_date)->diffInDays(now()) }} jours
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Exam Details --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Informations de l'Examen
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Date --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Date de l'examen</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($exam->exam_date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                            </p>
                        </div>
                    </div>

                    {{-- Time --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Horaire</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Durée: {{ \Carbon\Carbon::parse($exam->start_time)->diffInMinutes(\Carbon\Carbon::parse($exam->end_time)) }} minutes
                            </p>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Salle d'examen</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $exam->local ?? 'À définir' }}
                            </p>
                        </div>
                    </div>

                    {{-- Academic Year --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Année universitaire</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $exam->academic_year }}-{{ $exam->academic_year + 1 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Important Information --}}
        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-l-4 border-yellow-400 dark:border-yellow-600 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-yellow-400 dark:bg-yellow-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">Consignes Importantes</h3>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Arrivez au moins <strong>15 minutes avant</strong> le début de l'examen</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Munissez-vous de votre <strong>carte d'étudiant</strong> et d'une <strong>pièce d'identité</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Téléphones et appareils électroniques <strong>strictement interdits</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Consultez votre convocation pour plus de détails</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('exams.convocation', $exam) }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Télécharger la Convocation
            </a>
            <a href="{{ route('student.grades') }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Voir Mes Notes
            </a>
        </div>

    </div>
</div>
@endsection
