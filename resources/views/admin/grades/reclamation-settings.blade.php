@extends('admin.layouts.app')

@section('title', 'Paramètres des Réclamations')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Paramètres des Réclamations</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gérez l'activation des réclamations par filière, semestre ou module</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau Paramètre
            </button>
            <a href="{{ route('admin.grades.reclamations') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800 dark:text-blue-300">
                <p class="font-medium mb-1">Comment fonctionne la hiérarchie ?</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Les paramètres <strong>par module</strong> ont la priorité la plus élevée</li>
                    <li>Ensuite les paramètres <strong>par semestre et filière</strong></li>
                    <li>Puis les paramètres <strong>par filière uniquement</strong></li>
                    <li>Enfin le paramètre <strong>global</strong> (sans filière/semestre/module)</li>
                </ul>
            </div>
        </div>
    </div>

    <x-flash-message type="success" />
    <x-flash-message type="error" />

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <form method="GET" class="flex gap-4">
            <div class="flex-1">
                <select name="academic_year" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                    <option value="">Toutes les années</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->start_year }}" {{ request('academic_year') == $year->start_year ? 'selected' : '' }}>
                            {{ $year->label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <select name="session" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                    <option value="">Toutes les sessions</option>
                    <option value="normal" {{ request('session') == 'normal' ? 'selected' : '' }}>Normale</option>
                    <option value="rattrapage" {{ request('session') == 'rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Settings Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Année / Session</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Portée</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Période</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Notes</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($settings as $setting)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $setting->academic_year }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($setting->session) }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($setting->module)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 rounded">
                                        Module: {{ $setting->module->code }}
                                    </span>
                                @elseif($setting->semester && $setting->filiere)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded">
                                        {{ $setting->filiere->label_fr }} - {{ $setting->semester }}
                                    </span>
                                @elseif($setting->filiere)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded">
                                        Filière: {{ $setting->filiere->label_fr }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded">
                                        Global
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($setting->isCurrentlyActive())
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        Actif
                                    </span>
                                @elseif($setting->is_active && $setting->starts_at && now()->lt($setting->starts_at))
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                        Programmé
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></span>
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                @if($setting->starts_at || $setting->ends_at)
                                    <div class="space-y-1">
                                        @if($setting->starts_at)
                                            <div>Du {{ $setting->starts_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                        @if($setting->ends_at)
                                            <div>Au {{ $setting->ends_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Illimitée</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ Str::limit($setting->notes ?? '-', 50) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick='openEditModal(@json($setting))' class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.grades.reclamation-settings.delete', $setting->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paramètre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Aucun paramètre trouvé. Créez-en un pour activer les réclamations.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($settings->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $settings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Nouveau Paramètre de Réclamation</h3>
            <form action="{{ route('admin.grades.reclamation-settings.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Année Académique *</label>
                        <select name="academic_year" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Sélectionner...</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->start_year }}" {{ $loop->first ? 'selected' : '' }}>
                                    {{ $year->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session *</label>
                        <select name="session" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Sélectionner...</option>
                            <option value="normal">Normale</option>
                            <option value="rattrapage">Rattrapage</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Portée (laisser vide pour global)</p>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Filière</label>
                            <select name="filiere_id" id="create_filiere_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="">Aucune</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}">{{ $filiere->label_fr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Semestre</label>
                            <select name="semester" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="">Aucun</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                                <option value="S4">S4</option>
                                <option value="S5">S5</option>
                                <option value="S6">S6</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Module</label>
                            <select name="module_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="">Aucun</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Actif</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Début</label>
                        <input type="datetime-local" name="starts_at"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Fin</label>
                        <input type="datetime-local" name="ends_at"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" rows="3" placeholder="Notes internes..."
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeCreateModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Annuler</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Modifier le Paramètre</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Actif</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Début</label>
                        <input type="datetime-local" name="starts_at" id="edit_starts_at"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Fin</label>
                        <input type="datetime-local" name="ends_at" id="edit_ends_at"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" id="edit_notes" rows="3"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Annuler</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(setting) {
        document.getElementById('editForm').action = `/admin/grades/reclamation-settings/${setting.id}`;
        document.getElementById('edit_is_active').checked = setting.is_active;
        document.getElementById('edit_starts_at').value = setting.starts_at ? setting.starts_at.substring(0, 16) : '';
        document.getElementById('edit_ends_at').value = setting.ends_at ? setting.ends_at.substring(0, 16) : '';
        document.getElementById('edit_notes').value = setting.notes || '';
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Dynamic module loading with professional error handling and UX
    document.addEventListener('DOMContentLoaded', function() {
        const filiereSelect = document.getElementById('create_filiere_id');
        const semesterSelect = document.querySelector('#createModal select[name="semester"]');
        const moduleSelect = document.querySelector('#createModal select[name="module_id"]');

        if (!filiereSelect || !semesterSelect || !moduleSelect) {
            return;
        }

        let abortController = null;

        async function loadModules() {
            const filiereId = filiereSelect.value;
            const semester = semesterSelect.value;

            // Reset module dropdown
            moduleSelect.innerHTML = '<option value="">Aucun</option>';
            moduleSelect.disabled = true;

            // Only proceed if filière is selected
            if (!filiereId) {
                moduleSelect.disabled = false;
                return;
            }

            // Show loading state
            moduleSelect.innerHTML = '<option value="">Chargement...</option>';

            // Cancel previous request if still pending
            if (abortController) {
                abortController.abort();
            }
            abortController = new AbortController();

            try {
                // Build URL with query parameters
                const params = new URLSearchParams({
                    filiere_id: filiereId
                });

                if (semester) {
                    params.append('semester', semester);
                }

                const url = '{{ route('admin.grades.modules-by-filiere') }}?' + params.toString();

                // Fetch modules
                const response = await fetch(url, {
                    signal: abortController.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Clear loading state
                moduleSelect.innerHTML = '<option value="">Aucun</option>';

                if (data.success && data.modules && data.modules.length > 0) {
                    data.modules.forEach(module => {
                        const option = document.createElement('option');
                        option.value = module.id;
                        option.textContent = `${module.code} - ${module.label}`;
                        moduleSelect.appendChild(option);
                    });
                } else if (data.modules && data.modules.length === 0) {
                    // No modules found for this combination
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Aucun module disponible';
                    option.disabled = true;
                    moduleSelect.appendChild(option);
                }

                moduleSelect.disabled = false;

            } catch (error) {
                if (error.name === 'AbortError') {
                    // Request was cancelled, this is expected
                    return;
                }

                console.error('Error loading modules:', error);

                // Show error state
                moduleSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                moduleSelect.disabled = false;

                // Optional: Show user-friendly notification
                if (typeof notyf !== 'undefined') {
                    notyf.error('Erreur lors du chargement des modules');
                }
            }
        }

        // Debounce function to prevent excessive API calls
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Add event listeners with debouncing
        const debouncedLoadModules = debounce(loadModules, 300);

        filiereSelect.addEventListener('change', () => {
            // Reset semester if filière changes
            loadModules();
        });

        semesterSelect.addEventListener('change', debouncedLoadModules);
    });
</script>
@endpush
