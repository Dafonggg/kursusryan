@extends('student.layouts.master')

@section('title', 'Detail Sesi | Kursus Ryan Komputer')
@section('description', 'Detail sesi kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Detail Sesi</h1>
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
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.schedule') }}" class="text-muted text-hover-primary">Jadwal</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Detail Sesi</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('student.schedule') }}" class="btn btn-sm fw-bold btn-light">Kembali ke Jadwal</a>
		</div>
	</div>
</div>
@endsection

@section('content')
@php
	$canJoin = now() >= $session->scheduled_at;
@endphp
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<div class="card-title">
					<div class="d-flex align-items-center">
						<div class="symbol symbol-60px me-4">
							@if($session->course->image)
								<img src="{{ asset('storage/' . $session->course->image) }}" alt="{{ $session->course->title }}" class="w-100" />
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
							<span class="card-label fw-bold text-gray-900 fs-3">{{ $session->title }}</span>
							<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $session->course->title }}</span>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body pt-0">
				<div class="row g-5 g-xl-8">
					<!-- Informasi Sesi -->
					<div class="col-12 col-lg-8">
						<div class="mb-7">
							<h3 class="text-gray-900 fw-bold mb-4">Informasi Sesi</h3>
							<div class="d-flex flex-column gap-4">
								<!-- Tanggal & Waktu -->
								<div class="d-flex align-items-start">
									<div class="symbol symbol-40px me-4">
										<div class="symbol-label bg-light-primary">
											<i class="ki-duotone ki-calendar fs-2 text-primary">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</div>
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-500 fs-7 mb-1">Tanggal & Waktu</span>
										<span class="text-gray-900 fw-bold fs-6">{{ $startTime->format('d M Y') }}</span>
										<span class="text-gray-500 fs-7">{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }} WIB</span>
									</div>
								</div>

								<!-- Mode & Lokasi -->
								<div class="d-flex align-items-start">
									<div class="symbol symbol-40px me-4">
										<div class="symbol-label bg-light-info">
											<i class="ki-duotone ki-geolocation fs-2 text-info">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</div>
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-500 fs-7 mb-1">Mode & Lokasi</span>
										<span class="badge badge-light-{{ strtolower($session->mode) === 'online' ? 'primary' : (strtolower($session->mode) === 'offline' ? 'info' : 'warning') }} mb-2">
											{{ ucfirst($session->mode ?? 'Online') }}
										</span>
										@if(strtolower($session->mode ?? '') === 'online' && $session->meeting_url)
											@if($canJoin)
												<a href="{{ $session->meeting_url }}" target="_blank" class="text-primary fw-semibold">
													{{ $session->meeting_platform ?? 'Zoom Meeting' }}
												</a>
											@else
												<span class="text-gray-500 fw-semibold">{{ $session->meeting_platform ?? 'Zoom Meeting' }}</span>
												<span class="badge badge-light-warning ms-2">Belum waktunya</span>
											@endif
										@elseif(strtolower($session->mode ?? '') === 'offline' && $session->location)
											<span class="text-gray-900 fw-semibold">{{ $session->location }}</span>
										@else
											<span class="text-gray-500">Informasi lokasi akan diupdate</span>
										@endif
									</div>
								</div>

								<!-- Durasi -->
								<div class="d-flex align-items-start">
									<div class="symbol symbol-40px me-4">
										<div class="symbol-label bg-light-warning">
											<i class="ki-duotone ki-clock fs-2 text-warning">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</div>
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-500 fs-7 mb-1">Durasi</span>
										<span class="text-gray-900 fw-bold fs-6">{{ $session->duration_minutes ?? 120 }} menit</span>
									</div>
								</div>

								<!-- Instruktur -->
								@if($session->instructor)
								<div class="d-flex align-items-start">
									<div class="symbol symbol-40px me-4">
										<div class="symbol-label bg-light-success">
											<i class="ki-duotone ki-profile-user fs-2 text-success">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
												<span class="path4"></span>
											</i>
										</div>
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-500 fs-7 mb-1">Instruktur</span>
										<span class="text-gray-900 fw-bold fs-6">{{ $session->instructor->name }}</span>
									</div>
								</div>
								@endif
							</div>
						</div>
					</div>

					<!-- Aksi -->
					<div class="col-12 col-lg-4">
						<div class="card card-flush bg-light">
							<div class="card-body">
								<h4 class="text-gray-900 fw-bold mb-4">Aksi</h4>
								<div class="d-flex flex-column gap-3">
									@if(strtolower($session->mode ?? '') === 'online' && $session->meeting_url)
										@if($canJoin)
											<a href="{{ $session->meeting_url }}" target="_blank" class="btn btn-primary w-100">
												<i class="ki-duotone ki-link fs-2 me-2">
													<span class="path1"></span>
													<span class="path2"></span>
												</i>
												Bergabung ke Sesi
											</a>
										@else
											<button type="button" class="btn btn-light w-100" disabled>
												<i class="ki-duotone ki-link fs-2 me-2">
													<span class="path1"></span>
													<span class="path2"></span>
												</i>
												Bergabung ke Sesi
											</button>
											<div class="alert alert-warning d-flex align-items-center p-3 mb-0">
												<i class="ki-duotone ki-information fs-2x text-warning me-3">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
												</i>
												<div class="d-flex flex-column">
													<span class="fw-bold">Sesi Belum Dimulai</span>
													<span class="fs-7">Anda dapat bergabung ketika waktu sesi telah tiba.</span>
												</div>
											</div>
										@endif
									@endif
									
									@if(!$pendingReschedule)
										<a href="{{ route('student.reschedule') }}?session={{ $session->id }}" class="btn btn-light w-100">
											<i class="ki-duotone ki-calendar-tick fs-2 me-2">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
											Ajukan Reschedule
										</a>
									@else
										<div class="alert alert-warning d-flex align-items-center p-3">
											<i class="ki-duotone ki-information fs-2x text-warning me-3">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
											</i>
											<div class="d-flex flex-column">
												<span class="fw-bold">Reschedule Pending</span>
												<span class="fs-7">Anda sudah mengajukan reschedule untuk sesi ini. Menunggu persetujuan.</span>
											</div>
										</div>
									@endif

									<a href="{{ route('student.schedule') }}" class="btn btn-light w-100">
										<i class="ki-duotone ki-arrow-left fs-2 me-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										Kembali ke Jadwal
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

