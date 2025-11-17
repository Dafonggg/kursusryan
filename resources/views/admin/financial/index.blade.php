@extends('admin.layouts.master')

@section('title', 'Keuangan - Admin')
@section('description', 'Dashboard keuangan dan laporan pendapatan')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Keuangan</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Keuangan</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.export-financial') }}" class="btn btn-sm fw-bold btn-light-primary">Export CSV</a>
			<a href="{{ route('admin.export-financial-pdf') }}" class="btn btn-sm fw-bold btn-primary">Export PDF</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<!--begin::Row - KPI Summary-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		@include('admin.dashboard.components.kpi-summary')
	</div>
</div>
<!--end::Row - KPI Summary-->

<!--begin::Row - Income Chart-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		@include('admin.dashboard.components.income-chart')
	</div>
</div>
<!--end::Row - Income Chart-->
@endsection

@push('styles')
<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/custom/chartjs/chartjs.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

