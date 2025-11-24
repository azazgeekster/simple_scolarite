@extends('admin.layouts.app')

@section('title', 'Gestion - ' . $student->full_name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('admin.students.index') }}" class="hover:text-amber-600">Étudiants</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span>{{ $student->full_name }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Student Profile -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-8 text-center">
                    <img src="{{ $student->setAvatar() }}" alt="{{ $student->full_name }}" class="w-24 h-24 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                    <h2 class="mt-4 text-xl font-bold text-white">{{ $student->full_name }}</h2>
                    <p class="text-amber-100">{{ $student->full_name_ar }}</p>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Statut du compte</span>
                        @if($student->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Inactif
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('admin.students.toggle-status', $student->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 px-4 rounded-lg text-sm font-medium transition-colors {{ $student->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400' }}">
                            {{ $student->is_active ? 'Désactiver le compte' : 'Activer le compte' }}
                        </button>
                    </form>

                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">CNE</span>
                            <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $student->cne }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Apogée</span>
                            <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $student->apogee }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">CIN</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $student->cin ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Boursier</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $student->boursier ? 'Oui' : 'Non' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Statistiques Modules</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $moduleStats['total'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $moduleStats['passed'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Validés</div>
                    </div>
                    <div class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $moduleStats['failed'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Non validés</div>
                    </div>
                    <div class="text-center p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-amber-600">{{ $moduleStats['pending'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">En attente</div>
                    </div>
                </div>
            </div>

            <!-- Reset Password -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Réinitialiser Mot de Passe</h3>
                <form action="{{ route('admin.students.reset-password', $student->id) }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <input type="password" name="password" placeholder="Nouveau mot de passe" required class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <button type="submit" class="w-full py-2 px-4 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Tabs Content -->
        <div class="lg:col-span-2">
            <!-- Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow" x-data="{ activeTab: 'info' }">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px overflow-x-auto">
                        <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                            Informations
                        </button>
                        <button @click="activeTab = 'cursus'" :class="activeTab === 'cursus' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                            Cursus
                        </button>
                        <button @click="activeTab = 'modules'" :class="activeTab === 'modules' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                            Modules & Notes
                        </button>
                        <button @click="activeTab = 'demandes'" :class="activeTab === 'demandes' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                            Demandes
                        </button>
                        <button @click="activeTab = 'convocations'" :class="activeTab === 'convocations' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                            Convocations
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Info Tab -->
                    <div x-show="activeTab === 'info'" x-cloak>
                        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Prénom</label>
                                    <input type="text" name="prenom" value="{{ $student->prenom }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nom</label>
                                    <input type="text" name="nom" value="{{ $student->nom }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                    <input type="email" name="email" value="{{ $student->email }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                                    <input type="text" name="tel" value="{{ $student->tel }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone Urgence</label>
                                    <input type="text" name="tel_urgence" value="{{ $student->tel_urgence }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse</label>
                                    <textarea name="adresse" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">{{ $student->adresse }}</textarea>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="boursier" value="1" {{ $student->boursier ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-amber-600">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Boursier</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>

                        <!-- Additional Info -->
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Informations supplémentaires</h4>
                            <dl class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Date de naissance</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->date_naissance?->format('d/m/Y') ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Lieu de naissance</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->lieu_naissance ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Nationalité</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->nationalite ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Sexe</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->sexe === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Dernière connexion</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->last_login?->format('d/m/Y H:i') ?? 'Jamais' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Email vérifié</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->email_verified_at ? 'Oui' : 'Non' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Cursus Tab -->
                    <div x-show="activeTab === 'cursus'" x-cloak>
                        <!-- Add Program Enrollment -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Nouvelle inscription programme</h4>
                            <form action="{{ route('admin.students.enroll-program', $student->id) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                    <select name="filiere_id" required class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-white text-sm">
                                        <option value="">Filière</option>
                                        @foreach($filieres as $filiere)
                                            <option value="{{ $filiere->id }}">{{ $filiere->label_fr }}</option>
                                        @endforeach
                                    </select>
                                    <select name="academic_year" required class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-white text-sm">
                                        <option value="">Année</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->start_year }}">{{ $year->start_year }}/{{ $year->start_year + 1 }}</option>
                                        @endforeach
                                    </select>
                                    <select name="year_in_program" required class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-white text-sm">
                                        <option value="">Année prog.</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }}ère/ème année</option>
                                        @endfor
                                    </select>
                                    <select name="diploma_level" required class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-white text-sm">
                                        <option value="">Niveau</option>
                                        <option value="deug">DEUG</option>
                                        <option value="licence">Licence</option>
                                        <option value="master">Master</option>
                                        <option value="doctorat">Doctorat</option>
                                    </select>
                                    <select name="enrollment_status" required class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-white text-sm">
                                        <option value="active">Actif</option>
                                        <option value="completed">Terminé</option>
                                        <option value="suspended">Suspendu</option>
                                        <option value="withdrawn">Retiré</option>
                                    </select>
                                </div>
                                <button type="submit" class="mt-3 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Ajouter inscription
                                </button>
                            </form>
                        </div>

                        <!-- Program Enrollments List -->
                        <div class="space-y-4">
                            @forelse($student->programEnrollments as $enrollment)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h5 class="font-medium text-gray-900 dark:text-white">{{ $enrollment->filiere->label_fr ?? 'N/A' }}</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $enrollment->year_label }} - {{ $enrollment->academic_year_label }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                            {{ $enrollment->enrollment_status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                            {{ $enrollment->enrollment_status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                            {{ $enrollment->enrollment_status === 'suspended' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                            {{ $enrollment->enrollment_status === 'withdrawn' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                        ">
                                            {{ ucfirst($enrollment->enrollment_status) }}
                                        </span>
                                    </div>
                                    <form action="{{ route('admin.students.update-program-enrollment', [$student->id, $enrollment->id]) }}" method="POST" class="mt-3 flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="enrollment_status" class="flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                            <option value="active" {{ $enrollment->enrollment_status === 'active' ? 'selected' : '' }}>Actif</option>
                                            <option value="completed" {{ $enrollment->enrollment_status === 'completed' ? 'selected' : '' }}>Terminé</option>
                                            <option value="suspended" {{ $enrollment->enrollment_status === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                            <option value="withdrawn" {{ $enrollment->enrollment_status === 'withdrawn' ? 'selected' : '' }}>Retiré</option>
                                        </select>
                                        <button type="submit" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition-colors">
                                            Modifier
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Aucune inscription programme</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Modules Tab -->
                    <div x-show="activeTab === 'modules'" x-cloak>
                        <!-- Enroll in Module -->
                        @if($availableModules->count() > 0)
                            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Inscrire à un module</h4>
                                <form action="{{ route('admin.students.enroll-module', $student->id) }}" method="POST">
                                    @csrf
                                    <div class="flex gap-3">
                                        <select name="module_id" required class="flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 text-gray-900 dark:text-white text-sm">
                                            <option value="">Sélectionner un module</option>
                                            @foreach($availableModules->groupBy('semester') as $semester => $modules)
                                                <optgroup label="{{ $semester }}">
                                                    @foreach($modules as $module)
                                                        <option value="{{ $module->id }}" data-semester="{{ $module->semester }}">{{ $module->full_label }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="semester" id="moduleSemester" value="">
                                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            Inscrire
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Module Enrollments -->
                        <div class="space-y-3">
                            @forelse($student->moduleEnrollments->groupBy('semester') as $semester => $enrollments)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2">
                                        <h5 class="font-medium text-gray-900 dark:text-white">{{ $semester }}</h5>
                                    </div>
                                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($enrollments as $enrollment)
                                            <div class="px-4 py-3 flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $enrollment->module->label ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $enrollment->module->code ?? '' }}</div>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    @if($enrollment->final_grade !== null)
                                                        <div class="text-right">
                                                            <div class="text-sm font-bold {{ $enrollment->final_grade >= 10 ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ number_format($enrollment->final_grade, 2) }}/20
                                                            </div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $enrollment->final_result ?? '-' }}</div>
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-gray-400">Non noté</span>
                                                    @endif
                                                    @if(!$enrollment->grades()->exists())
                                                        <form action="{{ route('admin.students.unenroll-module', [$student->id, $enrollment->id]) }}" method="POST" onsubmit="return confirm('Confirmer la désinscription ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                                                Désinscrire
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Aucune inscription module</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Demandes Tab -->
                    <div x-show="activeTab === 'demandes'" x-cloak>
                        <div class="space-y-3">
                            @forelse($student->demandes as $demande)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <a href="{{ route('admin.document-requests.show', $demande->id) }}" class="text-sm font-medium text-amber-600 hover:text-amber-800">
                                                {{ $demande->reference_number }}
                                            </a>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $demande->document->label_fr ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $demande->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ $demande->status_color }}-100 text-{{ $demande->status_color }}-800 dark:bg-{{ $demande->status_color }}-900/30 dark:text-{{ $demande->status_color }}-400">
                                            {{ $demande->status_label }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Aucune demande</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Convocations Tab -->
                    <div x-show="activeTab === 'convocations'" x-cloak>
                        <div class="space-y-3">
                            @forelse($student->examConvocations as $convocation)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $convocation->exam->module->label ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $convocation->exam->exam_date ? \Carbon\Carbon::parse($convocation->exam->exam_date)->format('d/m/Y') : 'N/A' }} à {{ $convocation->exam->start_time }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Salle: {{ $convocation->exam->locals->first()->code ?? 'N/A' }} - Place: {{ $convocation->seat_number ?? '-' }}
                                            </p>
                                        </div>
                                        <a href="{{ route('admin.students.download-convocation', [$student->id, $convocation->id]) }}"
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-400 text-xs font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            PDF
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Aucune convocation</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-fill semester when module is selected
    document.querySelector('select[name="module_id"]')?.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        document.getElementById('moduleSemester').value = option.dataset.semester || '';
    });
</script>
@endsection
