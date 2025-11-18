@extends('student.layouts.app')

@section('main_content')
    @php
        // Helper function to safely access family data
        $family = $student->family ?? (object)[];
    @endphp

    <x-flash-message type="message" />
    <x-flash-message type="error" />

    @if ($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 p-4 mx-4 sm:mx-6 lg:mx-8 mt-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400 dark:text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400">Please fix the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 dark:text-red-500 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="py-6 lg:py-8 bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your personal information and contact details</p>
                    </div>
                    <a href="{{ route('student.profile.show') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Profile
                    </a>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="mb-6 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-5">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-2">Important Information</h3>
                        <ul class="text-sm text-amber-800 dark:text-amber-300 space-y-1">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>All changes will be reviewed by administration before being applied</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Please ensure all information is accurate and matches your official documents</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>You will be notified once your changes are reviewed</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route('student.profile.update') }}" method="POST" x-data="{ currentTab: 'personal' }">
                @csrf

                <!-- Tab Navigation -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="flex flex-wrap -mb-px" aria-label="Tabs">
                            <button type="button" @click="currentTab = 'personal'"
                                :class="currentTab === 'personal' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" :class="currentTab === 'personal' ? 'text-teal-500 dark:text-teal-400' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Personal Information
                            </button>

                            <button type="button" @click="currentTab = 'contact'"
                                :class="currentTab === 'contact' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" :class="currentTab === 'contact' ? 'text-teal-500 dark:text-teal-400' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                Contact & Address
                            </button>

                            <button type="button" @click="currentTab = 'family'"
                                :class="currentTab === 'family' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" :class="currentTab === 'family' ? 'text-teal-500 dark:text-teal-400' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                Family Information
                            </button>

                            <button type="button" @click="currentTab = 'special'"
                                :class="currentTab === 'special' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" :class="currentTab === 'special' ? 'text-teal-500 dark:text-teal-400' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Special Needs
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 lg:p-8">

                        <!-- Personal Information Tab -->
                        <div x-show="currentTab === 'personal'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">

                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Personal Information</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $student->prenom) }}" required
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('prenom')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nom" name="nom" value="{{ old('nom', $student->nom) }}" required
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('nom')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- First Name Arabic -->
                                <div>
                                    <label for="prenom_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        الإسم الشخصي
                                    </label>
                                    <input type="text" id="prenom_ar" name="prenom_ar" value="{{ old('prenom_ar', $student->prenom_ar) }}" dir="rtl"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('prenom_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name Arabic -->
                                <div>
                                    <label for="nom_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        الإسم العائلي
                                    </label>
                                    <input type="text" id="nom_ar" name="nom_ar" value="{{ old('nom_ar', $student->nom_ar) }}" dir="rtl"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('nom_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CIN -->
                                <div>
                                    <label for="cin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        CIN <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="cin" name="cin" value="{{ old('cin', $student->cin) }}" required
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('cin')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div>
                                    <label for="date_naissance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Date of Birth
                                    </label>
                                    <input type="date" id="date_naissance" name="date_naissance"
                                        value="{{ old('date_naissance', $student->date_naissance ? \Carbon\Carbon::parse($student->date_naissance)->format('Y-m-d') : '') }}"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('date_naissance')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="sexe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Gender
                                    </label>
                                    <select id="sexe" name="sexe"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                        <option value="">Select Gender</option>
                                        <option value="M" {{ old('sexe', $student->sexe) === 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('sexe', $student->sexe) === 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('sexe')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nationality -->
                                <div>
                                    <label for="nationalite" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nationality
                                    </label>
                                    <select id="nationalite" name="nationalite"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                        <option value="">Select Nationality</option>
                                        @foreach(\App\Helpers\CountryHelper::getNationalities() as $code => $name)
                                            @if($code === '')
                                                <option disabled>{{ $name }}</option>
                                            @else
                                                <option value="{{ $code }}" {{ old('nationalite', $student->nationalite) === $code ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('nationalite')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Place of Birth -->
                                <div>
                                    <label for="lieu_naissance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Place of Birth
                                    </label>
                                    <input type="text" id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance', $student->lieu_naissance) }}"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('lieu_naissance')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Place of Birth Arabic -->
                                <div>
                                    <label for="lieu_naissance_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        مكان الإزدياد
                                    </label>
                                    <input type="text" id="lieu_naissance_ar" name="lieu_naissance_ar" value="{{ old('lieu_naissance_ar', $student->lieu_naissance_ar) }}" dir="rtl"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('lieu_naissance_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Marital Status -->
                                <div>
                                    <label for="situation_familiale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Marital Status
                                    </label>
                                    <select id="situation_familiale" name="situation_familiale"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                        <option value="">Select Status</option>
                                        <option value="Célibataire" {{ old('situation_familiale', $student->situation_familiale) === 'Célibataire' ? 'selected' : '' }}>Single</option>
                                        <option value="Marié" {{ old('situation_familiale', $student->situation_familiale) === 'Marié' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorcé" {{ old('situation_familiale', $student->situation_familiale) === 'Divorcé' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Veuf" {{ old('situation_familiale', $student->situation_familiale) === 'Veuf' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    @error('situation_familiale')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Professional Status -->
                                <div>
                                    <label for="situation_professionnelle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Professional Status
                                    </label>
                                    <select id="situation_professionnelle" name="situation_professionnelle"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                        <option value="">Select Status</option>
                                        <option value="Étudiant" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Étudiant' ? 'selected' : '' }}>Student</option>
                                        <option value="Salarié" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Salarié' ? 'selected' : '' }}>Employed</option>
                                        <option value="Chômeur" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Chômeur' ? 'selected' : '' }}>Unemployed</option>
                                        <option value="Autre" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Autre' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('situation_professionnelle')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Address Tab -->
                        <div x-show="currentTab === 'contact'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0"
                            style="display: none;">

                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Contact & Address Information</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Phone -->
                                <div>
                                    <label for="tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" id="tel" name="tel" value="{{ old('tel', $student->tel) }}"
                                        placeholder="+212 600 000 000" maxlength="20"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('tel')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Emergency Phone -->
                                <div>
                                    <label for="tel_urgence" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Emergency Contact
                                    </label>
                                    <input type="tel" id="tel_urgence" name="tel_urgence" value="{{ old('tel_urgence', $student->tel_urgence) }}"
                                        placeholder="+212 600 000 000" maxlength="20"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('tel_urgence')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div class="md:col-span-2">
                                    <label for="pays" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Country
                                    </label>
                                    <select id="pays" name="pays"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                        <option value="">Select Country</option>
                                        @foreach(\App\Helpers\CountryHelper::getCountries() as $code => $name)
                                            <option value="{{ $code }}" {{ old('pays', $student->pays) === $code ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pays')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address (English) -->
                                <div class="md:col-span-2">
                                    <label for="adresse" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Address
                                    </label>
                                    <textarea id="adresse" name="adresse" rows="3"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">{{ old('adresse', $student->adresse) }}</textarea>
                                    @error('adresse')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address (Arabic) -->
                                <div class="md:col-span-2">
                                    <label for="adresse_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        العنوان بالعربية
                                    </label>
                                    <textarea id="adresse_ar" name="adresse_ar" rows="3" dir="rtl"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">{{ old('adresse_ar', $student->adresse_ar) }}</textarea>
                                    @error('adresse_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Family Information Tab -->
                        <div x-show="currentTab === 'family'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0"
                            style="display: none;">

                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Family Information</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">You only need to fill in the fields you wish to update.</p>

                            <div class="space-y-8">
                                <!-- Father Section -->
                                <fieldset class="border border-gray-200 dark:border-gray-700 p-6 rounded-lg">
                                    <legend class="text-base font-medium text-gray-900 dark:text-white px-2">Father Details</legend>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                                        @foreach(['father_firstname' => 'First Name', 'father_lastname' => 'Last Name', 'father_cin' => 'CIN'] as $field => $label)
                                            <div>
                                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $label }}</label>
                                                <input type="text" id="{{ $field }}" name="family[{{ $field }}]" value="{{ old('family.' . $field, $family->$field ?? '') }}"
                                                    class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                                @error("family.$field")
                                                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                        <!-- Father Birth Date -->
                                        <div>
                                            <label for="father_birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                            <input type="date" id="father_birth_date" name="family[father_birth_date]"
                                                value="{{ old('family.father_birth_date', $family->father_birth_date ? \Carbon\Carbon::parse($family->father_birth_date)->format('Y-m-d') : '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.father_birth_date')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- Father Death Date -->
                                        <div>
                                            <label for="father_death_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Death</label>
                                            <input type="date" id="father_death_date" name="family[father_death_date]"
                                                value="{{ old('family.father_death_date', $family->father_death_date ? \Carbon\Carbon::parse($family->father_death_date)->format('Y-m-d') : '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.father_death_date')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- Father Profession -->
                                        <div>
                                            <label for="father_profession" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profession</label>
                                            <input type="text" id="father_profession" name="family[father_profession]" value="{{ old('family.father_profession', $family->father_profession ?? '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.father_profession')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Mother Section -->
                                <fieldset class="border border-gray-200 dark:border-gray-700 p-6 rounded-lg">
                                    <legend class="text-base font-medium text-gray-900 dark:text-white px-2">Mother Details</legend>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                                        @foreach(['mother_firstname' => 'First Name', 'mother_lastname' => 'Last Name', 'mother_cin' => 'CIN'] as $field => $label)
                                            <div>
                                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $label }}</label>
                                                <input type="text" id="{{ $field }}" name="family[{{ $field }}]" value="{{ old('family.' . $field, $family->$field ?? '') }}"
                                                    class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                                @error("family.$field")
                                                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                        <!-- Mother Birth Date -->
                                        <div>
                                            <label for="mother_birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                            <input type="date" id="mother_birth_date" name="family[mother_birth_date]"
                                                value="{{ old('family.mother_birth_date', $family->mother_birth_date ? \Carbon\Carbon::parse($family->mother_birth_date)->format('Y-m-d') : '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.mother_birth_date')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- Mother Death Date -->
                                        <div>
                                            <label for="mother_death_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Death</label>
                                            <input type="date" id="mother_death_date" name="family[mother_death_date]"
                                                value="{{ old('family.mother_death_date', $family->mother_death_date ? \Carbon\Carbon::parse($family->mother_death_date)->format('Y-m-d') : '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.mother_death_date')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- Mother Profession -->
                                        <div>
                                            <label for="mother_profession" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profession</label>
                                            <input type="text" id="mother_profession" name="family[mother_profession]" value="{{ old('family.mother_profession', $family->mother_profession ?? '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.mother_profession')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Spouse Section (If married/divorced/widowed) -->
                                <fieldset class="border border-gray-200 dark:border-gray-700 p-6 rounded-lg">
                                    <legend class="text-base font-medium text-gray-900 dark:text-white px-2">Spouse Details (Optional)</legend>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                        <!-- Spouse CIN -->
                                        <div>
                                            <label for="spouse_cin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Spouse CIN</label>
                                            <input type="text" id="spouse_cin" name="family[spouse_cin]" value="{{ old('family.spouse_cin', $family->spouse_cin ?? '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.spouse_cin')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- Spouse Death Date -->
                                        <div>
                                            <label for="spouse_death_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Spouse Date of Death</label>
                                            <input type="date" id="spouse_death_date" name="family[spouse_death_date]"
                                                value="{{ old('family.spouse_death_date', $family->spouse_death_date ? \Carbon\Carbon::parse($family->spouse_death_date)->format('Y-m-d') : '') }}"
                                                class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                            @error('family.spouse_death_date')
                                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <!-- Special Needs Tab -->
                        <div x-show="currentTab === 'special'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0"
                            style="display: none;">

                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Special Needs Information</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Handicap Code -->
                                <div>
                                    <label for="handicap_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Handicap Code
                                    </label>
                                    <input type="text" id="handicap_code" name="family[handicap_code]" value="{{ old('family.handicap_code', $family->handicap_code ?? '') }}"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('family.handicap_code')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Handicap Type -->
                                <div>
                                    <label for="handicap_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Handicap Type
                                    </label>
                                    <input type="text" id="handicap_type" name="family[handicap_type]" value="{{ old('family.handicap_type', $family->handicap_type ?? '') }}"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('family.handicap_type')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Handicap Card Number -->
                                <div>
                                    <label for="handicap_card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Handicap Card Number
                                    </label>
                                    <input type="text" id="handicap_card_number" name="family[handicap_card_number]" value="{{ old('family.handicap_card_number', $family->handicap_card_number ?? '') }}"
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                                    @error('family.handicap_card_number')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors duration-200 dark:focus:ring-offset-gray-900">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Submit Changes for Review
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection