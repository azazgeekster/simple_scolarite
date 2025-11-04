@extends("student.layouts.app")

@section("title", "Mes Réclamations")

@section("main_content")
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Mes Réclamations
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Gérez vos réclamations concernant vos notes
                    </p>
                </div>
                <a href="{{ route('student.grades') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle Réclamation
                </a>
            </div>
        </div>

        {{-- Active Reclamations --}}
        @if($groupedReclamations['active']->isNotEmpty())
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm font-bold text-white">Réclamations Actives</span>
                    <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $groupedReclamations['active']->count() }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                @foreach($groupedReclamations['active'] as $reclamation)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                        {{ $reclamation->module->label }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium">
                                        {{ $reclamation->module->code }}
                                    </p>
                                </div>
                                <span class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                                    @if($reclamation->status === 'PENDING') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                                    @elseif($reclamation->status === 'UNDER_REVIEW') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                    @endif">
                                    {{ $reclamation->getStatusLabel() }}
                                </span>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-4 sm:p-6">
                            <div class="space-y-4">
                                {{-- Type --}}
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Type de réclamation</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $reclamation->getReclamationTypeLabel() }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Original Grade --}}
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Note concernée</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ number_format($reclamation->original_grade, 2) }}/20
                                        </p>
                                    </div>
                                </div>

                                {{-- Date --}}
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Soumise le</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $reclamation->created_at->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-6 flex gap-3">
                                <a href="{{ route('reclamations.show', $reclamation) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Voir Détails
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Closed Reclamations --}}
        @if($groupedReclamations['closed']->isNotEmpty())
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    <span class="text-sm font-bold text-white">Réclamations Traitées</span>
                    <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $groupedReclamations['closed']->count() }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{-- Desktop Table --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-750">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Module</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Type</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Note</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Date</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($groupedReclamations['closed'] as $reclamation)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $reclamation->module->label }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $reclamation->module->code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $reclamation->getReclamationTypeLabel() }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ number_format($reclamation->original_grade, 2) }}
                                            </span>
                                            @if($reclamation->hasGradeChanged())
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                                <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                                    {{ number_format($reclamation->revised_grade, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold
                                            @if($reclamation->status === 'RESOLVED') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                            @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                            @endif">
                                            {{ $reclamation->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ $reclamation->created_at->locale('fr')->isoFormat('DD/MM/YYYY') }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('reclamations.show', $reclamation) }}"
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
                    @foreach($groupedReclamations['closed'] as $reclamation)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $reclamation->module->label }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reclamation->module->code }}</p>
                                </div>
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold
                                    @if($reclamation->status === 'RESOLVED') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                    @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                    @endif">
                                    {{ $reclamation->getStatusLabel() }}
                                </span>
                            </div>
                            <div class="space-y-1.5 text-xs text-gray-600 dark:text-gray-400 mb-3">
                                <p>📋 {{ $reclamation->getReclamationTypeLabel() }}</p>
                                <p>📊 Note: {{ number_format($reclamation->original_grade, 2) }}
                                    @if($reclamation->hasGradeChanged())
                                        → {{ number_format($reclamation->revised_grade, 2) }}
                                    @endif
                                </p>
                                <p>📅 {{ $reclamation->created_at->locale('fr')->isoFormat('DD/MM/YYYY') }}</p>
                            </div>
                            <a href="{{ route('reclamations.show', $reclamation) }}"
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
        @if($reclamations->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12">
            <div class="text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune réclamation</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Vous n'avez pas encore soumis de réclamation.
                </p>
                <a href="{{ route('student.grades') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Voir mes notes
                </a>
            </div>
        </div>
        @endif

        {{-- Info Note --}}
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    <p class="font-semibold mb-1">À propos des réclamations</p>
                    <ul class="space-y-1 text-xs">
                        <li>• Vous pouvez soumettre une réclamation pour chaque note que vous contestez</li>
                        <li>• Les réclamations sont examinées par l'administration dans les plus brefs délais</li>
                        <li>• Vous serez notifié dès que votre réclamation sera traitée</li>
                        <li>• Une seule réclamation par module est autorisée</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
