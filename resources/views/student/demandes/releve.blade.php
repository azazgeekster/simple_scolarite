@extends("student.layouts.app")

@section("title", "Demande de Relevé de Notes")
@php
    $releveDocument = \App\Models\Document::where('slug', 'releve_notes')->first();
    $requiresReturn = $releveDocument ? $releveDocument->requires_return : true;
@endphp

@section("main_content")
    <x-flash-message type="error"></x-flash-message>
    <x-flash-message type="success"></x-flash-message>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 py-6 px-4 sm:px-6 lg:px-8" x-data="transcriptRequest()">
        <div class="max-w-7xl mx-auto">

            {{-- Header Section --}}
            <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 mb-6 sm:mb-8">
                {{-- Decorative background --}}
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-blue-400/20 to-indigo-400/20 dark:from-blue-600/10 dark:to-indigo-600/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-gradient-to-tr from-purple-400/20 to-pink-400/20 dark:from-purple-600/10 dark:to-pink-600/10 rounded-full blur-3xl"></div>
                </div>

                <div class="relative px-6 sm:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl blur-lg opacity-50"></div>
                                <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600 p-3 sm:p-4 rounded-2xl shadow-lg">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-1 sm:mb-2 tracking-tight">
                                Demande de Relevé de Notes
                            </h1>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                                Sélectionnez l'année académique pour laquelle vous souhaitez obtenir un relevé officiel
                            </p>
                        </div>

                        {{-- Stats badge (desktop only) --}}
                        <div class="hidden lg:flex flex-col items-end gap-2">
                            <div class="bg-blue-50 dark:bg-blue-900/30 px-4 py-2 rounded-xl border border-blue-100 dark:border-blue-800">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ count($availableReleves) }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Disponibles</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- Available Transcripts Section --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">

                        {{-- Section Header --}}
                        <div class="bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-800 dark:to-gray-750 px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-xl">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">Relevés Disponibles</h2>
                                </div>
                                <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                    {{ count($availableReleves) }} {{ count($availableReleves) > 1 ? 'relevés' : 'relevé' }}
                                </span>
                            </div>
                        </div>

                        {{-- Transcripts List --}}
                        <div class="p-4 sm:p-6">
                            <div class="space-y-3 sm:space-y-4">
                                @forelse($availableReleves as $index => $releve)
                                    <div class="group relative overflow-hidden border-2 {{ $releve['disponible'] ? 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-500' : 'border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/30' }} rounded-xl sm:rounded-2xl transition-all duration-300 {{ $releve['disponible'] ? 'hover:shadow-lg cursor-pointer' : 'opacity-60' }}"
                                        @if($releve['disponible'])
                                            @click="openModal(
                                                '{{ $releve['academic_year_label'] }} ({{ $releve['semesters'] }})',
                                                '{{ $releve['year_label'] }}',
                                                {{ $releve['academic_year'] }},
                                                '{{ $releve['semesters'] }}'
                                            )"
                                        @endif>

                                        {{-- Hover gradient overlay --}}
                                        @if($releve['disponible'])
                                            <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-900/10 dark:to-indigo-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        @endif

                                        <div class="relative p-4 sm:p-6">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                                {{-- Content --}}
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-3">
                                                        <h3 class="font-bold text-base sm:text-lg text-gray-900 dark:text-white">
                                                            {{ $releve['academic_year_label'] }}
                                                        </h3>
                                                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2.5 py-1 rounded-lg text-xs sm:text-sm font-medium whitespace-nowrap">
                                                            {{ $releve['semesters'] }}
                                                        </span>
                                                    </div>

                                                    <div class="space-y-1.5">
                                                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                            </svg>
                                                            <span class="break-words">{{ $releve['filiere_label'] }}</span>
                                                        </p>
                                                        <p class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-2">
                                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                            </svg>
                                                            {{ $releve['year_label'] }}
                                                        </p>
                                                    </div>
                                                </div>

                                                {{-- Status & Action --}}
                                                <div class="flex sm:flex-col items-center sm:items-end gap-3">
                                                    <span class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-bold rounded-full whitespace-nowrap {{ $releve['disponible'] ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                                        {{ $releve['disponible'] ? '✓ Disponible' : '⏳ Non disponible' }}
                                                    </span>

                                                    @if($releve['disponible'])
                                                        <div class="hidden sm:flex items-center gap-2 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                            <span class="text-sm font-medium">Demander</span>
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 sm:py-20">
                                        <div class="relative inline-block">
                                            <div class="absolute inset-0 bg-gray-200 dark:bg-gray-700 rounded-full blur-2xl opacity-50"></div>
                                            <svg class="relative w-16 h-16 sm:w-20 sm:h-20 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucun relevé disponible</h3>
                                        <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">Les relevés seront disponibles après validation de vos résultats</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- My Requests Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden lg:sticky lg:top-6">

                        {{-- Sidebar Header --}}
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-750 px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="bg-purple-100 dark:bg-purple-900/50 p-2 rounded-xl">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Mes Demandes</h2>
                                <span class="ml-auto bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 px-2.5 py-1 rounded-full text-xs font-bold">
                                    {{ count($studentDemandes) }}
                                </span>
                            </div>
                        </div>

                        {{-- Requests List --}}
                        <div class="p-4 max-h-[400px] lg:max-h-[600px] overflow-y-auto">
                            @forelse($studentDemandes as $demande)
                                <div class="mb-3 p-4 bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-700 dark:to-gray-750 rounded-xl border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-300">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-sm sm:text-base text-gray-900 dark:text-white truncate">
                                                {{ $demande->academicYear->label ?? $demande->academic_year . '-' . ($demande->academic_year + 1) }}
                                            </p>
                                            @if($demande->semester)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ $demande->semester }}</p>
                                            @else
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Année complète</p>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs font-bold rounded-lg whitespace-nowrap flex-shrink-0 ml-2
                                            {{ $demande->status === 'PENDING' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                            {{ $demande->status === 'READY' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                                            {{ $demande->status === 'PICKED' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                            {{ $demande->status === 'COMPLETED' ? 'bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-300' : '' }}">
                                            {{ $demande->status }}
                                        </span>
                                    </div>
                                    <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                        <p class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            <span>Type: <span class="font-medium">{{ ucfirst($demande->retrait_type) }}</span></span>
                                        </p>
                                        <p class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $demande->created_at->format('d/m/Y à H:i') }}</span>
                                        </p>
                                        @if($demande->reference_number)
                                            <p class="flex items-center gap-1.5 font-mono">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                </svg>
                                                <span class="truncate">{{ $demande->reference_number }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="relative inline-block mb-4">
                                        <div class="absolute inset-0 bg-gray-200 dark:bg-gray-700 rounded-full blur-xl opacity-50"></div>
                                        <svg class="relative w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Aucune demande en cours</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Vos demandes apparaîtront ici</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal for Request Form --}}
        <div x-show="showModal"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             style="display: none;">

            <div @click.away="showModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden max-h-[90vh] flex flex-col">

                {{-- Modal Header --}}
                <div class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 px-6 sm:px-8 py-6 text-white flex-shrink-0">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1 pr-4">
                            <h3 class="text-xl sm:text-2xl font-bold mb-1">Confirmer la Demande</h3>
                            <p class="text-blue-100 text-sm">Choisissez les détails de votre relevé</p>
                        </div>
                        <button @click="showModal = false" class="flex-shrink-0 text-white/80 hover:text-white transition-colors p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('student.releve.store') }}" class="flex-1 overflow-y-auto">
                    @csrf

                    <div class="p-6 sm:p-8 space-y-6">
                        {{-- Selected Year Info --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-750 rounded-2xl p-4 border border-blue-100 dark:border-gray-600">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-xl">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 dark:text-white text-sm sm:text-base truncate" x-text="selectedYearLabel"></p>
                                    <p class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 font-medium mt-0.5" x-text="selectedLevelLabel"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Field --}}
                        <input type="hidden" name="academic_year" x-model="selectedAcademicYear">

                        {{-- Important Notices --}}
                        <div class="space-y-3">
                            <!-- Cancellation Notice -->
                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-3">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-amber-900 dark:text-amber-200 mb-1">Délai d'annulation : 5 minutes</p>
                                        <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                            Vous pouvez annuler votre demande dans les 5 minutes suivant sa soumission.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Info: Document will be given directly -->
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-3">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-emerald-900 dark:text-emerald-200 mb-1">Relevé définitif</p>
                                        <p class="text-xs text-emerald-800 dark:text-emerald-300 leading-relaxed">
                                            Le relevé de notes vous sera remis définitivement, aucun retour nécessaire.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Semester Selection --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-900 dark:text-white mb-4">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Sélectionnez la période</span>
                            </label>

                            <div class="space-y-3">
                                {{-- Full Year Option --}}
                                <label class="relative flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20 group">
                                    <input type="radio" name="semester" value="" class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0" checked>
                                    <div class="ml-3 flex-1">
                                        <span class="block font-medium text-gray-900 dark:text-white group-has-[:checked]:text-blue-700 dark:group-has-[:checked]:text-blue-400">
                                            Année complète
                                        </span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400 mt-0.5" x-text="'(' + selectedSemesters + ')'"></span>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-600 opacity-0 group-has-[:checked]:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </label>

                                {{-- Individual Semesters --}}
                                <template x-if="selectedSemesters">
                                    <div class="grid grid-cols-2 gap-3">
                                        <template x-for="sem in getSemesterOptions()" :key="sem">
                                            <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20 group">
                                                <input type="radio" name="semester" :value="sem" class="sr-only">
                                                <div class="text-center">
                                                    <span class="block font-bold text-gray-900 dark:text-white group-has-[:checked]:text-blue-700 dark:group-has-[:checked]:text-blue-400 text-lg" x-text="sem"></span>
                                                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Semestre</span>
                                                </div>
                                                <svg class="absolute top-2 right-2 w-5 h-5 text-blue-600 opacity-0 group-has-[:checked]:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </label>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer with Buttons --}}
                    <div class="flex-shrink-0 bg-gray-50 dark:bg-gray-750 px-6 sm:px-8 py-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col-reverse sm:flex-row gap-3">
                            <button type="button"
                                    @click="showModal = false"
                                    class="flex-1 px-6 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center gap-2">
                                <span>Confirmer la demande</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function transcriptRequest() {
            return {
                showModal: false,
                selectedYearLabel: '',
                selectedLevelLabel: '',
                selectedAcademicYear: null,
                selectedSemesters: '',

                openModal(yearLabel, levelLabel, academicYear, semesters) {
                    this.selectedYearLabel = yearLabel;
                    this.selectedLevelLabel = levelLabel;
                    this.selectedAcademicYear = academicYear;
                    this.selectedSemesters = semesters;
                    this.showModal = true;

                    // Prevent body scroll when modal is open
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.showModal = false;
                    document.body.style.overflow = '';
                },

                getSemesterOptions() {
                    // Parse semesters string like "S1-S2" or "S3-S4"
                    if (!this.selectedSemesters) return [];

                    const parts = this.selectedSemesters.split('-');
                    if (parts.length !== 2) return [];

                    return [parts[0], parts[1]];
                },

                init() {
                    // Close modal on escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && this.showModal) {
                            this.closeModal();
                        }
                    });
                }
            }
        }
    </script>
    @endpush

    <style>
        /* Custom scrollbar for sidebar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Alpine cloak */
        [x-cloak] {
            display: none !important;
        }

        /* Smooth transitions */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        /* Better focus states for accessibility */
        button:focus-visible,
        a:focus-visible,
        input:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
    </style>
@endsection