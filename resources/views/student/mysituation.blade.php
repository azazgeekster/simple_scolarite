@extends("student.layouts.app")

@section("title", "Ma Situation Pédagogique")

@section("main_content")
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        {{-- Page Header with Year Selector --}}
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                Ma Situation Pédagogique
            </h1>
            <p class="text-gray-600">
                Consultez votre parcours académique et vos modules
            </p>
        </div>

        {{-- Year Selector Dropdown --}}
        @if($allEnrollments->count() > 0)
        <div class="flex-shrink-0">
            <form method="GET" action="{{ route('student.mysituation') }}" class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
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

                    <!-- Dropdown Icon -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 transition-colors duration-300"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Helper Text -->
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Sélectionnez une année pour afficher vos informations académiques
                </p>
            </form>
        </div>

        @else
            <div class="text-right">
                <div class="text-sm text-gray-600 mb-1">Année universitaire</div>
                <div class="text-2xl font-bold text-gray-900">
                    {{ $enrollment->academic_year }}-{{ $enrollment->academic_year + 1 }}
                </div>
            </div>
        @endif
    </div>

    {{-- Mini Timeline Indicator (Optional but nice) --}}
    @if($allEnrollments->count() > 1)
        <div class="mt-6 bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between text-sm mb-3">
                <span class="font-medium text-gray-700">Mon parcours</span>
                <span class="text-gray-500">{{ $allEnrollments->count() }} année(s)</span>
            </div>
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                @foreach($allEnrollments->reverse() as $historyEnrollment)
                    <a href="{{ route('student.mysituation', ['year' => $historyEnrollment->academic_year]) }}"
                       class="group relative flex-shrink-0 transition-all duration-200"
                       title="{{ $historyEnrollment->academic_year }}-{{ $historyEnrollment->academic_year + 1 }}">
                        <div class="flex flex-col items-center">
                            {{-- Year Dot --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-200
                                        {{ $historyEnrollment->academic_year == $enrollment->academic_year
                                           ? 'bg-blue-600 text-white ring-4 ring-blue-200 scale-110'
                                           : 'bg-gray-200 text-gray-600 group-hover:bg-gray-300' }}">
                                {{ $historyEnrollment->year_in_program }}
                            </div>
                            {{-- Year Label --}}
                            <div class="mt-1 text-xs font-medium whitespace-nowrap
                                        {{ $historyEnrollment->academic_year == $enrollment->academic_year
                                           ? 'text-blue-600'
                                           : 'text-gray-500' }}">
                                {{ $historyEnrollment->academic_year }}
                            </div>
                            {{-- Current Badge --}}
                            @if($historyEnrollment->academicYear && $historyEnrollment->academicYear->is_current)
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse ring-2 ring-white"></span>
                            @endif
                        </div>
                    </a>

                    {{-- Connector Line --}}
                    @if(!$loop->last)
                        <div class="h-0.5 w-6 bg-gray-300 flex-shrink-0 -mx-1 mb-4"></div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>

        {{-- Student Enrollment Card --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center border-4 border-white/30">
                            <span class="text-3xl font-bold text-white uppercase">
                                {{ substr($student->prenom, 0, 1) }}{{ substr($student->nom, 0, 1) }}
                            </span>
                        </div>

                        <div class="text-white">
                            <h2 class="text-2xl font-bold">{{ $student->full_name }}</h2>
                            <p class="text-blue-100 mt-1">CNE: {{ $student->cne }} • Apogée: {{ $student->apogee }}</p>
                        </div>
                    </div>
                    <div class="text-right text-white">
                        <div class="text-sm opacity-90 mb-1">Statut</div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-400 text-green-900">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Actif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Enrollment Details --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8 bg-gray-50">
                {{-- Filiere --}}
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-500 font-medium mb-1">Filière</div>
                        <div class="text-lg font-bold text-gray-900">{{ $enrollment->filiere->label_fr }}</div>
                        @if($enrollment->filiere->label_ar)
                        <div class="text-sm text-gray-600 text-right mt-1">{{ $enrollment->filiere->label_ar }}</div>
                        @endif
                        <div class="inline-flex items-center mt-2 px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                            {{ $enrollment->filiere->code }}
                        </div>
                    </div>
                </div>

                {{-- Department --}}
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-500 font-medium mb-1">Département</div>
                        <div class="text-lg font-bold text-gray-900">
                            {{ $enrollment->filiere->department->label ?? 'N/A' }}
                        </div>
                        @if($enrollment->filiere->coordinator)
                        <div class="text-sm text-gray-600 mt-2">
                            <span class="text-gray-500">Coordinateur:</span><br>
                            {{ $enrollment->filiere->coordinator->prenom }} {{ $enrollment->filiere->coordinator->nom }}
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Academic Year --}}
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-500 font-medium mb-1">Année Universitaire</div>
                        <div class="text-lg font-bold text-gray-900">
                            {{ $enrollment->academicYear->label }}
                        </div>
                        <div class="text-sm text-gray-600 mt-2">
                            <span class="text-gray-500">Niveau:</span>
                            <span class="font-semibold">{{ $enrollment->year_label }}</span>
                        </div>

                        @if($enrollment->enrollment_date)
                        <div class="text-xs text-gray-500 mt-1">
                            Inscrit le {{ $enrollment->enrollment_date->format('d/m/Y') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Modules Section --}}
        <div class="space-y-6">
            @foreach($modulesBySemester as $semesterCode => $modules)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                {{-- Semester Header --}}

                <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $semesterCode }}</span>
                            </div>
                            <div class="text-white">
                                <h3 class="text-xl font-bold">Semestre {{ substr($semesterCode, 1) }}</h3>
                                <p class="text-indigo-100 text-sm">{{ $modules->count() }} module(s)</p>
                            </div>
                        </div>
                        <div class="text-white text-right">
                            <div class="text-sm opacity-80">Modules à étudier</div>
                            <div class="text-2xl font-bold">{{ $modules->count() }}</div>
                        </div>
                    </div>
                </div>

                {{-- Modules Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Code
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Intitulé du Module
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Professeur Responsable
                                </th>
                                {{-- <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Crédits
                                </th> --}}
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Type
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($modules as $module)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Code --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-blue-100 text-blue-800">
                                        {{ $module->code }}
                                    </span>
                                </td>

                                {{-- Module Name --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $module->label }}
                                    </div>
                                    @if($module->label_ar)
                                    <div class="text-xs text-gray-500 mt-1 text-right">
                                        {{ $module->label_ar }}
                                    </div>
                                    @endif

                                    {{-- Module Status Badges --}}
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        {{-- Validation Status --}}
                                        @if(isset($module->validation_status))
                                            @if($module->validation_status === 'validated')
                                                <span class="inline-flex items-center text-xs font-semibold text-green-700 bg-green-100 px-2 py-1 rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Validé ({{ number_format($module->validated_grade, 2) }}/20)
                                                    @if($module->validated_session === 'rattrapage')
                                                        - Après Ratt.
                                                    @endif
                                                </span>
                                            @elseif($module->validation_status === 'retake')
                                                <span class="inline-flex items-center text-xs font-semibold text-orange-700 bg-orange-100 px-2 py-1 rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Rattrapage ({{ number_format($module->previous_grade, 2) }}/20)
                                                </span>
                                            @elseif($module->validation_status === 'blocked')
                                                <span class="inline-flex items-center text-xs font-semibold text-red-700 bg-red-100 px-2 py-1 rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Bloqué
                                                </span>
                                            @elseif($module->validation_status === 'in_progress')
                                                <span class="inline-flex items-center text-xs font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    En cours
                                                </span>
                                            @endif
                                        @endif

                                        {{-- Attempt Number if retake --}}
                                        @if(isset($module->attempt_number) && $module->attempt_number > 1)
                                            <span class="inline-flex items-center text-xs font-semibold text-purple-700 bg-purple-100 px-2 py-1 rounded">
                                                Tentative {{ $module->attempt_number }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Prerequisite info --}}
                                    @if($module->prerequisite)
                                    <div class="mt-2 inline-flex items-center text-xs @if(isset($module->prerequisite_validated) && !$module->prerequisite_validated) text-red-600 bg-red-50 @else text-orange-600 bg-orange-50 @endif px-2 py-1 rounded">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        Prérequis: {{ $module->prerequisite->code }}
                                        @if(isset($module->prerequisite_validated))
                                            @if($module->prerequisite_validated)
                                                <svg class="w-3 h-3 ml-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 ml-1 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                    @endif
                                </td>

                                {{-- Professor --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($module->professor)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-600">
                                                    {{ substr($module->professor->prenom, 0, 1) }}{{ substr($module->professor->nom, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $module->professor->prenom }} {{ $module->professor->nom }}
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-400 italic">Non assigné</span>
                                    @endif
                                </td>

                                {{-- Credits --}}
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-800 font-bold text-sm">
                                        {{ $module->credits ?? 3 }}
                                    </span>
                                </td> --}}

                                {{-- Type --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($module->is_optional)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Optionnel
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Obligatoire
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Aucun module disponible pour ce semestre</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Semester Footer --}}
                {{-- <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6 text-sm text-gray-600">
                            <div class="flex items-center">
                                <span class="font-semibold text-gray-900">{{ $modules->count() }}</span>
                                <span class="ml-1">module(s)</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-semibold text-gray-900">{{ $modules->sum('credits') ?? $modules->count() * 3 }}</span>
                                <span class="ml-1">crédits ECTS</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-semibold text-gray-900">{{ $modules->where('is_optional', false)->count() }}</span>
                                <span class="ml-1">obligatoire(s)</span>
                            </div>
                            @if($modules->where('is_optional', true)->count() > 0)
                            <div class="flex items-center">
                                <span class="font-semibold text-gray-900">{{ $modules->where('is_optional', true)->count() }}</span>
                                <span class="ml-1">optionnel(s)</span>
                            </div>
                            @endif
                        </div>

                    </div>
                </div> --}}
            </div>
            @endforeach
        </div>

        {{-- Info Cards --}}
        <div class="mt-8 space-y-4">
            {{-- Status Legend --}}
            {{-- <div class="bg-gradient-to-br from-purple-50 to-blue-50 border-l-4 border-purple-400 rounded-lg p-6 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-bold text-purple-900 mb-3">Légende des statuts de validation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div class="flex items-start gap-2">
                                <span class="inline-flex items-center text-xs font-semibold text-green-700 bg-green-100 px-2 py-1 rounded mt-0.5">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Validé
                                </span>
                                <span class="text-gray-700 flex-1">Module réussi (note ≥ 10/20) en session normale ou de rattrapage</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="inline-flex items-center text-xs font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded mt-0.5">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    En cours
                                </span>
                                <span class="text-gray-700 flex-1">Module en cours d'étude cette année (première tentative)</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="inline-flex items-center text-xs font-semibold text-orange-700 bg-orange-100 px-2 py-1 rounded mt-0.5">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                    </svg>
                                    Rattrapage
                                </span>
                                <span class="text-gray-700 flex-1">Module non validé l'année précédente, à repasser cette année</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="inline-flex items-center text-xs font-semibold text-red-700 bg-red-100 px-2 py-1 rounded mt-0.5">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Bloqué
                                </span>
                                <span class="text-gray-700 flex-1">Module non accessible car le prérequis n'est pas validé</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- Prerequisites Explanation --}}
            <div class="bg-gradient-to-br from-orange-50 to-yellow-50 border-l-4 border-orange-400 rounded-lg p-6 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-bold text-orange-900 mb-2">Système de prérequis entre semestres homologues</h3>
                        <div class="text-sm text-orange-800 space-y-1.5">
                            <p>• Les modules ont des relations de prérequis entre semestres homologues : <strong>S1→S3→S5</strong> et <strong>S2→S4→S6</strong></p>
                            <p>• Si un module du S1 n'est pas validé, vous devez le repasser en S3 et le module homologue du S3 sera bloqué</p>
                            <p>• Exemple : Si vous échouez au module M1 du S1, vous repasserez M1 en S3, mais vous ne pourrez pas suivre le module M7 du S3 qui dépend de M1</p>
                            <p>• Les modules avec prérequis sont clairement indiqués avec un badge orange ou rouge selon leur statut de validation</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- General Information --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-6 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-blue-900 mb-2">À propos de votre situation pédagogique</h3>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p>• Cette page présente les modules que vous devez étudier cette année universitaire</p>
                            @if($allEnrollments->count() > 1)
                                <p>• Utilisez le menu déroulant ci-dessus pour consulter vos inscriptions des années précédentes</p>
                            @endif
                            <p>• Pour consulter vos notes et résultats, rendez-vous dans la section "Mes Notes"</p>
                            <p>• Les convocations d'examens seront disponibles avant chaque session (normale et rattrapage)</p>
                            <p>• Pour toute question sur votre inscription, contactez le service de scolarité</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection