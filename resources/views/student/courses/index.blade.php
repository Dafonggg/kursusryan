@extends('student.layouts.master')

@section('title', 'Kursus Saya | Kursus Ryan Komputer')
@section('description', 'Daftar kursus yang diikuti')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Kursus Saya</h1>
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
				<li class="breadcrumb-item text-gray-900">Kursus Saya</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<!-- Kursus Aktif -->
@if($activeEnrollments->count() > 0)
<div class="card card-flush mb-5">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Kursus Aktif</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $activeEnrollments->count() }} kursus aktif</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th class="min-w-200px">Kursus</th>
						<th class="min-w-100px">Status</th>
						<th class="min-w-150px">Tanggal Mulai</th>
						<th class="min-w-150px">Berlaku Hingga</th>
						<th class="min-w-100px text-end">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($activeEnrollments as $enrollment)
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<div class="symbol symbol-50px me-3">
									@if($enrollment->course->image)
										<img src="{{ asset('storage/' . $enrollment->course->image) }}" alt="{{ $enrollment->course->title }}" class="w-100" />
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
									<span class="text-gray-900 fw-bold">{{ $enrollment->course->title }}</span>
									<span class="text-gray-500 fs-7">{{ ucfirst($enrollment->modality ?? 'Online') }}</span>
								</div>
							</div>
						</td>
						<td>
							<span class="badge badge-success">Aktif</span>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $enrollment->started_at ? $enrollment->started_at->format('d M Y') : '-' }}</span>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $enrollment->expires_at ? $enrollment->expires_at->format('d M Y') : 'Tidak Terbatas' }}</span>
						</td>
						<td class="text-end">
							<a href="{{ route('student.materials.course', $enrollment->course->slug) }}" class="btn btn-sm btn-primary">
								Lihat Materi
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endif

<!-- Kursus Selesai -->
@if($completedEnrollments->count() > 0)
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Kursus Selesai</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $completedEnrollments->count() }} kursus selesai</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th class="min-w-200px">Kursus</th>
						<th class="min-w-100px">Status</th>
						<th class="min-w-150px">Tanggal Selesai</th>
						<th class="min-w-100px text-end">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($completedEnrollments as $enrollment)
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<div class="symbol symbol-50px me-3">
									@if($enrollment->course->image)
										<img src="{{ asset('storage/' . $enrollment->course->image) }}" alt="{{ $enrollment->course->title }}" class="w-100" />
									@else
										<div class="symbol-label bg-light-secondary">
											<i class="ki-duotone ki-book fs-2x text-secondary">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</div>
									@endif
								</div>
								<div class="d-flex flex-column">
									<span class="text-gray-900 fw-bold">{{ $enrollment->course->title }}</span>
									<span class="text-gray-500 fs-7">{{ ucfirst($enrollment->modality ?? 'Online') }}</span>
								</div>
							</div>
						</td>
						<td>
							<span class="badge badge-secondary">Selesai</span>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $enrollment->expires_at ? $enrollment->expires_at->format('d M Y') : '-' }}</span>
						</td>
						<td class="text-end">
							@if($enrollment->certificate)
								<a href="{{ route('student.certificate') }}" class="btn btn-sm btn-primary">
									Lihat Sertifikat
								</a>
							@else
								<span class="text-gray-500 fs-7">-</span>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endif

@if($activeEnrollments->count() == 0 && $completedEnrollments->count() == 0)
<div class="card card-flush">
	<div class="card-body text-center py-10">
		<div class="mb-5">
			<i class="ki-duotone ki-information-5 fs-3x text-gray-400">
				<span class="path1"></span>
				<span class="path2"></span>
				<span class="path3"></span>
			</i>
		</div>
		<h3 class="text-gray-900 fw-bold mb-3">Belum Ada Kursus</h3>
		<p class="text-gray-500 mb-5">Anda belum terdaftar di kursus manapun. Silakan daftar kursus terlebih dahulu.</p>
		<a href="{{ route('daftar-kursus') }}" class="btn btn-primary">Lihat Kursus Tersedia</a>
	</div>
</div>
@endif
@endsection


