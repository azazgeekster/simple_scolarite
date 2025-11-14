@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Créer une Période d'Examens</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Définir une nouvelle période d'examens pour une année universitaire
        </p>
    </div>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Erreur!</span> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Erreurs de validation:</span>
            <ul class="mt-2 ml-4 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.exam-periods.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Année Universitaire <span class="text-red-500">*</span>
                        </label>
                        <select name="academic_year"
                                id="academic_year"
                                required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->start_year }}" {{ (old('academic_year') == $year->start_year || $year->is_current) ? 'selected' : '' }}>
                                    {{ $year->start_year }}-{{ $year->end_year }}
                                    @if($year->is_current)
                                        (En cours)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Season -->
                    <div>
                        <label for="season" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Saison <span class="text-red-500">*</span>
                        </label>
                        <select name="season"
                                id="season"
                                required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="autumn" {{ old('season') == 'autumn' ? 'selected' : '' }}>
                                Automne
                            </option>
                            <option value="spring" {{ old('season') == 'spring' ? 'selected' : '' }}>
                                Printemps
                            </option>
                        </select>
                        @error('season')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Session Type -->
                    <div>
                        <label for="session_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Type de Session <span class="text-red-500">*</span>
                        </label>
                        <select name="session_type"
                                id="session_type"
                                required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="normal" {{ old('session_type') == 'normal' ? 'selected' : '' }}>
                                Session Normale
                            </option>
                            <option value="rattrapage" {{ old('session_type') == 'rattrapage' ? 'selected' : '' }}>
                                Session Rattrapage
                            </option>
                        </select>
                        @error('session_type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Label (auto-generated but editable) -->
                    <div>
                        <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Libellé <span class="text-gray-400 text-xs">(optionnel, généré automatiquement)</span>
                        </label>
                        <input type="text"
                               name="label"
                               id="label"
                               value="{{ old('label') }}"
                               placeholder="Ex: Session Normale - Automne 2024"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @error('label')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Date de Début <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               value="{{ old('start_date') }}"
                               required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('start_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Date de Fin <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               value="{{ old('end_date') }}"
                               required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('end_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Description <span class="text-gray-400 text-xs">(optionnel)</span>
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              placeholder="Notes ou informations supplémentaires..."
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options -->
                <div class="mt-6 space-y-4">
                    <!-- Auto-publish exams -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   name="auto_publish_exams"
                                   id="auto_publish_exams"
                                   value="1"
                                   {{ old('auto_publish_exams') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="auto_publish_exams" class="font-medium text-gray-900 dark:text-white">
                                Auto-publier les examens lors de l'activation
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Tous les examens de cette période seront automatiquement publiés quand la période est activée
                            </p>
                        </div>
                    </div>

                    <!-- Activate immediately -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   {{ old('is_active') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-900 dark:text-white">
                                Activer immédiatement
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Définir cette période comme active dès sa création (désactive les autres périodes du même type)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                    <h3 class="font-medium mb-2">À propos des périodes:</h3>
                    <ul class="space-y-1 text-xs ml-4 list-disc">
                        <li>Une seule période peut exister par année/saison/session (ex: une seule "Session Normale - Automne 2024")</li>
                        <li>Les examens seront liés à cette période lors de l'importation</li>
                        <li>Vous pouvez publier/dépublier tous les examens d'une période en un clic</li>
                        <li>Une seule période peut être active à la fois pour un type de session donné</li>
                    </ul>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.exam-periods.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Créer la Période
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
