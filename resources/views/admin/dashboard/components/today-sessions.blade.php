<!--
Today Sessions Component
Admin Dashboard - All Sessions Today
-->
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Sesi Hari Ini</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Semua sesi yang dijadwalkan hari ini</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(isset($todaySessions) && $todaySessions->count() > 0)
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Kursus</th>
						<th>Instruktur</th>
						<th>Waktu</th>
						<th>Mode</th>
						<th>Peserta</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
						@foreach($todaySessions as $item)
							@php
								$session = $item->session;
								$scheduledAt = \Carbon\Carbon::parse($session->scheduled_at);
								$endTime = $scheduledAt->copy()->addMinutes($session->duration_minutes);
								$timeRange = $scheduledAt->format('H:i') . ' - ' . $endTime->format('H:i');
								$durationHours = floor($session->duration_minutes / 60);
								$durationMinutes = $session->duration_minutes % 60;
								$durationText = $durationHours > 0 ? $durationHours . ' jam' : '';
								$durationText .= $durationMinutes > 0 ? ($durationHours > 0 ? ' ' : '') . $durationMinutes . ' menit' : '';
								$modeClass = match($session->mode) {
									'online' => 'badge-light-primary',
									'offline' => 'badge-light-info',
									'hybrid' => 'badge-light-success',
									default => 'badge-light-secondary',
								};
								$statusClass = match($item->status) {
									'Ongoing' => 'badge-light-success',
									'Upcoming' => 'badge-light-warning',
									'Completed' => 'badge-light-info',
									default => 'badge-light-secondary',
								};
								$instructorAvatar = ($session->instructor && $session->instructor->profile && $session->instructor->profile->photo_path)
									? asset('storage/' . $session->instructor->profile->photo_path)
									: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
							@endphp
					<tr>
						<td>
									<span class="text-gray-900 fw-bold">{{ $session->course->title ?? $session->title }}</span>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div class="symbol symbol-30px me-3">
											<img src="{{ $instructorAvatar }}" alt="{{ $session->instructor->name ?? '-' }}" class="rounded-circle" />
								</div>
										<span class="text-gray-900 fw-semibold">{{ $session->instructor->name ?? '-' }}</span>
							</div>
						</td>
						<td>
									<span class="text-gray-900 fw-semibold">{{ $timeRange }}</span>
									<span class="text-gray-500 fs-7 d-block">{{ $durationText }}</span>
						</td>
						<td>
									<span class="badge {{ $modeClass }}">{{ ucfirst($session->mode) }}</span>
						</td>
						<td>
									<span class="text-gray-900 fw-bold">{{ $item->participant_count }}</span>
									<span class="text-gray-500 fs-7">peserta</span>
						</td>
						<td>
									<span class="badge {{ $statusClass }}">{{ $item->status }}</span>
						</td>
					</tr>
						@endforeach
				</tbody>
			</table>
		</div>
		@else
			<div class="empty-state text-center py-10">
				<div class="icon mb-5">
					<i class="ki-duotone ki-calendar fs-5x text-gray-400">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
				<div class="text text-gray-500 fw-semibold fs-5">Tidak ada sesi hari ini</div>
		</div>
		@endif
	</div>
</div>

