@extends('admin.layouts.app')

@section('title', 'Gestion des Notes')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Publication des Notes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gérez la publication des notes par module et session</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.grades.import') }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Importer
            </a>
            <a href="{{ route('admin.grades.reclamations') }}"
               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Réclamations
                @if($stats['total'] > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-amber-800 rounded-full">{{ \App\Models\Reclamation::where('status', 'PENDING')->count() }}</span>
                @endif
            </a>
            
            {{-- Rattrapage Actions --}}
            <button onclick="showBulkJustifyModal()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Justifier Absences
            </button>
            
            <a href="{{ route('admin.grades.rattrapage.convocations', ['academic_year' => $selectedYear]) }}"
               class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Convocations
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="GET" action="{{ route('admin.grades.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Academic Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année académique</label>
                    <select name="academic_year" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->start_year }}" {{ $selectedYear == $year->start_year ? 'selected' : '' }}>
                                {{ $year->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filiere -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filière</label>
                    <select name="filiere_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <option value="">Toutes les filières</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ $selectedFiliere == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->label_fr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semestre</label>
                    <select name="semester" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <option value="">Tous les semestres</option>
                        @foreach(['S1', 'S2', 'S3', 'S4', 'S5', 'S6'] as $sem)
                            <option value="{{ $sem }}" {{ $selectedSemester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Session -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session</label>
                    <select name="session" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <option value="normal" {{ $selectedSession == 'normal' ? 'selected' : '' }}>Normale</option>
                        <option value="rattrapage" {{ $selectedSession == 'rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                        Filtrer
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Total notes</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="text-2xl font-bold text-green-600">{{ number_format($stats['published']) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Publiées</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="text-2xl font-bold text-amber-600">{{ number_format($stats['unpublished']) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Non publiées</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['passed']) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Validées</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="text-2xl font-bold text-red-600">{{ number_format($stats['failed']) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Non validées</div>
        </div>
    </div>

    <!-- Modules Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Modules</h3>
            <div class="flex items-center gap-2">
                <!-- Bulk Publish Form -->
                <form method="POST" action="{{ route('admin.grades.bulk-publish') }}" id="bulkPublishForm" class="hidden">
                    @csrf
                    <input type="hidden" name="session" value="{{ $selectedSession }}">
                    <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                    <div id="selectedModulesPublish"></div>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm transition-colors">
                        Publier la sélection
                    </button>
                </form>

                <!-- Bulk Enable Reclamations -->
                <form method="POST" action="{{ route('admin.grades.bulk-toggle-reclamations') }}" id="bulkEnableReclamationsForm" class="hidden">
                    @csrf
                    <input type="hidden" name="session" value="{{ $selectedSession }}">
                    <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                    <input type="hidden" name="action" value="enable">
                    <div id="selectedModulesEnableReclam"></div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">
                        Activer réclamations
                    </button>
                </form>

                <!-- Bulk Disable Reclamations -->
                <form method="POST" action="{{ route('admin.grades.bulk-toggle-reclamations') }}" id="bulkDisableReclamationsForm" class="hidden">
                    @csrf
                    <input type="hidden" name="session" value="{{ $selectedSession }}">
                    <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                    <input type="hidden" name="action" value="disable">
                    <div id="selectedModulesDisableReclam"></div>
                    <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg text-sm transition-colors">
                        Désactiver réclamations
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Module</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Filière</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Sem.</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Publiées</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Moyenne</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Statut</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Réclam.</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">RATT</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Absents</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($modules as $module)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="module_ids[]" value="{{ $module->id }}" class="module-checkbox rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $module->code }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($module->label, 40) }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $module->filiere_name }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded">{{ $module->semester }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-gray-900 dark:text-white">{{ $module->total_grades }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm font-medium {{ $module->published_grades == $module->total_grades ? 'text-green-600' : 'text-amber-600' }}">
                                    {{ $module->published_grades }}/{{ $module->total_grades }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($module->avg_grade)
                                    <span class="text-sm font-medium {{ $module->avg_grade >= 10 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($module->avg_grade, 2) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($module->unpublished_grades == 0)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        Publié
                                    </span>
                                @elseif($module->published_grades == 0)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></span>
                                        Non publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>
                                        Partiel
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $setting = $reclamationSettings->get($module->id);
                                    $isActive = $setting && $setting->is_active;
                                @endphp
                                <form method="POST" action="{{ route('admin.grades.toggle-reclamations', $module->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="session" value="{{ $selectedSession }}">
                                    <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                                    <input type="hidden" name="action" value="{{ $isActive ? 'disable' : 'enable' }}">
                                    <button type="submit" class="px-2 py-1 text-xs font-medium rounded transition-colors {{ $isActive ? 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $isActive ? 'Activé' : 'Désactivé' }}
                                    </button>
                                </form>
                            </td>
                            {{-- RATT Column --}}
                            <td class="px-4 py-3 text-center">
                                @if($module->ratt_count > 0)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded">
                                        {{ $module->ratt_count }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            {{-- Absents Column --}}
                            <td class="px-4 py-3 text-center">
                                @if($module->abi_count > 0)
                                    <button type="button" 
                                            onclick="showAbsentsModal({{ $module->id }}, '{{ $module->label }}')"
                                            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        {{ $module->abi_count }}
                                        @if($module->justified_count > 0)
                                            <span class="text-green-600 dark:text-green-400">({{ $module->justified_count }} ✓)</span>
                                        @endif
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- PV Download Button -->
                                    <form method="POST" action="{{ route('admin.grades.module-pv') }}" class="inline" target="_blank">
                                        @csrf
                                        <input type="hidden" name="module_id" value="{{ $module->id }}">
                                        <input type="hidden" name="session" value="{{ $selectedSession }}">
                                        <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors" title="Télécharger PV">
                                            PV
                                        </button>
                                    </form>

                                    @if($module->unpublished_grades > 0)
                                        <form method="POST" action="{{ route('admin.grades.publish-module', $module->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="session" value="{{ $selectedSession }}">
                                            <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                                Publier
                                            </button>
                                        </form>
                                    @endif
                                    @if($module->published_grades > 0)
                                        <form method="POST" action="{{ route('admin.grades.unpublish-module', $module->id) }}" class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir dépublier ces notes ?')">
                                            @csrf
                                            <input type="hidden" name="session" value="{{ $selectedSession }}">
                                            <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg transition-colors">
                                                Dépublier
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Aucun module trouvé pour les critères sélectionnés.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($modules->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $modules->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Absents Modal --}}
<div id="absentsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">Étudiants Absents</h3>
            <button onclick="closeAbsentsModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="mt-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400">Apogée</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400">Étudiant</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400">Justifié?</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="absentsTableBody" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <svg class="animate-spin h-8 w-8 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Justify Modal --}}
<div id="bulkJustifyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Justifier les Absences en Masse</h3>
            <button onclick="closeBulkJustifyModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="mt-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700 dark:text-blue-300">
                        <p class="font-medium">Instructions:</p>
                        <ol class="list-decimal list-inside mt-2 space-y-1">
                            <li>Téléchargez le modèle Excel</li>
                            <li>Remplissez les colonnes: Apogée, Code Module, Raison (optionnel)</li>
                            <li>Importez le fichier ci-dessous</li>
                        </ol>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <a href="{{ route('admin.grades.rattrapage.justification-template') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger le Modèle
                </a>
            </div>
            
            <form action="{{ route('admin.grades.rattrapage.bulk-justify') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                <input type="hidden" name="session" value="{{ $selectedSession }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fichier Excel
                    </label>
                    <input type="file" 
                           name="file" 
                           accept=".xlsx,.xls,.csv" 
                           required
                           class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Formats acceptés: XLSX, XLS, CSV</p>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" 
                            onclick="closeBulkJustifyModal()"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Importer et Justifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.module-checkbox');
        const bulkPublishForm = document.getElementById('bulkPublishForm');
        const bulkEnableReclamationsForm = document.getElementById('bulkEnableReclamationsForm');
        const bulkDisableReclamationsForm = document.getElementById('bulkDisableReclamationsForm');
        const selectedModulesPublish = document.getElementById('selectedModulesPublish');
        const selectedModulesEnableReclam = document.getElementById('selectedModulesEnableReclam');
        const selectedModulesDisableReclam = document.getElementById('selectedModulesDisableReclam');

        function updateBulkForms() {
            const checked = document.querySelectorAll('.module-checkbox:checked');
            if (checked.length > 0) {
                // Show all bulk action forms
                bulkPublishForm.classList.remove('hidden');
                bulkEnableReclamationsForm.classList.remove('hidden');
                bulkDisableReclamationsForm.classList.remove('hidden');
                
                // Clear existing inputs
                selectedModulesPublish.innerHTML = '';
                selectedModulesEnableReclam.innerHTML = '';
                selectedModulesDisableReclam.innerHTML = '';
                
                // Add module IDs to all forms
                checked.forEach(cb => {
                    // For publish form
                    const inputPublish = document.createElement('input');
                    inputPublish.type = 'hidden';
                    inputPublish.name = 'module_ids[]';
                    inputPublish.value = cb.value;
                    selectedModulesPublish.appendChild(inputPublish);
                    
                    // For enable reclamations form
                    const inputEnable = document.createElement('input');
                    inputEnable.type = 'hidden';
                    inputEnable.name = 'module_ids[]';
                    inputEnable.value = cb.value;
                    selectedModulesEnableReclam.appendChild(inputEnable);
                    
                    // For disable reclamations form
                    const inputDisable = document.createElement('input');
                    inputDisable.type = 'hidden';
                    inputDisable.name = 'module_ids[]';
                    inputDisable.value = cb.value;
                    selectedModulesDisableReclam.appendChild(inputDisable);
                });
            } else {
                // Hide all bulk action forms
                bulkPublishForm.classList.add('hidden');
                bulkEnableReclamationsForm.classList.add('hidden');
                bulkDisableReclamationsForm.classList.add('hidden');
            }
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkForms();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkForms);
        });
    });
</script>
@endpush

<script>
    // Absents Modal Functions
    function showAbsentsModal(moduleId, moduleName) {
        const modal = document.getElementById('absentsModal');
        const modalTitle = document.getElementById('modalTitle');
        const tbody = document.getElementById('absentsTableBody');
        
        modalTitle.textContent = `Étudiants Absents - ${moduleName}`;
        modal.classList.remove('hidden');
        
        // Load absent students
        loadAbsentStudents(moduleId, tbody);
    }
    
    function closeAbsentsModal() {
        document.getElementById('absentsModal').classList.add('hidden');
    }
    
    function loadAbsentStudents(moduleId, tbody) {
        const academicYear = '{{ $selectedYear }}';
        const session = '{{ $selectedSession }}';
        
        fetch(`/admin/grades/rattrapage/students?module_id=${moduleId}&academic_year=${academicYear}&session=${session}&result=ABI`)
            .then(response => response.json())
            .then(data => {
                if (data.students && data.students.length > 0) {
                    tbody.innerHTML = data.students.map(student => `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">${student.apogee}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-white">${student.name}</td>
                            <td class="px-4 py-3 text-center">
                                ${student.is_justified ? 
                                    '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded">✓ Justifiée</span>' : 
                                    '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded">✗ Non justifiée</span>'}
                            </td>
                            <td class="px-4 py-3 text-right">
                                ${!student.is_justified ? 
                                    `<button onclick="justifyAbsence(${student.grade_id}, '${student.name}')" 
                                             class="px-3 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Justifier
                                    </button>` : 
                                    `<form action="/admin/grades/rattrapage/unjustify/${student.grade_id}" method="POST" class="inline" 
                                           onsubmit="return confirm('Annuler la justification de ${student.name}?')">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-orange-600 hover:bg-orange-700 text-white rounded transition-colors">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                            </svg>
                                            Annuler
                                        </button>
                                    </form>`}
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Aucun étudiant absent</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error loading students:', error);
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-red-500">Erreur de chargement</td></tr>';
            });
    }
    
    function justifyAbsence(gradeId, studentName) {
        const reason = prompt(`Justifier l'absence de ${studentName}:\n\nRaison (optionnel):`);
        if (reason !== null) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/grades/rattrapage/justify/${gradeId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="reason" value="${reason}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Bulk Justify Modal Functions
    function showBulkJustifyModal() {
        document.getElementById('bulkJustifyModal').classList.remove('hidden');
    }
    
    function closeBulkJustifyModal() {
        document.getElementById('bulkJustifyModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('absentsModal');
        if (event.target === modal) {
            closeAbsentsModal();
        }
        
        const bulkModal = document.getElementById('bulkJustifyModal');
        if (event.target === bulkModal) {
            closeBulkJustifyModal();
        }
    });
</script>

@endsection
