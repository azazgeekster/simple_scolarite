@extends("student.layouts.app")

@section("title", "Demande de Relevé de Notes")

@section("main_content")
    <x-flash-message type="error"></x-flash-message>
    <x-flash-message type="success"></x-flash-message>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 p-4 md:p-6" x-data="transcriptRequest()">
        <div class="max-w-6xl mx-auto">

            {{-- Header Section --}}
            <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl shadow-xl border dark:border-gray-700 p-8 mb-8">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 rounded-full -translate-y-16 translate-x-16 opacity-50"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-purple-100 to-pink-100 dark:from-purple-900 dark:to-pink-900 rounded-full translate-y-12 -translate-x-12 opacity-30"></div>

                <div class="relative flex items-center gap-6">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Demande de Relevé de Notes</h1>
                        <p class="text-gray-600 dark:text-gray-300">Sélectionnez l'année académique pour laquelle vous souhaitez obtenir un relevé</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Available Transcripts Section --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-750 px-8 py-6 border-b dark:border-gray-600">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-xl">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Relevés Disponibles</h2>
                                <span class="ml-auto bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ count($availableReleves) }} disponibles
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse($availableReleves as $index => $releve)
                                    <div class="group relative overflow-hidden border border-gray-200 dark:border-gray-600 rounded-xl p-6 transition-all duration-300 {{ $releve['disponible'] ? 'hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-lg cursor-pointer bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-gray-700 dark:hover:to-gray-750' : 'bg-gray-50 dark:bg-gray-700 opacity-75' }}"
                                        @if($releve['disponible'])
                                            @click="openModal(
                                                '{{ $releve['academic_year_label'] }} ({{ $releve['semesters'] }})',
                                                '{{ $releve['year_label'] }}',
                                                {{ $releve['academic_year'] }},
                                                '{{ $releve['semesters'] }}'
                                            )"
                                        @endif>

                                        @if($releve['disponible'])
                                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">
                                                        {{ $releve['academic_year_label'] }}
                                                    </h3>
                                                    <span class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-lg text-sm font-medium">
                                                        {{ $releve['semesters'] }}
                                                    </span>
                                                </div>

                                                <div class="space-y-1">
                                                    <p class="text-gray-600 dark:text-gray-300 font-medium">{{ $releve['filiere_label'] }}</p>
                                                    <p class="text-blue-600 dark:text-blue-400 font-medium text-sm">{{ $releve['year_label'] }}</p>
                                                </div>
                                            </div>

                                            <div class="flex flex-col items-end gap-2">
                                                <span class="px-4 py-2 text-sm font-bold rounded-full {{ $releve['disponible'] ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-gray-300' }}">
                                                    {{ $releve['disponible'] ? '✓ Disponible' : '⏳ Non disponible' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg">Aucun relevé disponible pour le moment</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- My Requests Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border dark:border-gray-700 overflow-hidden sticky top-6">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-750 px-6 py-5 border-b dark:border-gray-600">
                            <div class="flex items-center gap-3">
                                <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-xl">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Mes Demandes</h2>
                                <span class="ml-auto bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded-full text-xs font-bold">
                                    {{ count($studentDemandes) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 max-h-[600px] overflow-y-auto">
                            @forelse($studentDemandes as $demande)
                                <div class="mb-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-900 dark:text-white text-sm">
                                                {{ $demande->academicYear->label ?? $demande->academic_year . '-' . ($demande->academic_year + 1) }}
                                            </p>
                                            @if($demande->semester)
                                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $demande->semester }}</p>
                                            @else
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Année complète</p>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs font-bold rounded-full
                                            {{ $demande->status === 'PENDING' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                            {{ $demande->status === 'READY' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                            {{ $demande->status === 'PICKED' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ $demande->status === 'COMPLETED' ? 'bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-200' : '' }}">
                                            {{ $demande->status }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <p>Type: {{ ucfirst($demande->retrait_type) }}</p>
                                        <p>Demandé le: {{ $demande->created_at->format('d/m/Y') }}</p>
                                        @if($demande->reference_number)
                                            <p class="font-mono">Réf: {{ $demande->reference_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune demande</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal for Request Form --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
             style="display: none;">

            <div @click.away="showModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-2xl border dark:border-gray-700 overflow-hidden">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold">Confirmer la Demande</h3>
                            <p class="text-blue-100 text-sm mt-1">Choisissez les détails de votre relevé</p>
                        </div>
                        <button @click="showModal = false" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('student.releve.store') }}">
                    @csrf

                    <div class="p-8">
                        {{-- Selected Year Info --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white" x-text="selectedYearLabel"></p>
                                    <p class="text-sm text-blue-600 dark:text-blue-400" x-text="selectedLevelLabel"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Field for Academic Year --}}
                        <input type="hidden" name="academic_year" x-model="selectedAcademicYear">

                        {{-- Form Fields --}}
                        <div class="space-y-6">
                            {{-- Semester Selection --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Période
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all">
                                        <input type="radio" name="semester" value="" class="w-4 h-4 text-blue-600" checked>
                                        <span class="ml-3 text-gray-900 dark:text-white font-medium">Année complète (<span x-text="selectedSemesters"></span>)</span>
                                    </label>

                                    <template x-if="selectedSemesters">
                                        <div class="grid grid-cols-2 gap-3">
                                            <template x-for="sem in getSemesterOptions()" :key="sem">
                                                <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all">
                                                    <input type="radio" name="semester" :value="sem" class="w-4 h-4 text-blue-600">
                                                    <span class="ml-2 text-gray-900 dark:text-white font-medium" x-text="sem"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Retrait Type --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Type de retrait
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-start p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all">
                                        <input type="radio" name="retrait_type" value="temporaire" class="w-4 h-4 text-blue-600 mt-1" required>
                                        <div class="ml-3">
                                            <span class="text-gray-900 dark:text-white font-medium block">Temporaire</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">À retourner après utilisation</span>
                                        </div>
                                    </label>
                                    <label class="flex items-start p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all">
                                        <input type="radio" name="retrait_type" value="definitif" class="w-4 h-4 text-blue-600 mt-1" required>
                                        <div class="ml-3">
                                            <span class="text-gray-900 dark:text-white font-medium block">Définitif</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Conservation permanente</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="flex gap-3 mt-8">
                            <button type="button"
                                    @click="showModal = false"
                                    class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all">
                                Confirmer la demande
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function transcriptRequest() {
            return {
                showModal: false,
                selectedYearLabel: '',
                selectedLevelLabel: '',
                selectedAcademicYear: null,
                selectedSemesters: '',

                openModal(yearLabel, levelLabel, academicYear, semesters) {
                    this.selectedYearLabel = yearLabel;
                    this.selectedLevelLabel = levelLabel;
                    this.selectedAcademicYear = academicYear;
                    this.selectedSemesters = semesters;
                    this.showModal = true;
                },

                getSemesterOptions() {
                    // Parse semesters string like "S1-S2" or "S3-S4"
                    if (!this.selectedSemesters) return [];

                    const parts = this.selectedSemesters.split('-');
                    if (parts.length !== 2) return [];

                    return [parts[0], parts[1]];
                }
            }
        }
    </script>
    @endpush
@endsection