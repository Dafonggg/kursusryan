@extends('instructor.layouts.master')

@section('title', 'Instructor Dashboard - Metronic')
@section('description', 'Instructor Dashboard for Multi-Role System')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Instructor Dashboard</h1>
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
				<li class="breadcrumb-item text-gray-900">Dashboard</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('instructor.quick-actions') }}" class="btn btn-sm fw-bold btn-primary">Quick Actions</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<!--begin::Row - Today & Tomorrow Sessions-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Today Sessions -->
	<div class="col-12 col-lg-6">
		@include('instructor.dashboard.components.today-sessions')
	</div>
	<!-- Tomorrow Sessions -->
	<div class="col-12 col-lg-6">
		<div class="card card-flush h-md-100">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Sesi Saya Besok</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $tomorrow_sessions_count ?? 0 }} sesi</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="table-responsive">
					<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
						<thead>
							<tr>
								<th>Kursus</th>
								<th>Waktu</th>
								<th>Peserta</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@forelse($tomorrow_sessions ?? [] as $session)
							<tr>
								<td>{{ $session->course_name }}</td>
								<td>{{ $session->session_time }}</td>
								<td>{{ $session->participant_count }}</td>
								<td><a href="#" class="btn btn-sm btn-primary">Lihat</a></td>
							</tr>
							@empty
							<tr>
								<td colspan="4" class="text-center">
									<div class="empty-state">
										<div class="text">Tidak ada sesi besok</div>
									</div>
								</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end::Row - Today & Tomorrow Sessions-->
<!--begin::Row - My Courses & Reschedule Pending-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- My Courses -->
	<div class="col-12 col-lg-6">
		@include('instructor.dashboard.components.my-courses')
	</div>
	<!-- Reschedule Pending -->
	<div class="col-12 col-lg-6">
		@include('instructor.dashboard.components.reschedule-pending')
	</div>
</div>
<!--end::Row - My Courses & Reschedule Pending-->
<!--begin::Row - Attendance Pending & Latest Messages-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Attendance Pending -->
	<div class="col-12 col-lg-6">
		@include('instructor.dashboard.components.attendance-pending')
	</div>
	<!-- Latest Messages -->
	<div class="col-12 col-lg-6">
		@include('instructor.dashboard.components.latest-messages')
	</div>
</div>
<!--end::Row - Attendance Pending & Latest Messages-->
@endsection
