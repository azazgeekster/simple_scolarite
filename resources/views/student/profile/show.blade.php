{{-- @extends('student.layouts.app')

@section('main_content')
    <x-flash-message type="message" />
    <x-flash-message type="error" />

    <!-- Hero Notice Section -->
    <section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 dark:from-gray-800 dark:via-gray-900 dark:to-black overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>

        <!-- Animated background elements -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>

        <div class="relative mx-auto max-w-7xl px-6 py-16">
            <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg border border-white/20 dark:border-gray-700/50 rounded-2xl p-8 md:p-12 shadow-2xl">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-emerald-500/20 backdrop-blur-sm text-emerald-300 text-sm font-semibold inline-flex items-center px-4 py-2 rounded-full border border-emerald-400/30">
                        <svg class="w-4 h-4 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Important Notice
                    </div>
                </div>
                <h1 class="text-white text-3xl md:text-5xl font-bold mb-4 leading-tight">
                    Profile Verification Required
                </h1>
                <p class="text-xl font-medium text-white/90 mb-6">
                    Please review and verify all your personal information for accuracy
                </p>
                <div class="flex flex-wrap gap-3">
                    <button class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold px-6 py-3 rounded-lg border border-white/30 transition-all duration-300 hover:scale-105">
                        Review Details
                    </button>
                    <button class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300 hover:scale-105 shadow-lg">
                        Verify Now
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Profile Section -->
    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-6">
            <!-- Profile Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8 transform hover:scale-[1.01] transition-all duration-300">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-32 relative">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute -bottom-16 left-8">
                        <div class="relative">
                            <img src="{{ $student->setAvatar() }}"
                                 class="w-32 h-32 rounded-2xl ring-4 ring-white dark:ring-gray-800 shadow-2xl object-cover transform hover:scale-105 transition-transform duration-300"
                                 alt="Profile Picture" />
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-emerald-500 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-20 pb-8 px-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-6 lg:mb-0">
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $student->prenom }} {{ $student->nom }}
                            </h1>
                            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                {{ $student->prenom_ar }} {{ $student->nom_ar }}
                            </h2>
                            <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span class="font-medium">{{ $student->email }}</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('student.profile.edit') }}"
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profile
                            </a>
                            <button class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all duration-300 hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">CNE</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->cne }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Apogee</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->apogee }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">CIN</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->cin }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Birthdate</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Detailed Information Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Nationality</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $student->nationalite ?? '—' }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Gender</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ $student->sexe === 'm' ? 'Male' : ($student->sexe === 'f' ? 'Female' : '—') }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Marital Status</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $student->situation_familiale ?? '—' }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Province of Birth</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $student->province_naissance ?? '—' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-3 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            Contact Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Phone Number</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $student->tel ?? '—' }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Emergency Contact</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $student->tel_urgence ?? '—' }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Address</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $student->adresse ?? '—' }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" dir="rtl">
                                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">العنوان</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $student->adresse_ar ?? '—' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap gap-4 justify-center">
                <button class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Verify Information
                </button>
                <button class="inline-flex items-center px-8 py-4 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-2xl border-2 border-gray-300 hover:border-gray-400 shadow-lg transition-all duration-300 hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Profile
                </button>
            </div>
        </div>
    </section>

    <!-- Custom Styles for Animations -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .group:hover .group-hover\:animate-bounce {
            animation: bounce 1s infinite;
        }

        /* Custom gradient animations */
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-shift 4s ease infinite;
        }
    </style>

@endsection --}}


@extends('student.layouts.app')

@section('main_content')
    <x-flash-message type="message" />
    <x-flash-message type="error" />

    <!-- Streamlined Hero Notice -->
    <section class="relative bg-gradient-to-br from-blue-600 to-indigo-600 dark:from-gray-800 dark:to-gray-900"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

