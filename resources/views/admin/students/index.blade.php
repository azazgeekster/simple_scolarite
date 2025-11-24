@extends('admin.layouts.app')

@section('title', 'Gestion des Étudiants')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Étudiants</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Rechercher, consulter et gérer les comptes étudiants</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.students.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter CSV
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</p>
            <p class="mt-1 text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
        </div>
        <a href="{{ route('admin.students.index', ['status' => 'active']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actifs</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </a>
        <a href="{{ route('admin.students.index', ['status' => 'inactive']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Inactifs</p>
            <p class="mt-1 text-2xl font-bold text-red-600">{{ $stats['inactive'] }}</p>
        </a>
        <a href="{{ route('admin.students.index', ['boursier' => '1']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-amber-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Boursiers</p>
            <p class="mt-1 text-2xl font-bold text-amber-600">{{ $stats['boursiers'] }}</p>
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <form method="GET" class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nom, prénom, CNE, Apogée, CIN, email..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Filiere -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Filière</label>
                    <select name="filiere_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="">Toutes</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->label_fr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Academic Year -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Année</label>
                    <select name="academic_year" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="">Toutes</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->start_year }}" {{ request('academic_year') == $year->start_year ? 'selected' : '' }}>
                                {{ $year->start_year }}/{{ $year->start_year + 1 }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                    <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="">Tous</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rechercher
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-sm">
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <form id="bulkForm" method="POST">
        @csrf
        <div id="bulkActionBar" class="hidden bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 mb-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-amber-800 dark:text-amber-200">
                    <span id="selectedCount">0</span> étudiant(s) sélectionné(s)
                </span>
                <div class="flex gap-2">
                    <button type="submit" formaction="{{ route('admin.students.bulk-activate') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                        Activer
                    </button>
                    <button type="submit" formaction="{{ route('admin.students.bulk-deactivate') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                        Désactiver
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-amber-600">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Étudiant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">CNE / Apogée</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Filière</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Contact</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="select-item rounded border-gray-300 dark:border-gray-600 text-amber-600">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->setAvatar() }}" alt="{{ $student->full_name }}">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $student->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $student->full_name_ar }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-mono text-gray-900 dark:text-white">{{ $student->cne }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $student->apogee }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @php $currentEnrollment = $student->programEnrollments->first(); @endphp
                                    @if($currentEnrollment)
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $currentEnrollment->filiere->label_fr ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $currentEnrollment->year_label }}</div>
                                    @else
                                        <span class="text-xs text-gray-400">Non inscrit</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1">
                                        @if($student->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Actif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                Inactif
                                            </span>
                                        @endif
                                        @if($student->boursier)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                                Boursier
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $student->email }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $student->tel ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.students.show', $student->id) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Gérer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun étudiant trouvé</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Essayez de modifier vos critères de recherche.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </form>
</div>

<script>
    const selectAll = document.getElementById('selectAll');
    const selectItems = document.querySelectorAll('.select-item');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkActionBar() {
        const checked = document.querySelectorAll('.select-item:checked').length;
        selectedCount.textContent = checked;
        bulkActionBar.classList.toggle('hidden', checked === 0);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            selectItems.forEach(item => item.checked = this.checked);
            updateBulkActionBar();
        });
    }

    selectItems.forEach(item => {
        item.addEventListener('change', updateBulkActionBar);
    });
</script>
@endsection
