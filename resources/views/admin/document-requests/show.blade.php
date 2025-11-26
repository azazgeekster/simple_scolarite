@extends('admin.layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('admin.document-requests.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors group">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour à la liste
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Definitive Withdrawal Warning -->
        @if($demande->isDefinitiveWithdrawal() && !$demande->isCompleted())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-700 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800 dark:text-red-300">RETRAIT DÉFINITIF</h3>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                            Ce retrait est définitif. Le statut d'étudiant sera suspendu après le retrait de ce document.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white">{{ $demande->reference_number }}</h1>
                        <p class="text-amber-100 text-sm mt-1">{{ $demande->document->label_fr ?? 'Document' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($demande->status === 'PENDING') bg-yellow-100 text-yellow-800
                            @elseif($demande->status === 'READY') bg-blue-100 text-blue-800
                            @elseif($demande->status === 'PICKED') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $demande->status_label }}
                        </span>
                        @if($demande->isOverdue())
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                    {{ $demande->getDaysOverdue() }} jour(s) de retard
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-3">
                    @if($demande->isPending())
                        <form id="markReadyForm" action="{{ route('admin.document-requests.mark-ready', $demande->id) }}" method="POST" class="inline">
                            @csrf
                            @if($demande->isTemporaire())
                                <input type="hidden" name="return_days" value="14">
                            @endif
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Marquer Prêt
                            </button>
                        </form>
                    @elseif($demande->isReady())
                        <form action="{{ route('admin.document-requests.mark-picked', $demande->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="generate_decharge" value="1">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Marquer Retiré + Décharge
                            </button>
                        </form>
                    @elseif($demande->isPicked() && $demande->isTemporaire())
                        <form action="{{ route('admin.document-requests.mark-completed', $demande->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                Document Retourné
                            </button>
                        </form>
                    @endif

                    <!-- Generate Décharge -->
                    @if($demande->isPicked() || $demande->isCompleted())
                        <a href="{{ route('admin.document-requests.decharge', $demande->id) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 border border-amber-300 dark:border-amber-700 text-amber-700 dark:text-amber-400 text-sm font-medium rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Télécharger Décharge
                        </a>
                    @endif

                    @if(!$demande->isPicked() && !$demande->isCompleted())
                        <form action="{{ route('admin.document-requests.destroy', $demande->id) }}" method="POST" class="inline ml-auto" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-400 text-sm font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Détails de la Demande</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Document</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $demande->document->label_fr ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type Document</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $demande->document->type === 'DEPOSITED' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                        {{ $demande->document->type_label ?? 'N/A' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type de Retrait</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $demande->retrait_type === 'temporaire' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ ucfirst($demande->retrait_type) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Année Académique</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $demande->academic_year }}/{{ $demande->academic_year + 1 }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Semestre</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $demande->semester ?? 'N/A' }}</dd>
                            </div>
                            @if($demande->must_return_by)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date limite retour</dt>
                                    <dd class="mt-1 text-sm {{ $demande->isOverdue() ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-900 dark:text-white' }}">
                                        {{ $demande->must_return_by->format('d/m/Y') }}
                                    </dd>
                                </div>
                            @endif
                            @if($demande->reason)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Motif</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $demande->reason }}</dd>
                                </div>
                            @endif
                            @if($demande->admin_notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes Admin</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded">{{ $demande->admin_notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Historique</h2>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <!-- Created -->
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Demande créée</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $demande->created_at->format('d/m/Y à H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                @if($demande->ready_at)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($demande->collected_at)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Document prêt</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $demande->ready_at->format('d/m/Y à H:i') }}
                                                        @if($demande->processedBy)
                                                            par {{ $demande->processedBy->name }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                @if($demande->collected_at)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($demande->returned_at)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Document retiré</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $demande->collected_at->format('d/m/Y à H:i') }}</p>
                                                    @if($demande->must_return_by && $demande->isTemporaire())
                                                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                                            À retourner avant le {{ $demande->must_return_by->format('d/m/Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                @if($demande->returned_at)
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Document retourné</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $demande->returned_at->format('d/m/Y à H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Extension Request -->
                @if($demande->extension_requested_at)
                    <div class="bg-purple-50 dark:bg-purple-900/20 border-2 border-purple-200 dark:border-purple-800 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-purple-800 dark:text-purple-300">Demande de Prolongation</h3>
                                <p class="mt-1 text-sm text-purple-700 dark:text-purple-400">
                                    L'étudiant a demandé une prolongation le {{ $demande->extension_requested_at->format('d/m/Y à H:i') }}
                                </p>
                                <div class="mt-4 flex space-x-3">
                                    <form action="{{ route('admin.document-requests.approve-extension', $demande->id) }}" method="POST" class="inline">
                                        @csrf
                                        <div class="flex items-center space-x-2">
                                            <input type="number" name="extension_days" value="7" min="1" max="30" class="w-20 rounded-md border-purple-300 text-sm">
                                            <button type="submit" class="px-3 py-1.5 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                                                Approuver
                                            </button>
                                        </div>
                                    </form>
                                    <form action="{{ route('admin.document-requests.reject-extension', $demande->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 border border-purple-300 text-purple-700 text-sm font-medium rounded-md hover:bg-purple-100">
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Student Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Étudiant</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xl font-bold">
                                {{ strtoupper(substr($demande->student->prenom ?? 'N', 0, 1) . substr($demande->student->nom ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nom</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $demande->student->full_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">CNE</dt>
                                <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">{{ $demande->student->cne ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Apogée</dt>
                                <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">{{ $demande->student->apogee ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white truncate">{{ $demande->student->email ?? 'N/A' }}</dd>
                            </div>
                            @if($demande->student->programEnrollments->isNotEmpty())
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Filière</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $demande->student->programEnrollments->first()->filiere->label_fr ?? 'N/A' }}
                                    </dd>
                                </div>
                            @endif
                        </dl>

                        <!-- Student History Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Historique</h4>
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $studentHistory['total'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-green-600">{{ $studentHistory['completed'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Complété</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold {{ $studentHistory['pending_returns'] > 0 ? 'text-orange-600' : 'text-gray-400' }}">{{ $studentHistory['pending_returns'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">À retourner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Requests -->
                @if($otherRequests->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Autres Demandes</h2>
                        </div>
                        <div class="p-4">
                            <ul class="space-y-3">
                                @foreach($otherRequests as $otherReq)
                                    <li>
                                        <a href="{{ route('admin.document-requests.show', $otherReq->id) }}" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400">{{ $otherReq->reference_number }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $otherReq->document->label_fr ?? 'Document' }}</p>
                                            <div class="flex items-center justify-between mt-2">
                                                <span class="px-2 py-0.5 text-xs rounded-full
                                                    @if($otherReq->status === 'PENDING') bg-yellow-100 text-yellow-800
                                                    @elseif($otherReq->status === 'READY') bg-blue-100 text-blue-800
                                                    @elseif($otherReq->status === 'PICKED') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $otherReq->status_label }}
                                                </span>
                                                <span class="text-xs text-gray-400">{{ $otherReq->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle mark ready form
    const markReadyForm = document.getElementById('markReadyForm');
    if (markReadyForm) {
        markReadyForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const button = this.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;

            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Traitement...';

            // Get form data
            const formData = new FormData(this);

            // Send AJAX request
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('success', data.message);

                    // Reload page after short delay to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', error.message || 'Erreur lors de la mise à jour');

                // Re-enable button
                button.disabled = false;
                button.innerHTML = originalContent;
            });
        });
    }

    // Handle mark picked form
    const markPickedForms = document.querySelectorAll('form[action*="mark-picked"]');
    markPickedForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const button = this.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Traitement...';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', error.message || 'Erreur lors de la mise à jour');
                button.disabled = false;
                button.innerHTML = originalContent;
            });
        });
    });

    // Handle mark completed form
    const markCompletedForms = document.querySelectorAll('form[action*="mark-completed"]');
    markCompletedForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const button = this.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Traitement...';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', error.message || 'Erreur lors de la mise à jour');
                button.disabled = false;
                button.innerHTML = originalContent;
            });
        });
    });
});

// Notification function
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success'
            ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800'
            : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800'
    }`;

    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 ${type === 'success' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success'
                    ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                }
            </svg>
            <p class="ml-3 text-sm font-medium ${type === 'success' ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300'}">${message}</p>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>
@endsection
