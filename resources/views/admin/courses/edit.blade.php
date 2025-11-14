@extends('admin.layouts.master')

@section('title', 'Edit Kursus - Admin')
@section('description', 'Edit kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit Kursus</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.courses.index') }}" class="text-muted text-hover-primary">Kursus</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Edit</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.courses.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Form Edit Kursus</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Edit informasi kursus di bawah</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<form action="{{ route('admin.courses.update', $course->slug) }}" method="POST" enctype="multipart/form-data">
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

			<div class="mb-10">
				<label class="form-label required">Judul Kursus</label>
				<input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
					value="{{ old('title', $course->title) }}" placeholder="Masukkan judul kursus" required>
				@error('title')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label required">Deskripsi</label>
				<textarea name="description" class="form-control @error('description') is-invalid @enderror" 
					rows="5" placeholder="Masukkan deskripsi kursus" required>{{ old('description', $course->description) }}</textarea>
				@error('description')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="row mb-10">
				<div class="col-md-6">
					<label class="form-label required">Durasi (Bulan)</label>
					<input type="number" name="duration_months" class="form-control @error('duration_months') is-invalid @enderror" 
						value="{{ old('duration_months', $course->duration_months) }}" placeholder="3" min="1" required>
					@error('duration_months')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label required">Harga (Rp)</label>
					<input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
						value="{{ old('price', $course->price) }}" placeholder="500000" min="0" required>
					@error('price')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<div class="mb-10">
				<label class="form-label required">Mode Kursus</label>
				<select name="mode" class="form-select @error('mode') is-invalid @enderror" required>
					<option value="">Pilih Mode Kursus</option>
					<option value="online" {{ old('mode', $course->mode->value) == 'online' ? 'selected' : '' }}>Online</option>
					<option value="offline" {{ old('mode', $course->mode->value) == 'offline' ? 'selected' : '' }}>Offline</option>
					<option value="hybrid" {{ old('mode', $course->mode->value) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
				</select>
				@error('mode')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label">Gambar Kursus</label>
				@if($course->image)
					<div class="mb-3">
						<img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="img-thumbnail" style="max-width: 200px;">
						<p class="text-muted mt-2">Gambar saat ini</p>
					</div>
				@endif
				<input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
					accept="image/jpeg,image/png,image/jpg,image/gif">
				<div class="form-text">Format: JPEG, PNG, JPG, GIF (Maks: 2MB). Kosongkan jika tidak ingin mengubah gambar.</div>
				@error('image')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="d-flex justify-content-end gap-2">
				<a href="{{ route('admin.courses.index') }}" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Perbarui Kursus</button>
			</div>
		</form>
	</div>
</div>
@endsection

