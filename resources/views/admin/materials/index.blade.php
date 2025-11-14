@extends('admin.layouts.master')

@section('title', 'Materi Kursus - Admin')
@section('description', 'Daftar materi kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Materi Kursus: {{ $course->title }}</h1>
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
				<li class="breadcrumb-item text-gray-900">Materi</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.courses.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali ke Kursus</a>
			<a href="{{ route('admin.materials.create', $course->slug) }}" class="btn btn-sm fw-bold btn-primary">Tambah Materi</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Materi</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $materials->count() }} materi tersedia</span>
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
						<th>Urutan</th>
						<th>Judul</th>
						<th>Tipe</th>
						<th>Scope</th>
						<th>File/URL</th>
						<th>Dibuat</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($materials as $material)
						<tr>
							<td>
								<span class="badge badge-light-primary">{{ $material->order }}</span>
							</td>
							<td>
								<span class="text-gray-900 fw-bold">{{ $material->title }}</span>
							</td>
							<td>
								@php
									$typeClass = $material->type->value == 'video' ? 'success' : 'info';
									$typeLabel = $material->type->value == 'video' ? 'Video' : 'Dokumen';
								@endphp
								<span class="badge badge-light-{{ $typeClass }}">{{ $typeLabel }}</span>
							</td>
							<td>
								@php
									$scopeClass = match($material->scope) {
										'online' => 'success',
										'offline' => 'primary',
										'both' => 'info',
										default => 'secondary'
									};
								@endphp
								<span class="badge badge-light-{{ $scopeClass }}">{{ ucfirst($material->scope) }}</span>
							</td>
							<td>
								@if($material->path)
									<a href="{{ asset('storage/' . $material->path) }}" target="_blank" class="btn btn-sm btn-icon btn-light-primary">
										<i class="ki-duotone ki-file fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
								@elseif($material->url)
									<a href="{{ $material->url }}" target="_blank" class="btn btn-sm btn-light-info">Link</a>
								@else
									<span class="text-muted">-</span>
								@endif
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $material->created_at->format('d M Y') }}</span>
							</td>
							<td>
								<div class="d-flex gap-2">
									<a href="{{ route('admin.materials.edit', [$course->slug, $material->id]) }}" class="btn btn-sm btn-icon btn-light-primary" title="Edit">
										<i class="ki-duotone ki-pencil fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<form action="{{ route('admin.materials.destroy', [$course->slug, $material->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
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
							<td colspan="7" class="text-center text-muted">Belum ada materi</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

