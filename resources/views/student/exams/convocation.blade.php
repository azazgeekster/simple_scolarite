@extends("student.layouts.app")

@section("title", "Convocations d'Examens")

@section("main_content")
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-red-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Convocations d'Examens
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Téléchargez vos convocations pour les examens à venir
                    </p>
                </div>
            </div>
        </div>

        {{-- Alert for missing avatar --}}
        @if(empty($student->avatar))
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 dark:border-yellow-600 rounded-lg p-6 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-yellow-900 dark:text-yellow-300 mb-2">Photo de profil requise</h3>
                    <p class="text-sm text-yellow-800 dark:text-yellow-300 mb-3">
                        Vous devez définir votre photo de profil avant de pouvoir télécharger votre convocation d'examen.
                    </p>
                    <a href="{{ route('student.profile.edit') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Ajouter une photo
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Flash Messages --}}
        @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-600 rounded-lg p-6 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-800 dark:text-red-300">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if(session('message'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-600 rounded-lg p-6 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-green-800 dark:text-green-300">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        {{-- Student Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex items-center gap-4 flex-1">
                    @if($student->avatar)
                    <img src="{{ asset('storage/' . $student->avatar) }}" alt="Avatar"
                         class="w-14 h-14 rounded-xl object-cover shadow-lg ring-2 ring-blue-500">
                    @else
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        {{ substr($student->prenom, 0, 1) }}{{ substr($student->nom, 0, 1) }}
                    </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $student->full_name }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $enrollment->filiere->label_fr }} • {{ $enrollment->year_label }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 text-sm">
                    <div class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <span class="text-gray-600 dark:text-gray-400">CNE:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-1">{{ $student->cne }}</span>
                    </div>
                    @if($student->apogee)
                    <div class="px-3 py-1.5 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <span class="text-gray-600 dark:text-gray-400">Apogée:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-1">{{ $student->apogee }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Download Section --}}
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-orange-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                Convocation Complète
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $exams->count() }} examen(s) à passer
                            </p>
                        </div>
                    </div>

                    @if(empty($student->avatar))
                    <button disabled
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gray-400 dark:bg-gray-600 text-white font-bold rounded-xl shadow-lg cursor-not-allowed opacity-60">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Télécharger convocation </span>
                    </button>
                    @else
                    <a href="{{ route('student.convocations.download') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Télécharger ma convocation</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        

        {{-- Exams List by Session/Season --}}
        @php
            $examsBySession = $exams->groupBy(function($exam) {
                return $exam['session'] . ' - ' . $exam['season'];
            });
        @endphp

        @if($exams->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center border border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aucune convocation disponible</h3>
                <p class="text-gray-600 dark:text-gray-400">Les convocations d'examens seront disponibles prochainement</p>
            </div>
        @else
            @foreach($examsBySession as $sessionLabel => $sessionExams)
            <div class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    {{-- Session Header --}}
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-750 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 dark:bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $sessionLabel }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $sessionExams->count() }} examen(s)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Exams Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Module</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase hidden md:table-cell">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase hidden md:table-cell">Heure</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase hidden lg:table-cell">Salle</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase hidden lg:table-cell">Bâtiment</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($sessionExams as $exam)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-100 to-red-50 dark:from-orange-900/30 dark:to-red-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded text-xs font-bold">
                                                        {{ $exam['module_code'] }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded text-xs font-semibold">
                                                        {{ $exam['semester'] }}
                                                    </span>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $exam['module_label'] }}</p>
                                                {{-- @if($exam['module_label_ar'])
                                                <p class="text-xs text-gray-500 dark:text-gray-400 text-right mt-1">{{ $exam['module_label_ar'] }}</p>
                                                @endif --}}
                                                {{-- Mobile info --}}
                                                <div class="md:hidden mt-2 space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                                    <div>📅 {{ $exam['date']->format('d/m/Y') }} à {{ $exam['time'] }}</div>
                                                    <div>🏢 {{ $exam['room'] }} - {{ $exam['building'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                                        {{ $exam['date']->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                                        {{ $exam['time'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden lg:table-cell">
                                        {{ $exam['room'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden lg:table-cell">
                                        {{ $exam['building'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @endforeach
        @endif

        {{-- Important Info --}}
        <div class="mt-8 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-600 rounded-lg p-6 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-red-900 dark:text-red-300 mb-3">Documents requis le jour de l'examen</h3>
                    <ul class="space-y-2 text-sm text-red-800 dark:text-red-300">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Carte d'identité nationale (CIN) ou passeport</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Carte d'étudiant ou attestation d'inscription originale</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Convocation d'examen imprimée</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Aucun appareil électronique ou document non autorisé</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection