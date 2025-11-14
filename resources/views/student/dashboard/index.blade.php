@extends('student.layouts.master')

@section('title', isset($showMyCoursesOnly) && $showMyCoursesOnly ? 'Kursus Saya | Kursus Ryan Komputer' : (isset($showScheduleOnly) && $showScheduleOnly ? 'Jadwal | Kursus Ryan Komputer' : (isset($showPaymentOnly) && $showPaymentOnly ? 'Pembayaran | Kursus Ryan Komputer' : (isset($showCertificateOnly) && $showCertificateOnly ? 'Sertifikat | Kursus Ryan Komputer' : (isset($showChatOnly) && $showChatOnly ? 'Chat | Kursus Ryan Komputer' : 'Dashboard | Kursus Ryan Komputer')))))
@section('description', 'Student Dashboard for Multi-Role System')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
				@if(isset($showMyCoursesOnly) && $showMyCoursesOnly)
					Kursus Saya
				@elseif(isset($showScheduleOnly) && $showScheduleOnly)
					Jadwal
				@elseif(isset($showPaymentOnly) && $showPaymentOnly)
					Pembayaran
				@elseif(isset($showCertificateOnly) && $showCertificateOnly)
					Sertifikat
				@elseif(isset($showChatOnly) && $showChatOnly)
					Chat
				@else
					Student Dashboard
				@endif
			</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Student</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">
					@if(isset($showMyCoursesOnly) && $showMyCoursesOnly)
						Kursus Saya
					@elseif(isset($showScheduleOnly) && $showScheduleOnly)
						Jadwal
					@elseif(isset($showPaymentOnly) && $showPaymentOnly)
						Pembayaran
					@elseif(isset($showCertificateOnly) && $showCertificateOnly)
						Sertifikat
					@elseif(isset($showChatOnly) && $showChatOnly)
						Chat
					@else
						Dashboard
					@endif
				</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="/student/quick-actions" class="btn btn-sm fw-bold btn-primary">Quick Actions</a>
		</div>
	</div>
</div>
@endsection

@section('content')
@if(isset($showMyCoursesOnly) && $showMyCoursesOnly)
	<!--begin::Row - Continue Learning & Active Days Counter (Kursus Saya)-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Continue Learning -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.continue-learning')
		</div>
		<!-- Active Days Counter -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.active-days-counter')
		</div>
	</div>
	<!--end::Row - Continue Learning & Active Days Counter (Kursus Saya)-->
@elseif(isset($showScheduleOnly) && $showScheduleOnly)
	<!--begin::Row - Next Session (Jadwal)-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Next Session -->
		<div class="col-12">
			@include('student.dashboard.components.next-session')
		</div>
	</div>
	<!--end::Row - Next Session (Jadwal)-->
@elseif(isset($showPaymentOnly) && $showPaymentOnly)
	<!--begin::Row - Payment Status (Pembayaran)-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Payment Status -->
		<div class="col-12">
			@include('student.dashboard.components.payment-status')
		</div>
	</div>
	<!--end::Row - Payment Status (Pembayaran)-->
@elseif(isset($showCertificateOnly) && $showCertificateOnly)
	<!--begin::Row - Certificate Ready (Sertifikat)-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Certificate Ready -->
		<div class="col-12">
			@include('student.dashboard.components.certificate-ready')
		</div>
	</div>
	<!--end::Row - Certificate Ready (Sertifikat)-->
@elseif(isset($showChatOnly) && $showChatOnly)
	<!--begin::Row - Chat Shortcut (Chat)-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Chat Shortcut -->
		<div class="col-12">
			@include('student.dashboard.components.chat-shortcut')
		</div>
	</div>
	<!--end::Row - Chat Shortcut (Chat)-->
@else
	<!--begin::Row - Continue Learning & Next Session-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Continue Learning -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.continue-learning')
		</div>
		<!-- Next Session -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.next-session')
		</div>
	</div>
	<!--end::Row - Continue Learning & Next Session-->
	<!--begin::Row - Active Days Counter & Payment Status-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Active Days Counter -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.active-days-counter')
		</div>
		<!-- Payment Status -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.payment-status')
		</div>
	</div>
	<!--end::Row - Active Days Counter & Payment Status-->
	<!--begin::Row - Certificate Ready & Chat Shortcut-->
	<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
		<!-- Certificate Ready -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.certificate-ready')
		</div>
		<!-- Chat Shortcut -->
		<div class="col-12 col-lg-6">
			@include('student.dashboard.components.chat-shortcut')
		</div>
	</div>
	<!--end::Row - Certificate Ready & Chat Shortcut-->
@endif
@endsection
