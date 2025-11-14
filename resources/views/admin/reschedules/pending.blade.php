@extends('admin.layouts.master')

@section('title', 'Reschedule Pending - Admin')
@section('description', 'Permintaan reschedule yang pending')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Reschedule Pending</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.reschedules.index') }}" class="text-muted text-hover-primary">Reschedule</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Pending</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.reschedules.index') }}" class="btn btn-sm fw-bold btn-secondary">Semua Reschedule</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Permintaan Reschedule Pending</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $rescheduleRequests->total() }} permintaan menunggu</span>
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

		@if($rescheduleRequests->count() > 0)
			<div class="table-responsive">
				<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
					<thead>
						<tr>
							<th>Peserta</th>
							<th>Sesi</th>
							<th>Tanggal Lama</th>
							<th>Tanggal Baru</th>
							<th>Alasan</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach($rescheduleRequests as $request)
							@php
								$requesterAvatar = ($request->requester && $request->requester->profile && $request->requester->profile->photo_path)
									? asset('storage/' . $request->requester->profile->photo_path)
									: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
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
									<span class="text-gray-700">{{ $request->reason ?? '-' }}</span>
								</td>
								<td>
									<div class="d-flex gap-2">
										<form action="{{ route('admin.reschedules.approve', $request->id) }}" method="POST" class="d-inline">
											@csrf
											<button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui permintaan reschedule ini?')">
												Approve
											</button>
										</form>
										<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
											Reject
										</button>
									</div>

									<!-- Reject Modal -->
									<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Tolak Permintaan Reschedule</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
												</div>
												<form action="{{ route('admin.reschedules.reject', $request->id) }}" method="POST">
													@csrf
													<div class="modal-body">
														<div class="mb-5">
															<label class="form-label">Alasan Penolakan (Opsional)</label>
															<textarea name="decision_notes" class="form-control" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
														<button type="submit" class="btn btn-danger">Tolak</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="mt-5">
				{{ $rescheduleRequests->links() }}
			</div>
		@else
			<div class="empty-state text-center py-10">
				<div class="icon mb-5">
					<i class="ki-duotone ki-check-circle fs-5x text-gray-400">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
				<div class="text text-gray-500 fw-semibold fs-5">Tidak ada permintaan reschedule pending</div>
			</div>
		@endif
	</div>
</div>
@endsection

