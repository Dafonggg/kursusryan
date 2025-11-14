@extends('admin.layouts.master')

@section('title', 'Admin Dashboard - Metronic')
@section('description', 'Admin Dashboard for Multi-Role System')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Admin Dashboard</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Admin</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Dashboard</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.quick-actions') }}" class="btn btn-sm fw-bold btn-primary">Quick Actions</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<!--begin::Row - KPI Summary-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- KPI Summary Component -->
	<div class="col-12">
		@include('admin.dashboard.components.kpi-summary')
	</div>
</div>
<!--end::Row - KPI Summary-->
<!--begin::Row - Income Chart & Latest Registrations-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Income Chart -->
	<div class="col-12 col-lg-8">
		@include('admin.dashboard.components.income-chart')
	</div>
	<!-- Latest Registrations -->
	<div class="col-12 col-lg-4">
		@include('admin.dashboard.components.latest-registrations')
	</div>
</div>
<!--end::Row - Income Chart & Latest Registrations-->
<!--begin::Row - Reschedule Requests & Today Sessions-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Reschedule Requests -->
	<div class="col-12 col-lg-6">
		@include('admin.dashboard.components.reschedule-requests')
	</div>
	<!-- Today Sessions -->
	<div class="col-12 col-lg-6">
		@include('admin.dashboard.components.today-sessions')
	</div>
</div>
<!--end::Row - Reschedule Requests & Today Sessions-->
<!--begin::Row - Latest Tickets-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		@include('admin.dashboard.components.latest-tickets')
	</div>
</div>
<!--end::Row - Latest Tickets-->
@endsection
