@extends('admin.layouts.master')

@section('title', 'Pengaturan Sistem - Admin')
@section('description', 'Konfigurasi pengaturan sistem')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Pengaturan Sistem</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Pengaturan</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Pengaturan Sistem</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Konfigurasi pengaturan aplikasi</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			
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

			<!-- Course Settings -->
			<div class="separator separator-dashed my-10"></div>
			<div class="mb-10">
				<h3 class="text-gray-900 fw-bold mb-5">Pengaturan Kursus</h3>
				
				<div class="mb-10">
					<label class="form-label required">Batas Waktu Kursus (Bulan)</label>
					<input type="number" name="course_expiry_months" class="form-control @error('course_expiry_months') is-invalid @enderror" 
						value="{{ old('course_expiry_months', $settings['course_expiry_months']) }}" 
						placeholder="3" min="1" max="12" required>
					<div class="form-text">Masa berlaku default untuk kursus (1-12 bulan)</div>
					@error('course_expiry_months')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Site Settings -->
			<div class="separator separator-dashed my-10"></div>
			<div class="mb-10">
				<h3 class="text-gray-900 fw-bold mb-5">Pengaturan Website</h3>
				
				<div class="mb-10">
					<label class="form-label required">Nama Website</label>
					<input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" 
						value="{{ old('site_name', $settings['site_name']) }}" 
						placeholder="Nama Website" required>
					@error('site_name')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-10">
					<label class="form-label">Deskripsi Website</label>
					<textarea name="site_description" class="form-control @error('site_description') is-invalid @enderror" 
						rows="3" placeholder="Deskripsi website">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
					@error('site_description')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-10">
					<label class="form-label">Logo Website</label>
					@if($settings['logo_path'])
						<div class="mb-3">
							<img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Logo" class="img-thumbnail" style="max-width: 200px;">
							<p class="text-muted mt-2">Logo saat ini</p>
						</div>
					@endif
					<input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
						accept="image/jpeg,image/png,image/jpg,image/gif,image/svg">
					<div class="form-text">Format: JPEG, PNG, JPG, GIF, SVG (Maks: 2MB). Kosongkan jika tidak ingin mengubah logo.</div>
					@error('logo')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- Email Settings -->
			<div class="separator separator-dashed my-10"></div>
			<div class="mb-10">
				<h3 class="text-gray-900 fw-bold mb-5">Pengaturan Email</h3>
				
				<div class="mb-10">
					<label class="form-check form-switch form-check-custom form-check-solid">
						<input class="form-check-input" type="checkbox" name="email_notifications_enabled" value="1" 
							{{ old('email_notifications_enabled', $settings['email_notifications_enabled']) ? 'checked' : '' }}>
						<span class="form-check-label fw-semibold text-gray-700">
							Aktifkan Notifikasi Email
						</span>
					</label>
					<div class="form-text">Aktifkan untuk mengirim notifikasi email ke pengguna</div>
				</div>

				<div class="mb-10">
					<label class="form-label">Email Support</label>
					<input type="email" name="email_support" class="form-control @error('email_support') is-invalid @enderror" 
						value="{{ old('email_support', $settings['email_support'] ?? '') }}" 
						placeholder="support@example.com">
					<div class="form-text">Email untuk dukungan dan pertanyaan pengguna</div>
					@error('email_support')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<!-- System Settings -->
			<div class="separator separator-dashed my-10"></div>
			<div class="mb-10">
				<h3 class="text-gray-900 fw-bold mb-5">Pengaturan Sistem</h3>
				
				<div class="mb-10">
					<label class="form-check form-switch form-check-custom form-check-solid">
						<input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" 
							{{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
						<span class="form-check-label fw-semibold text-gray-700">
							Mode Maintenance
						</span>
					</label>
					<div class="form-text">Aktifkan untuk menempatkan website dalam mode maintenance</div>
				</div>
			</div>

			<div class="d-flex justify-content-end gap-2 mt-10">
				<button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
			</div>
		</form>
	</div>
</div>
@endsection

