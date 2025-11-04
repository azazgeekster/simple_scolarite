@extends("student.layouts.app")

@section("title", "Détails de la Réclamation")

@section("main_content")
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('reclamations.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux réclamations
            </a>
        </div>

        {{-- Status Header --}}
        <div class="bg-gradient-to-br
            @if($reclamation->status === 'PENDING') from-yellow-500 to-amber-600
            @elseif($reclamation->status === 'UNDER_REVIEW') from-blue-500 to-indigo-600
            @elseif($reclamation->status === 'RESOLVED') from-green-500 to-emerald-600
            @else from-red-500 to-rose-600
            @endif
            rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 rounded-lg text-xs font-bold text-white">
                            {{ $reclamation->getStatusLabel() }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 rounded-lg text-xs font-bold text-white">
                            {{ $reclamation->getReclamationTypeLabel() }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
                        {{ $reclamation->module->label }}
                    </h1>
                    <p class="text-white/90 text-sm font-medium">
                        {{ $reclamation->module->code }}
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                        <p class="text-xs text-white/90 mb-1 text-center">Soumise le</p>
                        <p class="text-lg font-bold text-white text-center">
                            {{ $reclamation->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grade Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Informations sur la Note
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Original Grade --}}
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 rounded-xl border-2 border-gray-200 dark:border-gray-700">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">Note Originale</p>
                        <p class="text-4xl font-bold
                            @if($reclamation->original_grade >= 10) text-gray-900 dark:text-white
                            @else text-red-600 dark:text-red-400
                            @endif">
                            {{ number_format($reclamation->original_grade, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">/20</p>
                    </div>

                    {{-- Arrow or Status --}}
                    <div class="flex items-center justify-center">
                        @if($reclamation->hasGradeChanged())
                            <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        @else
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-gray-500 dark:text-gray-400">En attente</p>
                            </div>
                        @endif
                    </div>

                    {{-- Revised Grade --}}
                    <div class="text-center p-6 bg-gradient-to-br
                        @if($reclamation->hasGradeChanged()) from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-green-200 dark:border-green-700
                        @else from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 border-gray-200 dark:border-gray-700
                        @endif
                        rounded-xl border-2">
                        <p class="text-xs font-semibold
                            @if($reclamation->hasGradeChanged()) text-green-700 dark:text-green-400
                            @else text-gray-500 dark:text-gray-400
                            @endif mb-2">Note Révisée</p>
                        @if($reclamation->revised_grade !== null)
                            <p class="text-4xl font-bold
                                @if($reclamation->revised_grade >= 10) text-green-600 dark:text-green-400
                                @else text-red-600 dark:text-red-400
                                @endif">
                                {{ number_format($reclamation->revised_grade, 2) }}
                            </p>
                            <p class="text-xs
                                @if($reclamation->hasGradeChanged()) text-green-600 dark:text-green-400
                                @else text-gray-500 dark:text-gray-400
                                @endif mt-1">/20</p>
                            @if($reclamation->hasGradeChanged())
                                <p class="text-xs font-bold text-green-600 dark:text-green-400 mt-2">
                                    {{ $reclamation->getGradeDifference() > 0 ? '+' : '' }}{{ number_format($reclamation->getGradeDifference(), 2) }} points
                                </p>
                            @endif
                        @else
                            <p class="text-4xl font-bold text-gray-400 dark:text-gray-600">--</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">/20</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Reclamation Details --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Votre Réclamation
                </h2>
            </div>

            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Type de réclamation</p>
                    <p class="text-base text-gray-900 dark:text-white">{{ $reclamation->getReclamationTypeLabel() }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Explication</p>
                    <div class="p-4 bg-gray-50 dark:bg-gray-750 rounded-xl">
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $reclamation->reason }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Response --}}
        @if($reclamation->admin_response)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r
                @if($reclamation->status === 'RESOLVED') from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20
                @else from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20
                @endif
                px-6 py-4 border-b
                @if($reclamation->status === 'RESOLVED') border-green-200 dark:border-green-800
                @else border-red-200 dark:border-red-800
                @endif">
                <h2 class="text-lg font-bold flex items-center gap-2
                    @if($reclamation->status === 'RESOLVED') text-green-900 dark:text-green-300
                    @else text-red-900 dark:text-red-300
                    @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Réponse de l'Administration
                </h2>
            </div>

            <div class="p-6">
                <div class="p-4 bg-gradient-to-br
                    @if($reclamation->status === 'RESOLVED') from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20
                    @else from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20
                    @endif
                    rounded-xl border-2
                    @if($reclamation->status === 'RESOLVED') border-green-200 dark:border-green-800
                    @else border-red-200 dark:border-red-800
                    @endif">
                    <p class="text-sm
                        @if($reclamation->status === 'RESOLVED') text-green-900 dark:text-green-100
                        @else text-red-900 dark:text-red-100
                        @endif
                        whitespace-pre-line">{{ $reclamation->admin_response }}</p>
                </div>

                @if($reclamation->reviewed_at)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                    Traitée le {{ \Carbon\Carbon::parse($reclamation->reviewed_at)->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm') }}
                </p>
                @endif
            </div>
        </div>
        @endif

        {{-- Timeline --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Historique
                </h2>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    {{-- Created --}}
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Réclamation soumise</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $reclamation->created_at->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm') }}
                            </p>
                        </div>
                    </div>

                    {{-- Under Review --}}
                    @if($reclamation->isUnderReview() || $reclamation->isClosed())
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Prise en charge</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($reclamation->reviewed_at)
                                    {{ \Carbon\Carbon::parse($reclamation->reviewed_at)->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm') }}
                                @else
                                    En cours d'examen
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Resolved/Rejected --}}
                    @if($reclamation->isClosed())
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                            @if($reclamation->status === 'RESOLVED') bg-green-100 dark:bg-green-900/30
                            @else bg-red-100 dark:bg-red-900/30
                            @endif">
                            <svg class="w-5 h-5
                                @if($reclamation->status === 'RESOLVED') text-green-600 dark:text-green-400
                                @else text-red-600 dark:text-red-400
                                @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($reclamation->status === 'RESOLVED')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold
                                @if($reclamation->status === 'RESOLVED') text-green-900 dark:text-green-300
                                @else text-red-900 dark:text-red-300
                                @endif">
                                {{ $reclamation->status === 'RESOLVED' ? 'Réclamation acceptée' : 'Réclamation rejetée' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($reclamation->reviewed_at)
                                    {{ \Carbon\Carbon::parse($reclamation->reviewed_at)->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3">
            @if($reclamation->isPending())
            <form action="{{ route('reclamations.destroy', $reclamation) }}" method="POST" class="flex-1"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réclamation ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuler la Réclamation
                </button>
            </form>
            @endif

            <a href="{{ route('reclamations.index') }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Voir Toutes les Réclamations
            </a>
        </div>

    </div>
</div>
@endsection
