@extends('admin.layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.document-requests.index') }}" class="mr-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white">
                            Types de Documents
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Gérer les types de documents disponibles pour les demandes
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouveau Type
                </button>
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

        <!-- Document Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($documents as $document)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <div class="flex-shrink-0 h-10 w-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $document->label_fr }}</h3>
                                        <p class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $document->slug }}</p>
                                    </div>
                                </div>

                                @if($document->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">{{ Str::limit($document->description, 100) }}</p>
                                @endif

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @if($document->requires_return)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Retour requis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                            Permanent
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ $document->demandes_count }} demande(s)
                                    </span>
                                </div>

                                @if($document->label_ar)
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 text-right" dir="rtl">{{ $document->label_ar }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end space-x-3">
                            <button onclick="openEditModal({{ json_encode($document) }})" class="text-sm text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 font-medium">
                                Modifier
                            </button>
                            @if($document->demandes_count === 0)
                                <form action="{{ route('admin.document-requests.document-types.destroy', $document->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type de document?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                        Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun type de document</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par créer un type de document.</p>
                        <div class="mt-6">
                            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Nouveau Type
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="documentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg transform transition-all">
        <form id="documentForm" method="POST">
            @csrf
            <div id="methodField"></div>

            <div class="px-6 py-5">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                    Nouveau Type de Document
                </h3>

                <div class="space-y-4">
                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Identifiant (slug) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="slug" id="slug" required
                            class=" bg-white text-black mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                                   dark:bg-gray-700 dark:text-white shadow-sm text-sm"
                            placeholder="attestation_inscription">
                    </div>

                    <!-- Label FR -->
                    <div>
                        <label for="label_fr" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Libellé (Français) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="label_fr" id="label_fr" required
                            class=" bg-white text-black mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                                   dark:bg-gray-700 dark:text-white shadow-sm text-sm"
                            placeholder="Attestation d'Inscription">
                    </div>

                    <!-- Label AR -->
                    <div>
                        <label for="label_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Libellé (Arabe)
                        </label>
                        <input type="text" name="label_ar" id="label_ar" dir="rtl"
                            class=" bg-white text-black mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                                   dark:bg-gray-700 dark:text-white shadow-sm text-sm"
                            placeholder="شهادة التسجيل">
                    </div>

                    <!-- Label EN -->
                    <div>
                        <label for="label_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Libellé (Anglais)
                        </label>
                        <input type="text" name="label_en" id="label_en"
                            class=" bg-white text-black mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                                   dark:bg-gray-700 dark:text-white shadow-sm text-sm"
                            placeholder="Registration Certificate">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="text-black bg-white mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                                   dark:bg-gray-700 dark:text-white shadow-sm text-sm"
                            placeholder="Description du document..."></textarea>
                    </div>

                    <!-- Requires Return -->
                    <div class="flex items-center">
                        <input type="checkbox" name="requires_return" id="requires_return" value="1"
                            class="bg-white h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <label for="requires_return" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Document à retourner (temporaire)
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-3 flex justify-end space-x-3">
                <button type="submit"
                    class="px-4 py-2 rounded-md bg-amber-600 text-white text-sm font-medium hover:bg-amber-700 shadow-sm">
                    Enregistrer
                </button>
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300
                           hover:bg-gray-50 dark:hover:bg-gray-600">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    function openCreateModal() {
        document.getElementById('modal-title').textContent = 'Nouveau Type de Document';
        document.getElementById('documentForm').action = '{{ route("admin.document-requests.document-types.store") }}';
        document.getElementById('methodField').innerHTML = '';

        // Clear form
        document.getElementById('slug').value = '';
        document.getElementById('label_fr').value = '';
        document.getElementById('label_ar').value = '';
        document.getElementById('label_en').value = '';
        document.getElementById('description').value = '';
        document.getElementById('requires_return').checked = false;

        document.getElementById('documentModal').classList.remove('hidden');
    }

    function openEditModal(doc) {
        // Now 'document' refers to the global DOM correctly
        document.getElementById('modal-title').textContent = 'Modifier Type de Document';

        // Use 'doc' to access your data
        document.getElementById('documentForm').action = '{{ url("admin/document-requests/document-types") }}/' + doc.id;
        document.getElementById('methodField').innerHTML = '@method("PUT")';

        // Fill form using 'doc'
        document.getElementById('slug').value = doc.slug || '';
        document.getElementById('label_fr').value = doc.label_fr || '';
        document.getElementById('label_ar').value = doc.label_ar || '';
        document.getElementById('label_en').value = doc.label_en || '';
        document.getElementById('description').value = doc.description || '';
        document.getElementById('requires_return').checked = doc.requires_return == 1;

        document.getElementById('documentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('documentModal').classList.add('hidden');
    }

    // Close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection
