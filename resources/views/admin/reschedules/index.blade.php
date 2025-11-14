@extends('admin.layouts.master')

@section('title', 'Daftar Reschedule - Admin')
@section('description', 'Daftar semua permintaan reschedule')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Daftar Reschedule</h1>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.reschedules.pending') }}" class="btn btn-sm fw-bold btn-warning">Pending</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Reschedule</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Semua permintaan reschedule yang terdaftar</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		@if(session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{{ session('error') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>Peserta</th>
						<th>Sesi</th>
						<th>Tanggal Lama</th>
						<th>Tanggal Baru</th>
						<th>Status</th>
						<th>Dibuat</th>
					</tr>
				</thead>
				<tbody>
					@forelse($rescheduleRequests as $request)
						@php
							$requesterAvatar = ($request->requester && $request->requester->profile && $request->requester->profile->photo_path)
								? asset('storage/' . $request->requester->profile->photo_path)
								: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
							$statusClass = match($request->status->value) {
								'pending' => 'badge-light-warning',
								'approved' => 'badge-light-success',
								'rejected' => 'badge-light-danger',
								default => 'badge-light-secondary',
							};
						@endphp
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="symbol symbol-40px me-3">
										<img src="{{ $requesterAvatar }}" alt="{{ $request->requester->name ?? '-' }}" class="rounded-circle" />
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-900 fw-bold">{{ $request->requester->name ?? '-' }}</span>
										<span class="text-gray-500 fs-7">{{ $request->requester->email ?? '-' }}</span>
									</div>
								</div>
							</td>
							<td>
								<span class="text-gray-900 fw-semibold">{{ $request->session->title ?? '-' }}</span>
								<span class="text-gray-500 fs-7 d-block">{{ $request->session->course->title ?? '-' }}</span>
							</td>
							<td>
								@if($request->session->scheduled_at)
									<span class="text-gray-900 fw-semibold">{{ $request->session->scheduled_at->format('d M Y') }}</span>
									<span class="text-gray-500 fs-7 d-block">{{ $request->session->scheduled_at->format('H:i') }}</span>
								@else
									<span class="text-gray-500">-</span>
								@endif
							</td>
							<td>
								@if($request->proposed_at)
									<span class="text-gray-900 fw-semibold">{{ $request->proposed_at->format('d M Y') }}</span>
									<span class="text-gray-500 fs-7 d-block">{{ $request->proposed_at->format('H:i') }}</span>
								@else
									<span class="text-gray-500">-</span>
								@endif
							</td>
							<td>
								<span class="badge {{ $statusClass }}">{{ ucfirst($request->status->value) }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $request->created_at->format('d M Y H:i') }}</span>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="6" class="text-center text-muted">Tidak ada permintaan reschedule</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-5">
			{{ $rescheduleRequests->links() }}
		</div>
	</div>
</div>
@endsection