<div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
   <div class="bg-white/10 dark:bg-black/20 backdrop-blur-md border border-white/20 rounded-2xl p-6 lg:p-8">

       <!-- Header -->
       <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 mb-6">
           <div class="flex-shrink-0">
               <div class="w-12 h-12 bg-amber-400/20 rounded-xl flex items-center justify-center">
                   <svg class="w-6 h-6 text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                       <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                   </svg>
               </div>
           </div>
           <div class="flex-1">
               <h3 class="text-white text-lg font-semibold mb-1">{{ __('messages.profile_verification_required') }}</h3>
               <p class="text-white/80 text-sm">{{ __('messages.review_information') }}</p>
           </div>
       </div>

       <!-- Warning Points -->
       <div class="bg-white/5 rounded-xl p-5 border border-white/10 space-y-3">
           <div class="flex items-start gap-3 text-white/90">
               <svg class="w-5 h-5 text-amber-300 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
               </svg>
               <p class="text-sm leading-relaxed">{{ __('messages.one_time_edit') }}</p>
           </div>

           <div class="flex items-start gap-3 text-white/90">
               <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
               </svg>
               <p class="text-sm leading-relaxed">{{ __('messages.invalid_documents_warning') }}</p>
           </div>

           <div class="flex items-start gap-3 text-white/90">
               <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
               </svg>
               <p class="text-sm leading-relaxed">{{ __('messages.final_approval') }}</p>
           </div>

           <div class="flex items-start gap-3 text-white/90">
               <svg class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
               </svg>
               <p class="text-sm leading-relaxed">{{ __('messages.no_responsibility') }}</p>
           </div>
       </div>

       <!-- Call to Action -->
       <div class="mt-5 text-center">
           <p class="text-white/70 text-sm">⚠️ {{ __('messages.double_check') }}</p>
       </div>

   </div>
</div>
</section>
    <!-- Main Profile Container -->
    <section class="py-8 lg:py-12 bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8 transform hover:scale-[1.01] transition-all duration-300">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-32 relative">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute -bottom-16 left-8">
                        <div class="relative">
                            <img src="{{ $student->setAvatar() }}"
                                 class="w-32 h-32 rounded-2xl ring-4 ring-white dark:ring-gray-800 shadow-2xl object-cover transform hover:scale-105 transition-transform duration-300"
                                 alt="Profile Picture" />
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-emerald-500 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-20 pb-8 px-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-6 lg:mb-0">
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $student->prenom }} {{ $student->nom }}
                            </h1>
                            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                {{ $student->prenom_ar }} {{ $student->nom_ar }}
                            </h2>
                            <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span class="font-medium">{{ $student->email }}</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('student.profile.edit') }}"
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profile
                            </a>
                            <button class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all duration-300 hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- CNE -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">CNE</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $student->cne }}</p>
                        </div>
                    </div>
                </div>

                <!-- Apogee -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Apogee</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $student->apogee }}</p>
                        </div>
                    </div>
                </div>

                <!-- CIN -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">CIN</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $student->cin }}</p>
                        </div>
                    </div>
                </div>

                <!-- Birth Date -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Birth Date</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Personal Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nationality</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white text-right">{{ $student->nationalite ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white text-right">
                                    {{ strtoupper($student->sexe) === 'M' ? 'Male' : (strtoupper($student->sexe) === 'F' ? 'Female' : '—') }}
                                </dd>
                            </div>
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Marital Status</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white text-right">{{ $student->situation_familiale ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Place</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white text-right">{{ $student->lieu_naissance ?? '—' }}</dd>
                            </div>
                            @if($student->boursier)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Scholarship Holder
                                </span>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            Contact Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white text-right">
                                    <a href="tel:{{ $student->tel }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $student->tel ?? '—' }}
                                    </a>
                                </dd>
                            </div>
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white text-right">
                                    <a href="tel:{{ $student->tel_urgence }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $student->tel_urgence ?? '—' }}
                                    </a>
                                </dd>
                            </div>
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Address</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $student->adresse ?? '—' }}</dd>
                            </div>
                            @if($student->adresse_ar)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700" dir="rtl">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">العنوان</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $student->adresse_ar }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                            </svg>
                            Academic Status
                        </h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $student->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                                <dd class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $student->email_verified_at ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                        {{ $student->email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </dd>
                            </div>
                            @if($student->last_login)
                            <div class="flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Login</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white text-right">
                                    {{ \Carbon\Carbon::parse($student->last_login)->diffForHumans() }}
                                </dd>
                            </div>
                            @endif
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Professional Status</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $student->situation_professionnelle ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Ready to verify?</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Confirm your information is correct</p>
                        </div>
                    </div>
                    <div class="flex gap-3 w-full sm:w-auto">
                        <button class="flex-1 sm:flex-none px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            Verify Information
                        </button>
                        <button class="flex-shrink-0 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200 inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Minimal Custom Styles -->
    <style>
        .bg-grid-pattern {
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.1) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>

@endsection