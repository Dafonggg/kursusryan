@extends('instructor.layouts.master')

@section('title', 'Sertifikat - Instructor')
@section('description', 'Daftar sertifikat peserta')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Sertifikat</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Sertifikat</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Sertifikat</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $certificates->count() }} sertifikat diterbitkan</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		@if(session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{{ session('error') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>Peserta</th>
						<th>Kursus</th>
						<th>Nomor Sertifikat</th>
						<th>Tanggal Terbit</th>
						<th>File</th>
					</tr>
				</thead>
				<tbody>
					@forelse($certificates as $certificate)
						<tr>
							<td>
								<span class="text-gray-900 fw-bold">{{ $certificate->enrollment->user->name ?? 'N/A' }}</span>
								<div class="text-gray-500 fs-7">{{ $certificate->enrollment->user->email ?? 'N/A' }}</div>
							</td>
							<td>
								<span class="text-gray-900">{{ $certificate->enrollment->course->title ?? 'N/A' }}</span>
							</td>
							<td>
								<span class="text-gray-900 fw-bold">{{ $certificate->certificate_no }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $certificate->issued_at->format('d M Y') }}</span>
							</td>
							<td>
								@if($certificate->file_path)
									<a href="{{ asset('storage/' . $certificate->file_path) }}" target="_blank" class="btn btn-sm btn-light-primary">
										<i class="ki-duotone ki-file fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										Unduh
									</a>
								@else
									<span class="text-muted">-</span>
								@endif
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center text-muted">Belum ada sertifikat diterbitkan</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

