@extends('admin.layouts.app')

@section('title', 'Gestion des Professeurs')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Professeurs</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gérer les professeurs avec import/export Excel</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Professeurs</p>
                    <p class="text-3xl font-bold mt-1">{{ $professors->total() }}</p>
                </div>
                <div>
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Départements</p>
                    <p class="text-3xl font-bold mt-1">{{ $departementsCount }}</p>
                </div>
                <div>
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Modules Assignés</p>
                    <p class="text-3xl font-bold mt-1">{{ $assignedModulesCount }}</p>
                </div>
                <div>
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Non Assignés</p>
                    <p class="text-3xl font-bold mt-1">{{ $unassignedProfessorsCount }}</p>
                </div>
                <div>
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl p-6 mb-6 shadow-lg">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-white">Actions Rapides</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.academic.professors.export') }}" class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-gray-50 transition-colors shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exporter Excel
                </a>
                <a href="{{ route('admin.academic.professors.template') }}" class="inline-flex items-center px-4 py-2 bg-white text-pink-600 rounded-lg hover:bg-gray-50 transition-colors shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Télécharger Template
                </a>
                <button onclick="openImportModal()" class="inline-flex items-center px-4 py-2 bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-300 transition-colors shadow-md font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Importer Excel
                </button>
                <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors shadow-md font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau Professeur
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6 border border-gray-200 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.academic.professors') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="nom, prénom, email..."
                           class="w-full px-4 py-2
                                  border border-gray-300 dark:border-gray-600
                                  rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                  bg-white text-gray-900
                                  dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Département</label>
                    <select name="departement"
                            class="w-full px-4 py-2
                                   border border-gray-300 dark:border-gray-600
                                   rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                   bg-white text-gray-900
                                   dark:bg-gray-700 dark:text-white">
                        <option value="">Tous les départements</option>
                        @foreach($departements as $departement)
                            <option value="{{ $departement->id }}" {{ request('departement') == $departement->id ? 'selected' : '' }}>
                                {{ $departement->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Filtrer
                    </button>
                    <a href="{{ route('admin.academic.professors') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Professors Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CIN</th> --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom Complet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Département</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spécialisation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Modules</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($professors as $professor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            {{-- <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $professor->cin }}</span>
                            </td> --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                            <span class="text-purple-600 dark:text-purple-300 font-semibold text-sm">
                                                {{ substr($professor->nom, 0, 1) }}{{ substr($professor->prenom, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $professor->nom }} {{ $professor->prenom }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $professor->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $professor->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $professor->department->label ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                    {{ $professor->grade ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $professor->specialization ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $professor->modules_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button onclick='openEditModal(@json($professor))' class="text-purple-600 hover:text-purple-900 dark:text-purple-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.academic.professors.destroy', $professor->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce professeur ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun professeur</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par créer un nouveau professeur.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($professors->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $professors->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black bg-opacity-20 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeCreateModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full relative z-10">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">Nouveau Professeur</h3>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.academic.professors.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-3 gap-4">
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CIN *</label>
                            <input type="text" name="cin" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div> --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom *</label>
                            <input type="text" name="nom" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prénom *</label>
                            <input type="text" name="prenom" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                            <input type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                            <input type="text" name="phone"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Département</label>
                            <select name="departement_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                           focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                           bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                                <option value="">Sélectionner un département</option>
                                @foreach($departements as $departement)
                                    <option value="{{ $departement->id }}">{{ $departement->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade</label>
                            <input type="text" name="grade" placeholder="ex: Professeur, Docteur..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Spécialisation</label>
                        <input type="text" name="specialization"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                      focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                      bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Créer le professeur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0   bg-opacity-20 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full relative z-10">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Modifier le Professeur</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-3 gap-4">
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CIN *</label>
                            <input type="text" id="edit_cin" name="cin" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div> --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom *</label>
                            <input type="text" id="edit_nom" name="nom" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prénom *</label>
                            <input type="text" id="edit_prenom" name="prenom" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                            <input type="email" id="edit_email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                            <input type="text" id="edit_phone" name="phone"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Département</label>
                            <select id="edit_departement_id" name="departement_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                           focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                           bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                                <option value="">Sélectionner un département</option>
                                @foreach($departements as $departement)
                                    <option value="{{ $departement->id }}">{{ $departement->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade</label>
                            <input type="text" id="edit_grade" name="grade"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                          bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Spécialisation</label>
                        <input type="text" id="edit_specialization" name="specialization"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                      focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                      bg-white text-gray-900 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black bg-opacity-20 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeImportModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Importer des Professeurs</h3>
                    <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.academic.professors.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fichier Excel *</label>
                        <input type="file" name="file" accept=".xlsx,.xls" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <p class="mt-2 text-xs text-gray-500">Format accepté: .xlsx, .xls (max 10MB)</p>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-200">
                                    <strong>Instructions:</strong><br>
                                    1. Téléchargez le template Excel<br>
                                    2. Remplissez les données ( Nom, Prénom, Email, Tél, Code Dept, Grade, Spécialisation)<br>
                                    3. Importez le fichier rempli
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeImportModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(professor) {
    document.getElementById('editForm').action = `/admin/academic/professors/${professor.id}`;
    // document.getElementById('edit_cin').value = professor.cin;
    document.getElementById('edit_nom').value = professor.nom;
    document.getElementById('edit_prenom').value = professor.prenom;
    document.getElementById('edit_email').value = professor.email;
    document.getElementById('edit_phone').value = professor.phone || '';
    document.getElementById('edit_departement_id').value = professor.department_id || '';
    document.getElementById('edit_grade').value = professor.grade || '';
    document.getElementById('edit_specialization').value = professor.specialization || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateModal();
        closeEditModal();
        closeImportModal();
    }
});
</script>
@endsection
