<!--
Reschedule Requests Component
Admin Dashboard - Pending Reschedule Requests
-->
<div class="card card-flush">
	<div class="card-header pt-5">
		<div class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Permintaan Reschedule Pending</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ isset($pendingReschedules) ? $pendingReschedules->count() : 0 }} permintaan menunggu</span>
		</div>
		<div class="card-toolbar">
			<a href="{{ route('admin.reschedules.pending') }}" class="btn btn-sm btn-light-primary">Lihat Semua</a>
		</div>
	</div>
	<div class="card-body pt-0">
		@if(isset($pendingReschedules) && $pendingReschedules->count() > 0)
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Peserta</th>
						<th>Sesi</th>
						<th>Tanggal Lama</th>
						<th>Tanggal Baru</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
						@foreach($pendingReschedules as $request)
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
							<div class="d-flex gap-2">
										<form action="{{ route('admin.reschedules.approve', $request->id) }}" method="POST" class="d-inline">
											@csrf
											<button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui permintaan reschedule ini?')">
									Approve
								</button>
										</form>
										<form action="{{ route('admin.reschedules.reject', $request->id) }}" method="POST" class="d-inline">
											@csrf
											<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak permintaan reschedule ini?')">
									Reject
								</button>
										</form>
							</div>
						</td>
					</tr>
						@endforeach
				</tbody>
			</table>
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

