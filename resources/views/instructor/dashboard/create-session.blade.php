@extends('instructor.layouts.master')

@section('title', 'Buat Sesi Baru - Metronic')
@section('description', 'Halaman Buat Sesi Baru untuk Instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Buat Sesi Baru</h1>
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
				<li class="breadcrumb-item text-gray-900">Buat Sesi Baru</li>
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
					<span class="card-label fw-bold text-gray-900">Form Buat Sesi Baru</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Isi form di bawah untuk membuat sesi baru</span>
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

				<form action="{{ route('instructor.sessions.store') }}" method="POST">
					@csrf
					
					<div class="row mb-5">
						<div class="col-md-6">
							<label class="form-label required">Kursus</label>
							<select name="course_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Kursus" required>
								<option value="">Pilih Kursus</option>
								@foreach($courses as $course)
								<option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
									{{ $course->title }}
								</option>
								@endforeach
							</select>
							@error('course_id')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
						
						<div class="col-md-6">
							<label class="form-label required">Judul Sesi</label>
							<input type="text" name="title" class="form-control form-control-solid" placeholder="Contoh: Sesi 1 - Pengenalan Laravel" value="{{ old('title') }}" required>
							@error('title')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
					</div>

					<div class="row mb-5">
						<div class="col-md-4">
							<label class="form-label required">Mode</label>
							<select name="mode" id="mode" class="form-select form-select-solid" required>
								<option value="">Pilih Mode</option>
								<option value="online" {{ old('mode') == 'online' ? 'selected' : '' }}>Online</option>
								<option value="offline" {{ old('mode') == 'offline' ? 'selected' : '' }}>Offline</option>
								<option value="hybrid" {{ old('mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
							</select>
							@error('mode')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
						
						<div class="col-md-4">
							<label class="form-label required">Tanggal & Waktu</label>
							<input type="datetime-local" name="scheduled_at" class="form-control form-control-solid" value="{{ old('scheduled_at') }}" required>
							@error('scheduled_at')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
						
						<div class="col-md-4">
							<label class="form-label required">Durasi (menit)</label>
							<input type="number" name="duration_minutes" class="form-control form-control-solid" placeholder="90" value="{{ old('duration_minutes', 90) }}" min="30" max="480" required>
							@error('duration_minutes')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
					</div>

					<div class="row mb-5" id="online-fields" style="display: none;">
						<div class="col-md-6">
							<label class="form-label">Meeting URL</label>
							<input type="url" name="meeting_url" class="form-control form-control-solid" placeholder="https://zoom.us/j/..." value="{{ old('meeting_url') }}">
							@error('meeting_url')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
						
						<div class="col-md-6">
							<label class="form-label">Platform</label>
							<select name="meeting_platform" class="form-select form-select-solid">
								<option value="">Pilih Platform</option>
								<option value="Zoom" {{ old('meeting_platform') == 'Zoom' ? 'selected' : '' }}>Zoom</option>
								<option value="Google Meet" {{ old('meeting_platform') == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
								<option value="Microsoft Teams" {{ old('meeting_platform') == 'Microsoft Teams' ? 'selected' : '' }}>Microsoft Teams</option>
								<option value="Lainnya" {{ old('meeting_platform') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
							</select>
							@error('meeting_platform')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
					</div>

					<div class="row mb-5" id="offline-fields" style="display: none;">
						<div class="col-12">
							<label class="form-label">Lokasi</label>
							<input type="text" name="location" class="form-control form-control-solid" placeholder="Alamat lengkap atau nama ruangan" value="{{ old('location') }}">
							@error('location')
							<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
					</div>

					<div class="d-flex justify-content-end gap-3">
						<a href="{{ route('instructor.sessions') }}" class="btn btn-light">Batal</a>
						<button type="submit" class="btn btn-primary">Buat Sesi</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const modeSelect = document.getElementById('mode');
		const onlineFields = document.getElementById('online-fields');
		const offlineFields = document.getElementById('offline-fields');
		
		function toggleFields() {
			const mode = modeSelect.value;
			
			if (mode === 'online') {
				onlineFields.style.display = 'block';
				offlineFields.style.display = 'none';
			} else if (mode === 'offline') {
				onlineFields.style.display = 'none';
				offlineFields.style.display = 'block';
			} else if (mode === 'hybrid') {
				onlineFields.style.display = 'block';
				offlineFields.style.display = 'block';
			} else {
				onlineFields.style.display = 'none';
				offlineFields.style.display = 'none';
			}
		}
		
		modeSelect.addEventListener('change', toggleFields);
		toggleFields(); // Initial call
	});
</script>
@endpush

