@extends('admin.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nouveau Message</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Envoyer un message aux étudiants avec ciblage précis
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.messages.store') }}" method="POST">
                @csrf

                <!-- Recipient Type -->
                <div class="mb-6">
                    <label class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">
                        Type de destinataire <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="recipient_type" value="individual" class="mr-2" {{ old('recipient_type', 'individual') == 'individual' ? 'checked' : '' }} onchange="updateRecipientFields()">
                            <span class="text-sm font-medium">Individuel</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="recipient_type" value="filiere" class="mr-2" {{ old('recipient_type') == 'filiere' ? 'checked' : '' }} onchange="updateRecipientFields()">
                            <span class="text-sm font-medium">Filière</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="recipient_type" value="year" class="mr-2" {{ old('recipient_type') == 'year' ? 'checked' : '' }} onchange="updateRecipientFields()">
                            <span class="text-sm font-medium">Année</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="recipient_type" value="semester" class="mr-2" {{ old('recipient_type') == 'semester' ? 'checked' : '' }} onchange="updateRecipientFields()">
                            <span class="text-sm font-medium">Semestre</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="recipient_type" value="filiere_year" class="mr-2" {{ old('recipient_type') == 'filiere_year' ? 'checked' : '' }} onchange="updateRecipientFields()">
                            <span class="text-sm font-medium">Filière + Année</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="recipient_type" value="filiere_semester" class="mr-2" {{ old('recipient_type') == 'filiere_semester' ? 'checked' : '' }} onchange="updateRecipientFields()">
                            <span class="text-sm font-medium">Filière + Semestre</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition" style="grid-column: span 2">
                            <input type="radio" name="recipient_type" value="all" class="mr-2" {{ old('recipient_type') == 'all' ? 'checked' : '' }} onchange="updateRecipientFields()">
                            <span class="text-sm font-medium">Tous les étudiants</span>
                        </label>
                    </div>
                </div>

                <!-- Individual Student -->
                <div id="individual_field" class="mb-6" style="display: none;">
                    <label for="recipient_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Étudiant
                    </label>
                    <select name="recipient_id" id="recipient_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Sélectionner un étudiant</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('recipient_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->nom }} {{ $student->prenom }} ({{ $student->apogee }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filiere -->
                <div id="filiere_field" class="mb-6" style="display: none;">
                    <label for="filiere_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Filière
                    </label>
                    <select name="filiere_id" id="filiere_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Sélectionner une filière</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->label_fr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Year -->
                <div id="year_field" class="mb-6" style="display: none;">
                    <label for="year_in_program" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Année
                    </label>
                    <select name="year_in_program" id="year_in_program" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Sélectionner une année</option>
                        <option value="1" {{ old('year_in_program') == '1' ? 'selected' : '' }}>1ère année</option>
                        <option value="2" {{ old('year_in_program') == '2' ? 'selected' : '' }}>2ème année</option>
                        <option value="3" {{ old('year_in_program') == '3' ? 'selected' : '' }}>3ème année</option>
                    </select>
                </div>

                <!-- Semester -->
                <div id="semester_field" class="mb-6" style="display: none;">
                    <label for="semester" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Semestre
                    </label>
                    <select name="semester" id="semester" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Sélectionner un semestre</option>
                        <option value="S1" {{ old('semester') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('semester') == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('semester') == 'S3' ? 'selected' : '' }}>S3</option>
                        <option value="S4" {{ old('semester') == 'S4' ? 'selected' : '' }}>S4</option>
                        <option value="S5" {{ old('semester') == 'S5' ? 'selected' : '' }}>S5</option>
                        <option value="S6" {{ old('semester') == 'S6' ? 'selected' : '' }}>S6</option>
                    </select>
                </div>

                <!-- Priority and Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="priority" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Priorité <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normale</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>

                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="category" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="general" {{ old('category', 'general') == 'general' ? 'selected' : '' }}>Général</option>
                            <option value="exam" {{ old('category') == 'exam' ? 'selected' : '' }}>Examen</option>
                            <option value="grade" {{ old('category') == 'grade' ? 'selected' : '' }}>Note</option>
                            <option value="administrative" {{ old('category') == 'administrative' ? 'selected' : '' }}>Administratif</option>
                            <option value="important" {{ old('category') == 'important' ? 'selected' : '' }}>Important</option>
                        </select>
                    </div>
                </div>

                <!-- Subject -->
                <div class="mb-6">
                    <label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Sujet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required maxlength="255" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Sujet du message">
                </div>

                <!-- Message with Rich Text Editor -->
                <div class="mb-6">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" id="message" rows="12" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('message') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Utilisez l'éditeur pour formater votre message</p>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Annuler
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Envoyer le message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#message',
    height: 400,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic underline forecolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

function updateRecipientFields() {
    const type = document.querySelector('input[name="recipient_type"]:checked').value;

    document.getElementById('individual_field').style.display = 'none';
    document.getElementById('filiere_field').style.display = 'none';
    document.getElementById('year_field').style.display = 'none';
    document.getElementById('semester_field').style.display = 'none';

    if (type === 'individual') {
        document.getElementById('individual_field').style.display = 'block';
    } else if (type === 'filiere' || type === 'filiere_year' || type === 'filiere_semester') {
        document.getElementById('filiere_field').style.display = 'block';
    }
    
    if (type === 'year' || type === 'filiere_year') {
        document.getElementById('year_field').style.display = 'block';
    }
    
    if (type === 'semester' || type === 'filiere_semester') {
        document.getElementById('semester_field').style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateRecipientFields);
</script>
@endsection
