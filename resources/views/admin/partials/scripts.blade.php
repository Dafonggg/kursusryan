<!--begin::Javascript-->
<script>var hostUrl = "{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/') }}";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--end::Vendors Javascript-->
<!--begin::Shared Utilities-->
<script type="module" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/dashboard/js/shared/chart-config.js') }}"></script>
<script type="module" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/dashboard/js/shared/formatters.js') }}"></script>
<!--end::Shared Utilities-->
<!--begin::Page Scripts-->
<script type="module" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/dashboard/js/admin/_scripts.js') }}"></script>
<!--end::Page Scripts-->
@stack('scripts')
<!--end::Javascript-->

