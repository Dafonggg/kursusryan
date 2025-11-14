@extends('instructor.layouts.master')

@section('title', 'Peserta Kursus - Instructor')
@section('description', 'Daftar peserta kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Peserta Kursus: {{ $course->title }}</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.courses') }}" class="text-muted text-hover-primary">Kursus</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Peserta</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('instructor.courses') }}" class="btn btn-sm fw-bold btn-secondary">Kembali ke Kursus</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Peserta</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $enrollments->count() }} peserta terdaftar</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>Nama</th>
						<th>Email</th>
						<th>Status</th>
						<th>Tanggal Daftar</th>
						<th>Tanggal Mulai</th>
						<th>Tanggal Berakhir</th>
						<th>Sertifikat</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($enrollments as $enrollment)
						<tr>
							<td>
								<span class="text-gray-900 fw-bold">{{ $enrollment->user->name ?? 'N/A' }}</span>
							</td>
							<td>
								<span class="text-gray-600">{{ $enrollment->user->email ?? 'N/A' }}</span>
							</td>
							<td>
								@php
									$statusClass = match($enrollment->status->value) {
										'pending' => 'warning',
										'active' => 'success',
										'completed' => 'info',
										'expired' => 'danger',
										'cancelled' => 'secondary',
										default => 'secondary'
									};
									$statusLabel = ucfirst($enrollment->status->value);
								@endphp
								<span class="badge badge-light-{{ $statusClass }}">{{ $statusLabel }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $enrollment->created_at->format('d M Y') }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $enrollment->started_at ? $enrollment->started_at->format('d M Y') : '-' }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $enrollment->expires_at ? $enrollment->expires_at->format('d M Y') : '-' }}</span>
							</td>
							<td>
								@if($enrollment->certificate)
									<span class="badge badge-light-success">Sudah Ada</span>
								@else
									<span class="badge badge-light-warning">Belum Ada</span>
								@endif
							</td>
							<td>
								@if(!$enrollment->certificate && $enrollment->status->value == 'completed')
									<form action="{{ route('instructor.certificates.generate', $enrollment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membuat sertifikat untuk peserta ini?')">
										@csrf
										<button type="submit" class="btn btn-sm btn-light-success" title="Generate Sertifikat">
											<i class="ki-duotone ki-award fs-5">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</button>
									</form>
								@elseif($enrollment->certificate)
									<span class="text-muted fs-7">Sudah dibuat</span>
								@else
									<span class="text-muted fs-7">Kursus belum selesai</span>
								@endif
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="8" class="text-center text-muted">Belum ada peserta terdaftar</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

