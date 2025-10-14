@extends("student.layouts.app")

@section("title", "Ma Situation Pédagogique")

@section("main_content")
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                Ma Situation Pédagogique
            </h1>
            <p class="text-gray-600">
                Année universitaire {{ $enrollment->academicYear->label }}
            </p>
        </div>

        {{-- Student Enrollment Card --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center border-4 border-white/30">
                            <span class="text-3xl font-bold text-white">{{ $enrollment->year_label }}</span>
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
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
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
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Crédits
                                </th>
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
                                    {{-- Prerequisite info --}}
                                    @if($module->prerequisite)
                                    <div class="mt-2 inline-flex items-center text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        Prérequis: {{ $module->prerequisite->code }}
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
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-800 font-bold text-sm">
                                        {{ $module->credits ?? 3 }}
                                    </span>
                                </td>

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
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
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
                        <button onclick="window.print()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Imprimer
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Info Card --}}
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 rounded-lg p-6 shadow-sm">
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
                        <p>• Pour consulter vos notes et résultats, rendez-vous dans la section "Mes Notes"</p>
                        <p>• Les emplois du temps seront communiqués par votre département</p>
                        <p>• Pour toute question sur votre inscription, contactez le service de scolarité</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection