{{-- <nav class="fixed top-0 z-50 w-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-b border-gray-200/50 dark:border-gray-700/50 shadow-lg">
    <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-between">
            <!-- Left section with mobile menu and logo -->
            <div class="flex items-center space-x-4">
                <!-- Mobile menu button -->
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                    aria-controls="logo-sidebar" type="button"
                    class="group inline-flex items-center justify-center p-2.5 text-gray-600 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 sm:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Logo section -->
                <a href="#" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            FSAAM
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                            Faculté des Sciences Appliquées
                        </p>
                    </div>
                </a>
            </div>

            <!-- Right section with actions -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button type="button" data-dropdown-toggle="notification-dropdown"
                        class="group relative p-2.5 text-gray-600 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200">
                        <span class="sr-only">View notifications</span>
                        <!-- Notification badge -->
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-white">3</span>
                        </div>
                        <svg class="w-6 h-6 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                        </svg>
                    </button>

                    <!-- Enhanced Notifications Dropdown -->
                    <div class="z-50 hidden max-w-sm sm:max-w-md my-4 overflow-hidden text-base list-none bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg divide-y divide-gray-100 dark:divide-gray-700 rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50"
                        id="notification-dropdown">

                        <!-- Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">3 nouvelles</span>
                            </div>
                        </div>

                        <!-- Notification Items -->
                        <div class="max-h-96 overflow-y-auto">
                            <a href="#" class="flex px-6 py-4 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200 border-l-4 border-transparent hover:border-blue-500">
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                        Document prêt pour retrait
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        Votre certificat de scolarité est maintenant disponible au bureau des affaires académiques.
                                    </div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                        Il y a 5 minutes
                                    </div>
                                </div>
                            </a>

                            <a href="#" class="flex px-6 py-4 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200 border-l-4 border-transparent hover:border-yellow-500">
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                        Rappel: Date limite approche
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        N'oubliez pas de retourner votre relevé de notes avant le 15 février 2025.
                                    </div>
                                    <div class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">
                                        Il y a 2 heures
                                    </div>
                                </div>
                            </a>

                            <a href="#" class="flex px-6 py-4 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200 border-l-4 border-transparent hover:border-purple-500">
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                        Mise à jour système
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        Le système sera en maintenance demain de 2h à 4h du matin.
                                    </div>
                                    <div class="text-xs text-purple-600 dark:text-purple-400 font-medium">
                                        Il y a 1 jour
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-gray-50/80 dark:bg-gray-700/50">
                            <a href="#" class="flex items-center justify-center space-x-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Voir toutes les notifications</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button id="theme-toggle" data-tooltip-target="tooltip-toggle" type="button"
                    class="group relative p-2.5 text-gray-600 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200">
                    <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6 transition-transform duration-200 group-hover:scale-110 group-hover:rotate-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-6 h-6 transition-transform duration-200 group-hover:scale-110 group-hover:rotate-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Enhanced Tooltip -->
                <div id="tooltip-toggle" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 dark:bg-gray-600 rounded-lg shadow-lg opacity-0 tooltip">
                    Basculer le mode sombre
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button"
                        class="group flex items-center justify-center w-12 h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800"
                        aria-expanded="false" :aria-expanded="open.toString()" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <div class="relative">
                            <img class="w-9 h-9 rounded-lg object-cover transition-transform duration-200 group-hover:scale-105"
                                src="{{ auth('student')->user()->setAvatar() }}" alt="user photo">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                        </div>
                    </button>

                    <!-- Enhanced User Dropdown -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-3 w-64 origin-top-right rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg border border-gray-200/50 dark:border-gray-700/50 overflow-hidden"
                        style="display: none;" id="dropdown-user">

                        <!-- User Info Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <img class="w-12 h-12 rounded-xl object-cover"
                                        src="{{ auth('student')->user()->setAvatar() }}" alt="user photo">
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ auth('student')->user()->full_name }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        Étudiant actif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('student.profile.show') }}"
                                class="group flex items-center px-6 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/70 transition-colors duration-200">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium">Mon Profil</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Gérer vos informations</div>
                                </div>
                            </a>

                            <a href="#" class="group flex items-center px-6 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg mr-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-900/70 transition-colors duration-200">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium">Paramètres</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Préférences et options</div>
                                </div>
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>

                        <!-- Logout -->
                        <div class="py-2">
                            <form method="POST" action="{{ route('student.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="group w-full flex items-center px-6 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50/80 dark:hover:bg-red-900/20 transition-all duration-200">
                                    <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg mr-3 group-hover:bg-red-200 dark:group-hover:bg-red-900/70 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-left">Se déconnecter</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-left">Fermer la session</div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile search bar (hidden by default, can be toggled) -->
    <div class="hidden px-4 pb-3 sm:hidden" id="mobile-search">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" placeholder="Rechercher..."
                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200">
        </div>
    </div>
