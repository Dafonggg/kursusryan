@extends('admin.layouts.master')

@section('title', 'Buat Sesi - Admin')
@section('description', 'Buat sesi kursus baru')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Buat Sesi</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.sessions.index') }}" class="text-muted text-hover-primary">Sesi</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Buat</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.sessions.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Form Buat Sesi</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Isi form di bawah untuk membuat sesi kursus baru</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<form action="{{ route('admin.sessions.store') }}" method="POST">
			@csrf
			
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

			<div class="mb-10">
				<label class="form-label required">Kursus</label>
				<select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
					<option value="">Pilih Kursus</option>
					@foreach($courses as $course)
						<option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
							{{ $course->title }}
						</option>
					@endforeach
				</select>
				@error('course_id')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label required">Instruktur</label>
				<select name="instructor_id" class="form-select @error('instructor_id') is-invalid @enderror" required>
					<option value="">Pilih Instruktur</option>
					@foreach($instructors as $instructor)
						<option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
							{{ $instructor->name }}
						</option>
					@endforeach
				</select>
				@error('instructor_id')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label required">Judul Sesi</label>
				<input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
					value="{{ old('title') }}" placeholder="Masukkan judul sesi" required>
				@error('title')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="row mb-10">
				<div class="col-md-6">
					<label class="form-label required">Mode Sesi</label>
					<select name="mode" id="mode" class="form-select @error('mode') is-invalid @enderror" required>
						<option value="">Pilih Mode</option>
						<option value="online" {{ old('mode') == 'online' ? 'selected' : '' }}>Online</option>
						<option value="offline" {{ old('mode') == 'offline' ? 'selected' : '' }}>Offline</option>
						<option value="hybrid" {{ old('mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
					</select>
					@error('mode')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label required">Durasi (Menit)</label>
					<input type="number" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" 
						value="{{ old('duration_minutes', 120) }}" min="15" max="480" required>
					@error('duration_minutes')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<div class="mb-10">
				<label class="form-label required">Jadwal</label>
				<input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" 
					value="{{ old('scheduled_at') }}" required>
				@error('scheduled_at')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10" id="online-fields" style="display: none;">
				<div class="row">
					<div class="col-md-6">
						<label class="form-label required">Meeting URL</label>
						<input type="url" name="meeting_url" class="form-control @error('meeting_url') is-invalid @enderror" 
							value="{{ old('meeting_url') }}" placeholder="https://meet.google.com/...">
						@error('meeting_url')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="col-md-6">
						<label class="form-label required">Platform</label>
						<input type="text" name="meeting_platform" class="form-control @error('meeting_platform') is-invalid @enderror" 
							value="{{ old('meeting_platform') }}" placeholder="Google Meet, Zoom, dll">
						@error('meeting_platform')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="mb-10" id="offline-fields" style="display: none;">
				<label class="form-label required">Lokasi</label>
				<input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
					value="{{ old('location') }}" placeholder="Alamat lengkap lokasi">
				@error('location')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="d-flex justify-content-end gap-2">
				<a href="{{ route('admin.sessions.index') }}" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan Sesi</button>
			</div>
		</form>
	</div>
</div>

@push('scripts')
<script>
	document.getElementById('mode').addEventListener('change', function() {
		const mode = this.value;
		const onlineFields = document.getElementById('online-fields');
		const offlineFields = document.getElementById('offline-fields');
		
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
	});

	// Trigger on page load if mode already selected
	if (document.getElementById('mode').value) {
		document.getElementById('mode').dispatchEvent(new Event('change'));
	}
</script>
@endpush
@endsection

