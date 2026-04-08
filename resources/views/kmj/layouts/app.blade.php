<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SURETECH Systems')</title>

    {{-- Meta Tags --}}
    <meta name="description"
        content="The most advanced Bootstrap Admin Theme on Bootstrap Market trusted by over 4,000 beginners and professionals. Multi-demo, Dark Mode, RTL support. Grab your copy now and get life-time updates for free.">
    <meta name="keywords"
        content="keen, bootstrap, bootstrap 5, bootstrap 4, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Keen - Multi-demo Bootstrap 5 HTML Admin Dashboard Template by KeenThemes">
    <meta property="og:url" content="https://keenthemes.com/keen">
    <meta property="og:site_name" content="Keen by Keenthemes">

    <link rel="canonical" href="https://suretech.co.tz/">
    <link rel="shortcut icon" href="{{ asset('assets/dash/board_files/favicon-01.svg') }}">
    <link rel="canonical" href="https://suretech.co.tz">
    <link rel="shortcut icon" href="{{ asset('assets/dash/board_files/favicon--02-01.svg') }}">

    <script src="{{ asset('assets/dash/board_files/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/dash/board_files/scripts.bundle.js') }}"></script>
    <link href="{{ asset('assets/dash/board_files/plugins.bundle.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dash/board_files/style.bundle.css') }}" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!--begin::Fonts(mandatory for all pages)-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <!--end::Vendor Stylesheets-->

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/dash/board_files/style.bundle.css') }}" rel="stylesheet" type="text/css">
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Fonts(mandatory for all pages)-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/dash/board_files/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css">
    <!--end::Vendor Stylesheets-->

    <link rel="stylesheet" href="{{ asset('assets/dash/board_files/css7b91.css') }}"> <!--end::Fonts-->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/dash/board_files/plugins.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dash/board_files/datatables.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dash/board_files/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dash/board_files/product.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    {{-- Page Specific CSS --}}
    @stack('styles')

    <style>
        .symbol .symbol-label1 {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            background-color: #ffffff;
        }

        .symbol .symbol-label1 .bi {
            color: #003153 !important;
        }

        /* tbody tr:nth-child(odd) {
            background-color: #e6e9e7;
        } */

        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        tbody tr:hover {
            background-color: #d9dedb;
        }
    </style>
</head>

<body id="kt_app_body" class="app-default" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" style="background-color: #f5f6f5">
    {{-- Theme Mode --}}
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--Begin::Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!--End::Google Tag Manager (noscript) -->

    {{-- Begin App --}}
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            {{-- Header --}}
            @include('kmj.layouts.partials.header')

            {{-- Wrapper --}}
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">



                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    {{-- Alert Messages --}}
                    @include('kmj.components.alert-message')

                    <div class="mb-4"></div>

                    @yield('content')
                    {{-- Footer --}}
                    @include('kmj.layouts.partials.footer')
                </div>

            </div>

        </div>
    </div>

    {{-- Modals --}}
    @include('kmj.layouts.partials.modals')

    {{-- Global Scripts --}}

    {{-- Vendor JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function confirmAndDisable(link) {
            // Show confirmation
            if (!confirm('Are you sure you want to send TIRA?')) {
                return false; // cancel navigation
            }

            // Disable link to prevent double click
            link.style.pointerEvents = 'none';
            link.style.opacity = '0.6'; // optional visual feedback
            return true; // proceed navigation
        }
    </script>

    {{-- Page Specific Scripts --}}
    @stack('scripts')
</body>

</html>
