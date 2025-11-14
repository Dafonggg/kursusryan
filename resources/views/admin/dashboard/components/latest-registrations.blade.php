<!--
Latest Registrations Component
Admin Dashboard - Latest 10 Registrations
-->
<div class="card card-flush">
	<div class="card-header pt-7 pb-3">
		<div class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Pendaftaran Terbaru</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ isset($latestRegistrations) ? $latestRegistrations->count() : 0 }} pendaftaran terakhir</span>
		</div>
		<div class="card-toolbar">
			<a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light-primary">Lihat Semua</a>
		</div>
	</div>
	<div class="card-body pt-0 pb-5">
		@if(isset($latestRegistrations) && $latestRegistrations->count() > 0)
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Nama</th>
						<th>Kursus</th>
							<th>Status</th>
						<th>Tanggal</th>
					</tr>
				</thead>
				<tbody>
						@foreach($latestRegistrations as $enrollment)
							@php
								$userAvatar = ($enrollment->user && $enrollment->user->profile && $enrollment->user->profile->photo_path)
									? asset('storage/' . $enrollment->user->profile->photo_path)
									: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
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
							<div class="d-flex align-items-center">
								<div class="symbol symbol-40px me-3">
											<img src="{{ $userAvatar }}" alt="{{ $enrollment->user->name ?? '-' }}" class="rounded-circle" />
								</div>
								<div class="d-flex flex-column">
											<span class="text-gray-900 fw-bold">{{ $enrollment->user->name ?? '-' }}</span>
											<span class="text-gray-500 fs-7">{{ $enrollment->user->email ?? '-' }}</span>
								</div>
							</div>
						</td>
						<td>
									<span class="text-gray-900 fw-semibold">{{ $enrollment->course->title ?? '-' }}</span>
						</td>
						<td>
									<span class="badge {{ $statusClass }}">{{ ucfirst($enrollment->status->value) }}</span>
						</td>
						<td>
									<span class="text-gray-500 fs-7">{{ $enrollment->created_at->format('d M Y') }}</span>
						</td>
					</tr>
						@endforeach
				</tbody>
			</table>
		</div>
		@else
			<div class="empty-state text-center py-10">
				<div class="icon mb-5">
					<i class="ki-duotone ki-information-5 fs-5x text-gray-400">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
				</i>
			</div>
				<div class="text text-gray-500 fw-semibold fs-5">Tidak ada pendaftaran terbaru</div>
		</div>
		@endif
	</div>
</div>

