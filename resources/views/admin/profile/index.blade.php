@extends('admin.layouts.master')

@section('title', 'Profil | Kursus Ryan Komputer')
@section('description', 'Edit profil pengguna')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Profil</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Admin</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Profil</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
@endif

<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Edit Profil</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Perbarui informasi profil Anda</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			
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

			<!-- Foto Profil -->
			<div class="row mb-10">
				<div class="col-12">
					<label class="form-label">Foto Profil</label>
					<div class="d-flex align-items-center gap-5">
						<div class="symbol symbol-100px">
							@if($profile && $profile->photo_path)
								<img id="photo-preview" src="{{ asset('storage/' . $profile->photo_path) }}" alt="Foto Profil" class="w-100" />
							@else
								<div id="photo-preview" class="symbol-label bg-light-primary">
									<i class="ki-duotone ki-profile-user fs-2x text-primary">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</div>
							@endif
						</div>
						<div class="flex-grow-1">
							<input type="file" name="photo" id="photo-input" class="form-control form-control-solid" accept="image/jpeg,image/png,image/jpg">
							<div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
							@error('photo')
								<div class="text-danger mt-1">{{ $message }}</div>
							@enderror
						</div>
					</div>
				</div>
			</div>

			<!-- Informasi Dasar -->
			<div class="row mb-10">
				<div class="col-md-6">
					<label class="form-label required">Nama Lengkap</label>
					<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
						value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required>
					@error('name')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label">Email</label>
					<input type="email" class="form-control" value="{{ $user->email }}" disabled>
					<div class="form-text">Email tidak dapat diubah</div>
				</div>
			</div>

			<div class="row mb-10">
				<div class="col-md-6">
					<label class="form-label">Nomor Telepon</label>
					<input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
						value="{{ old('phone', $profile->phone ?? '') }}" placeholder="08xxxxxxxxxx">
					@error('phone')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label">Alamat</label>
					<textarea name="address" class="form-control @error('address') is-invalid @enderror" 
						rows="3" placeholder="Masukkan alamat lengkap">{{ old('address', $profile->address ?? '') }}</textarea>
					@error('address')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<div class="d-flex justify-content-end">
				<button type="submit" class="btn btn-primary">
					<i class="ki-duotone ki-check fs-2">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
					Simpan Perubahan
				</button>
			</div>
		</form>
	</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
	const photoInput = document.getElementById('photo-input');
	const photoPreview = document.getElementById('photo-preview');
	
	if (photoInput && photoPreview) {
		photoInput.addEventListener('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				// Validasi ukuran file (2MB)
				if (file.size > 2 * 1024 * 1024) {
					alert('Ukuran file terlalu besar. Maksimal 2MB.');
					photoInput.value = '';
					return;
				}
				
				// Validasi tipe file
				const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
				if (!validTypes.includes(file.type)) {
					alert('Format file tidak didukung. Gunakan JPG atau PNG.');
					photoInput.value = '';
					return;
				}
				
				// Preview gambar
				const reader = new FileReader();
				reader.onload = function(e) {
					// Jika preview adalah div dengan icon, ganti dengan img
					if (photoPreview.tagName === 'DIV') {
						const img = document.createElement('img');
						img.src = e.target.result;
						img.alt = 'Foto Profil';
						img.className = 'w-100';
						img.id = 'photo-preview';
						photoPreview.parentNode.replaceChild(img, photoPreview);
					} else {
						photoPreview.src = e.target.result;
					}
				};
				reader.readAsDataURL(file);
			}
		});
	}
});
</script>
@endpush

