@extends('admin.layouts.master')

@section('title', 'Daftar Kursus - Admin')
@section('description', 'Daftar semua kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Daftar Kursus</h1>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.courses.create') }}" class="btn btn-sm fw-bold btn-primary">Tambah Kursus</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Kursus</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Semua kursus yang terdaftar dalam sistem</span>
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
						<th>ID</th>
						<th>Judul</th>
						<th>Pemilik</th>
						<th>Mode</th>
						<th>Harga</th>
						<th>Durasi</th>
						<th>Dibuat</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($courses as $course)
						<tr>
							<td>{{ $course->id }}</td>
							<td>{{ $course->title }}</td>
							<td>{{ $course->owner->name ?? '-' }}</td>
							<td>
								<span class="badge badge-{{ $course->mode->value == 'online' ? 'success' : ($course->mode->value == 'offline' ? 'primary' : 'info') }}">
									{{ ucfirst($course->mode->value) }}
								</span>
							</td>
							<td>Rp {{ number_format($course->price, 0, ',', '.') }}</td>
							<td>{{ $course->duration_months }} bulan</td>
							<td>{{ $course->created_at->format('d M Y') }}</td>
							<td>
								<div class="d-flex gap-2">
									<a href="{{ route('admin.materials.index', $course->slug) }}" class="btn btn-sm btn-icon btn-light-info" title="Materi">
										<i class="ki-duotone ki-file fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<a href="{{ route('admin.courses.edit', $course->slug) }}" class="btn btn-sm btn-icon btn-light-primary" title="Edit">
										<i class="ki-duotone ki-pencil fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<form action="{{ route('admin.courses.destroy', $course->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
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
							<td colspan="8" class="text-center text-muted">Belum ada kursus</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-5">
			{{ $courses->links() }}
		</div>
	</div>
</div>
@endsection

