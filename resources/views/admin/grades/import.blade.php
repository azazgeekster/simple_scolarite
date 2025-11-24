@extends('admin.layouts.app')

@section('title', 'Importer des Notes')

@section('content')
<div class="space-y-6" x-data="gradeImporter()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Importer des Notes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Importez les notes des étudiants via un fichier Excel</p>
        </div>
        <a href="{{ route('admin.grades.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Retour
        </a>
    </div>

    <!-- Step Indicator -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <template x-for="(step, index) in steps" :key="index">
                <div class="flex items-center" :class="{ 'flex-1': index < steps.length - 1 }">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300"
                             :class="{
                                 'bg-blue-600 text-white': currentStep === index,
                                 'bg-green-500 text-white': currentStep > index,
                                 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400': currentStep < index
                             }">
                            <template x-if="currentStep > index">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="currentStep <= index">
                                <span x-text="index + 1"></span>
                            </template>
                        </div>
                        <span class="ml-3 text-sm font-medium hidden sm:block"
                              :class="{
                                  'text-blue-600 dark:text-blue-400': currentStep === index,
                                  'text-green-600 dark:text-green-400': currentStep > index,
                                  'text-gray-500 dark:text-gray-400': currentStep < index
                              }"
                              x-text="step"></span>
                    </div>
                    <template x-if="index < steps.length - 1">
                        <div class="flex-1 h-0.5 mx-4"
                             :class="currentStep > index ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-700'"></div>
                    </template>
                </div>
            </template>
        </div>
    </div>

    <!-- Step 1: Download Template -->
    <div x-show="currentStep === 0" x-transition class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Download Template -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Télécharger le Template
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Sélectionnez le module et téléchargez le fichier Excel pré-rempli avec la liste des étudiants inscrits.
            </p>

            <form method="GET" action="{{ route('admin.grades.download-template') }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année académique</label>
                    <select name="academic_year" x-model="templateForm.academicYear" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->start_year }}">{{ $year->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filière</label>
                    <select x-model="templateForm.filiereId" @change="loadTemplateModules()" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <option value="">Sélectionner une filière</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}">{{ $filiere->label_fr }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Module</label>
                    <select name="module_id" x-model="templateForm.moduleId" required :disabled="templateModules.length === 0"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm disabled:opacity-50">
                        <option value="">Sélectionner un module</option>
                        <template x-for="module in templateModules" :key="module.id">
                            <option :value="module.id" x-text="`${module.code} - ${module.label} (${module.semester})`"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session</label>
                    <select name="session" x-model="templateForm.session" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <option value="normal">Normale</option>
                        <option value="rattrapage">Rattrapage</option>
                    </select>
                </div>

                <button type="submit" :disabled="!templateForm.moduleId"
                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors text-sm">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Télécharger Template
                </button>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Instructions</h3>
            <ul class="list-disc list-inside text-sm text-blue-800 dark:text-blue-200 space-y-2">
                <li>Téléchargez d'abord le template pour le module concerné</li>
                <li>Le template contient la liste des étudiants inscrits au module</li>
                <li>Remplissez la colonne "Note (0-20)" avec les notes des étudiants</li>
                <li>Marquez "O" dans la colonne "Absent" si l'étudiant était absent</li>
                <li>Ne modifiez pas les colonnes CNE, Apogée, Nom et Prénom</li>
                <li>Les notes existantes seront mises à jour si elles existent déjà</li>
            </ul>

            <div class="mt-6">
                <button @click="currentStep = 1"
                        class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm">
                    Continuer vers l'import
                    <svg class="w-4 h-4 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Step 2: Upload & Preview -->
    <div x-show="currentStep === 1" x-transition class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Importer les Notes
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upload Form -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année académique</label>
                        <select x-model="importForm.academicYear" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->start_year }}">{{ $year->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filière</label>
                        <select x-model="importForm.filiereId" @change="loadImportModules()" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            <option value="">Sélectionner une filière</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}">{{ $filiere->label_fr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Module</label>
                        <select x-model="importForm.moduleId" required :disabled="importModules.length === 0"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm disabled:opacity-50">
                            <option value="">Sélectionner un module</option>
                            <template x-for="module in importModules" :key="module.id">
                                <option :value="module.id" x-text="`${module.code} - ${module.label} (${module.semester})`"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session</label>
                        <select x-model="importForm.session" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            <option value="normal">Normale</option>
                            <option value="rattrapage">Rattrapage</option>
                        </select>
                    </div>
                </div>

                <!-- File Upload Zone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fichier Excel</label>
                    <div class="relative border-2 border-dashed rounded-lg p-6 text-center transition-colors"
                         :class="isDragging ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-600'"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleFileDrop($event)">

                        <template x-if="!selectedFile">
                            <div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Glissez-déposez votre fichier ici, ou
                                    <label class="text-blue-600 hover:text-blue-500 cursor-pointer">
                                        parcourez
                                        <input type="file" class="hidden" accept=".xlsx,.xls" @change="handleFileSelect($event)">
                                    </label>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">XLSX, XLS jusqu'à 10MB</p>
                            </div>
                        </template>

                        <template x-if="selectedFile">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="selectedFile.name"></p>
                                        <p class="text-xs text-gray-500" x-text="formatFileSize(selectedFile.size)"></p>
                                    </div>
                                </div>
                                <button @click="selectedFile = null" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button @click="currentStep = 0"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 font-medium transition-colors text-sm">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour
                </button>

                <button @click="previewFile()"
                        :disabled="!selectedFile || !importForm.moduleId || isLoading"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors text-sm">
                    <template x-if="isLoading">
                        <svg class="animate-spin w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <template x-if="!isLoading">
                        <span>
                            Analyser le fichier
                            <svg class="w-4 h-4 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </template>
                </button>
            </div>
        </div>
    </div>

    <!-- Step 3: Preview & Confirm -->
    <div x-show="currentStep === 2" x-transition class="space-y-6">
        <!-- Preview Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white" x-text="previewStats.total"></div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-green-600" x-text="previewStats.valid"></div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Valides</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600" x-text="previewStats.new"></div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Nouvelles</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-amber-600" x-text="previewStats.updates"></div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Mises à jour</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-red-600" x-text="previewStats.errors"></div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Erreurs</div>
            </div>
        </div>

        <!-- Module Info -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <span class="font-medium text-blue-900 dark:text-blue-100" x-text="moduleInfo.code"></span>
                    <span class="text-blue-700 dark:text-blue-300 mx-2">-</span>
                    <span class="text-blue-700 dark:text-blue-300" x-text="moduleInfo.label"></span>
                    <span class="text-blue-600 dark:text-blue-400 ml-2">(<span x-text="moduleInfo.filiere"></span>)</span>
                </div>
            </div>
        </div>

        <!-- Preview Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Aperçu des données</h3>
                <div class="flex items-center gap-2">
                    <label class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <input type="checkbox" x-model="showOnlyErrors" class="rounded mr-2">
                        Afficher uniquement les erreurs
                    </label>
                </div>
            </div>

            <div class="overflow-x-auto max-h-96">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ligne</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">CNE</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Prénom</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Note</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Absent</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="row in filteredPreview" :key="row.row">
                            <tr :class="row.status === 'error' ? 'bg-red-50 dark:bg-red-900/20' : ''">
                                <td class="px-4 py-3 text-sm text-gray-500" x-text="row.row"></td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" x-text="row.cne"></td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" x-text="row.nom"></td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" x-text="row.prenom"></td>
                                <td class="px-4 py-3 text-sm">
                                    <template x-if="row.action === 'update' && row.old_grade !== null">
                                        <span>
                                            <span class="text-gray-400 line-through" x-text="row.old_grade"></span>
                                            <span class="mx-1">→</span>
                                            <span class="font-medium" :class="row.grade >= 10 ? 'text-green-600' : 'text-red-600'" x-text="row.grade"></span>
                                        </span>
                                    </template>
                                    <template x-if="row.action !== 'update' || row.old_grade === null">
                                        <span class="font-medium" :class="row.grade >= 10 ? 'text-green-600' : 'text-red-600'" x-text="row.absent ? '-' : row.grade"></span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span x-show="row.absent" class="text-amber-600">Oui</span>
                                    <span x-show="!row.absent" class="text-gray-400">Non</span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span x-show="row.action === 'new'" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Nouvelle
                                    </span>
                                    <span x-show="row.action === 'update'" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                        Mise à jour
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <template x-if="row.status === 'valid'">
                                        <span class="inline-flex items-center text-green-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Valide
                                        </span>
                                    </template>
                                    <template x-if="row.status === 'error'">
                                        <span class="inline-flex items-center text-red-600" :title="row.error">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <span x-text="row.error"></span>
                                        </span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <button @click="currentStep = 1; previewData = []; tempFile = null;"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 font-medium transition-colors text-sm">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Retour
            </button>

            <button @click="startImport()"
                    :disabled="previewStats.valid === 0 || isImporting"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors text-sm">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Importer <span x-text="previewStats.valid"></span> note(s)
            </button>
        </div>
    </div>

    <!-- Step 4: Processing -->
    <div x-show="currentStep === 3" x-transition class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="text-center">
                <template x-if="isImporting">
                    <div>
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                            <svg class="animate-spin w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Importation en cours...</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Veuillez patienter pendant le traitement des données.</p>
                    </div>
                </template>

                <!-- Progress Bar -->
                <div class="max-w-md mx-auto mb-6">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span>Progression</span>
                        <span x-text="`${importProgress}%`"></span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-300 ease-out"
                             :style="`width: ${importProgress}%`"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span x-text="`${importProcessed} / ${importTotal} lignes`"></span>
                        <span x-text="`${importResults.imported} importées, ${importResults.updated} mises à jour`"></span>
                    </div>
                </div>

                <!-- Real-time Stats -->
                <div class="grid grid-cols-3 gap-4 max-w-md mx-auto">
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-lg font-bold text-green-600" x-text="importResults.imported"></div>
                        <div class="text-xs text-green-700 dark:text-green-400">Importées</div>
                    </div>
                    <div class="text-center p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                        <div class="text-lg font-bold text-amber-600" x-text="importResults.updated"></div>
                        <div class="text-xs text-amber-700 dark:text-amber-400">Mises à jour</div>
                    </div>
                    <div class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <div class="text-lg font-bold text-red-600" x-text="importResults.errors.length"></div>
                        <div class="text-xs text-red-700 dark:text-red-400">Erreurs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 5: Complete -->
    <div x-show="currentStep === 4" x-transition class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Importation terminée!</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Les notes ont été importées avec succès.</p>

                <!-- Final Stats -->
                <div class="grid grid-cols-3 gap-4 max-w-md mx-auto mb-6">
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-green-600" x-text="importResults.imported"></div>
                        <div class="text-sm text-green-700 dark:text-green-400">Nouvelles notes</div>
                    </div>
                    <div class="text-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-amber-600" x-text="importResults.updated"></div>
                        <div class="text-sm text-amber-700 dark:text-amber-400">Mises à jour</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-red-600" x-text="importResults.errors.length"></div>
                        <div class="text-sm text-red-700 dark:text-red-400">Erreurs</div>
                    </div>
                </div>

                <!-- Errors List -->
                <template x-if="importResults.errors.length > 0">
                    <div class="max-w-md mx-auto mb-6 text-left">
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-red-800 dark:text-red-400 mb-2">Erreurs rencontrées:</h4>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1 max-h-32 overflow-y-auto">
                                <template x-for="error in importResults.errors" :key="error">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </template>

                <!-- Actions -->
                <div class="flex items-center justify-center gap-4">
                    <button @click="resetImporter()"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors text-sm">
                        Nouvelle importation
                    </button>
                    <a href="{{ route('admin.grades.index') }}"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                        Voir les notes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function gradeImporter() {
    return {
        steps: ['Template', 'Upload', 'Aperçu', 'Import', 'Terminé'],
        currentStep: 0,

        // Template form
        templateForm: {
            academicYear: '{{ $academicYears->first()?->start_year }}',
            filiereId: '',
            moduleId: '',
            session: 'normal'
        },
        templateModules: [],

        // Import form
        importForm: {
            academicYear: '{{ $academicYears->first()?->start_year }}',
            filiereId: '',
            moduleId: '',
            session: 'normal'
        },
        importModules: [],

        // File handling
        selectedFile: null,
        isDragging: false,
        isLoading: false,

        // Preview
        previewData: [],
        previewStats: { total: 0, valid: 0, errors: 0, new: 0, updates: 0 },
        moduleInfo: { code: '', label: '', filiere: '' },
        tempFile: null,
        showOnlyErrors: false,

        // Import progress
        isImporting: false,
        importProgress: 0,
        importProcessed: 0,
        importTotal: 0,
        importResults: { imported: 0, updated: 0, errors: [] },

        get filteredPreview() {
            if (this.showOnlyErrors) {
                return this.previewData.filter(row => row.status === 'error');
            }
            return this.previewData;
        },

        async loadTemplateModules() {
            if (!this.templateForm.filiereId) {
                this.templateModules = [];
                this.templateForm.moduleId = '';
                return;
            }

            try {
                const response = await fetch(`{{ route('admin.grades.modules-for-filiere') }}?filiere_id=${this.templateForm.filiereId}`);
                this.templateModules = await response.json();
                this.templateForm.moduleId = '';
            } catch (error) {
                console.error('Error loading modules:', error);
                this.templateModules = [];
            }
        },

        async loadImportModules() {
            if (!this.importForm.filiereId) {
                this.importModules = [];
                this.importForm.moduleId = '';
                return;
            }

            try {
                const response = await fetch(`{{ route('admin.grades.modules-for-filiere') }}?filiere_id=${this.importForm.filiereId}`);
                this.importModules = await response.json();
                this.importForm.moduleId = '';
            } catch (error) {
                console.error('Error loading modules:', error);
                this.importModules = [];
            }
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFile = file;
            }
        },

        handleFileDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            if (file && (file.name.endsWith('.xlsx') || file.name.endsWith('.xls'))) {
                this.selectedFile = file;
            }
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        async previewFile() {
            if (!this.selectedFile || !this.importForm.moduleId) return;

            this.isLoading = true;

            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('module_id', this.importForm.moduleId);
            formData.append('academic_year', this.importForm.academicYear);
            formData.append('session', this.importForm.session);

            try {
                const response = await fetch('{{ route('admin.grades.import.preview') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.previewData = data.preview;
                    this.previewStats = data.stats;
                    this.moduleInfo = data.module;
                    this.tempFile = data.temp_file;
                    this.currentStep = 2;
                } else {
                    alert(data.message || 'Erreur lors de l\'analyse du fichier');
                }
            } catch (error) {
                console.error('Error previewing file:', error);
                alert('Erreur lors de l\'analyse du fichier');
            } finally {
                this.isLoading = false;
            }
        },

        async startImport() {
            if (!this.tempFile || this.previewStats.valid === 0) return;

            this.currentStep = 3;
            this.isImporting = true;
            this.importProgress = 0;
            this.importProcessed = 0;
            this.importTotal = this.previewStats.total;
            this.importResults = { imported: 0, updated: 0, errors: [] };

            const chunkSize = 50;
            let chunkStart = 0;

            while (chunkStart < this.importTotal) {
                try {
                    const response = await fetch('{{ route('admin.grades.import.chunk') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            temp_file: this.tempFile,
                            module_id: this.importForm.moduleId,
                            academic_year: this.importForm.academicYear,
                            session: this.importForm.session,
                            chunk_start: chunkStart,
                            chunk_size: chunkSize
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.importResults.imported += data.imported;
                        this.importResults.updated += data.updated;
                        this.importResults.errors = this.importResults.errors.concat(data.errors);
                        this.importProcessed = data.processed;
                        this.importProgress = data.progress;

                        if (data.is_complete) {
                            break;
                        }

                        chunkStart = data.processed;
                    } else {
                        alert(data.message || 'Erreur lors de l\'importation');
                        break;
                    }
                } catch (error) {
                    console.error('Error importing chunk:', error);
                    alert('Erreur lors de l\'importation');
                    break;
                }
            }

            this.isImporting = false;
            this.currentStep = 4;
        },

        resetImporter() {
            this.currentStep = 0;
            this.selectedFile = null;
            this.previewData = [];
            this.previewStats = { total: 0, valid: 0, errors: 0, new: 0, updates: 0 };
            this.tempFile = null;
            this.importProgress = 0;
            this.importResults = { imported: 0, updated: 0, errors: [] };
        }
    }
}
</script>
@endsection
