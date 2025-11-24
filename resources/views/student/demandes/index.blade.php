@extends("student.layouts.app")

@section("main_content")
    @if($overdueDemandes->count() > 0)
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-red-800 dark:text-red-200">
                        ⚠️ Documents en retard ({{ $overdueDemandes->count() }})
                    </h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                        Vous avez {{ $overdueDemandes->count() }} document(s) qui n'ont pas été retournés dans les délais.
                        Veuillez les retourner immédiatement à la scolarité pour éviter des pénalités.
                    </p>
                </div>
            </div>
        </div>
    @endif
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-blue-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h1
                        class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Retrait de documents
                    </h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                    Gérez vos demandes de documents académiques en toute simplicité
                </p>
            </div>

            <x-flash-message type="error" />
            <x-flash-message type="success" />

            <!-- Request Form Card -->
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-6 sm:p-8 mb-8 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-white">
                        Nouvelle demande
                    </h2>
                </div>

                {{-- //////////////// --}}


                <div class="mb-6 space-y-3">
                    <!-- Cancellation Notice -->
                    <div
                        class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 dark:border-amber-600 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-bold text-amber-800 dark:text-amber-200">Délai d'annulation</h4>
                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                                    Vous pouvez annuler votre demande dans les <strong>5 minutes</strong> suivant sa
                                    soumission.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Return Time Notice (for documents requiring return) -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-200">Délai de retour (documents
                                    temporaires)</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                    Pour les documents à retour temporaire : vous devez retourner le document dans un délai
                                    maximum de <strong>48 heures</strong> après le retrait, sinon le retrait devient
                                    définitive.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- //////////////// --}}




                <form method="POST" action="{{ route('student.demande.store') }}" x-data="demandeForm()">
                    @csrf

                    <!-- Document Cards Grid -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Sélectionnez un ou plusieurs documents</span>
                            </span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($documents as $doc)
                                @if($doc->slug !== 'releve_notes')
                                    <label
                                        class="relative cursor-pointer group"
                                        :class="selectedDocuments.includes({{ $doc->id }}) ? 'ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-800' : ''"
                                    >
                                        <input
                                            type="checkbox"
                                            name="document_ids[]"
                                            value="{{ $doc->id }}"
                                            data-requires-return="{{ $doc->requires_return ? '1' : '0' }}"
                                            class="sr-only"
                                            @change="toggleDocument({{ $doc->id }}, $event.target.checked, {{ $doc->requires_return ? 'true' : 'false' }})"
                                        >
                                        <div
                                            class="relative p-4 rounded-xl border-2 transition-all duration-200 h-full"
                                            :class="selectedDocuments.includes({{ $doc->id }})
                                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                                                : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-500 hover:shadow-md'"
                                        >
                                            <!-- Checkmark indicator -->
                                            <div
                                                class="absolute top-2 right-2 w-6 h-6 rounded-full flex items-center justify-center transition-all duration-200"
                                                :class="selectedDocuments.includes({{ $doc->id }})
                                                    ? 'bg-blue-500 text-white'
                                                    : 'bg-gray-100 dark:bg-gray-600 text-gray-400'"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>

                                            <!-- Document Icon -->
                                            <div class="mb-3">
                                                @php
                                                    $iconColor = match($doc->slug) {
                                                        'attestation_inscription' => 'text-blue-500',
                                                        'attestation_reussite' => 'text-green-500',
                                                        'certificat_scolarite' => 'text-purple-500',
                                                        'diplome' => 'text-amber-500',
                                                        'releve_notes' => 'text-indigo-500',
                                                        default => 'text-gray-500'
                                                    };
                                                    $bgColor = match($doc->slug) {
                                                        'attestation_inscription' => 'bg-blue-100 dark:bg-blue-900/30',
                                                        'attestation_reussite' => 'bg-green-100 dark:bg-green-900/30',
                                                        'certificat_scolarite' => 'bg-purple-100 dark:bg-purple-900/30',
                                                        'diplome' => 'bg-amber-100 dark:bg-amber-900/30',
                                                        'releve_notes' => 'bg-indigo-100 dark:bg-indigo-900/30',
                                                        default => 'bg-gray-100 dark:bg-gray-700'
                                                    };
                                                @endphp
                                                <div class="w-12 h-12 {{ $bgColor }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                    @switch($doc->slug)
                                                        @case('attestation_inscription')
                                                            <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            @break
                                                        @case('attestation_reussite')
                                                            <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                            </svg>
                                                            @break
                                                        @case('certificat_scolarite')
                                                            <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                            </svg>
                                                            @break
                                                        @case('diplome')
                                                            <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                                            </svg>
                                                            @break
                                                        @default
                                                            <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                    @endswitch
                                                </div>
                                            </div>

                                            <!-- Document Name -->
                                            <h4 class="font-semibold text-sm text-gray-900 dark:text-white mb-1 pr-6">
                                                {{ $doc->label_fr }}
                                            </h4>

                                            <!-- Return indicator -->
                                            @if($doc->requires_return)
                                                <span class="inline-flex items-center gap-1 text-xs text-amber-600 dark:text-amber-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Retour possible
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-xs text-green-600 dark:text-green-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Remise définitive
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Selected count badge -->
                    <div x-show="selectedDocuments.length > 0" x-transition class="mb-4">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-semibold" x-text="selectedDocuments.length + ' document(s) sélectionné(s)'"></span>
                        </div>
                    </div>

                    <!-- Retrait Type (shown when any selected document requires return) -->
                    <div x-show="hasDocumentRequiringReturn" x-transition class="mb-6">
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                            <label for="retrait_type" class="block text-sm font-semibold text-amber-800 dark:text-amber-200 mb-3">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Type de retrait (pour documents avec retour)</span>
                                </span>
                            </label>
                            <select name="retrait_type" id="retrait_type" :required="hasDocumentRequiringReturn"
                                class="w-full sm:w-auto px-4 py-3 rounded-xl border-2 border-amber-200 dark:border-amber-700 bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-all duration-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/20">
                                <option value="" disabled selected>Choisissez le type</option>
                                <option value="temporaire">Temporaire (retour dans 48h)</option>
                                <option value="definitif">Définitif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            :disabled="selectedDocuments.length === 0"
                            :class="selectedDocuments.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:from-blue-700 hover:to-indigo-700 hover:scale-[1.02] hover:shadow-lg'"
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 transform focus:ring-4 focus:ring-blue-500/25 active:scale-[0.98]">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span x-text="selectedDocuments.length > 1 ? 'Soumettre les demandes' : 'Soumettre la demande'">Soumettre</span>
                            </span>
                        </button>
                    </div>
                </form>

            </div>

            <!-- History Section -->
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-white">
                            Historique des demandes
                        </h3>
                    </div>
                </div>

                <!-- Mobile Cards View (visible on small screens) -->
                <div class="block lg:hidden">
                    @forelse($demandes as $demande)
                        <div
                            class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <div class="space-y-4">
                                <!-- Header -->
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-lg">
                                        {{ $demande->document->label_fr }}
                                    </h4>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold w-fit
                                                        @if($demande->status === 'PENDING') bg-yellow-100 text-yellow-800 dark:bg-yellow-300 dark:text-yellow-900
                                                        @elseif($demande->status === 'PROCESSING') bg-blue-100 text-blue-800 dark:bg-blue-300 dark:text-blue-900
                                                        @elseif($demande->status === 'READY') bg-indigo-100 text-indigo-800 dark:bg-indigo-300 dark:text-indigo-900
                                                        @elseif($demande->status === 'COMPLETED') bg-green-100 text-green-800 dark:bg-green-300 dark:text-green-900
                                                        @elseif($demande->status === 'PICKED') bg-red-100 text-red-800 dark:bg-red-300 dark:text-red-900
                                                        @endif">
                                        {{ ucfirst(strtolower(str_replace('_', ' ', $demande->status))) }}
                                    </span>
                                </div>

                                <!-- Details Grid -->
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="space-y-1">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Type:</span>
                                        <p class="text-gray-900 dark:text-white capitalize">{{ $demande->retrait_type }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Année académique:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $demande->academicYear->label }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Demandé le:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $demande->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    @if($demande->retrait_type === 'temporaire' && $demande->must_return_by)
                                        <div class="space-y-1">
                                            <span class="text-gray-500 dark:text-gray-400 font-medium">Retour avant:</span>
                                            <p class="text-red-600 dark:text-red-400 font-semibold">
                                                {{ \Carbon\Carbon::parse($demande->must_return_by)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Extension Info -->
                                @if($demande->extension_days)
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-3">
                                        <div class="text-yellow-700 dark:text-yellow-300 text-sm font-medium">
                                            Extension: +{{ $demande->extension_days }} jours demandés
                                        </div>
                                        <div class="text-yellow-600 dark:text-yellow-400 text-xs">
                                            le {{ \Carbon\Carbon::parse($demande->extension_requested_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Returned Info -->
                                @if($demande->collected_at)
                                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3">
                                        <div class="text-green-700 dark:text-green-300 text-sm font-medium">
                                            Retourné le {{ \Carbon\Carbon::parse($demande->collected_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex flex-wrap gap-2 pt-2">
                                    @if($demande->status === 'PENDING')
                                        <form action="{{ route('student.demande.cancel', $demande->id) }}" method="POST"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Annuler"
                                                class="flex items-center space-x-1 px-3 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg text-sm font-medium transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                <span>Annuler</span>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('student.demande.print', $demande->id) }}" title="Imprimer"
                                        target="_blank"
                                        class="flex items-center space-x-1 px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-400 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                            </path>
                                        </svg>
                                        <span>Imprimer</span>
                                    </a>

                                    @if($demande->status === 'PICKED' && now()->greaterThan($demande->must_return_by))
                                        <button onclick="handleExtension({{ $demande->id }})" title="Demander une extension"
                                            class="flex items-center space-x-1 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-lg text-sm font-medium transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Extension</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div
                                class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">Aucune demande enregistrée.</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Vos demandes apparaîtront ici une fois
                                soumises.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View (hidden on small screens) -->
                <!-- Enhanced Professional Desktop Table View -->
                <div
                    class="hidden lg:block overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Enhanced Table Header -->
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Document
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            Type
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Année
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Créé le
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Retour avant
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Extension
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Retourné
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Statut
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <!-- Enhanced Table Body -->
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($demandes as $demande)
                                    <tr
                                        class="group hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-purple-50/50 dark:hover:from-gray-750 dark:hover:to-gray-700 transition-all duration-300 ease-in-out">

                                        <!-- Document Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                        {{ $demande->document->label_fr }}
                                                    </p>
                                                    @if($demande->semester)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $demande->semester }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Type Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                        @if($demande->retrait_type === 'temporaire')
                                            bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                        @else
                                            bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @endif">
                                                @if($demande->retrait_type === 'temporaire')
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                                <span class="capitalize">{{ $demande->retrait_type }}</span>
                                            </div>
                                        </td>

                                        <!-- Academic Year Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $demande->academicYear->label }}
                                            </div>
                                        </td>

                                        <!-- Created At Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                                <div class="font-medium">{{ $demande->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $demande->created_at->format('H:i') }}</div>
                                            </div>
                                        </td>

                                        <!-- Return Deadline Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($demande->retrait_type === 'temporaire' && $demande->must_return_by)
                                                @php
                                                    $deadline = \Carbon\Carbon::parse($demande->must_return_by);
                                                    $now = now();
                                                    $isOverdue = $now->isAfter($deadline);
                                                    $daysLeft = $now->diffInDays($deadline, false);
                                                @endphp
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                                @if($isOverdue)
                                                    bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-red-600/20 dark:ring-red-400/20
                                                @elseif($daysLeft <= 1)
                                                    bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400 ring-1 ring-orange-600/20 dark:ring-orange-400/20
                                                @else
                                                    bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                @endif">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $deadline->format('d/m/Y') }}</span>
                                                </div>
                                                @if($isOverdue)
                                                    <div class="text-xs text-red-600 dark:text-red-400 font-medium mt-1">
                                                        Retard: {{ abs($daysLeft) }}j
                                                    </div>
                                                @elseif($daysLeft >= 0)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        Reste: {{ $daysLeft }}j
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500 italic">N/A</span>
                                            @endif
                                        </td>

                                        <!-- Extension Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($demande->extension_days)
                                                <div class="inline-flex flex-col gap-1">
                                                    <div
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        <span>+{{ $demande->extension_days }} jours</span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($demande->extension_requested_at)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500 italic">N/A</span>
                                            @endif
                                        </td>

                                        <!-- Returned At Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($demande->collected_at)
                                                <div
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ \Carbon\Carbon::parse($demande->collected_at)->format('d/m/Y') }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500 italic">Non retourné</span>
                                            @endif
                                        </td>

                                        <!-- Status Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($demande->isOverdue())
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 ring-2 ring-red-600/20 dark:ring-red-400/20 shadow-sm">
                                                    <span class="relative flex h-2 w-2">
                                                        <span
                                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                    </span>
                                                    <span>En retard</span>
                                                    <span
                                                        class="bg-red-200 dark:bg-red-800 px-1.5 py-0.5 rounded text-[10px] font-black">
                                                        {{ $demande->getDaysOverdue() }}j
                                                    </span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold shadow-sm
                                                @if($demande->status === 'PENDING')
                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300 ring-1 ring-yellow-600/20
                                                @elseif($demande->status === 'PROCESSING')
                                                    bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 ring-1 ring-blue-600/20
                                                @elseif($demande->status === 'READY')
                                                    bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300 ring-1 ring-indigo-600/20
                                                @elseif($demande->status === 'COMPLETED')
                                                    bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 ring-1 ring-green-600/20
                                                @elseif($demande->status === 'PICKED')
                                                    bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300 ring-1 ring-purple-600/20
                                                @endif">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="relative inline-flex rounded-full h-2 w-2
                                                        @if($demande->status === 'PENDING') bg-yellow-500
                                                        @elseif($demande->status === 'PROCESSING') bg-blue-500
                                                        @elseif($demande->status === 'READY') bg-indigo-500
                                                        @elseif($demande->status === 'COMPLETED') bg-green-500
                                                        @elseif($demande->status === 'PICKED') bg-purple-500
                                                        @endif"></span>
                                                    </span>
                                                    {{ ucfirst(strtolower(str_replace('_', ' ', $demande->status))) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Actions Column -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <!-- Cancel Button -->
                                                @if($demande->status === 'PENDING')
                                                    @php
                                                        $canCancel = $demande->canBeCancelled();
                                                        $remainingMinutes = $demande->getRemainingCancellationTime();
                                                    @endphp

                                                    @if($canCancel)
                                                        <form action="{{ route('student.demande.cancel', $demande->id) }}" method="POST"
                                                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                title="Annuler ({{ $remainingMinutes }} min restantes)"
                                                                class="group/btn relative inline-flex items-center gap-1.5 px-3 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg text-xs font-semibold transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                <span>Annuler</span>
                                                                <span class="bg-red-700/50 px-1.5 py-0.5 rounded text-[10px] font-bold">
                                                                    {{ $remainingMinutes }}min
                                                                </span>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <div
                                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-lg text-xs font-medium cursor-not-allowed opacity-50">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                            </svg>
                                                            <span>Annuler</span>
                                                        </div>
                                                    @endif
                                                @endif

                                                <!-- Print Button -->
                                                <a href="{{ route('student.demande.print', $demande->id) }}"
                                                    title="Imprimer le reçu" target="_blank"
                                                    class="group/btn relative inline-flex items-center justify-center p-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-110">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                </a>

                                                <!-- Extension Button -->
                                                @if($demande->status === 'PICKED' && $demande->retrait_type === 'temporaire' && $demande->must_return_by && now()->greaterThan($demande->must_return_by))
                                                    <button onclick="handleExtension({{ $demande->id }})"
                                                        title="Demander une extension"
                                                        class="group/btn relative inline-flex items-center justify-center p-2 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-110">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-4">
                                                <div class="relative">
                                                    <div
                                                        class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center shadow-inner">
                                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div
                                                        class="absolute -top-1 -right-1 w-6 h-6 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <p class="text-gray-600 dark:text-gray-300 text-lg font-semibold">Aucune
                                                        demande trouvée</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md">
                                                        Commencez par soumettre votre première demande de document. Vos demandes
                                                        apparaîtront ici.
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Enhanced Mobile Card View -->
                <div class="block lg:hidden space-y-4">
                    @forelse($demandes as $demande)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md hover:border-blue-300 dark:hover:border-blue-700">
                            <!-- Card Header with Status -->
                            <div
                                class="relative px-4 py-3 bg-gradient-to-r from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div
                                            class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white text-base truncate">
                                                {{ $demande->document->label_fr }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $demande->academicYear->label }}
                                                @if($demande->semester) • {{ $demande->semester }} @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    @if($demande->isOverdue())
                                        <span
                                            class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 ring-1 ring-red-600/20">
                                            <span class="relative flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                            </span>
                                            Retard
                                        </span>
                                    @else
                                        <span class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold
                                        @if($demande->status === 'PENDING') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                                        @elseif($demande->status === 'PROCESSING') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                                        @elseif($demande->status === 'READY') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300
                                        @elseif($demande->status === 'COMPLETED') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                                        @elseif($demande->status === 'PICKED') bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300
                                        @endif">
                                            <span class="relative flex h-2 w-2">
                                                <span class="relative inline-flex rounded-full h-2 w-2
                                                @if($demande->status === 'PENDING') bg-yellow-500
                                                @elseif($demande->status === 'PROCESSING') bg-blue-500
                                                @elseif($demande->status === 'READY') bg-indigo-500
                                                @elseif($demande->status === 'COMPLETED') bg-green-500
                                                @elseif($demande->status === 'PICKED') bg-purple-500
                                                @endif"></span>
                                            </span>
                                            {{ ucfirst(strtolower(str_replace('_', ' ', $demande->status))) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body - Details Grid -->
                            <div class="px-4 py-4 space-y-3">
                                <!-- Type & Date Row -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <span
                                            class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            Type
                                        </span>
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                    @if($demande->retrait_type === 'temporaire')
                                        bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                    @else
                                        bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @endif">
                                            <span class="capitalize">{{ $demande->retrait_type }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <span
                                            class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Créé le
                                        </span>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $demande->created_at->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $demande->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Deadline & Return Row (Conditional) -->
                                @if($demande->retrait_type === 'temporaire' && $demande->must_return_by)
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <span
                                                class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Retour avant
                                            </span>
                                            @php
                                                $deadline = \Carbon\Carbon::parse($demande->must_return_by);
                                                $now = now();
                                                $isOverdue = $now->isAfter($deadline);
                                                $daysLeft = $now->diffInDays($deadline, false);
                                            @endphp
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold
                                            @if($isOverdue)
                                                bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                            @elseif($daysLeft <= 1)
                                                bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400
                                            @else
                                                bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @endif">
                                                {{ $deadline->format('d/m/Y') }}
                                            </div>
                                            @if($isOverdue)
                                                <p class="text-xs text-red-600 dark:text-red-400 font-semibold">
                                                    Retard: {{ abs($daysLeft) }} jour(s)
                                                </p>
                                            @elseif($daysLeft >= 0)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    Reste: {{ $daysLeft }} jour(s)
                                                </p>
                                            @endif
                                        </div>

                                        @if($demande->collected_at)
                                            <div class="space-y-1">
                                                <span
                                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Retourné
                                                </span>
                                                <div
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    {{ \Carbon\Carbon::parse($demande->collected_at)->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Extension Info (if exists) -->
                                @if($demande->extension_days)
                                    <div
                                        class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-xs font-semibold text-yellow-800 dark:text-yellow-300">
                                                    Extension de +{{ $demande->extension_days }} jours demandée
                                                </p>
                                                <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-0.5">
                                                    le
                                                    {{ \Carbon\Carbon::parse($demande->extension_requested_at)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer - Actions -->
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-750 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2 justify-end">
                                    <!-- Cancel Button -->
                                    @if($demande->status === 'PENDING')
                                        @php
                                            $canCancel = $demande->canBeCancelled();
                                            $remainingMinutes = $demande->getRemainingCancellationTime();
                                        @endphp

                                        @if($canCancel)
                                            <form action="{{ route('student.demande.cancel', $demande->id) }}" method="POST"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')"
                                                class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span>Annuler</span>
                                                    <span class="bg-red-700/50 px-2 py-0.5 rounded text-xs font-bold">
                                                        {{ $remainingMinutes }}min
                                                    </span>
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <!-- Print Button -->
                                    <a href="{{ route('student.demande.print', $demande->id) }}" target="_blank"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        <span>Imprimer</span>
                                    </a>

                                    <!-- Extension Button -->
                                    @if($demande->status === 'PICKED' && $demande->retrait_type === 'temporaire' && $demande->must_return_by && now()->greaterThan($demande->must_return_by))
                                        <button onclick="handleExtension({{ $demande->id }})"
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Extension</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                            <div class="flex flex-col items-center justify-center space-y-4 text-center">
                                <div class="relative">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center shadow-inner">
                                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute -top-1 -right-1 w-6 h-6 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-gray-600 dark:text-gray-300 text-base font-semibold">Aucune demande trouvée
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                                        Commencez par soumettre votre première demande.<br>Vos demandes apparaîtront ici.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Loading Animation -->
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Enhanced hover effects */
        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }

        /* Smooth transitions for all interactive elements */
        select,
        button,
        a {
            transition: all 0.2s ease-in-out;
        }

        /* Custom scrollbar for webkit browsers */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Dark mode scrollbar */
        .dark .overflow-x-auto::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
@endsection

@push('js')
    <script>
        // Enhanced extension handling with better UX
        function handleExtension(demandeId) {
            // Create a more sophisticated modal-like prompt
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            modal.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Demande d'extension</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Combien de jours supplémentaires souhaitez-vous demander ?</p>
                        <input type="number" id="extensionDays" min="1" max="30"
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/20 mb-6"
                            placeholder="Nombre de jours (1-30)">
                        <div class="flex space-x-3">
                            <button onclick="submitExtension(${demandeId})"
                                class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200">
                                Confirmer
                            </button>
                            <button onclick="closeExtensionModal()"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200">
                                Annuler
                            </button>
                        </div>
                    </div>
                `;

            document.body.appendChild(modal);
            document.getElementById('extensionDays').focus();

            // Handle Enter key
            document.getElementById('extensionDays').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    submitExtension(demandeId);
                }
            });

            // Handle Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeExtensionModal();
                }
            });
        }

        function submitExtension(demandeId) {
            const days = document.getElementById('extensionDays').value;
            const parsed = parseInt(days);

            if (!parsed || parsed < 1 || parsed > 30) {
                showNotification('Veuillez entrer un nombre valide de jours (1-30).', 'error');
                return;
            }

            // Show loading
            showLoading(true);
            closeExtensionModal();

            fetch(`/student/document/${demandeId}/extension`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    extra_days: parsed
                })
            })
                .then(async response => {
                    const data = await response.json();
                    showLoading(false);

                    if (!response.ok) {
                        showNotification(data.error || "Une erreur s'est produite.", 'error');
                        throw new Error(data.error || "Erreur non spécifiée");
                    }

                    showNotification("Extension demandée avec succès.", 'success');
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(error => {
                    showLoading(false);
                    console.error("Extension error:", error);
                    showNotification("Une erreur s'est produite lors de la demande.", 'error');
                });
        }

        function closeExtensionModal() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) {
                modal.remove();
            }
        }

        function showLoading(show) {
            let overlay = document.querySelector('.loading-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.innerHTML = '<div class="loading-spinner"></div>';
                document.body.appendChild(overlay);
            }

            if (show) {
                overlay.classList.add('active');
            } else {
                overlay.classList.remove('active');
            }
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            notification.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                    type === 'error' ?
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

            document.body.appendChild(notification);

            // Slide in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // Enhanced form submission with loading state
        document.querySelector('form[action*="demande.store"]')?.addEventListener('submit', function (e) {
            const selectedDocs = document.querySelectorAll('input[name="document_ids[]"]:checked');
            if (selectedDocs.length === 0) {
                e.preventDefault();
                showNotification('Veuillez sélectionner au moins un document.', 'error');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Envoi en cours...</span>
                    </span>
                `;

            // Reset after 10 seconds in case of error
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 10000);
        });

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Enhanced mobile menu toggle animation
        function toggleMobileView() {
            const mobileView = document.querySelector('.block.lg\\:hidden');
            const desktopView = document.querySelector('.hidden.lg\\:block');

            if (window.innerWidth < 1024) {
                mobileView.style.display = 'block';
                desktopView.style.display = 'none';
            } else {
                mobileView.style.display = 'none';
                desktopView.style.display = 'block';
            }
        }

        // Listen for window resize
        window.addEventListener('resize', toggleMobileView);

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function () {
            toggleMobileView();

            // Add loading animation to the page
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });

        function demandeForm() {
            return {
                selectedDocuments: [],
                hasDocumentRequiringReturn: false,

                toggleDocument(documentId, isChecked, requiresReturn) {
                    if (isChecked) {
                        if (!this.selectedDocuments.includes(documentId)) {
                            this.selectedDocuments.push(documentId);
                        }
                    } else {
                        this.selectedDocuments = this.selectedDocuments.filter(id => id !== documentId);
                    }

                    // Update hasDocumentRequiringReturn based on all selected documents
                    this.updateRequiresReturn();
                },

                updateRequiresReturn() {
                    // Check all checkboxes to see if any selected document requires return
                    const checkboxes = document.querySelectorAll('input[name="document_ids[]"]:checked');
                    this.hasDocumentRequiringReturn = Array.from(checkboxes).some(
                        cb => cb.getAttribute('data-requires-return') === '1'
                    );
                }
            }
        }
    </script>
@endpush