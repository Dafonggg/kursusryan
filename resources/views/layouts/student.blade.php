<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('description', 'Student Dashboard | Kursus Ryan Komputer')">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Student Dashboard | Kursus Ryan Komputer')</title>

        <!--begin::Student Dashboard Styles-->
        @include('student.partials.style')
        @stack('styles')
        <!--end::Student Dashboard Styles-->
    </head>
    
    <body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default @yield('body-class', '')">
        <!--begin::Theme mode setup on page load-->
        <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
        <!--end::Theme mode setup on page load-->
        
        <main>
            @yield('content')
        </main>

        <!--begin::Student Dashboard Scripts-->
        @include('student.partials.scripts')
        @stack('scripts')
        <!--end::Student Dashboard Scripts-->
    </body>
</html>

