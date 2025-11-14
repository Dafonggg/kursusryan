<!--
Attendance Pending Component
Instructor Dashboard - Sessions with Pending Attendance
-->
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Attendance Belum Diinput</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $pending_attendance_count }} sesi</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Sesi</th>
						<th>Tanggal</th>
						<th>Peserta</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($attendance_pending_sessions ?? [] as $session)
					<tr>
						<td>
							<span class="text-gray-900 fw-bold">{{ $session->session_name }}</span>
							<span class="text-gray-500 fs-7 d-block">{{ $session->course_name }}</span>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $session->session_date }}</span>
							<span class="text-gray-500 fs-7 d-block">{{ $session->session_time }}</span>
						</td>
						<td>
							<span class="text-gray-900 fw-bold">{{ $session->participant_count }}</span>
							<span class="text-gray-500 fs-7">peserta</span>
						</td>
						<td>
							<a href="{{ route('instructor.attendance.input', $session->session_id) }}" class="btn btn-sm btn-primary">
								Input Absensi
							</a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="4" class="text-center">
							<div class="empty-state">
								<div class="text">Semua absensi sudah diinput</div>
							</div>
						</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<!-- If no pending attendance -->
		<div class="empty-state d-none">
			<div class="icon">
				<i class="ki-duotone ki-check-circle fs-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
			<div class="text">Semua absensi sudah diinput</div>
		</div>
	</div>
</div>

