@extends('admin.layouts.app')

@section('title', 'Importer des Notes')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Importer des Notes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Importez toutes les notes (toutes filières, tous semestres) en une seule fois</p>
        </div>
        <a href="{{ route('admin.grades.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Retour
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    @if(session('batch_id'))
                        <a href="{{ route('admin.grades.import-errors', session('batch_id')) }}"
                           class="text-sm text-green-700 dark:text-green-300 underline hover:text-green-600 mt-1 inline-block">
                            Voir les détails de l'import →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="border-b border-gray-200 dark:border-gray-700" x-data="{ activeTab: 'session' }">
            <nav class="flex -mb-px">
                <button @click="activeTab = 'session'"
                        :class="activeTab === 'session' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                        class="px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                    Notes de Session
                </button>
                <button @click="activeTab = 'final'"
                        :class="activeTab === 'final' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                        class="px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                    Notes Finales
                </button>
            </nav>

            <!-- Session Grades Tab -->
            <div x-show="activeTab === 'session'" class="p-6 space-y-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                    <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">Import des Notes de Session</h3>
                    <p class="text-sm text-blue-800 dark:text-blue-200 mb-3">
                        Utilisez cette option pour importer les notes d'une session d'examens (normale ou rattrapage) pour tous les modules et toutes les filières en même temps.
                    </p>
                    <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>Un fichier contenant: <strong>Apogee | Code Module | Note | Resultat</strong></li>
                        <li>Le système identifie automatiquement l'étudiant et le module</li>
                        <li>Détection automatique de la période d'examen active</li>
                        <li>Fichiers jusqu'à <strong>50MB</strong> supportés</li>
                        <li>Toutes les erreurs sont enregistrées et téléchargeables</li>
                    </ul>
                </div>

                <!-- Download Template -->
                <form method="GET" action="{{ route('admin.grades.import.session-grades-template') }}" class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">1. Télécharger le Template</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année académique</label>
                            <select name="academic_year" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->start_year }}" {{ $year->is_current ? 'selected' : '' }}>
                                        {{ $year->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session</label>
                            <select name="session" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="normal">Normale</option>
                                <option value="rattrapage">Rattrapage</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Période d'examen (optionnel)</label>
                            <select name="exam_period_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="">Détection automatique</option>
                                @foreach($examPeriods as $period)
                                    <option value="{{ $period->id }}">{{ $period->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="mt-4 w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Télécharger Template avec Instructions
                    </button>
                </form>

                <!-- Upload Form -->
                <form method="POST" action="{{ route('admin.grades.import.session-grades') }}" enctype="multipart/form-data" class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                    @csrf
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">2. Uploader le Fichier Rempli</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année académique</label>
                            <select name="academic_year" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->start_year }}" {{ $year->is_current ? 'selected' : '' }}>
                                        {{ $year->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session</label>
                            <select name="session" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="normal">Normale</option>
                                <option value="rattrapage">Rattrapage</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Période d'examen (optionnel)</label>
                            <select name="exam_period_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="">Détection automatique</option>
                                @foreach($examPeriods as $period)
                                    <option value="{{ $period->id }}">{{ $period->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fichier Excel</label>
                        <input type="file" name="file" accept=".xlsx,.xls" required
                               class="block w-full text-sm text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">XLSX ou XLS, max 50MB</p>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Lancer l'Import en Masse
                    </button>
                </form>
            </div>

            <!-- Final Grades Tab -->
            <div x-show="activeTab === 'final'" class="p-6 space-y-6">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 p-4">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-100 mb-2">Import des Notes Finales</h3>
                    <p class="text-sm text-green-800 dark:text-green-200 mb-3">
                        Utilisez cette option pour importer les notes finales après délibérations et réclamations (généralement une fois par an).
                    </p>
                    <ul class="list-disc list-inside text-sm text-green-700 dark:text-green-300 space-y-1">
                        <li>Fichier: <strong>Apogee | Code Module | Note Finale | Resultat Final | Session Finale | Ancienne Note</strong></li>
                        <li>Met à jour les notes finales dans les inscriptions des étudiants</li>
                        <li>Met également à jour la note de la session correspondante</li>
                        <li>Utilisé une fois par an après toutes les délibérations</li>
                    </ul>
                </div>

                <!-- Download Template -->
                <form method="GET" action="{{ route('admin.grades.import.final-grades-template') }}" class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">1. Télécharger le Template</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année académique</label>
                            <select name="academic_year" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->start_year }}" {{ $year->is_current ? 'selected' : '' }}>
                                        {{ $year->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="mt-4 w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Télécharger Template avec Instructions
                    </button>
                </form>

                <!-- Upload Form -->
                <form method="POST" action="{{ route('admin.grades.import.final-grades') }}" enctype="multipart/form-data" class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                    @csrf
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">2. Uploader le Fichier Rempli</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année académique</label>
                            <select name="academic_year" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->start_year }}" {{ $year->is_current ? 'selected' : '' }}>
                                        {{ $year->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fichier Excel</label>
                        <input type="file" name="file" accept=".xlsx,.xls" required
                               class="block w-full text-sm text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">XLSX ou XLS, max 50MB</p>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Lancer l'Import des Notes Finales
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aide et Instructions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">📊 Format du Fichier (Notes de Session)</h4>
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded p-3 text-sm font-mono text-gray-700 dark:text-gray-300">
                    Apogee | Code Module | Note | Resultat
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Le système identifie automatiquement l'étudiant (via Apogee) et le module (via Code Module).
                </p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">✅ Avantages</h4>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Import de milliers de notes en quelques minutes</li>
                    <li>• Toutes filières et semestres en même temps</li>
                    <li>• Erreurs traçables et téléchargeables</li>
                    <li>• Traitement par chunks de 100 lignes</li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">⚠️ Points Importants</h4>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Les étudiants doivent être inscrits au module</li>
                    <li>• Le code module doit correspondre exactement</li>
                    <li>• Les notes doivent être entre 0 et 20</li>
                    <li>• Le traitement peut prendre plusieurs minutes</li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">🔍 En Cas d'Erreurs</h4>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Toutes les erreurs sont enregistrées</li>
                    <li>• Consultez le rapport d'erreurs après l'import</li>
                    <li>• Téléchargez le fichier Excel des erreurs</li>
                    <li>• Corrigez et réimportez uniquement les lignes erronées</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bulkImport', () => ({
            activeTab: 'session'
        }));
    });
</script>
@endsection
