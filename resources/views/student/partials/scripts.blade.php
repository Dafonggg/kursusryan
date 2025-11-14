<!--begin::Javascript-->
<script>var hostUrl = "{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/') }}";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Shared Utilities-->
<script type="module" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/src/shared/utils/chart-config.js') }}"></script>
<script type="module" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/src/shared/utils/formatters.js') }}"></script>
<!--end::Shared Utilities-->
<!--begin::Page Scripts-->
<script type="module" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/src/partialsStudent/_scripts.js') }}"></script>
<!--end::Page Scripts-->
@stack('scripts')
<!--end::Javascript-->

