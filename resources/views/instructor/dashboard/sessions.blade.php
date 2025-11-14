@extends('instructor.layouts.master')

@section('title', 'Sesi Saya - Metronic')
@section('description', 'Halaman Sesi Saya untuk Instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Sesi Saya</h1>
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
				<li class="breadcrumb-item text-gray-900">Sesi Saya</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<!--begin::Row - My Courses & Today Sessions-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- My Courses -->
	<div class="col-12 col-lg-6">
		@include('instructor.dashboard.components.my-courses')
	</div>
	<!-- Today Sessions -->
	<div class="col-12 col-lg-6">
		@include('instructor.dashboard.components.today-sessions')
	</div>
</div>
<!--end::Row - My Courses & Today Sessions-->
@endsection

