@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Importer des Convocations</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Téléchargez un fichier CSV pour importer des convocations d'examens en masse
        </p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Succès!</span> {{ session('success') }}
        </div>

        @if(session('details'))
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Détails:</h3>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1 max-h-60 overflow-y-auto">
                    @foreach(session('details') as $detail)
                        <li>{{ $detail }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Erreur!</span> {{ session('error') }}
        </div>

        @if(session('details'))
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Détails:</h3>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1 max-h-60 overflow-y-auto">
                    @foreach(session('details') as $detail)
                        <li>{{ $detail }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    <!-- Import Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.exams.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Academic Year -->
                <div class="mb-6">
                    <label for="academic_year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Année Universitaire <span class="text-red-500">*</span>
                    </label>
                    <select name="academic_year"
                            id="academic_year"
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->start_year }}" {{ ($year->is_current || old('academic_year') == $year->start_year) ? 'selected' : '' }}>
                                {{ $year->start_year }}-{{ $year->end_year }}
                                @if($year->is_current)
                                    (En cours)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Season -->
                <div class="mb-6">
                    <label for="season" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Saison <span class="text-red-500">*</span>
                    </label>
                    <select name="season"
                            id="season"
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="autumn" {{ old('season') == 'autumn' ? 'selected' : '' }}>
                            Automne
                        </option>
                        <option value="spring" {{ old('season') == 'spring' ? 'selected' : '' }}>
                            Printemps 
                        </option>
                    </select>
                    @error('season')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Session Type -->
                <div class="mb-6">
                    <label for="session_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Type de Session <span class="text-red-500">*</span>
                    </label>
                    <select name="session_type"
                            id="session_type"
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="normal" {{ old('session_type') == 'normal' ? 'selected' : '' }}>
                            Session Normale
                        </option>
                        <option value="rattrapage" {{ old('session_type') == 'rattrapage' ? 'selected' : '' }}>
                            Session Rattrapage
                        </option>
                    </select>
                    @error('session_type')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">
                        Fichier CSV <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                           id="file"
                           name="file"
                           type="file"
                           accept=".csv,.txt"
                           required>
                    @error('file')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        CSV uniquement (max: 10MB)
                    </p>
                </div>

                <!-- CSV Format Instructions -->
                <div class="p-4 mb-6 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                    <h3 class="font-medium mb-2">Format du fichier CSV:</h3>
                    <p class="mb-2">Le fichier doit contenir les colonnes suivantes (première ligne = en-têtes):</p>
                    <code class="block p-2 bg-white dark:bg-gray-900 rounded text-xs">
                        numero_examen,code_apogee,code_module,date_examen,heure_debut,heure_fin,salle,observations
                    </code>
                    <div class="mt-3 space-y-1 text-xs">
                        <p><strong>numero_examen:</strong> Numéro d'examen (optionnel)</p>
                        <p><strong>code_apogee:</strong> Code Apogée de l'étudiant (requis)</p>
                        <p><strong>code_module:</strong> Code du module (requis)</p>
                        <p><strong>date_examen:</strong> Date de l'examen au format YYYY-MM-DD (requis)</p>
                        <p><strong>heure_debut:</strong> Heure de début au format HH:MM (requis)</p>
                        <p><strong>heure_fin:</strong> Heure de fin au format HH:MM (requis)</p>
                        <p><strong>salle:</strong> Salle d'examen (optionnel)</p>
                        <p><strong>observations:</strong> Remarques (optionnel)</p>
                    </div>
                    <p class="mt-3 text-xs">
                        <strong>Exemple:</strong>
                        <code class="bg-white dark:bg-gray-900 px-1 rounded">
                            E001,A12345,INF101,2024-12-15,08:00,10:00,Amphi A,RAS
                        </code>
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Importer les Examens
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Download Template -->
    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Besoin d'un modèle?</h3>
        <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">
            Téléchargez un fichier exemple pour voir le format correct:
        </p>
        <a href="{{ route('admin.exams.download-template') }}"
           class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            Télécharger le modèle CSV
        </a>
    </div>

    <!-- Info Box - Point to Exam Periods -->
    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1">Gestion de la Publication</h3>
                <p class="text-xs text-blue-700 dark:text-blue-400">
                    Pour publier ou dépublier des examens par période, utilisez la page
                    <a href="{{ route('admin.exam-periods.index') }}" class="font-semibold underline hover:text-blue-600 dark:hover:text-blue-300">
                        Périodes d'Examens
                    </a>
                    qui offre un contrôle plus précis par année universitaire, saison et session.
                </p>
            </div>
        </div>
    </div>

    <!-- Export Rattrapage Candidates Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg border-2 border-orange-200 dark:border-orange-900">
        <div class="p-6">
            <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        Exporter les Candidats au Rattrapage
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Télécharger la liste des étudiants ayant échoué (note < 10) à la session normale + résultats des réclamations traitées
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.exams.export-rattrapage-candidates') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Academic Year for Rattrapage -->
                    <div>
                        <label for="rattrapage_academic_year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Année Universitaire <span class="text-red-500">*</span>
                        </label>
                        <select name="academic_year"
                                id="rattrapage_academic_year"
                                required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->start_year }}" {{ $year->is_current ? 'selected' : '' }}>
                                    {{ $year->start_year }}-{{ $year->end_year }}
                                    @if($year->is_current)
                                        (En cours)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Season for Rattrapage -->
                    <div>
                        <label for="rattrapage_season" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Saison <span class="text-red-500">*</span>
                        </label>
                        <select name="season"
                                id="rattrapage_season"
                                required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="autumn">Automne (S1, S3, S5)</option>
                            <option value="spring">Printemps (S2, S4, S6)</option>
                        </select>
                    </div>
                </div>

                <div class="p-4 mb-6 text-sm text-orange-800 rounded-lg bg-orange-50 dark:bg-gray-800 dark:text-orange-400 border border-orange-200 dark:border-orange-900">
                    <h3 class="font-medium mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Comment ça marche ?
                    </h3>
                    <ul class="space-y-2 text-xs ml-7">
                        <li>✓ Le système recherche tous les étudiants avec une note finale < 10 en session normale</li>
                        <li>✓ Seules les notes publiées sont prises en compte</li>
                        <li>✓ Les notes mises à jour via réclamations traitées sont incluses</li>
                        <li>✓ Le fichier CSV téléchargé contient les informations des étudiants et modules</li>
                        <li>✓ Vous pouvez ensuite compléter les dates/heures/locaux dans le CSV</li>
                        <li>✓ Importer le CSV complété via la section "Importer des Convocations" ci-dessus</li>
                    </ul>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-red-600 rounded-lg hover:from-orange-600 hover:to-red-700 focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger la Liste des Candidats
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
