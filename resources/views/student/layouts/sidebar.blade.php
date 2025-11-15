<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-72 h-screen pt-20 transition-all duration-300 -translate-x-full bg-gradient-to-b from-slate-50 to-white border-r border-slate-200/60 shadow-xl sm:translate-x-0 dark:from-slate-900 dark:to-slate-800 dark:border-slate-700/50 backdrop-blur-sm"
    aria-label="Sidebar">


 {{-- <aside  id="logo-sidebar"
 class="fixed top-0 {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }} h-screen w-64 bg-white dark:bg-gray-800 shadow-lg z-40"> --}}


    <!-- Sidebar Header -->
    <div class="px-6 pb-4 border-b border-slate-200/50 dark:border-slate-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5.334 6.343 5.376A1.086 1.086 0 005 6.459v9.982c0 .6.504 1.109 1.12 1.096C8.712 17.495 10.259 17.794 12 18.536m0-12.28C13.168 5.477 14.754 5.334 17.657 5.376A1.086 1.086 0 0119 6.459v9.982c0 .6-.504 1.109-1.12 1.096-1.692-.042-3.239.263-4.88 1.005"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Portail Étudiant</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Tableau de bord</p>
            </div>
        </div>
    </div>

    <div class="h-full px-4 pb-4 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600">
        <nav class="mt-6">
            <ul class="space-y-2">

                <!-- Ma Situation -->
                <li>
                    <a href="{{ route('student.mysituation') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-blue-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 group-hover:bg-blue-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Ma Situation</span>
                    </a>
                </li>



                <!-- Relevé de notes -->
                <li>
                    <a href="{{ route('student.releve.index') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-green-50 hover:text-green-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-green-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-green-100 group-hover:bg-green-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Relevé de Notes</span>
                    </a>
                </li>

                <!-- Retrait Documents -->
                <li>
                    <a href="{{ route('student.demande.index') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-purple-50 hover:text-purple-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-purple-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-purple-100 group-hover:bg-purple-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Retrait Documents</span>
                    </a>
                </li>

                <!-- Transfert -->
                <li>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-cyan-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-cyan-100 group-hover:bg-cyan-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Transfert</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="my-6">
                    <hr class="border-slate-200 dark:border-slate-700">
                </li>

                <!-- Opérations Dropdown -->
                <li x-data="{ open: false }">
                    <button @click="open = !open"
                        class="group flex items-center justify-between w-full px-4 py-3 text-slate-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-indigo-400 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 group-hover:bg-indigo-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                </svg>
                            </div>
                            <span class="ml-4 font-medium">Opérations</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 ml-6 space-y-1">
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-cyan-400 rounded-full mr-3"></span>
                                Transfert
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-emerald-400 rounded-full mr-3"></span>
                                Règlement de situation
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-orange-400 rounded-full mr-3"></span>
                                Changement de parcours
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Documents Dropdown -->
                <li x-data="{ open: false }">
                    <button @click="open = !open"
                        class="group flex items-center justify-between w-full px-4 py-3 text-slate-700 rounded-xl hover:bg-rose-50 hover:text-rose-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-rose-400 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-rose-100 group-hover:bg-rose-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <span class="ml-4 font-medium">Mes Documents</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 ml-6 space-y-1">
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                                Attestation de scolarité
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                Attestation de réussite
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                                Carte d'étudiant
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Examens Dropdown -->
                <li x-data="{ open: false }">
                    <button @click="open = !open"
                        class="group flex items-center justify-between w-full px-4 py-3 text-slate-700 rounded-xl hover:bg-orange-50 hover:text-orange-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-orange-400 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-orange-100 group-hover:bg-orange-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <span class="ml-4 font-medium">Examens</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('exams.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                                Calendrier des Examens
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.convocations') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-amber-400 rounded-full mr-3"></span>
                                Convocations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.grades') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                Notes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reclamations.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-3"></span>
                                Mes Réclamations
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Divider -->
                <li class="my-6">
                    <hr class="border-slate-200 dark:border-slate-700">
                </li>

                <!-- Messages -->
                <li>
                    <a href="{{ route('student.messages.index') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-pink-50 hover:text-pink-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-pink-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1 {{ Request::routeIs('student.messages.*') ? 'bg-pink-50 text-pink-700 dark:bg-slate-700/50 dark:text-pink-400' : '' }}">
                        <div class="flex items-center justify-center w-10 h-10 bg-pink-100 group-hover:bg-pink-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Messages</span>
                    </a>
                </li>

                <!-- Réinscription -->
                <li>
                    <a href="{{ route('student.mysituation') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-emerald-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-emerald-100 group-hover:bg-emerald-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Réinscription</span>
                    </a>
                </li>

                <!-- Sign Out -->
                <li class="mt-8">
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit"
                            class="group flex items-center w-full px-4 py-3 text-slate-700 rounded-xl hover:bg-red-50 hover:text-red-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-red-400 transition-all duration-200 border border-slate-200 dark:border-slate-700 hover:border-red-200 dark:hover:border-red-800">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 group-hover:bg-red-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="ml-4 font-medium">Déconnexion</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>

    /* Custom scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }

    .scrollbar-thumb-slate-300::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 2px;
    }

    .dark .scrollbar-thumb-slate-600::-webkit-scrollbar-thumb {
        background-color: #475569;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }

    .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background-color: #64748b;
    }
</style>
