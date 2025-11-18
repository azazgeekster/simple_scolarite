<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-72 h-screen pt-20 transition-all duration-300 -translate-x-full bg-gradient-to-b from-slate-50 to-white border-r border-slate-200/60 shadow-xl sm:translate-x-0 dark:from-slate-900 dark:to-slate-800 dark:border-slate-700/50 backdrop-blur-sm"
    aria-label="Sidebar">

    <!-- Sidebar Header -->
    <div class="px-6 pb-4 border-b border-slate-200/50 dark:border-slate-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Admin Portal</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Management Panel</p>
            </div>
        </div>
    </div>

    <div class="h-full px-4 pb-4 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600">
        <nav class="mt-6">
            <ul class="space-y-2">

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-purple-50 hover:text-purple-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-purple-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1 {{ Request::routeIs('admin.dashboard') ? 'bg-purple-50 text-purple-700 dark:bg-slate-700/50 dark:text-purple-400' : '' }}">
                        <div class="flex items-center justify-center w-10 h-10 bg-purple-100 group-hover:bg-purple-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Dashboard</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="my-6">
                    <hr class="border-slate-200 dark:border-slate-700">
                    <p class="px-4 mt-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Student Management</p>
                </li>

                <!-- Students -->
                <li>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-blue-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 group-hover:bg-blue-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Students</span>
                    </a>
                </li>

                <!-- Enrollments -->
                <li>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-green-50 hover:text-green-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-green-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-green-100 group-hover:bg-green-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Enrollments</span>
                    </a>
                </li>

                <!-- Document Requests -->
                <li>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-amber-50 hover:text-amber-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-amber-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-amber-100 group-hover:bg-amber-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Document Requests</span>
                    </a>
                </li>

                <!-- Profile Change Requests -->
                <li>
                    <a href="{{ route('admin.profile-change-requests.index') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-teal-50 hover:text-teal-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-teal-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1 {{ Request::routeIs('admin.profile-change-requests.*') ? 'bg-teal-50 text-teal-700 dark:bg-slate-700/50 dark:text-teal-400' : '' }}">
                        <div class="flex items-center justify-center w-10 h-10 bg-teal-100 group-hover:bg-teal-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Profile Changes</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="my-6">
                    <hr class="border-slate-200 dark:border-slate-700">
                    <p class="px-4 mt-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Academic</p>
                </li>

                <!-- Exams Dropdown -->
                <li x-data="{ open: false }">
                    <button @click="open = !open"
                        class="group flex items-center justify-between w-full px-4 py-3 text-slate-700 rounded-xl hover:bg-orange-50 hover:text-orange-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-orange-400 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-orange-100 group-hover:bg-orange-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <span class="ml-4 font-medium">Exams</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('admin.exam-periods.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200 {{ Request::routeIs('admin.exam-periods.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-orange-600 dark:text-orange-400' : '' }}">
                                <span class="w-2 h-2 bg-indigo-400 rounded-full mr-3"></span>
                                Exam Periods
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.exam-scheduling.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200 {{ Request::routeIs('admin.exam-scheduling.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-orange-600 dark:text-orange-400' : '' }}">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                                Schedule Exams
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.locals.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200 {{ Request::routeIs('admin.locals.*') ? 'bg-slate-100 dark:bg-slate-700/50 text-orange-600 dark:text-orange-400' : '' }}">
                                <span class="w-2 h-2 bg-cyan-400 rounded-full mr-3"></span>
                                Manage Rooms
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.exams.import') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200 {{ Request::routeIs('admin.exams.import') ? 'bg-slate-100 dark:bg-slate-700/50 text-orange-600 dark:text-orange-400' : '' }}">
                                <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                                Import Exams
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                Grades Management
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-3"></span>
                                Reclamations
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Courses -->
                <li>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-indigo-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 group-hover:bg-indigo-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Courses</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="my-6">
                    <hr class="border-slate-200 dark:border-slate-700">
                    <p class="px-4 mt-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Communication</p>
                </li>

                <!-- Messages -->
                <li>
                    <a href="{{ route('admin.messages.index') }}"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-pink-50 hover:text-pink-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-pink-400 transition-all duration-200 hover:shadow-sm hover:translate-x-1 {{ Request::routeIs('admin.messages.*') ? 'bg-pink-50 text-pink-700 dark:bg-slate-700/50 dark:text-pink-400' : '' }}">
                        <div class="flex items-center justify-center w-10 h-10 bg-pink-100 group-hover:bg-pink-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Messages</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="my-6">
                    <hr class="border-slate-200 dark:border-slate-700">
                    <p class="px-4 mt-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">System</p>
                </li>

                <!-- Users & Permissions Dropdown -->
                <li x-data="{ open: false }">
                    <button @click="open = !open"
                        class="group flex items-center justify-between w-full px-4 py-3 text-slate-700 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-cyan-400 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-cyan-100 group-hover:bg-cyan-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="ml-4 font-medium">Users & Access</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                                Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                                Roles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.permissions.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                Permissions
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Settings -->
                <li>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-slate-700 rounded-xl hover:bg-slate-100 hover:text-slate-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-slate-200 transition-all duration-200 hover:shadow-sm hover:translate-x-1">
                        <div class="flex items-center justify-center w-10 h-10 bg-slate-200 group-hover:bg-slate-300 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="ml-4 font-medium">Settings</span>
                    </a>
                </li>

                <!-- Sign Out -->
                <li class="mt-8">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                            class="group flex items-center w-full px-4 py-3 text-slate-700 rounded-xl hover:bg-red-50 hover:text-red-700 dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-red-400 transition-all duration-200 border border-slate-200 dark:border-slate-700 hover:border-red-200 dark:hover:border-red-800">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 group-hover:bg-red-200 dark:bg-slate-700 dark:group-hover:bg-slate-600 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="ml-4 font-medium">Sign Out</span>
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
