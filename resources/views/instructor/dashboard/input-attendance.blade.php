@extends('instructor.layouts.master')

@section('title', 'Input Absensi - Metronic')
@section('description', 'Halaman Input Absensi untuk Instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Input Absensi</h1>
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
				<li class="breadcrumb-item text-gray-900">Input Absensi</li>
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
					<span class="card-label fw-bold text-gray-900">Input Absensi - {{ $session->title }}</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $session->course->title ?? 'N/A' }}</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				@if(session('success'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					{{ session('success') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
				@endif

				@if($errors->any())
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<ul class="mb-0">
						@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
				@endif

				<div class="mb-5">
					<p class="text-gray-600">
						<strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($session->scheduled_at)->format('d M Y') }}<br>
						<strong>Waktu:</strong> {{ \Carbon\Carbon::parse($session->scheduled_at)->format('H:i') }} - 
						{{ \Carbon\Carbon::parse($session->scheduled_at)->addMinutes($session->duration_minutes ?? 90)->format('H:i') }}
					</p>
				</div>

				<form action="{{ route('instructor.attendance.store', $session->id) }}" method="POST">
					@csrf
					
					<div class="table-responsive">
						<table class="table table-row-dashed align-middle gs-0 gy-4">
							<thead>
								<tr>
									<th>Nama Peserta</th>
									<th>Email</th>
									<th>Status</th>
									<th>Catatan</th>
								</tr>
							</thead>
							<tbody>
								@foreach($students as $index => $student)
								<tr>
									<td>
										<span class="text-gray-900 fw-bold">{{ $student->name }}</span>
									</td>
									<td>
										<span class="text-gray-600">{{ $student->email }}</span>
									</td>
									<td>
										<input type="hidden" name="attendances[{{ $index }}][user_id]" value="{{ $student->user_id }}">
										<select name="attendances[{{ $index }}][status]" class="form-select form-select-sm form-select-solid" required>
											<option value="present" {{ $student->status == 'present' ? 'selected' : '' }}>Hadir</option>
											<option value="absent" {{ $student->status == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
											<option value="excused" {{ $student->status == 'excused' ? 'selected' : '' }}>Izin</option>
										</select>
									</td>
									<td>
										<input type="text" name="attendances[{{ $index }}][notes]" class="form-control form-control-sm form-control-solid" placeholder="Catatan (opsional)" value="{{ $student->notes ?? '' }}" maxlength="500">
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					@if($students->isEmpty())
					<div class="alert alert-info">
						<p class="mb-0">Tidak ada peserta terdaftar untuk sesi ini.</p>
					</div>
					@endif

					<div class="d-flex justify-content-end gap-3 mt-5">
						<a href="{{ route('instructor.attendance') }}" class="btn btn-light">Batal</a>
						@if(!$students->isEmpty())
						<button type="submit" class="btn btn-primary">Simpan Absensi</button>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

