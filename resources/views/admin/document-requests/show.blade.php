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

                    <!-- Generate/Download Attestation de Scolarité or Décharge -->
                    @if($demande->document->slug === 'attestation_scolarite')
                        <!-- Attestation Button -->
                        <a href="{{ route('admin.document-requests.attestation', $demande->id) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 {{ ($demande->isPicked() || $demande->isCompleted()) ? 'border border-purple-300 dark:border-purple-700 text-purple-700 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20' : 'bg-purple-600 text-white hover:bg-purple-700' }} text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($demande->isPicked() || $demande->isCompleted())
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                @endif
                            </svg>
                            {{ ($demande->isPicked() || $demande->isCompleted()) ? 'Télécharger Attestation' : 'Générer Attestation' }}
                        </a>
                    @else
                        <!-- Décharge Button (for other documents) -->
                        @if($demande->isPicked() || $demande->isCompleted())
                            <a href="{{ route('admin.document-requests.decharge', $demande->id) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 border border-amber-300 dark:border-amber-700 text-amber-700 dark:text-amber-400 text-sm font-medium rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Télécharger Décharge
                            </a>
                        @endif
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
                <!-- Student Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <!-- Header with gradient -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informations Étudiant
                            </h2>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Avatar and Name -->
                        <div class="text-center mb-6">
                            <div class="relative inline-block">
                                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-2xl font-bold shadow-lg ring-4 ring-white dark:ring-gray-800">
                                    {{ strtoupper(substr($demande->student->prenom ?? 'N', 0, 1) . substr($demande->student->nom ?? 'A', 0, 1)) }}
                                </div>
                                <!-- Status indicator -->
                                <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 border-4 border-white dark:border-gray-800 rounded-full" title="Actif"></div>
                            </div>
                            <h3 class="mt-3 text-lg font-bold text-gray-900 dark:text-white">
                                {{ $demande->student->full_name ?? 'N/A' }}
                            </h3>
                            @if($demande->student->programEnrollments->isNotEmpty())
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $demande->student->programEnrollments->first()->filiere->label_fr ?? '' }}
                                </p>
                            @endif
                        </div>

                        <!-- Contact & ID Information -->
                        <dl class="space-y-4">
                            <!-- CNE -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">CNE</dt>
                                    <dd class="mt-1 text-sm font-mono font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/50 px-2 py-1 rounded inline-block">
                                        {{ $demande->student->cne ?? 'N/A' }}
                                    </dd>
                                </div>
                            </div>

                            <!-- Apogée -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Code Apogée</dt>
                                    <dd class="mt-1 text-sm font-mono font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/50 px-2 py-1 rounded inline-block">
                                        {{ $demande->student->apogee ?? 'N/A' }}
                                    </dd>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white truncate" title="{{ $demande->student->email ?? 'N/A' }}">
                                        {{ $demande->student->email ?? 'N/A' }}
                                    </dd>
                                </div>
                            </div>
                        </dl>

                        <!-- Student Statistics -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Statistiques
                                </h4>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Total Requests -->
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                                    <div class="flex justify-center mb-2">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $studentHistory['total'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total</p>
                                </div>

                                <!-- Completed -->
                                <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                                    <div class="flex justify-center mb-2">
                                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $studentHistory['completed'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Complétés</p>
                                </div>

                                <!-- Pending Returns -->
                                <div class="text-center p-3 {{ $studentHistory['pending_returns'] > 0 ? 'bg-orange-50 dark:bg-orange-900/20' : 'bg-gray-50 dark:bg-gray-900/50' }} rounded-lg hover:{{ $studentHistory['pending_returns'] > 0 ? 'bg-orange-100 dark:bg-orange-900/30' : 'bg-gray-100 dark:bg-gray-900' }} transition-colors">
                                    <div class="flex justify-center mb-2">
                                        <div class="w-10 h-10 rounded-full {{ $studentHistory['pending_returns'] > 0 ? 'bg-orange-100 dark:bg-orange-900/30' : 'bg-gray-100 dark:bg-gray-800' }} flex items-center justify-center">
                                            <svg class="w-5 h-5 {{ $studentHistory['pending_returns'] > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold {{ $studentHistory['pending_returns'] > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-gray-400' }}">{{ $studentHistory['pending_returns'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">En attente</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.students.show', $demande->student->id ?? '#') }}" class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir le profil complet
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Other Requests -->
                @if($otherRequests->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Historique des demandes
                                </h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                                    {{ $otherRequests->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- List -->
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($otherRequests as $otherReq)
                                <a href="{{ route('admin.document-requests.show', $otherReq->id) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-150 group">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <!-- Reference Number -->
                                            <div class="flex items-center space-x-2 mb-2">
                                                <svg class="w-4 h-4 text-gray-400 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <span class="text-sm font-semibold text-amber-600 dark:text-amber-400 group-hover:text-amber-700 dark:group-hover:text-amber-300 transition-colors">
                                                    {{ $otherReq->reference_number }}
                                                </span>
                                            </div>

                                            <!-- Document Name -->
                                            <p class="text-sm text-gray-900 dark:text-white font-medium mb-2 group-hover:text-gray-700 dark:group-hover:text-gray-200 transition-colors">
                                                {{ $otherReq->document->label_fr ?? 'Document' }}
                                            </p>

                                            <!-- Meta Info -->
                                            <div class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $otherReq->created_at->format('d/m/Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $otherReq->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="flex-shrink-0 ml-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium shadow-sm
                                                @if($otherReq->status === 'PENDING') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                @elseif($otherReq->status === 'READY') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                @elseif($otherReq->status === 'PICKED') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                                @endif">
                                                {{ $otherReq->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- View All Link -->
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.document-requests.index', ['search' => $demande->student->cne ?? '']) }}" class="flex items-center justify-center text-sm font-medium text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors group">
                                <span>Voir toutes les demandes</span>
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
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
