<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FSAAM') }} - @yield('title', 'Admin Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="https://flowbite-admin-dashboard.vercel.app/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://flowbite-admin-dashboard.vercel.app/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://flowbite-admin-dashboard.vercel.app/favicon-16x16.png">
    <link rel="icon" type="image/png" href="https://flowbite-admin-dashboard.vercel.app/favicon.ico">
    <link rel="manifest" href="https://flowbite-admin-dashboard.vercel.app/site.webmanifest">
    <link rel="mask-icon" href="https://flowbite-admin-dashboard.vercel.app/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Meta Tags -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="FSAAM Admin Portal">
    <meta name="twitter:description" content="Admin portal for FSAAM university management">
    <meta property="og:title" content="FSAAM Admin Portal">
    <meta property="og:description" content="Admin portal for FSAAM university management">
    <meta property="og:type" content="website">

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-optical-sizing: auto;
        }

        /* Arabic font */
        body.rtl,
        [dir="rtl"],
        .font-arabic {
            font-family: 'Noto Kufi Arabic', sans-serif;
            font-optical-sizing: auto;
        }

        /* RTL Support */
        [dir="rtl"] #logo-sidebar {
            right: 0;
            left: auto;
            border-left: 1px solid;
            border-right: none;
        }

        [dir="rtl"] .sm\:mr-64 {
            margin-right: 0;
            margin-left: 16rem;
        }

        /* Mobile drawer animations */
        @media (max-width: 640px) {
            #logo-sidebar {
                transform: translateX(-100%);
            }

            [dir="rtl"] #logo-sidebar {
                transform: translateX(100%);
            }

            #logo-sidebar.translate-x-0 {
                transform: translateX(0) !important;
            }

            .p-4.sm\:ml-64,
            .p-4.sm\:mr-64 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }

        /* Ensure smooth transitions */
        #logo-sidebar {
            transition: transform 0.3s ease-in-out;
        }

        /* Fix for drawer backdrop */
        .drawer-backdrop {
            position: fixed;
            inset: 0;
            z-index: 30;
            background-color: rgba(17, 24, 39, 0.5);
        }

        /* Dropdown animations */
        [data-collapse-toggle] + ul {
            transition: all 0.3s ease-in-out;
            max-height: 0;
            overflow: hidden;
        }

        [data-collapse-toggle][aria-expanded="true"] + ul,
        [data-collapse-toggle] + ul:not(.hidden) {
            max-height: 500px;
        }

        /* Smooth scrollbar for sidebar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }

        /* Dark mode scrollbar */
        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(75, 85, 99, 0.5);
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: rgba(75, 85, 99, 0.7);
        }

        /* RTL spacing fixes */
        [dir="rtl"] .space-x-4 > * + * {
            margin-left: 0;
            margin-right: 1rem;
        }

        [dir="rtl"] .space-x-reverse > * + * {
            margin-right: 0;
            margin-left: 1rem;
        }

        /* Dropdown positioning */
        [dir="rtl"] [data-dropdown-toggle] + div[id*="dropdown"] {
            left: auto;
            right: 0;
        }

        /* Active link styling */
        #logo-sidebar a.active,
        #logo-sidebar button.active {
            background-color: rgb(59 130 246 / 0.1);
            color: rgb(59 130 246);
        }

        .dark #logo-sidebar a.active,
        .dark #logo-sidebar button.active {
            background-color: rgb(59 130 246 / 0.2);
            color: rgb(96 165 250);
        }

        /* Page transition animation */
        .page-transition {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Admin badge styling */
        .admin-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* Print styles - hide sidebar */
        @media print {
            #logo-sidebar,
            nav {
                display: none !important;
            }

            .sm\:ml-64,
            .sm\:mr-64 {
                margin: 0 !important;
            }
        }
    </style>

    @vite('resources/css/app.css')
    @stack('css')
</head>