</nav>

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Theme toggle functionality
        const themeToggle = document.getElementById("theme-toggle");
        const darkIcon = document.getElementById("theme-toggle-dark-icon");
        const lightIcon = document.getElementById("theme-toggle-light-icon");

        function updateThemeUI(isDark) {
            if (isDark) {
                document.documentElement.classList.add("dark");
                localStorage.setItem("theme", "dark");
                darkIcon.classList.remove("hidden");
                lightIcon.classList.add("hidden");
            } else {
                document.documentElement.classList.remove("dark");
                localStorage.setItem("theme", "light");
                darkIcon.classList.add("hidden");
                lightIcon.classList.remove("hidden");
            }
        }

        // Get user theme preference or system setting
        const userPrefersDark = localStorage.getItem("theme") === "dark" ||
            (!localStorage.getItem("theme") && window.matchMedia("(prefers-color-scheme: dark)").matches);

        updateThemeUI(userPrefersDark);

        themeToggle.addEventListener("click", function () {
            const isDark = document.documentElement.classList.contains("dark");
            updateThemeUI(!isDark);

            // Add a subtle animation to the navbar
            const navbar = document.querySelector('nav');
            navbar.style.transform = 'scale(0.99)';
            setTimeout(() => {
                navbar.style.transform = 'scale(1)';
            }, 100);
        });

        // Enhanced dropdown functionality
        const notificationButton = document.querySelector('[data-dropdown-toggle="notification-dropdown"]');
        const notificationDropdown = document.getElementById('notification-dropdown');

        if (notificationButton && notificationDropdown) {
            notificationButton.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');

                // Add entrance animation
                if (!notificationDropdown.classList.contains('hidden')) {
                    notificationDropdown.style.opacity = '0';
                    notificationDropdown.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        notificationDropdown.style.transition = 'all 0.2s ease-out';
                        notificationDropdown.style.opacity = '1';
                        notificationDropdown.style.transform = 'translateY(0)';
                    }, 10);
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                if (!notificationDropdown.classList.contains('hidden')) {
                    notificationDropdown.style.opacity = '0';
                    notificationDropdown.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        notificationDropdown.classList.add('hidden');
                    }, 150);
                }
            });
        }

        // Navbar scroll effect
        let lastScrollY = window.scrollY;
        const navbar = document.querySelector('nav');

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;

            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                // Scrolling down - hide navbar
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up - show navbar
                navbar.style.transform = 'translateY(0)';
            }

            // Add backdrop blur effect based on scroll
            if (currentScrollY > 50) {
                navbar.classList.add('bg-white/95', 'dark:bg-gray-900/95');
                navbar.classList.remove('bg-white/90', 'dark:bg-gray-900/90');
            } else {
                navbar.classList.add('bg-white/90', 'dark:bg-gray-900/90');
                navbar.classList.remove('bg-white/95', 'dark:bg-gray-900/95');
            }

            lastScrollY = currentScrollY;
        });

        // Mobile menu toggle (if you have a sidebar)
        const mobileMenuButton = document.querySelector('[data-drawer-toggle="logo-sidebar"]');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                // Add click animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        }

        // Add loading animation when page loads
        navbar.style.opacity = '0';
        navbar.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            navbar.style.transition = 'all 0.4s ease-out';
            navbar.style.opacity = '1';
            navbar.style.transform = 'translateY(0)';
        }, 100);

        // Enhanced notification interactions
        const notificationItems = document.querySelectorAll('#notification-dropdown a');
        notificationItems.forEach((item, index) => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(4px)';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });

            // Staggered animation on dropdown open
            item.style.opacity = '0';
            item.style.transform = 'translateY(10px)';
            setTimeout(() => {
                item.style.transition = 'all 0.2s ease-out';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 50 + (index * 50));
        });

        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');

                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Keyboard navigation support
        document.addEventListener('keydown', function(e) {
            // ESC key closes dropdowns
            if (e.key === 'Escape') {
                const openDropdowns = document.querySelectorAll('.z-50:not(.hidden)');
                openDropdowns.forEach(dropdown => {
                    if (dropdown.id === 'notification-dropdown') {
                        dropdown.style.opacity = '0';
                        dropdown.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            dropdown.classList.add('hidden');
                        }, 150);
                    }
                });
            }
        });

        // Add smooth transitions to all interactive elements
        const interactiveElements = document.querySelectorAll('button, a, [role="button"]');
        interactiveElements.forEach(element => {
            element.style.transition = 'all 0.2s ease-in-out';
        });
    });
