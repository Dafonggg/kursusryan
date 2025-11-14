<!--begin::Head-->
<base href="{{ asset('/') }}" />
<title>@yield('title', 'Admin Dashboard - Metronic')</title>
<meta charset="utf-8" />
<meta name="description" content="@yield('description', 'Admin Dashboard for Multi-Role System')" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/logos/favicon.ico') }}" />
<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Global Stylesheets Bundle-->
<!--begin::Shared Styles-->
<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/dashboard/css/shared/global.css') }}" rel="stylesheet" type="text/css" />
<!--end::Shared Styles-->
<!--begin::Page Styles-->
<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/dashboard/css/admin/_styles.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Styles-->
@stack('styles')
<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
<!--end::Head-->

