@extends('admin.layouts.master')

@section('title', 'Daftar Pengguna - Admin')
@section('description', 'Daftar semua pengguna sistem')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Daftar Pengguna</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Pengguna</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.users.create') }}" class="btn btn-sm fw-bold btn-primary">Tambah Pengguna</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Pengguna</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Semua pengguna yang terdaftar dalam sistem</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>Nama</th>
						<th>Email</th>
						<th>Role</th>
						<th>Status</th>
						<th>Bergabung</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($users as $user)
						@php
							$userAvatar = ($user->profile && $user->profile->photo_path)
								? asset('storage/' . $user->profile->photo_path)
								: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
							$roleClass = match($user->role) {
								'admin' => 'badge-light-danger',
								'instructor' => 'badge-light-primary',
								'student', 'user' => 'badge-light-success',
								default => 'badge-light-secondary',
							};
							$statusClass = match($user->status ?? 'active') {
								'active' => 'badge-light-success',
								'suspended' => 'badge-light-danger',
								default => 'badge-light-secondary',
							};
						@endphp
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="symbol symbol-40px me-3">
										<img src="{{ $userAvatar }}" alt="{{ $user->name }}" class="rounded-circle" />
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-900 fw-bold">{{ $user->name }}</span>
										<span class="text-gray-500 fs-7">ID: {{ $user->id }}</span>
									</div>
								</div>
							</td>
							<td>
								<span class="text-gray-900 fw-semibold">{{ $user->email }}</span>
							</td>
							<td>
								<span class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
							</td>
							<td>
								<span class="badge {{ $statusClass }}">{{ ucfirst($user->status ?? 'active') }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $user->created_at->format('d M Y') }}</span>
							</td>
							<td>
								<div class="d-flex gap-2">
									<a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-icon btn-light-primary" title="Detail">
										<i class="ki-duotone ki-eye fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
										</i>
									</a>
									<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-icon btn-light-warning" title="Edit">
										<i class="ki-duotone ki-pencil fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus">
											<i class="ki-duotone ki-trash fs-5">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
												<span class="path4"></span>
												<span class="path5"></span>
											</i>
										</button>
									</form>
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="6" class="text-center text-muted">Tidak ada pengguna</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-5">
			{{ $users->links() }}
		</div>
	</div>
</div>
@endsection

