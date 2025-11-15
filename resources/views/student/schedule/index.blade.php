@extends('student.layouts.master')

@section('title', 'Jadwal | Kursus Ryan Komputer')
@section('description', 'Jadwal kursus aktif')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Jadwal</h1>
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
				<li class="breadcrumb-item text-gray-900">Jadwal</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
@if($sessions->count() > 0)
	@foreach($sessionsByCourse as $courseId => $courseSessions)
		@php
			$course = $courseSessions->first()->course;
		@endphp
		<div class="card card-flush mb-5">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<div class="d-flex align-items-center">
						<div class="symbol symbol-50px me-3">
							@if($course->image)
								<img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-100" />
							@else
								<div class="symbol-label bg-light-primary">
									<i class="ki-duotone ki-book fs-2x text-primary">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
							@endif
						</div>
						<div class="d-flex flex-column">
							<span class="card-label fw-bold text-gray-900">{{ $course->title }}</span>
							<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $courseSessions->count() }} sesi mendatang</span>
						</div>
					</div>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="table-responsive">
					<table class="table table-row-dashed align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bold text-muted">
								<th class="min-w-200px">Sesi</th>
								<th class="min-w-150px">Tanggal & Waktu</th>
								<th class="min-w-100px">Mode</th>
								<th class="min-w-200px">Lokasi/Link</th>
								<th class="min-w-100px text-end">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach($courseSessions as $session)
							<tr>
								<td>
									<span class="text-gray-900 fw-bold">{{ $session->title }}</span>
								</td>
								<td>
									<div class="d-flex flex-column">
										<span class="text-gray-900 fw-semibold">{{ $session->scheduled_at->format('d M Y') }}</span>
										<span class="text-gray-500 fs-7">{{ $session->scheduled_at->format('H:i') }} - {{ $session->scheduled_at->copy()->addMinutes($session->duration_minutes ?? 120)->format('H:i') }} WIB</span>
									</div>
								</td>
								<td>
									<span class="badge badge-light-{{ strtolower($session->mode) === 'online' ? 'primary' : (strtolower($session->mode) === 'offline' ? 'info' : 'warning') }}">
										{{ ucfirst($session->mode ?? 'Online') }}
									</span>
								</td>
								<td>
									@if(strtolower($session->mode ?? '') === 'online' && $session->meeting_url)
										<a href="{{ $session->meeting_url }}" target="_blank" class="text-primary fw-semibold">
											{{ $session->meeting_platform ?? 'Zoom Meeting' }}
										</a>
									@elseif(strtolower($session->mode ?? '') === 'offline' && $session->location)
										<span class="text-gray-900 fw-semibold">{{ $session->location }}</span>
									@else
										<span class="text-gray-500">-</span>
									@endif
								</td>
								<td class="text-end">
									@if(strtolower($session->mode ?? '') === 'online' && $session->meeting_url)
										<a href="{{ $session->meeting_url }}" target="_blank" class="btn btn-sm btn-primary">
											Bergabung
										</a>
									@else
										<a href="{{ route('student.reschedule') }}" class="btn btn-sm btn-light">
											Reschedule
										</a>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	@endforeach
@else
<div class="card card-flush">
	<div class="card-body text-center py-10">
		<div class="mb-5">
			<i class="ki-duotone ki-calendar fs-3x text-gray-400">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<h3 class="text-gray-900 fw-bold mb-3">Tidak Ada Jadwal</h3>
		<p class="text-gray-500 mb-5">Belum ada jadwal kursus yang akan datang. Silakan cek kembali nanti.</p>
		<a href="{{ route('student.dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
	</div>
</div>
@endif
@endsection


