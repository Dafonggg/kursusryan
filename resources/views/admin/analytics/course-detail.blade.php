@extends('admin.layouts.master')

@section('title', 'Detail Analisis Kursus - Admin')
@section('description', 'Detail analisis peserta per kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Detail Analisis: {{ $course->title }}</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.analytics.index') }}" class="text-muted text-hover-primary">Analisis</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Detail</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.analytics.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<!-- Statistics Summary -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Ringkasan Statistik</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Kursus: {{ $course->title }}</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="row g-5">
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Total Peserta</span>
							<span class="text-gray-900 fw-bold fs-2">{{ number_format($stats['total'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Aktif</span>
							<span class="text-success fw-bold fs-2">{{ number_format($stats['active'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Selesai</span>
							<span class="text-info fw-bold fs-2">{{ number_format($stats['completed'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Dengan Sertifikat</span>
							<span class="text-warning fw-bold fs-2">{{ number_format($stats['with_certificate'], 0, ',', '.') }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Enrollments List -->
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
						<th>Mulai</th>
						<th>Berakhir</th>
						<th>Sertifikat</th>
						<th>Pembayaran</th>
					</tr>
				</thead>
				<tbody>
					@forelse($enrollments as $enrollment)
						@php
							$statusClass = match($enrollment->status->value) {
								'pending' => 'badge-light-warning',
								'active' => 'badge-light-success',
								'completed' => 'badge-light-info',
								'expired' => 'badge-light-danger',
								'cancelled' => 'badge-light-secondary',
								default => 'badge-light-secondary',
							};
						@endphp
						<tr>
							<td>
								<span class="text-gray-900 fw-bold">{{ $enrollment->user->name }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $enrollment->user->email }}</span>
							</td>
							<td>
								<span class="badge {{ $statusClass }}">{{ ucfirst($enrollment->status->value) }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $enrollment->started_at ? $enrollment->started_at->format('d M Y') : '-' }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $enrollment->expires_at ? $enrollment->expires_at->format('d M Y') : '-' }}</span>
							</td>
							<td>
								@if($enrollment->certificate)
									<span class="badge badge-light-success">Ya</span>
								@else
									<span class="badge badge-light-secondary">Tidak</span>
								@endif
							</td>
							<td>
								@php
									$paymentStatus = $enrollment->payments->first()->status->value ?? 'pending';
									$paymentClass = match($paymentStatus) {
										'paid' => 'badge-light-success',
										'pending' => 'badge-light-warning',
										'failed' => 'badge-light-danger',
										default => 'badge-light-secondary',
									};
								@endphp
								<span class="badge {{ $paymentClass }}">{{ ucfirst($paymentStatus) }}</span>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center text-muted">Belum ada peserta</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

