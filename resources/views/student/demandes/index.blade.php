@extends("student.layouts.app")

@section("main_content")
@if($overdueDemandes->count() > 0)
<div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r-lg">
    <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
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
                                    maximum de <strong>48 heures</strong> après le retrait, sinon le retrait devient définitive.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- //////////////// --}}




                <form method="POST" action="{{ route('student.demande.store') }}" x-data="demandeForm()">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Document Select -->
                        <div class="group">
                            <label for="document_id"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span>Document</span>
                                </span>
                            </label>
                            <select name="document_id" id="document_id"
                                @change="updateDocumentInfo($event.target.value)"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-300 dark:hover:border-gray-500">
                                <option disabled selected>Choisissez un document</option>
                                @foreach($documents as $doc)
                                    {{-- Hide "Relevé de notes" as it has a dedicated page --}}
                                    @if($doc->slug !== 'releve_notes')
                                        <option value="{{ $doc->id }}"
                                                data-requires-return="{{ $doc->requires_return ? '1' : '0' }}">
                                            {{ $doc->label_fr }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Retrait Type Select (Conditional) -->
                        <div class="group" x-show="requiresReturn" x-transition>
                            <label for="retrait_type"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Type de retrait</span>
                                </span>
                            </label>
                            <select name="retrait_type" id="retrait_type" :required="requiresReturn"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 hover:border-gray-300 dark:hover:border-gray-500">
                                <option disabled selected>Choisissez le type</option>
                                <option value="temporaire">Temporaire</option>
                                <option value="definitif">Définitif</option>
                            </select>
                        </div>

                        <!-- Info Message (when document doesn't require return) -->
                        <div x-show="!requiresReturn && documentSelected" x-transition class="sm:col-span-2 lg:col-span-3">
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        Ce document vous sera remis directement, aucun retour nécessaire.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end sm:col-span-2 lg:col-span-1">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg focus:ring-4 focus:ring-blue-500/25 active:scale-[0.98]">
                                <span class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    <span>Soumettre</span>
                                </span>
                            </button>
                        </div>
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
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Document</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    An. académique</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Fait le</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Retour avant</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Extension</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Retourné le</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Statut</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($demandes as $demande)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/25 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $demande->document->label_fr }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="capitalize text-gray-700 dark:text-gray-300">{{ $demande->retrait_type }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ $demande->academicYear->label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($demande->retrait_type === 'temporaire' && $demande->must_return_by)
                                            <span class="text-sm font-semibold text-red-700 dark:text-red-400">
                                                {{ \Carbon\Carbon::parse($demande->must_return_by)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400 italic">--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        @if($demande->extension_days)
                                            <div class="text-yellow-600 dark:text-yellow-300">
                                                +{{ $demande->extension_days }} j demandés
                                            </div>
                                            <div class="text-gray-500 dark:text-gray-400">
                                                le {{ \Carbon\Carbon::parse($demande->extension_requested_at)->format('d/m/Y') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        @if($demande->collected_at)
                                            <span class="text-green-600 dark:text-green-400 font-medium">
                                                {{ \Carbon\Carbon::parse($demande->collected_at)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($demande->isOverdue())
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                                </svg>
                                                En retard ({{ $demande->getDaysOverdue() }} jours)
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if($demande->status === 'PENDING') bg-yellow-100 text-yellow-800 dark:bg-yellow-300 dark:text-yellow-900
                                                        @elseif($demande->status === 'PROCESSING') bg-blue-100 text-blue-800 dark:bg-blue-300 dark:text-blue-900
                                                        @elseif($demande->status === 'READY') bg-indigo-100 text-indigo-800 dark:bg-indigo-300 dark:text-indigo-900
                                                        @elseif($demande->status === 'COMPLETED') bg-green-100 text-green-800 dark:bg-green-300 dark:text-green-900
                                                        @elseif($demande->status === 'PICKED') bg-red-100 text-red-800 dark:bg-red-300 dark:text-red-900
                                                        @endif">
                                                {{ ucfirst(strtolower(str_replace('_', ' ', $demande->status))) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
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
                                                        <button type="submit" title="Annuler ({{ $remainingMinutes }} min restantes)"
                                                            class="flex items-center space-x-1 px-3 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg text-sm font-medium transition-colors duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            <span>Annuler</span>
                                                            <span class="text-xs opacity-75">({{ $remainingMinutes }}min)</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div
                                                        class="inline-flex items-center space-x-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg>
                                                        <span>Annuler</span>
                                                    </div>
                                                @endif
                                            @endif

                                            <a href="{{ route('student.demande.print', $demande->id) }}" title="Imprimer"
                                                target="_blank"
                                                class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                    </path>
                                                </svg>
                                            </a>

                                            @if($demande->status === 'PICKED' && now()->greaterThan($demande->must_return_by))
                                                <button onclick="handleExtension({{ $demande->id }})" title="Demander une extension"
                                                    class="p-2 text-yellow-500 hover:text-yellow-700 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all duration-200">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Aucune demande
                                                enregistrée.</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Vos demandes apparaîtront
                                                ici une fois soumises.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        document.querySelector('form[action*="demande.store"]').addEventListener('submit', function () {
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
                requiresReturn: false,
                documentSelected: false,

                updateDocumentInfo(documentId) {
                    this.documentSelected = !!documentId;

                    if (!documentId) {
                        this.requiresReturn = false;
                        return;
                    }

                    // Get the selected option
                    const select = document.getElementById('document_id');
                    const option = select.options[select.selectedIndex];
                    this.requiresReturn = option.getAttribute('data-requires-return') === '1';
                }
            }
        }
    </script>
@endpush