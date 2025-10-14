<!doctype html>
<html lang="en" dir="ltr">
@include('student.layouts.head')
@stack('css')
<body class="bg-gray-100 min-h-screen flex flex-col" x-data="{ isDark: false }" x-init="
    if (!('isDark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      localStorage.setItem('isDark', JSON.stringify(true));
    }
    isDark = JSON.parse(localStorage.getItem('isDark'));
    $watch('isDark', value => localStorage.setItem('isDark', JSON.stringify(value)))" x-cloak
    >
    @include('student.layouts.nav')
    @include('student.layouts.sidebar')
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

        <div id="main-content" class="flex flex-col w-full h-screen overflow-y-auto bg-gray-50 lg:ml-72 dark:bg-gray-900">
            <main class="flex-grow px-6 pt-6 pb-6">
                @yield('main_content')
            </main>

            {{-- Footer will always be visible at bottom --}}
            <footer class="mt-auto">
                @include('student.layouts.footer')

                <p class="py-6 text-sm text-center text-gray-500 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    &copy; {{ date("Y") }} <a href="https://flowbite.com/" class="hover:underline" target="_blank">FSAAM</a>. all rights reserved.
                </p>
            </footer>
        </div>
    </div>

    @stack('js')
    @include('student.layouts.scripts')
</body>

</html>
