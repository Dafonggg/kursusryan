@extends('admin.layouts.master')

@section('title', 'Analisis Peserta - Admin')
@section('description', 'Statistik dan analisis peserta')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Analisis Peserta</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Analisis Peserta</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<!-- Completion Statistics -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Statistik Kelulusan</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Ringkasan tingkat kelulusan peserta</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="row g-5">
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Total Pendaftaran</span>
							<span class="text-gray-900 fw-bold fs-2">{{ number_format($completionStats['total'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Aktif</span>
							<span class="text-success fw-bold fs-2">{{ number_format($completionStats['active'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Selesai</span>
							<span class="text-info fw-bold fs-2">{{ number_format($completionStats['completed'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Tingkat Kelulusan</span>
							<span class="text-primary fw-bold fs-2">{{ number_format($completionStats['completion_rate'], 2) }}%</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Certificate Statistics -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Statistik Sertifikat</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Jumlah sertifikat yang diterbitkan</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="row g-5">
					<div class="col-md-4">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Total Sertifikat</span>
							<span class="text-gray-900 fw-bold fs-2">{{ number_format($certificateStats['total'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Bulan Ini</span>
							<span class="text-primary fw-bold fs-2">{{ number_format($certificateStats['this_month'], 0, ',', '.') }}</span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fs-7">Tahun Ini</span>
							<span class="text-info fw-bold fs-2">{{ number_format($certificateStats['this_year'], 0, ',', '.') }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Course Statistics -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Statistik Per Kursus</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Detail peserta per kursus</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="table-responsive">
					<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bold text-muted">
								<th>Kursus</th>
								<th>Total Peserta</th>
								<th>Aktif</th>
								<th>Selesai</th>
								<th>Tingkat Kelulusan</th>
								<th>Sertifikat</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@forelse($courseStats as $stat)
								<tr>
									<td>
										<span class="text-gray-900 fw-bold">{{ $stat['course']->title }}</span>
									</td>
									<td>
										<span class="badge badge-light-info">{{ number_format($stat['total_enrollments'], 0, ',', '.') }}</span>
									</td>
									<td>
										<span class="badge badge-light-success">{{ number_format($stat['active_enrollments'], 0, ',', '.') }}</span>
									</td>
									<td>
										<span class="badge badge-light-primary">{{ number_format($stat['completed_enrollments'], 0, ',', '.') }}</span>
									</td>
									<td>
										@php
											$completionRate = $stat['total_enrollments'] > 0 
												? round(($stat['completed_enrollments'] / $stat['total_enrollments']) * 100, 2) 
												: 0;
										@endphp
										<span class="text-gray-900 fw-bold">{{ number_format($completionRate, 2) }}%</span>
									</td>
									<td>
										<span class="badge badge-light-warning">{{ number_format($stat['certificates_issued'], 0, ',', '.') }}</span>
									</td>
									<td>
										<a href="{{ route('admin.analytics.course-detail', $stat['course']->slug) }}" class="btn btn-sm btn-primary">
											Detail
										</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="7" class="text-center text-muted">Belum ada data kursus</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Students Per Course Chart -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Distribusi Peserta Per Kursus</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Jumlah peserta aktif dan selesai per kursus</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="table-responsive">
					<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bold text-muted">
								<th>Kursus</th>
								<th>Total</th>
								<th>Aktif</th>
								<th>Selesai</th>
								<th>Tingkat Kelulusan</th>
							</tr>
						</thead>
						<tbody>
							@forelse($studentPerCourse as $course)
								<tr>
									<td>
										<span class="text-gray-900 fw-bold">{{ $course['course_name'] }}</span>
									</td>
									<td>
										<span class="text-gray-900 fw-bold">{{ number_format($course['total'], 0, ',', '.') }}</span>
									</td>
									<td>
										<span class="badge badge-light-success">{{ number_format($course['active'], 0, ',', '.') }}</span>
									</td>
									<td>
										<span class="badge badge-light-primary">{{ number_format($course['completed'], 0, ',', '.') }}</span>
									</td>
									<td>
										<span class="text-gray-900 fw-bold">{{ number_format($course['completion_rate'], 2) }}%</span>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="5" class="text-center text-muted">Belum ada data</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

