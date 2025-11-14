<!--
Today Sessions Component
Instructor Dashboard - My Sessions Today
-->
<div class="card card-flush h-md-100">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Sesi Saya Hari Ini</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $today_sessions_count }} sesi</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Kursus</th>
						<th>Waktu</th>
						<th>Peserta</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@forelse($today_sessions ?? [] as $session)
					<tr>
						<td>
							<span class="text-gray-900 fw-bold">{{ $session->course_name }}</span>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $session->session_time }}</span>
							<span class="text-gray-500 fs-7 d-block">{{ $session->session_duration }}</span>
						</td>
						<td>
							<span class="text-gray-900 fw-bold">{{ $session->participant_count }}</span>
							<span class="text-gray-500 fs-7">/ {{ $session->max_participants }}</span>
						</td>
						<td>
							<span class="badge badge-light-{{ $session->status_badge }}">{{ $session->session_status }}</span>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="4" class="text-center">
							<div class="empty-state">
								<div class="text">Tidak ada sesi hari ini</div>
							</div>
						</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<!-- If no sessions -->
		<div class="empty-state d-none">
			<div class="icon">
				<i class="ki-duotone ki-calendar fs-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
			<div class="text">Tidak ada sesi hari ini</div>
		</div>
	</div>
</div>

