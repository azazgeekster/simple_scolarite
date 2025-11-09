<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

@include('admin.layouts.head')

<body class="bg-gray-100 min-h-screen flex flex-col" x-data="{ isDark: false }" x-init="
    // Set light mode as default
    if (!('isDark' in localStorage)) {
      localStorage.setItem('isDark', JSON.stringify(false));
    }
    isDark = JSON.parse(localStorage.getItem('isDark'));
    $watch('isDark', value => localStorage.setItem('isDark', JSON.stringify(value)))" x-cloak
    :class="{ 'dark': isDark }">

    @include('admin.layouts.nav')
    @include('admin.layouts.sidebar')

    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

        <div id="main-content" class="flex flex-col w-full h-screen overflow-y-auto bg-gray-50 lg:ml-72 dark:bg-gray-900">
            <main class="flex-grow px-6 pt-6 pb-6">
                <!-- Flash Messages -->
                <x-flash-message type="message"/>
                <x-flash-message type="error"/>
                <x-flash-message type="success"/>
                <x-flash-message type="status"/>

                @yield('content')
            </main>

            <!-- Footer will always be visible at bottom -->
            <footer class="mt-auto">
                @include('admin.layouts.footer')

                <p class="py-6 text-sm text-center text-gray-500 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    &copy; {{ date("Y") }} <a href="https://flowbite.com/" class="hover:underline" target="_blank">FSAAM</a>. All rights reserved.
                </p>
            </footer>
        </div>
    </div>

    @stack('js')
    @include('admin.layouts.scripts')
</body>

</html>
