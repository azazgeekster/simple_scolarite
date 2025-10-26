@extends("student.layouts.app")

@section("title", "Mes Notes")

@section("main_content")
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header with Year Selector --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                {{-- Title --}}
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Mes Notes
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Consultez vos résultats par semestre
                    </p>
                </div>

                {{-- Year Selector --}}
                <div class="flex-shrink-0">
                    <form method="GET" action="{{ route('student.grades') }}" class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                        <label for="year-select" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-base font-semibold">Année universitaire</span>
                            </span>
                        </label>

                        <div class="relative group">
                            <select name="year" id="year-select" onchange="this.form.submit()"
                                class="block w-full md:w-96 pl-4 pr-11 py-3 text-sm font-medium
                                       border-2 border-gray-200 dark:border-gray-700
                                       bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900
                                       text-gray-900 dark:text-gray-100
                                       rounded-xl shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       hover:border-blue-400 dark:hover:border-blue-500
                                       hover:shadow-md transition-all duration-300 cursor-pointer appearance-none">
                                @foreach($allEnrollments as $historyEnrollment)
                                    <option value="{{ $historyEnrollment->academic_year }}"
                                            {{ $historyEnrollment->academic_year == $enrollment->academic_year ? 'selected' : '' }}>
                                        {{ $historyEnrollment->academic_year }}-{{ $historyEnrollment->academic_year + 1 }}
                                        • {{ $historyEnrollment->filiere->label_fr }}
                                        • {{ $historyEnrollment->year_in_program }}{{ $historyEnrollment->year_in_program == 1 ? 'ère' : 'ème' }} année
                                        @if($historyEnrollment->academicYear && $historyEnrollment->academicYear->is_current)
                                            ✓ En cours
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            {{-- Dropdown Icon --}}
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 transition-colors duration-300"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Helper Text --}}
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sélectionnez une année pour afficher vos notes
                        </p>
                    </form>
                </div>
            </div>
        </div>

        {{-- Current Enrollment Info Card --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-750 rounded-2xl p-4 sm:p-6 mb-6 border border-blue-100 dark:border-gray-700">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
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

        {{-- Grades by Semester --}}
        <div class="space-y-6">
            @foreach($gradesBySemester as $semester => $semesterData)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    {{-- Semester Header --}}
                    <div class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-50 dark:from-purple-900/30 dark:to-purple-800/30 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $semester }}</span>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                        Semestre {{ substr($semester, 1) }}
                                    </h2>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $semesterData['total_modules'] }} module(s)
                                    </p>
                                </div>
                            </div>

                            {{-- Semester Stats --}}
                            <div class="flex items-center gap-3 sm:gap-4">
                                @if($semesterData['average'])
                                <div class="text-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-xs font-medium text-gray-600 dark:text-gray-400">Moyenne</div>
                                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($semesterData['average'], 2) }}/20
                                    </div>
                                </div>
                                @endif

                                <div class="text-center px-3 py-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-xs font-medium text-gray-600 dark:text-gray-400">Validés</div>
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                        {{ $semesterData['passed_modules'] }}/{{ $semesterData['total_modules'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Session Normale --}}
                    @if($semesterData['normal_grades']->isNotEmpty())
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-semibold text-blue-800 dark:text-blue-300">Session Normale</span>
                            </div>
                        </div>

                        {{-- Desktop Table --}}
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-750">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Module</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Note</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($semesterData['normal_grades'] as $grade)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $grade->module->label }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $grade->module->code }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl font-bold text-xl shadow-sm
                                                    @if($grade->final_grade >= 16) bg-gradient-to-br from-green-500 to-emerald-600 text-white
                                                    @elseif($grade->final_grade >= 14) bg-gradient-to-br from-green-400 to-green-500 text-white
                                                    @elseif($grade->final_grade >= 12) bg-gradient-to-br from-blue-400 to-blue-500 text-white
                                                    @elseif($grade->final_grade >= 10) bg-gradient-to-br from-yellow-400 to-yellow-500 text-white
                                                    @else bg-gradient-to-br from-red-400 to-red-500 text-white
                                                    @endif">
                                                    {{ number_format($grade->final_grade, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                @php $status = $grade->validation_status ?? ['label' => 'Non noté', 'color' => 'gray']; @endphp
                                                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold
                                                    @if($status['color'] === 'green') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                                    @elseif($status['color'] === 'yellow') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                                                    @elseif($status['color'] === 'gray') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                    @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                                    @endif">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="block lg:hidden space-y-3">
                            @foreach($semesterData['normal_grades'] as $grade)
                                <div class="p-4 bg-gray-50 dark:bg-gray-750 rounded-xl">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $grade->module->label }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $grade->module->code }}</p>
                                        </div>
                                        <div class="flex-shrink-0 w-14 h-14 rounded-xl font-bold text-lg flex items-center justify-center shadow-sm
                                            @if($grade->final_grade >= 10) bg-gradient-to-br from-green-400 to-green-500 text-white
                                            @else bg-gradient-to-br from-red-400 to-red-500 text-white
                                            @endif">
                                            {{ number_format($grade->final_grade, 2) }}
                                        </div>
                                    </div>
                                    @php $status = $grade->validation_status ?? ['label' => 'Non noté', 'color' => 'gray']; @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold
                                        @if($status['color'] === 'green') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                        @elseif($status['color'] === 'yellow') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                                        @elseif($status['color'] === 'gray') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                        @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                        @endif">
                                        {{ $status['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Session Rattrapage --}}
                    @if($semesterData['rattrapage_grades']->isNotEmpty())
                    <div class="p-4 sm:p-6 bg-orange-50/50 dark:bg-orange-900/10 border-t-2 border-orange-200 dark:border-orange-800">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span class="text-sm font-semibold text-orange-800 dark:text-orange-300">Session de Rattrapage</span>
                            </div>
                        </div>

                        {{-- Desktop Table --}}
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-orange-100 dark:bg-orange-900/20">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Module</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Note</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-orange-100 dark:divide-orange-900/20">
                                    @foreach($semesterData['rattrapage_grades'] as $grade)
                                        <tr class="hover:bg-orange-50 dark:hover:bg-orange-900/10 transition-colors">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/30 dark:to-orange-800/30 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $grade->module->label }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $grade->module->code }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl font-bold text-xl shadow-sm
                                                    @if($grade->final_grade >= 10) bg-gradient-to-br from-blue-400 to-blue-500 text-white
                                                    @else bg-gradient-to-br from-red-400 to-red-500 text-white
                                                    @endif">
                                                    {{ number_format($grade->final_grade, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                @php $status = $grade->validation_status ?? ['label' => 'Non noté', 'color' => 'gray']; @endphp
                                                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold
                                                    @if($status['color'] === 'blue') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                                    @elseif($status['color'] === 'gray') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                    @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                                    @endif">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="block lg:hidden space-y-3">
                            @foreach($semesterData['rattrapage_grades'] as $grade)
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-orange-200 dark:border-orange-800">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $grade->module->label }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $grade->module->code }}</p>
                                        </div>
                                        <div class="flex-shrink-0 w-14 h-14 rounded-xl font-bold text-lg flex items-center justify-center shadow-sm
                                            @if($grade->final_grade >= 10) bg-gradient-to-br from-blue-400 to-blue-500 text-white
                                            @else bg-gradient-to-br from-red-400 to-red-500 text-white
                                            @endif">
                                            {{ number_format($grade->final_grade, 2) }}
                                        </div>
                                    </div>
                                    @php $status = $grade->validation_status ?? ['label' => 'Non noté', 'color' => 'gray']; @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold
                                        @if($status['color'] === 'blue') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                        @elseif($status['color'] === 'gray') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                        @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                        @endif">
                                        {{ $status['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Empty State --}}
                    @if($semesterData['normal_grades']->isEmpty() && $semesterData['rattrapage_grades']->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune note disponible pour ce semestre</p>
                    </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- Info Note --}}
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    <p class="font-semibold mb-1">À propos de vos notes</p>
                    <ul class="space-y-1 text-xs">
                        <li>• Les notes sont affichées dès leur validation par les professeurs</li>
                        <li>• Les modules avec note ≥ 10 sont validés</li>
                        <li>• Les notes entre 8 et 10 peuvent être validées par compensation</li>
                        <li>• La session de rattrapage est disponible pour les notes < 10</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection