@extends('admin.layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Demandes de Documents
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestion des demandes de documents des étudiants
                </p>
            </div>
            <div class="mt-4 flex flex-wrap gap-2 md:mt-0 md:ml-4">
                <a href="{{ route('admin.document-requests.statistics') }}"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistiques
                </a>
                <a href="{{ route('admin.document-requests.document-types') }}"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Types
                </a>
                <a href="{{ route('admin.document-requests.export', request()->query()) }}"
                    class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exporter
                </a>
            </div>
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

        <!-- Quick Return Card -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-4 mb-6">
            <form method="POST" action="{{ route('admin.document-requests.quick-return') }}" class="flex items-center gap-4">
                @csrf
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-white text-sm font-medium mb-1">Retour Rapide</label>
                    <div class="flex gap-2">
                        <input type="text" name="identifier" placeholder="CNE ou N° Apogée de l'étudiant..."
                            class="flex-1 rounded-md border-0 bg-white/90 text-gray-900 text-sm placeholder-gray-500 focus:ring-2 focus:ring-white">
                        <button type="submit" class="px-4 py-2 bg-white text-green-600 text-sm font-medium rounded-md hover:bg-green-50 transition-colors">
                            Valider Retour
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-6">
            <a href="{{ route('admin.document-requests.index', ['status' => 'PENDING']) }}"
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-yellow-500 hover:shadow-md transition-shadow cursor-pointer">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">En Attente</p>
                <p class="mt-1 text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </a>
            <a href="{{ route('admin.document-requests.index', ['status' => 'READY']) }}"
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500 hover:shadow-md transition-shadow cursor-pointer">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Prêt</p>
                <p class="mt-1 text-2xl font-bold text-blue-600">{{ $stats['ready'] }}</p>
            </a>
            <a href="{{ route('admin.document-requests.index', ['status' => 'PICKED']) }}"
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500 hover:shadow-md transition-shadow cursor-pointer">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Retiré</p>
                <p class="mt-1 text-2xl font-bold text-green-600">{{ $stats['picked'] }}</p>
            </a>
            <a href="{{ route('admin.document-requests.index', ['overdue' => 1]) }}"
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500 hover:shadow-md transition-shadow cursor-pointer">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">En Retard</p>
                <p class="mt-1 text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
            </a>
            <a href="{{ route('admin.document-requests.index', ['extension_requested' => 1]) }}"
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-purple-500 hover:shadow-md transition-shadow cursor-pointer">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Extensions</p>
                <p class="mt-1 text-2xl font-bold text-purple-600">{{ $stats['extension_requested'] }}</p>
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <form method="GET" class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-3">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Recherche: référence, nom, CNE..."
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                            <option value="all">Tous statuts</option>
                            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>En Attente</option>
                            <option value="READY" {{ request('status') === 'READY' ? 'selected' : '' }}>Prêt</option>
                            <option value="PICKED" {{ request('status') === 'PICKED' ? 'selected' : '' }}>Retiré</option>
                            <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>Complété</option>
                        </select>
                    </div>

                    <!-- Document Type -->
                    <div>
                        <select name="document_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Tous documents</option>
                            @foreach($documents as $doc)
                                <option value="{{ $doc->id }}" {{ request('document_id') == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->label_fr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filiere -->
                    <div>
                        <select name="filiere_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Toutes filières</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->label_fr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <select name="academic_year" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Toutes années</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}/{{ $year + 1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-md hover:bg-amber-700 transition-colors">
                            Filtrer
                        </button>
                        <a href="{{ route('admin.document-requests.index') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="flex flex-wrap gap-3 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }}
                            class="bg-white rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">En retard</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="extension_requested" value="1" {{ request('extension_requested') ? 'checked' : '' }}
                            class="bg-white  rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-purple-600 shadow-sm focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Extensions demandées</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="permanent" value="1" {{ request('permanent') ? 'checked' : '' }}
                            class="bg-white rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-600 shadow-sm focus:ring-gray-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Retraits définitifs</span>
                    </label>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <form id="bulkForm" method="POST" action="{{ route('admin.document-requests.bulk-mark-ready') }}">
            @csrf
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <!-- Bulk Action Bar -->
                <div id="bulkActionBar" class="hidden bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-amber-800 dark:text-amber-300">
                            <span id="selectedCount">0</span> demande(s) sélectionnée(s)
                        </span>
                        <button type="submit" class="px-3 py-1 bg-amber-600 text-white text-sm font-medium rounded hover:bg-amber-700 transition-colors">
                            Marquer comme Prêt
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-amber-600">
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Référence
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Étudiant
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Document
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($requests as $request)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $request->isOverdue() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <td class="px-4 py-3">
                                        @if($request->isPending())
                                            <input type="checkbox" name="ids[]" value="{{ $request->id }}" class="select-item rounded border-gray-300 dark:border-gray-600 text-amber-600">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('admin.document-requests.show', $request->id) }}" class="text-sm font-mono font-medium text-amber-600 hover:text-amber-800 dark:text-amber-400">
                                            {{ $request->reference_number }}
                                        </a>
                                        @if($request->extension_requested_at)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                                    Extension
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $request->student->full_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $request->student->cne ?? 'N/A' }}
                                            @if($request->student->programEnrollments->isNotEmpty())
                                                <span class="mx-1">•</span>
                                                {{ Str::limit($request->student->programEnrollments->first()->filiere->label_fr ?? '', 15) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $request->document->label_fr ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $request->document->type_label ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($request->status === 'PENDING') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @elseif($request->status === 'READY') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($request->status === 'PICKED') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                            @endif">
                                            {{ $request->status_label }}
                                        </span>
                                        @if($request->isOverdue())
                                            <div class="mt-1">
                                                <span class="px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded">
                                                    {{ $request->getDaysOverdue() }}j retard
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-xs px-2 py-1 rounded {{ $request->retrait_type === 'temporaire' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                                            {{ ucfirst($request->retrait_type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->created_at->format('d/m/Y') }}
                                        <div class="text-xs">{{ $request->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Quick Actions based on status -->
                                            @if($request->isPending())
                                   
                                                <form action="{{ route('admin.document-requests.mark-ready', $request->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors shadow-sm" title="Marquer Prêt">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Prêt
                                                    </button>
                                                </form>
                                            @elseif($request->isReady())
                                                <form action="{{ route('admin.document-requests.mark-picked', $request->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 transition-colors shadow-sm" title="Marquer Retiré">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Retiré
                                                    </button>
                                                </form>
                                            @elseif($request->isPicked() && $request->isTemporaire())
                                                <form action="{{ route('admin.document-requests.mark-completed', $request->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 transition-colors shadow-sm" title="Marquer Retourné">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                        </svg>
                                                        Retourné
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Décharge Download for ready/picked/completed -->
                                            @if($request->isReady() || $request->isPicked() || $request->isCompleted())
                                                <a href="{{ route('admin.document-requests.decharge', $request->id) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors" title="Télécharger Décharge">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Décharge
                                                </a>
                                            @endif

                                            <!-- View Details -->
                                            <a href="{{ route('admin.document-requests.show', $request->id) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Voir détails">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Détails
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Aucune demande trouvée</p>
                                        <a href="{{ route('admin.document-requests.index') }}" class="mt-2 inline-block text-sm text-amber-600 hover:text-amber-500">
                                            Voir toutes les demandes
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($requests->hasPages())
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    // Bulk selection handling
    const selectAll = document.getElementById('selectAll');
    const selectItems = document.querySelectorAll('.select-item');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkActionBar() {
        const checked = document.querySelectorAll('.select-item:checked');
        selectedCount.textContent = checked.length;
        bulkActionBar.classList.toggle('hidden', checked.length === 0);
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

    // AJAX handling for status change buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Handle all mark-ready forms
        const markReadyForms = document.querySelectorAll('form[action*="mark-ready"]');
        markReadyForms.forEach(form => {
            form.addEventListener('submit', handleStatusChange);
        });

        // Handle all mark-picked forms
        const markPickedForms = document.querySelectorAll('form[action*="mark-picked"]');
        markPickedForms.forEach(form => {
            form.addEventListener('submit', handleStatusChange);
        });

        // Handle all mark-completed forms
        const markCompletedForms = document.querySelectorAll('form[action*="mark-completed"]');
        markCompletedForms.forEach(form => {
            form.addEventListener('submit', handleStatusChange);
        });
    });

    function handleStatusChange(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const originalContent = button.innerHTML;

        // Disable button and show loading
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        // Get form data
        const formData = new FormData(form);

        // Send AJAX request
        fetch(form.action, {
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
                // Show success notification
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
    }

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
