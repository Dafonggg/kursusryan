@extends('instructor.layouts.master')

@section('title', 'Quick Actions - Metronic')
@section('description', 'Halaman Quick Actions untuk Instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Quick Actions</h1>
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
				<li class="breadcrumb-item text-gray-900">Quick Actions</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="row g-5 g-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Quick Actions</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Aksi cepat untuk instruktur</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="row g-5">
					<!-- Buat Sesi Baru -->
					<div class="col-md-6 col-lg-4">
						<a href="{{ route('instructor.sessions.create') }}" class="quick-action-btn text-decoration-none d-block">
							<div class="card card-hover h-100">
								<div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
									<div class="icon mb-3">
										<i class="ki-duotone ki-calendar-add fs-1 text-primary">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</div>
									<div class="text">
										<span class="fw-bold text-gray-900">Buat Sesi Baru</span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!-- Input Absensi -->
					<div class="col-md-6 col-lg-4">
						<a href="{{ route('instructor.attendance') }}" class="quick-action-btn text-decoration-none d-block">
							<div class="card card-hover h-100">
								<div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
									<div class="icon mb-3">
										<i class="ki-duotone ki-user-check fs-1 text-success">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</div>
									<div class="text">
										<span class="fw-bold text-gray-900">Input Absensi</span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!-- Approve/Reject Reschedule -->
					<div class="col-md-6 col-lg-4">
						<a href="{{ route('instructor.reschedule') }}" class="quick-action-btn text-decoration-none d-block">
							<div class="card card-hover h-100">
								<div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
									<div class="icon mb-3">
										<i class="ki-duotone ki-calendar-edit fs-1 text-warning">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</div>
									<div class="text">
										<span class="fw-bold text-gray-900">Approve/Reject Reschedule</span>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
	.quick-action-btn .card-hover {
		transition: all 0.3s ease;
		cursor: pointer;
	}
	.quick-action-btn .card-hover:hover {
		transform: translateY(-5px);
		box-shadow: 0 10px 20px rgba(0,0,0,0.1);
	}
	.quick-action-btn {
		color: inherit;
	}
	.quick-action-btn:hover {
		text-decoration: none;
	}
</style>
@endpush

