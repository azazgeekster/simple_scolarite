<!doctype html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
      x-data="{ isDark: localStorage.getItem('isDark') === 'true' }"
      x-init="$watch('isDark', value => localStorage.setItem('isDark', String(value)))"
      x-cloak
      :class="{ 'dark': isDark }">

@include('student.layouts.head')

<body class="bg-gray-100 min-h-screen flex flex-col">
    @include('student.layouts.nav')
    @include('student.layouts.sidebar')

    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

        <div id="main-content" class="flex flex-col w-full h-screen overflow-y-auto bg-gray-50 lg:ml-72 dark:bg-gray-900">
            <main class="flex-grow px-6 pt-6 pb-6">
                @yield('main_content')
            </main>

            <footer class="mt-auto">
                @include('student.layouts.footer')
                <p class="py-6 text-sm text-center text-gray-500 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    &copy; {{ date("Y") }} <a href="/" class="hover:underline" target="_blank">FSAAM</a>. all rights reserved.
                </p>
            </footer>
        </div>
    </div>

    @stack('js')
    @include('student.layouts.scripts')
</body>
</html>
