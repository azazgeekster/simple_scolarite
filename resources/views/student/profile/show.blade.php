@extends('student.layouts.app')

@section('main_content')
    <x-flash-message type="message" />
    <x-flash-message type="error" />

    <!-- Enhanced Notice Section with Better UX -->
    @if(!$student->email_verified_at || count($pendingChanges ?? []) > 0)
    <section class="relative bg-gradient-to-br from-slate-50 via-teal-50 to-emerald-50 dark:from-gray-800 dark:to-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            @if(count($pendingChanges ?? []) > 0)
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-teal-200 dark:border-teal-800 rounded-xl p-5 mb-4">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Changes Pending Approval</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">You have {{ count($pendingChanges) }} change(s) waiting for administrative review.</p>
                        <div class="space-y-2">
                            @foreach($pendingChanges as $change)
                            <div class="text-xs bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300 px-3 py-1.5 rounded-md inline-block mr-2">
                                {{ $change->field_label }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(!$student->email_verified_at)
            <div class="bg-amber-50/80 dark:bg-amber-900/20 backdrop-blur-sm border border-amber-200 dark:border-amber-800 rounded-xl p-5">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-1">Email Verification Required</h4>
                        <p class="text-sm text-amber-700 dark:text-amber-300">Please verify your email address to access all features.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Main Profile Section -->
    <section class="py-8 lg:py-12 bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Profile Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <!-- Elegant Gradient Header -->
                <div class="bg-gradient-to-r from-slate-600 via-teal-600 to-emerald-600 h-28 relative">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute -bottom-12 left-6">
                        <div class="relative">
                            <img src="{{ $student->setAvatar() }}"
                                 class="w-24 h-24 rounded-xl ring-4 ring-white dark:ring-gray-800 shadow-lg object-cover"
                                 alt="Profile Picture" />
                            @if($student->email_verified_at)
                            <div class="absolute -top-1 -right-1 w-7 h-7 bg-emerald-500 rounded-full border-3 border-white dark:border-gray-800 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-16 pb-6 px-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="flex-1">
                            <!-- French Name -->
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                {{ $student->prenom }} {{ $student->nom }}
                            </h1>
                            <!-- Arabic Name -->
                            @if($student->prenom_ar && $student->nom_ar)
                            <h2 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-3" dir="rtl">
                                {{ $student->prenom_ar }} {{ $student->nom_ar }}
                            </h2>
                            @endif
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    {{ $student->email }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('student.profile.edit') }}"
                               class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">CNE</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $student->cne }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Apogee</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $student->apogee }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">CIN</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $student->cin }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Birth Date</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $student->date_naissance ? \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Personal Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-600 dark:text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-3">
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Nationality</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white text-right">{{ $student->nationalite ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Gender</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white">
                                    {{ strtoupper($student->sexe) === 'M' ? 'Male' : (strtoupper($student->sexe) === 'F' ? 'Female' : '—') }}
                                </dd>
                            </div>
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Marital Status</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white text-right">{{ $student->situation_familiale ?? '—' }}</dd>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Place of Birth</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->lieu_naissance ?? '—' }}</dd>
                            </div>
                            @if($student->lieu_naissance_ar)
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700" dir="rtl">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">مكان الإزدياد</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->lieu_naissance_ar }}</dd>
                            </div>
                            @endif
                            @if($student->boursier)
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
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
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            Contact Details
                        </h3>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-3">
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white">
                                    @if($student->tel)
                                    <a href="tel:{{ $student->tel }}" class="hover:text-teal-600 dark:hover:text-teal-400">
                                        {{ $student->tel }}
                                    </a>
                                    @else
                                    —
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Emergency</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white">
                                    @if($student->tel_urgence)
                                    <a href="tel:{{ $student->tel_urgence }}" class="hover:text-teal-600 dark:hover:text-teal-400">
                                        {{ $student->tel_urgence }}
                                    </a>
                                    @else
                                    —
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Country</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white text-right">{{ $student->pays ?? '—' }}</dd>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Address</dt>
                                <dd class="text-sm text-gray-900 dark:text-white leading-relaxed">{{ $student->adresse ?? '—' }}</dd>
                            </div>
                            @if($student->adresse_ar)
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700" dir="rtl">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">العنوان</dt>
                                <dd class="text-sm text-gray-900 dark:text-white leading-relaxed">{{ $student->adresse_ar }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                            </svg>
                            Academic Status
                        </h3>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-3">
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $student->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $student->email_verified_at ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                        {{ $student->email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </dd>
                            </div>
                            @if($student->last_login)
                            <div class="flex justify-between items-start text-sm">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Last Login</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white text-right">
                                    {{ \Carbon\Carbon::parse($student->last_login)->diffForHumans() }}
                                </dd>
                            </div>
                            @endif
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Professional Status</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->situation_professionnelle ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Family Information Section -->
            @if($student->family)
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Family Information
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Father Information -->
                        @if($student->family->father_firstname || $student->family->father_lastname)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <span class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                Father
                            </h4>
                            <dl class="space-y-2 text-sm ml-8">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->father_firstname }} {{ $student->family->father_lastname }}</dd>
                                </div>
                                @if($student->family->father_cin)
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">CIN</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->father_cin }}</dd>
                                </div>
                                @endif
                                @if($student->family->father_profession)
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Profession</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->father_profession }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        @endif

                        <!-- Mother Information -->
                        @if($student->family->mother_firstname || $student->family->mother_lastname)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <span class="w-6 h-6 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                Mother
                            </h4>
                            <dl class="space-y-2 text-sm ml-8">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->mother_firstname }} {{ $student->family->mother_lastname }}</dd>
                                </div>
                                @if($student->family->mother_cin)
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">CIN</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->mother_cin }}</dd>
                                </div>
                                @endif
                                @if($student->family->mother_profession)
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400">Profession</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->mother_profession }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        @endif
                    </div>

                    <!-- Spouse Information -->
                    @if($student->family->spouse_cin || $student->family->spouse_death_date)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                            <span class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-3 h-3 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </span>
                            Spouse Information
                        </h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm ml-8">
                            @if($student->family->spouse_cin)
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400 mb-1">CIN</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->spouse_cin }}</dd>
                            </div>
                            @endif
                            @if($student->family->spouse_death_date)
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400 mb-1">Death Date</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($student->family->spouse_death_date)->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif

                    <!-- Handicap Information -->
                    @if($student->family->handicap_type || $student->family->handicap_card_number || $student->family->handicap_code)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                            <span class="w-6 h-6 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-3 h-3 text-teal-600 dark:text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Special Needs Information
                        </h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm ml-8">
                            @if($student->family->handicap_type)
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400 mb-1">Type</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->handicap_type }}</dd>
                            </div>
                            @endif
                            @if($student->family->handicap_card_number)
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400 mb-1">Card Number</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->handicap_card_number }}</dd>
                            </div>
                            @endif
                            @if($student->family->handicap_code)
                            <div>
                                <dt class="text-gray-500 dark:text-gray-400 mb-1">Code</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $student->family->handicap_code }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection