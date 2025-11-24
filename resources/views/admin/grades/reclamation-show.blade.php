@extends('admin.layouts.app')

@section('title', 'Détails Réclamation')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Détails de la Réclamation</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Réclamation #{{ $reclamation->id }}</p>
        </div>
        <a href="{{ route('admin.grades.reclamations') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Retour
        </a>
    </div>

    <x-flash-message type="success" />
    <x-flash-message type="error" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student & Module Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Étudiant</h4>
                        <div class="space-y-1">
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $reclamation->moduleGrade->moduleEnrollment->student->nom }}
                                {{ $reclamation->moduleGrade->moduleEnrollment->student->prenom }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                CNE: {{ $reclamation->moduleGrade->moduleEnrollment->student->cne }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Apogée: {{ $reclamation->moduleGrade->moduleEnrollment->student->apogee }}
                            </p>
                        </div>
                    </div>

                    <!-- Module -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Module</h4>
                        <div class="space-y-1">
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $reclamation->moduleGrade->moduleEnrollment->module->code }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $reclamation->moduleGrade->moduleEnrollment->module->label }}
                            </p>
                            @if($reclamation->moduleGrade->moduleEnrollment->module->professor)
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Prof: {{ $reclamation->moduleGrade->moduleEnrollment->module->professor->name ?? 'N/A' }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Filiere -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Filière</h4>
                        <p class="text-gray-900 dark:text-white">
                            {{ $reclamation->moduleGrade->moduleEnrollment->programEnrollment->filiere->label_fr }}
                        </p>
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Année Académique</h4>
                        <p class="text-gray-900 dark:text-white">
                            {{ $reclamation->moduleGrade->moduleEnrollment->programEnrollment->academic_year }}-{{ $reclamation->moduleGrade->moduleEnrollment->programEnrollment->academic_year + 1 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Grade Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Note Concernée</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="text-2xl font-bold {{ $reclamation->moduleGrade->grade >= 10 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($reclamation->moduleGrade->grade, 2) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Note Actuelle</div>
                    </div>

                    @if($reclamation->revised_grade)
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ number_format($reclamation->revised_grade, 2) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Note Révisée</div>
                        </div>
                    @endif

                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white capitalize">
                            {{ $reclamation->session }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Session</div>
                    </div>

                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $reclamation->moduleGrade->result ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Résultat</div>
                    </div>
                </div>
            </div>

            <!-- Reclamation Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Détails de la Réclamation</h3>

                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Type de Réclamation</h4>
                        <p class="text-gray-900 dark:text-white">
                            @php
                                $types = [
                                    'grade_calculation_error' => 'Erreur de calcul',
                                    'missing_grade' => 'Note manquante',
                                    'transcription_error' => 'Erreur de transcription',
                                    'exam_paper_review' => 'Révision de copie',
                                    'other' => 'Autre',
                                ];
                            @endphp
                            {{ $types[$reclamation->reclamation_type] ?? $reclamation->reclamation_type }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Message de l'Étudiant</h4>
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $reclamation->description }}</p>
                        </div>
                    </div>

                    @if($reclamation->admin_response)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Réponse Administrative</h4>
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $reclamation->admin_response }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statut</h3>

                <div class="text-center mb-4">
                    @switch($reclamation->status)
                        @case('PENDING')
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 rounded-full">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                En attente
                            </span>
                            @break
                        @case('UNDER_REVIEW')
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                En révision
                            </span>
                            @break
                        @case('RESOLVED')
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Résolue
                            </span>
                            @break
                        @case('REJECTED')
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-full">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Rejetée
                            </span>
                            @break
                    @endswitch
                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                    <p>Créée: {{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                    @if($reclamation->updated_at != $reclamation->created_at)
                        <p>Mise à jour: {{ $reclamation->updated_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if(in_array($reclamation->status, ['PENDING', 'UNDER_REVIEW']))
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>

                    <div class="space-y-4">
                        @if($reclamation->status === 'PENDING')
                            <!-- Mark as Under Review -->
                            <form method="POST" action="{{ route('admin.grades.reclamations.review', $reclamation->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="admin_response" rows="2" placeholder="Commentaire (optionnel)..."
                                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"></textarea>
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                                    Mettre en Révision
                                </button>
                            </form>
                        @endif

                        <!-- Resolve -->
                        <form method="POST" action="{{ route('admin.grades.reclamations.resolve', $reclamation->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nouvelle Note</label>
                                <input type="number" name="revised_grade" step="0.01" min="0" max="20" required
                                       value="{{ $reclamation->moduleGrade->grade }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Justification</label>
                                <textarea name="admin_response" rows="3" required placeholder="Expliquez la décision..."
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm">
                                Résoudre et Mettre à Jour
                            </button>
                        </form>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">ou</span>
                            </div>
                        </div>

                        <!-- Reject -->
                        <form method="POST" action="{{ route('admin.grades.reclamations.reject', $reclamation->id) }}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette réclamation ?')">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motif du Rejet</label>
                                <textarea name="admin_response" rows="3" required placeholder="Expliquez le rejet..."
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors text-sm">
                                Rejeter
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
