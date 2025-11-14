@extends('admin.layouts.master')

@section('title', 'Pesan Baru - Admin')
@section('description', 'Mulai percakapan baru')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Pesan Baru</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.chat.index') }}" class="text-muted text-hover-primary">Chat</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Pesan Baru</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.chat.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Mulai Percakapan Baru</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Pilih user dan kirim pesan pertama</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<form action="{{ route('admin.chat.store') }}" method="POST">
			@csrf
			
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
				<label class="form-label required">Pilih User</label>
				<select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
					<option value="">Pilih User</option>
					@foreach($users as $user)
						@php
							$avatar = ($user->profile && $user->profile->photo_path)
								? asset('storage/' . $user->profile->photo_path)
								: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
						@endphp
						<option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
							{{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
						</option>
					@endforeach
				</select>
				@error('user_id')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label required">Pesan</label>
				<textarea name="message" class="form-control @error('message') is-invalid @enderror" 
					rows="5" placeholder="Tulis pesan Anda di sini..." required>{{ old('message') }}</textarea>
				<div class="form-text">Maksimal 5000 karakter</div>
				@error('message')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="d-flex justify-content-end gap-2">
				<a href="{{ route('admin.chat.index') }}" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Kirim Pesan</button>
			</div>
		</form>
	</div>
</div>
@endsection

