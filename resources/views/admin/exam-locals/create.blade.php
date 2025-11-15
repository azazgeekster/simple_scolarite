@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nouveau Local d'Examen</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Créer un nouveau local pour les examens
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <form action="{{ route('admin.exam-locals.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Code -->
                <div>
                    <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="code"
                           id="code"
                           value="{{ old('code') }}"
                           required
                           placeholder="Ex: FS2, AMPHI8"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Ex: Salle 2 - Bloc F"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type"
                            id="type"
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('type') border-red-500 @enderror">
                        <option value="">Sélectionnez un type</option>
                        <option value="salle" {{ old('type') === 'salle' ? 'selected' : '' }}>Salle</option>
                        <option value="amphi" {{ old('type') === 'amphi' ? 'selected' : '' }}>Amphithéâtre</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bloc -->
                <div>
                    <label for="bloc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Bloc
                    </label>
                    <select name="bloc"
                            id="bloc"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('bloc') border-red-500 @enderror">
                        <option value="">Sélectionnez un bloc</option>
                        <option value="F" {{ old('bloc') === 'F' ? 'selected' : '' }}>Bloc F</option>
                        <option value="E" {{ old('bloc') === 'E' ? 'selected' : '' }}>Bloc E</option>
                        <option value="AMPHI" {{ old('bloc') === 'AMPHI' ? 'selected' : '' }}>Amphithéâtre</option>
                    </select>
                    @error('bloc')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Capacité Totale <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="capacity"
                           id="capacity"
                           value="{{ old('capacity') }}"
                           required
                           min="1"
                           max="500"
                           placeholder="Ex: 90"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('capacity') border-red-500 @enderror">
                    @error('capacity')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rows -->
                <div>
                    <label for="rows" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nombre de Rangées
                    </label>
                    <input type="number"
                           name="rows"
                           id="rows"
                           value="{{ old('rows') }}"
                           min="1"
                           max="50"
                           placeholder="Ex: 10"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('rows') border-red-500 @enderror">
                    @error('rows')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optionnel - pour la disposition des sièges</p>
                </div>

                <!-- Seats per Row -->
                <div>
                    <label for="seats_per_row" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Places par Rangée
                    </label>
                    <input type="number"
                           name="seats_per_row"
                           id="seats_per_row"
                           value="{{ old('seats_per_row') }}"
                           min="1"
                           max="50"
                           placeholder="Ex: 9"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('seats_per_row') border-red-500 @enderror">
                    @error('seats_per_row')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optionnel - doit correspondre à la capacité</p>
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Local actif (disponible pour les allocations)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Notes
                </label>
                <textarea name="notes"
                          id="notes"
                          rows="3"
                          placeholder="Remarques ou informations supplémentaires..."
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                <h3 class="font-medium mb-2">Information</h3>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Le code doit être unique (ex: FS2 pour Salle 2 Bloc F)</li>
                    <li>Si vous spécifiez des rangées et places par rangée, leur produit doit égaler la capacité</li>
                    <li>Les locaux inactifs ne seront pas disponibles pour les allocations de sièges</li>
                </ul>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.exam-locals.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Annuler
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Créer le Local
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
