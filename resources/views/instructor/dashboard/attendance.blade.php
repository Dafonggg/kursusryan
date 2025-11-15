@extends('instructor.layouts.master')

@section('title', 'Absensi - Metronic')
@section('description', 'Halaman Absensi untuk Instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Absensi</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Instructor</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Absensi</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<!--begin::Row - Attendance Pending-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Attendance Pending -->
	<div class="col-12">
		@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
		@endif

		@if(session('info'))
		<div class="alert alert-info alert-dismissible fade show" role="alert">
			{{ session('info') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
		@endif

		@include('instructor.dashboard.components.attendance-pending')
	</div>
</div>
<!--end::Row - Attendance Pending-->
@endsection

