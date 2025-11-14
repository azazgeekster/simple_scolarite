@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Périodes d'Examens</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Gérer les dates des sessions d'examens et publier/dépublier les examens par période
            </p>
        </div>
        <a href="{{ route('admin.exam-periods.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nouvelle Période
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Succès!</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Erreur!</span> {{ session('error') }}
        </div>
    @endif

    <!-- Academic Year Selector -->
    <div class="mb-6 bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.exam-periods.index') }}" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Année Universitaire
                </label>
                <select name="year"
                        id="year"
                        onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->start_year }}" {{ $selectedYear == $year->start_year ? 'selected' : '' }}>
                            {{ $year->start_year }}-{{ $year->end_year }}
                            @if($year->is_current)
                                (En cours)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Current Period Indicator -->
    @if($currentPeriod)
        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-300 dark:border-blue-700 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Période Actuelle: {{ $currentPeriod->label }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Du {{ $currentPeriod->start_date->format('d/m/Y') }} au {{ $currentPeriod->end_date->format('d/m/Y') }}
                        •
                        <span class="font-semibold">
                            @if($currentPeriod->isOngoing())
                                En cours
                            @elseif($currentPeriod->isUpcoming())
                                À venir
                            @else
                                Terminée
                            @endif
                        </span>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Periods List -->
    @if($periods->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucune période</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Commencez en créant une nouvelle période d'examens
            </p>
            <div class="mt-6">
                <a href="{{ route('admin.exam-periods.create') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Créer une période
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($periods as $period)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg border-l-4
                    {{ $period->is_active ? 'border-green-500' : 'border-gray-300 dark:border-gray-600' }}">
                    <div class="p-6">
                        <!-- Period Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $period->label }}
                                    </h3>

                                    <!-- Status Badges -->
                                    @if($period->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                            Activée
                                        </span>
                                    @endif

                                    @if($period->isOngoing())
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-200">
                                            En cours
                                        </span>
                                    @elseif($period->isUpcoming())
                                        <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">
                                            À venir
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-200">
                                            Terminée
                                        </span>
                                    @endif

                                    @if($period->auto_publish_exams)
                                        <span class="px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full dark:bg-purple-900 dark:text-purple-200">
                                            Auto-publication
                                        </span>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    <p>
                                        <span class="font-medium">Période:</span>
                                        Du {{ $period->start_date->format('d/m/Y') }} au {{ $period->end_date->format('d/m/Y') }}
                                        ({{ $period->start_date->diffInDays($period->end_date) + 1 }} jours)
                                    </p>
                                    <p>
                                        <span class="font-medium">Type:</span>
                                        {{ $period->session_type === 'normal' ? 'Session Normale' : 'Session Rattrapage' }}
                                        •
                                        <span class="font-medium">Saison:</span>
                                        {{ $period->season === 'autumn' ? 'Automne' : 'Printemps' }}
                                    </p>
                                    @if($period->description)
                                        <p class="text-xs">{{ $period->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Exam Statistics -->
                        @php
                            $stats = $period->getExamStats();
                        @endphp
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Total Examens</div>
                                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3">
                                <div class="text-xs text-green-600 dark:text-green-400">Publiés</div>
                                <div class="text-xl font-bold text-green-700 dark:text-green-300">{{ $stats['published'] }}</div>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-3">
                                <div class="text-xs text-yellow-600 dark:text-yellow-400">Non Publiés</div>
                                <div class="text-xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['unpublished'] }}</div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                                <div class="text-xs text-blue-600 dark:text-blue-400">Étudiants</div>
                                <div class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['students'] }}</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <!-- Activate/Deactivate -->
                            @if(!$period->is_active)
                                <form action="{{ route('admin.exam-periods.activate', $period) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Activer
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.exam-periods.deactivate', $period) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Désactiver
                                    </button>
                                </form>
                            @endif

                            <!-- Publish All Exams -->
                            @if($stats['unpublished'] > 0)
                                <form action="{{ route('admin.exam-periods.publish-exams', $period) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Publier tous les {{ $stats['unpublished'] }} examens non publiés ?')">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Publier Tout ({{ $stats['unpublished'] }})
                                    </button>
                                </form>
                            @endif

                            <!-- Unpublish All Exams -->
                            @if($stats['published'] > 0)
                                <form action="{{ route('admin.exam-periods.unpublish-exams', $period) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Dépublier tous les {{ $stats['published'] }} examens publiés ?')">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                        Dépublier Tout ({{ $stats['published'] }})
                                    </button>
                                </form>
                            @endif

                            <!-- Edit -->
                            <a href="{{ route('admin.exam-periods.edit', $period) }}"
                               class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </a>

                            <!-- Delete -->
                            @if($stats['total'] === 0)
                                <form action="{{ route('admin.exam-periods.destroy', $period) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette période ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            @else
                                <button type="button"
                                        disabled
                                        title="Impossible de supprimer une période avec des examens"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed dark:bg-gray-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Supprimer
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
