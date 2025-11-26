@extends('admin.layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <a href="{{ route('admin.document-requests.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour aux demandes
                </a>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl">
                    Statistiques des Demandes
                </h2>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date début</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class=" bg-white text-black rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date fin</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class=" bg-white text-black rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-md hover:bg-amber-700">
                    Appliquer
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribution par Statut</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($statusDistribution as $status)
                            @php
                                $total = $statusDistribution->sum('count');
                                $percentage = $total > 0 ? round(($status->count / $total) * 100) : 0;
                                $color = match($status->status) {
                                    'PENDING' => 'yellow',
                                    'READY' => 'blue',
                                    'PICKED' => 'green',
                                    'COMPLETED' => 'gray',
                                    default => 'gray'
                                };
                                $label = match($status->status) {
                                    'PENDING' => 'En attente',
                                    'READY' => 'Prêt',
                                    'PICKED' => 'Retiré',
                                    'COMPLETED' => 'Complété',
                                    default => $status->status
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $status->count }} ({{ $percentage }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Document Type Popularity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Documents les plus demandés</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($byDocumentType as $doc)
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $doc->document }}</span>
                                    <span class="ml-2 text-xs px-2 py-0.5 rounded {{ $doc->type === 'DEPOSITED' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                        {{ $doc->type === 'DEPOSITED' ? 'Déposé' : 'Généré' }}
                                    </span>
                                </div>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $doc->count }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucune donnée</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Definitive Withdrawals by Month -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Retraits Définitifs par Mois</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Documents déposés retirés définitivement</p>
                </div>
                <div class="p-6">
                    @if($definitiveByMonth->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($definitiveByMonth as $month)
                                @php
                                    $monthName = \Carbon\Carbon::create($month->year, $month->month)->translatedFormat('F Y');
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($monthName) }}</span>
                                    <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ $month->count }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Total</span>
                                <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $definitiveByMonth->sum('count') }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucun retrait définitif sur cette période</p>
                    @endif
                </div>
            </div>

            <!-- Withdrawals by Filiere -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Retraits Définitifs par Filière</h3>
                </div>
                <div class="p-6">
                    @if($byFiliere->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($byFiliere as $filiere)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $filiere->filiere }}</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $filiere->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucune donnée</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Processing Time -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-amber-600">
                            {{ $avgProcessingTime ? round($avgProcessingTime) : 0 }}h
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Temps moyen de traitement</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">
                            {{ $statusDistribution->where('status', 'COMPLETED')->first()->count ?? 0 }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Demandes complétées</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600">
                            {{ $statusDistribution->sum('count') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total demandes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