</script>

<style>
    /* Enhanced navbar styles */
    nav {
        transition: transform 0.3s ease-in-out, background-color 0.3s ease-in-out !important;
    }

    /* Ripple effect */
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Enhanced scrollbar for notification dropdown */
    #notification-dropdown .max-h-96::-webkit-scrollbar {
        width: 6px;
    }

    #notification-dropdown .max-h-96::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    #notification-dropdown .max-h-96::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    #notification-dropdown .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Dark mode scrollbar */
    .dark #notification-dropdown .max-h-96::-webkit-scrollbar-track {
        background: #374151;
    }

    .dark #notification-dropdown .max-h-96::-webkit-scrollbar-thumb {
        background: #6b7280;
    }

    .dark #notification-dropdown .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Smooth hover transitions */
    .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }

    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }

    .group:hover .group-hover\:rotate-12 {
        transform: rotate(12deg);
    }

    /* Enhanced focus states */
    button:focus-visible,
    a:focus-visible {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
        border-radius: 0.75rem;
    }

    /* Mobile optimizations */
    @media (max-width: 640px) {
        .backdrop-blur-lg {
            backdrop-filter: blur(12px);
        }
    }

    /* Loading animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out;
    }
</style>
@endpush --}}
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="https://flowbite.com" class="flex ms-2 md:me-24">
                    <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 me-3" alt="FlowBite Logo" />
                    <span
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-gray-800 dark:text-white">FSAAM</span>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Notification button -->
                <button type="button" data-dropdown-toggle="notification-dropdown"
                    class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                    <span class="sr-only">View notifications</span>

                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
                        </path>
                    </svg>
                </button>
                <x-language-switcher />

                <div class="z-20 z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:divide-gray-600 dark:bg-gray-700"
                    id="notification-dropdown">
                    <div
                        class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        Notifications
                    </div>
                    <div>
                        <a href="#"
                            class="flex px-4 py-3 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                            <div class="flex-shrink-0">
                                <img class="rounded-full w-11 h-11"
                                    src="https://flowbite-admin-dashboard.vercel.app/images/users/bonnie-green.png"
                                    alt="Jese image">
                                <div
                                    class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 border border-white rounded-full bg-primary-700 dark:border-gray-700">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z">
                                        </path>
                                        <path
                                            d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full pl-3">
                                <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">New message
                                    from <span class="font-semibold text-gray-900 dark:text-white">Bonnie
                                        Green</span>: "Hey, what's up? All set for the presentation?"</div>
                                <div class="text-xs font-medium text-primary-700 dark:text-primary-400">a few
                                    moments ago</div>
                            </div>
                        </a>
                        <a href="#"
                            class="flex px-4 py-3 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                            <div class="flex-shrink-0">
                                <img class="rounded-full w-11 h-11"
                                    src="https://flowbite-admin-dashboard.vercel.app/images/users/jese-leos.png"
                                    alt="Jese image">
                                <div
                                    class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-gray-900 border border-white rounded-full dark:border-gray-700">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full pl-3">
                                <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"><span
                                        class="font-semibold text-gray-900 dark:text-white">Jese leos</span> and
                                    <span class="font-medium text-gray-900 dark:text-white">5 others</span> started
                                    following you.
                                </div>
                                <div class="text-xs font-medium text-primary-700 dark:text-primary-400">10 minutes
                                    ago</div>
                            </div>
                        </a>
                        <a href="#"
                            class="flex px-4 py-3 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                            <div class="flex-shrink-0">
                                <img class="rounded-full w-11 h-11"
                                    src="https://flowbite-admin-dashboard.vercel.app/images/users/joseph-mcfall.png"
                                    alt="Joseph image">
                                <div
                                    class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-red-600 border border-white rounded-full dark:border-gray-700">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full pl-3">
                                <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"><span
                                        class="font-semibold text-gray-900 dark:text-white">Joseph Mcfall</span> and
                                    <span class="font-medium text-gray-900 dark:text-white">141 others</span> love
                                    your story. See it and view more stories.
                                </div>
                                <div class="text-xs font-medium text-primary-700 dark:text-primary-400">44 minutes
                                    ago</div>
                            </div>
                        </a>
                        <a href="#"
                            class="flex px-4 py-3 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                            <div class="flex-shrink-0">
                                <img class="rounded-full w-11 h-11"
                                    src="https://flowbite-admin-dashboard.vercel.app/images/users/leslie-livingston.png"
                                    alt="Leslie image">
                                <div
                                    class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-green-400 border border-white rounded-full dark:border-gray-700">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full pl-3">
                                <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"><span
                                        class="font-semibold text-gray-900 dark:text-white">Leslie Livingston</span>
                                    mentioned you in a comment: <span
                                        class="font-medium text-primary-700 dark:text-primary-500">@bonnie.green</span>
                                    what do you say?</div>
                                <div class="text-xs font-medium text-primary-700 dark:text-primary-400">1 hour ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <div class="flex-shrink-0">
                                <img class="rounded-full w-11 h-11"
                                    src="https://flowbite-admin-dashboard.vercel.app/images/users/robert-brown.png"
                                    alt="Robert image">
                                <div
                                    class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-purple-500 border border-white rounded-full dark:border-gray-700">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full pl-3">
                                <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"><span
                                        class="font-semibold text-gray-900 dark:text-white">Robert Brown</span>
                                    posted a new video: Glassmorphism - learn how to implement the new design trend.
                                </div>
                                <div class="text-xs font-medium text-primary-700 dark:text-primary-400">3 hours ago
                                </div>
                            </div>
                        </a>
                    </div>

                    <a href="#"
                        class="block py-2 text-base font-normal text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:underline">
                        <div class="inline-flex items-center ">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            View all
                        </div>
                    </a>
                </div>


                <!-- Theme toggle -->
                <button id="theme-toggle" data-tooltip-target="tooltip-toggle" type="button"
                    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div id="tooltip-toggle" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Toggle dark mode
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <!-- User menu -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button"
                        class="group flex items-center justify-center w-12 h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800"
                        aria-expanded="false" :aria-expanded="open.toString()" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <div class="relative">
                            <img class="w-9 h-9 rounded-lg object-cover transition-transform duration-200 group-hover:scale-105"
                                src="{{ auth('student')->user()->setAvatar() }}" alt="user photo">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                        </div>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-3 w-64 origin-top-right rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg border border-gray-200/50 dark:border-gray-700/50 overflow-hidden"
                        style="display: none;" id="dropdown-user">

                        <!-- User Info Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <img class="w-12 h-12 rounded-xl object-cover"
                                        src="{{ auth('student')->user()->setAvatar() }}" alt="user photo">
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ auth('student')->user()->full_name }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        Étudiant actif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('student.profile.show') }}"
                                class="group flex items-center px-6 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/70 transition-colors duration-200">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium">Mon Profil</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Gérer vos informations</div>
                                </div>
                            </a>

                            <a href="#" class="group flex items-center px-6 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg mr-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-900/70 transition-colors duration-200">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium">Paramètres</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Préférences et options</div>
                                </div>
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>

                        <!-- Logout -->
                        <div class="py-2">
                            <form method="POST" action="{{ route('student.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="group w-full flex items-center px-6 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50/80 dark:hover:bg-red-900/20 transition-all duration-200">
                                    <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg mr-3 group-hover:bg-red-200 dark:group-hover:bg-red-900/70 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-left">Se déconnecter</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-left">Fermer la session</div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>


@push('js')

    <script>

        document.addEventListener("DOMContentLoaded", function () {


            const themeToggle = document.getElementById("theme-toggle");
            const darkIcon = document.getElementById("theme-toggle-dark-icon");
            const lightIcon = document.getElementById("theme-toggle-light-icon");

            function updateThemeUI(isDark) {
                if (isDark) {
                    console.log("D")
                    document.documentElement.classList.add("dark");
                    localStorage.setItem("theme", "dark");
                    darkIcon.classList.remove("hidden");
                    lightIcon.classList.add("hidden");
                } else {
                    console.log("L")

                    document.documentElement.classList.remove("dark");
                    localStorage.setItem("theme", "light");
                    darkIcon.classList.add("hidden");
                    lightIcon.classList.remove("hidden");
                }
            }

            // Get user theme preference or system setting
            const userPrefersDark = localStorage.getItem("theme") === "dark" ||
                (!localStorage.getItem("theme") && window.matchMedia("(prefers-color-scheme: dark)").matches);

            updateThemeUI(userPrefersDark);

            themeToggle.addEventListener("click", function () {
                const isDark = document.documentElement.classList.contains("dark");
                updateThemeUI(!isDark);
            });
        });

    </script>


@endpush