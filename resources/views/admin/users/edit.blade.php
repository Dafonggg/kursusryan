@extends('admin.layouts.master')

@section('title', 'Edit Pengguna - Admin')
@section('description', 'Edit pengguna')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit Pengguna</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.users.index') }}" class="text-muted text-hover-primary">Pengguna</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Edit</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.users.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Form Edit Pengguna</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Edit informasi pengguna di bawah</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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
					<label class="form-label required">Email</label>
					<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
						value="{{ old('email', $user->email) }}" placeholder="user@example.com" required>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<div class="row mb-10">
				<div class="col-md-6">
					<label class="form-label">Password</label>
					<input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
						placeholder="Kosongkan jika tidak ingin mengubah password">
					<div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
					@error('password')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label">Konfirmasi Password</label>
					<input type="password" name="password_confirmation" class="form-control" 
						placeholder="Ulangi password">
				</div>
			</div>

			<div class="row mb-10">
				<div class="col-md-6">
					<label class="form-label required">Role</label>
					<select name="role" class="form-select @error('role') is-invalid @enderror" required>
						<option value="">Pilih Role</option>
						<option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
						<option value="instructor" {{ old('role', $user->role) == 'instructor' ? 'selected' : '' }}>Instruktur</option>
						<option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Siswa</option>
						<option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
					</select>
					@error('role')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label required">Status</label>
					<select name="status" class="form-select @error('status') is-invalid @enderror" required>
						<option value="active" {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
						<option value="suspended" {{ old('status', $user->status ?? 'active') == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
					</select>
					@error('status')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<div class="row mb-10">
				<div class="col-md-6">
					<label class="form-label">Nomor Telepon</label>
					<input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
						value="{{ old('phone', $user->profile->phone ?? '') }}" placeholder="081234567890">
					@error('phone')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label">Foto Profil</label>
					@if($user->profile && $user->profile->photo_path)
						<div class="mb-3">
							<img src="{{ asset('storage/' . $user->profile->photo_path) }}" alt="{{ $user->name }}" class="img-thumbnail" style="max-width: 100px;">
							<p class="text-muted mt-2">Foto saat ini</p>
						</div>
					@endif
					<input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
						accept="image/jpeg,image/png,image/jpg,image/gif">
					<div class="form-text">Format: JPEG, PNG, JPG, GIF (Maks: 2MB). Kosongkan jika tidak ingin mengubah foto.</div>
					@error('photo')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<div class="mb-10">
				<label class="form-label">Alamat</label>
				<textarea name="address" class="form-control @error('address') is-invalid @enderror" 
					rows="3" placeholder="Masukkan alamat lengkap">{{ old('address', $user->profile->address ?? '') }}</textarea>
				@error('address')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="d-flex justify-content-end gap-2">
				<a href="{{ route('admin.users.index') }}" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
			</div>
		</form>
	</div>
</div>
@endsection

