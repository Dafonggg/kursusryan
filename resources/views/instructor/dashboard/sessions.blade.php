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
<!--begin::Row - Filter & Today Sessions-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Filter & Today Sessions -->
	<div class="col-12">
		<div class="card card-flush mb-5">
			<div class="card-body">
				<form method="GET" action="{{ route('instructor.sessions') }}" class="d-flex gap-3 align-items-end">
					<div class="flex-grow-1">
						<label class="form-label">Filter Tanggal</label>
						<input type="date" name="date" class="form-control form-control-solid" value="{{ $dateFilter ?? '' }}">
					</div>
					<button type="submit" class="btn btn-primary">Filter</button>
					@if($dateFilter)
					<a href="{{ route('instructor.sessions') }}" class="btn btn-light">Reset</a>
					@endif
				</form>
			</div>
		</div>
	</div>
</div>

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

<!--begin::Row - All Sessions-->
@if(isset($sessions) && count($sessions) > 0)
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Jadwal Mengajar</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ count($sessions) }} sesi</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="table-responsive">
					<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
						<thead>
							<tr>
								<th>Kursus</th>
								<th>Judul Sesi</th>
								<th>Tanggal</th>
								<th>Waktu</th>
								<th>Mode</th>
								<th>Peserta</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach($sessions as $session)
							<tr>
								<td>
									<span class="text-gray-900 fw-bold">{{ $session->course_name }}</span>
								</td>
								<td>
									<span class="text-gray-900">{{ $session->title }}</span>
								</td>
								<td>
									<span class="text-gray-900 fw-semibold">{{ $session->session_date }}</span>
								</td>
								<td>
									<span class="text-gray-900">{{ $session->session_time }}</span>
								</td>
								<td>
									<span class="badge badge-light-info">{{ ucfirst($session->mode) }}</span>
								</td>
								<td>
									<span class="text-gray-900 fw-bold">{{ $session->participant_count }}</span>
									<span class="text-gray-500 fs-7">peserta</span>
								</td>
								<td>
									<span class="badge badge-light-{{ $session->status_badge }}">{{ $session->session_status }}</span>
								</td>
								<td>
									@php
										$now = \Carbon\Carbon::now();
										// Parse waktu sesi dengan format yang benar
										$timeParts = explode(' - ', $session->session_time);
										$startTimeStr = $session->session_date . ' ' . $timeParts[0];
										$sessionTime = \Carbon\Carbon::createFromFormat('d M Y H:i', $startTimeStr);
										// Hitung waktu akhir sesi
										$durationParts = explode(' ', $session->session_duration);
										$durationMinutes = isset($durationParts[0]) ? (int)$durationParts[0] : 90;
										$sessionEnd = $sessionTime->copy()->addMinutes($durationMinutes);
									@endphp
									
									@if(isset($session->attendance_complete) && $session->attendance_complete)
										<span class="badge badge-light-success">Absensi Lengkap</span>
									@elseif($now->greaterThan($sessionEnd))
									<a href="{{ route('instructor.attendance.input', $session->id) }}" class="btn btn-sm btn-primary">Input Absensi</a>
									@else
										<span class="text-muted">Belum bisa input</span>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
<!--end::Row - All Sessions-->
@endsection

