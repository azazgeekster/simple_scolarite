@extends('admin.layouts.app')

@section('title', 'Convocations Rattrapage')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Convocations Rattrapage</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Convoquer les étudiants RATT aux examens de rattrapage</p>
        </div>
        <a href="{{ route('admin.grades.rattrapage.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
    </div>

    {{-- Info Alert --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-700 dark:text-blue-300">
                <p class="font-semibold mb-1">Comment ça marche:</p>
                <ol class="list-decimal list-inside space-y-1">
                    <li><strong>Création automatique:</strong> Lors de la planification d'un examen de rattrapage, seuls les étudiants RATT sont automatiquement convoqués</li>
                    <li><strong>Ajout manuel:</strong> Utilisez cette page pour convoquer les étudiants qui sont devenus éligibles après la création de l'examen (ex: absences justifiées)</li>
                    <li><strong>Éligibilité:</strong> Seuls les étudiants avec RATT (note < 10 ou absence justifiée) peuvent être convoqués</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Exams Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exams as $exam)
            @php
                $module = $exam->module;
                $eligibleCount = \App\Models\ModuleGrade::where('result', 'RATT')
                    ->where('session', 'normal')
                    ->whereHas('moduleEnrollment', function($q) use ($module) {
                        $q->where('module_id', $module->id);
                    })
                    ->count();
                
                $convocatedCount = \App\Models\ExamConvocation::where('exam_id', $exam->id)->count();
            @endphp
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">{{ $module->label }}</h3>
                    <p class="text-sm text-purple-100">{{ $module->code }}</p>
                </div>
                
                {{-- Card Body --}}
                <div class="p-4 space-y-3">
                    {{-- Exam Details --}}
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">Date:</span>
                            <span class="ml-2">{{ $exam->exam_date ? $exam->exam_date->format('d/m/Y') : 'Non définie' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Heure:</span>
                            <span class="ml-2">
                                @if($exam->start_time && $exam->end_time)
                                    {{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }}
                                @else
                                    Non définie
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    {{-- Divider --}}
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                    
                    {{-- Statistics --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $eligibleCount }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Éligibles</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $convocatedCount }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Convoqués</div>
                        </div>
                    </div>
                    
                    {{-- Action Button --}}
                    @if($eligibleCount > 0)
                        <form action="{{ route('admin.grades.rattrapage.convocate', $exam->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Convoquer {{ $eligibleCount }} étudiant(s)?')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                Convoquer Tous ({{ $eligibleCount }})
                            </button>
                        </form>
                    @else
                        <button disabled 
                                class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-medium rounded-lg cursor-not-allowed">
                            Aucun étudiant éligible
                        </button>
                    @endif
                </div>
                
                {{-- Card Footer --}}
                <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Période: {{ $exam->examPeriod->label ?? 'N/A' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucun examen de rattrapage</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Créez d'abord une période d'examen de rattrapage et planifiez les examens.</p>
                    <a href="{{ route('admin.exam-scheduling.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Gérer les Examens
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Bulk Convocation --}}
    @if($exams->count() > 1)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Convocation en Masse</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.grades.rattrapage.bulk-convocate') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Sélectionner les examens</label>
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 max-h-64 overflow-y-auto bg-gray-50 dark:bg-gray-900/50">
                            <div class="space-y-2">
                                @foreach($exams as $exam)
                                    <label class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded cursor-pointer">
                                        <input type="checkbox" 
                                               name="exam_ids[]" 
                                               value="{{ $exam->id }}" 
                                               class="rounded border-gray-300 dark:border-gray-600 text-purple-600 focus:ring-purple-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $exam->module->label }} ({{ $exam->module->code }})
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                onclick="return confirm('Convoquer tous les étudiants éligibles pour les examens sélectionnés?')"
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Convoquer en Masse
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
