@extends('admin.layouts.master')

@section('title', 'Daftar Sesi - Admin')
@section('description', 'Daftar semua sesi kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Daftar Sesi</h1>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.sessions.create') }}" class="btn btn-sm fw-bold btn-primary">Buat Sesi</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Sesi</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Semua sesi kursus yang terdaftar</span>
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
						<th>Kursus</th>
						<th>Instruktur</th>
						<th>Mode</th>
						<th>Jadwal</th>
						<th>Durasi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($sessions as $session)
						<tr>
							<td>{{ $session->id }}</td>
							<td>{{ $session->title }}</td>
							<td>{{ $session->course->title ?? '-' }}</td>
							<td>{{ $session->instructor->name ?? '-' }}</td>
							<td>
								<span class="badge badge-{{ $session->mode == 'online' ? 'success' : ($session->mode == 'offline' ? 'primary' : 'info') }}">
									{{ ucfirst($session->mode) }}
								</span>
							</td>
							<td>{{ $session->scheduled_at ? \Carbon\Carbon::parse($session->scheduled_at)->format('d M Y H:i') : '-' }}</td>
							<td>{{ $session->duration_minutes }} menit</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center text-muted">Belum ada sesi</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-5">
			{{ $sessions->links() }}
		</div>
	</div>
</div>
@endsection

