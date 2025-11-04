@extends("student.layouts.app")

@section("title", "Nouvelle Réclamation")

@section("main_content")
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('student.grades') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux notes
            </a>
        </div>

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Nouvelle Réclamation
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Soumettez une réclamation concernant votre note
            </p>
        </div>

        {{-- Module Info Card --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-750 rounded-2xl p-6 mb-6 border border-blue-100 dark:border-gray-700">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                        {{ $moduleGrade->module->label }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $moduleGrade->module->code }}
                    </p>
                    <div class="mt-3 flex items-center gap-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Note actuelle:</span>
                        <span class="inline-flex items-center justify-center px-3 py-1.5 bg-white dark:bg-gray-700 rounded-lg font-bold text-lg
                            @if($moduleGrade->final_grade >= 10) text-green-600 dark:text-green-400
                            @else text-red-600 dark:text-red-400
                            @endif">
                            {{ number_format($moduleGrade->final_grade, 2) }}/20
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reclamation Form --}}
        <form action="{{ route('reclamations.store', $moduleGrade) }}" method="POST" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                {{-- Reclamation Type --}}
                <div>
                    <label for="reclamation_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Type de réclamation <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        @foreach($reclamationTypes as $key => $label)
                            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 transition-colors
                                {{ old('reclamation_type') === $key ? 'border-blue-500 dark:border-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <input type="radio"
                                       name="reclamation_type"
                                       value="{{ $key }}"
                                       {{ old('reclamation_type') === $key ? 'checked' : '' }}
                                       class="mt-1 w-4 h-4 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 border-gray-300 dark:border-gray-600">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $label }}</p>
                                    @if($key === 'grade_calculation_error')
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">La note finale ne correspond pas au calcul (CC + Examen)</p>
                                    @elseif($key === 'missing_grade')
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Une ou plusieurs notes sont manquantes</p>
                                    @elseif($key === 'transcription_error')
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Erreur lors de la saisie de la note</p>
                                    @elseif($key === 'exam_paper_review')
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Demande de révision de la copie d'examen</p>
                                    @elseif($key === 'other')
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Autre motif à préciser dans la description</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('reclamation_type')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Reason --}}
                <div>
                    <label for="reason" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Explication détaillée <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                        Expliquez clairement et précisément le motif de votre réclamation. Plus votre explication est détaillée, plus vite votre demande sera traitée.
                    </p>
                    <textarea
                        id="reason"
                        name="reason"
                        rows="6"
                        placeholder="Exemple: J'ai obtenu 15/20 au CC et 14/20 à l'examen final. Selon le système de notation (40% CC + 60% Examen), ma note finale devrait être 14.4/20 et non 13.5/20 comme indiqué..."
                        class="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white bg-white dark:bg-gray-900 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                        {{ $errors->has('reason') ? 'border-red-500 dark:border-red-500' : '' }}"
                    >{{ old('reason') }}</textarea>
                    <div class="mt-2 flex items-center justify-between">
                        @error('reason')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400">Minimum 10 caractères, maximum 1000 caractères</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <span id="charCount">0</span>/1000
                        </p>
                    </div>
                </div>

                {{-- Warning --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 dark:border-yellow-600 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="text-sm text-yellow-800 dark:text-yellow-300">
                            <p class="font-semibold mb-1">Important</p>
                            <ul class="space-y-1 text-xs">
                                <li>• Une seule réclamation par module est autorisée</li>
                                <li>• Assurez-vous que les informations fournies sont exactes</li>
                                <li>• Vous recevrez une réponse dans les meilleurs délais</li>
                                <li>• Les fausses déclarations peuvent entraîner des sanctions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-gray-50 dark:bg-gray-750 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                <a href="{{ route('student.grades') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Soumettre la Réclamation
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // Character counter
    const textarea = document.getElementById('reason');
    const charCount = document.getElementById('charCount');

    if (textarea && charCount) {
        // Update on page load
        charCount.textContent = textarea.value.length;

        // Update on input
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
</script>
@endsection
