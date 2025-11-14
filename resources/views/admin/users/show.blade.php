@extends('admin.layouts.master')

@section('title', 'Detail Pengguna - Admin')
@section('description', 'Detail pengguna dan pendaftarannya')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Detail Pengguna</h1>
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
				<li class="breadcrumb-item text-gray-900">Detail</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.users.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<!-- User Info -->
	<div class="col-12 col-lg-4">
		<div class="card card-flush">
			<div class="card-header pt-5 text-center">
				@php
					$userAvatar = ($user->profile && $user->profile->photo_path)
						? asset('storage/' . $user->profile->photo_path)
						: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
				@endphp
				<div class="symbol symbol-100px mb-5">
					<img src="{{ $userAvatar }}" alt="{{ $user->name }}" class="rounded-circle" />
				</div>
				<h3 class="card-title fw-bold text-gray-900">{{ $user->name }}</h3>
				<span class="text-gray-500 fs-6">{{ $user->email }}</span>
			</div>
			<div class="card-body pt-0">
				<div class="mb-5">
					<label class="form-label fw-bold">Role</label>
					<div>
						@php
							$roleClass = match($user->role) {
								'admin' => 'badge-danger',
								'instructor' => 'badge-primary',
								'student', 'user' => 'badge-success',
								default => 'badge-secondary',
							};
						@endphp
						<span class="badge {{ $roleClass }} fs-6">{{ ucfirst($user->role) }}</span>
					</div>
				</div>
				<div class="mb-5">
					<label class="form-label fw-bold">Status</label>
					<div>
						@php
							$statusClass = match($user->status ?? 'active') {
								'active' => 'badge-success',
								'suspended' => 'badge-danger',
								default => 'badge-secondary',
							};
						@endphp
						<span class="badge {{ $statusClass }} fs-6">{{ ucfirst($user->status ?? 'active') }}</span>
					</div>
				</div>
				<div class="mb-5">
					<label class="form-label fw-bold">Bergabung</label>
					<div class="text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</div>
				</div>
				@if($user->profile)
					<div class="mb-5">
						<label class="form-label fw-bold">Telepon</label>
						<div class="text-gray-900">{{ $user->profile->phone ?? '-' }}</div>
					</div>
					<div class="mb-5">
						<label class="form-label fw-bold">Alamat</label>
						<div class="text-gray-900">{{ $user->profile->address ?? '-' }}</div>
					</div>
				@endif
			</div>
		</div>
	</div>
	<!-- Enrollments -->
	<div class="col-12 col-lg-8">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Pendaftaran Kursus</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $user->enrollments->count() }} pendaftaran</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="table-responsive">
					<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bold text-muted">
								<th>Kursus</th>
								<th>Status</th>
								<th>Mulai</th>
								<th>Berakhir</th>
							</tr>
						</thead>
						<tbody>
							@forelse($user->enrollments as $enrollment)
								@php
									$statusClass = match($enrollment->status->value) {
										'pending' => 'badge-light-warning',
										'active' => 'badge-light-success',
										'completed' => 'badge-light-info',
										'expired' => 'badge-light-danger',
										'cancelled' => 'badge-light-secondary',
										default => 'badge-light-secondary',
									};
								@endphp
								<tr>
									<td>
										<span class="text-gray-900 fw-bold">{{ $enrollment->course->title ?? '-' }}</span>
									</td>
									<td>
										<span class="badge {{ $statusClass }}">{{ ucfirst($enrollment->status->value) }}</span>
									</td>
									<td>
										<span class="text-gray-500 fs-7">{{ $enrollment->started_at ? $enrollment->started_at->format('d M Y') : '-' }}</span>
									</td>
									<td>
										<span class="text-gray-500 fs-7">{{ $enrollment->expires_at ? $enrollment->expires_at->format('d M Y') : '-' }}</span>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="4" class="text-center text-muted">Tidak ada pendaftaran</td>
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

