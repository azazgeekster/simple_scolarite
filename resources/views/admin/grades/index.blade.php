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

    <x-flash-message type="success" />
    <x-flash-message type="error" />

    <!-- Modules Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Modules</h3>
            <form method="POST" action="{{ route('admin.grades.bulk-publish') }}" id="bulkForm" class="hidden">
                @csrf
                <input type="hidden" name="session" value="{{ $selectedSession }}">
                <input type="hidden" name="academic_year" value="{{ $selectedYear }}">
                <div id="selectedModules"></div>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm transition-colors">
                    Publier la sélection
                </button>
            </form>
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
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
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
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.module-checkbox');
        const bulkForm = document.getElementById('bulkForm');
        const selectedModules = document.getElementById('selectedModules');

        function updateBulkForm() {
            const checked = document.querySelectorAll('.module-checkbox:checked');
            if (checked.length > 0) {
                bulkForm.classList.remove('hidden');
                selectedModules.innerHTML = '';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'module_ids[]';
                    input.value = cb.value;
                    selectedModules.appendChild(input);
                });
            } else {
                bulkForm.classList.add('hidden');
            }
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkForm();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkForm);
        });
    });
</script>
@endpush
@endsection
