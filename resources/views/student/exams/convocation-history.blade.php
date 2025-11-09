@extends("student.layouts.app")

@section("title", "Historique des Convocations")

@section("main_content")
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header with Year Selector --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                {{-- Title --}}
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            Historique des Convocations
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            Consultez vos convocations d'examens des années précédentes
                        </p>
                    </div>
                </div>

                {{-- Year Selector --}}
                @if($allEnrollments->count() > 0)
                <div class="flex-shrink-0">
                    <form method="GET" action="{{ route('student.convocations.history') }}" class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                        <label for="year-select" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-base font-semibold">Année universitaire</span>
                            </span>
                        </label>

                        <select name="year" id="year-select" onchange="this.form.submit()"
                            class="block w-full md:w-96 pl-4 pr-11 py-3 text-sm font-medium border-2 border-gray-200 dark:border-gray-700 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all duration-300 cursor-pointer appearance-none">
                            @foreach($allEnrollments as $historyEnrollment)
                                <option value="{{ $historyEnrollment->academic_year }}"
                                        {{ $enrollment && $historyEnrollment->academic_year == $enrollment->academic_year ? 'selected' : '' }}>
                                    {{ $historyEnrollment->academic_year }}-{{ $historyEnrollment->academic_year + 1 }}
                                    • {{ $historyEnrollment->filiere->label_fr }}
                                    @if($historyEnrollment->academicYear && $historyEnrollment->academicYear->is_current)
                                        ✓ En cours
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 mt-10">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            {{-- Back to Current Convocation --}}
            <div class="mt-6">
                <a href="{{ route('student.exams.convocation') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour aux convocations en cours
                </a>
            </div>
        </div>

        {{-- Current Enrollment Info --}}
        @if($enrollment)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-500 dark:bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $enrollment->filiere->label_fr }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        {{ $enrollment->year_label }} • {{ $enrollment->academic_year_label }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Sessions List --}}
        @if($sessions->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center border border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aucune convocation trouvée</h3>
                <p class="text-gray-600 dark:text-gray-400">Aucun examen n'a été enregistré pour cette année universitaire</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($sessions as $session)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    {{-- Session Header --}}
                    <div class="px-6 py-4 bg-gradient-to-r @if($session['type'] === 'normal') from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 @else from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 @endif border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 @if($session['type'] === 'normal') bg-blue-500 dark:bg-blue-600 @else bg-orange-500 dark:bg-orange-600 @endif rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $session['label'] }}</h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                        {{ $session['label_ar'] }} • {{ $session['exams']->count() }} examen(s)
                                    </p>
                                </div>
                            </div>
                            @if($session['is_past'])
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Terminée
                            </span>
                            @endif
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
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase hidden lg:table-cell">Durée</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase hidden lg:table-cell">Salle</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($session['exams'] as $exam)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                {{-- Mobile info --}}
                                                <div class="md:hidden mt-2 space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                                    <div>📅 {{ $exam['date']->format('d/m/Y') }} à {{ $exam['time'] }}</div>
                                                    <div>⏱️ Durée: {{ $exam['duration'] }}</div>
                                                    <div>🏢 {{ $exam['room'] }}</div>
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
                                        {{ $exam['duration'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden lg:table-cell">
                                        {{ $exam['room'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                @endforeach
            </div>
        @endif

        {{-- Info Card --}}
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 rounded-lg p-6 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-blue-900 dark:text-blue-300 mb-2">À propos de l'historique</h3>
                    <div class="text-sm text-blue-800 dark:text-blue-300 space-y-1">
                        <p>• Cette page affiche toutes les convocations d'examens pour l'année universitaire sélectionnée</p>
                        <p>• Les sessions normale et de rattrapage sont affichées séparément</p>
                        <p>• Utilisez le menu déroulant pour consulter les convocations d'autres années</p>
                        <p>• Seules les convocations publiées par l'administration sont visibles</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
