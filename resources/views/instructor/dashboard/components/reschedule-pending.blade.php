<!--
Reschedule Pending Component
Instructor Dashboard - Pending Reschedule Requests
-->
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Reschedule Request Pending</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $pending_reschedule_count }} permintaan</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Peserta</th>
						<th>Sesi</th>
						<th>Tanggal Baru</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($reschedule_requests ?? [] as $request)
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<div class="symbol symbol-40px me-3">
									<img src="{{ $request->student_avatar }}" 
										alt="{{ $request->student_name }}" 
										onerror="this.src='{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-1.jpg') }}'" />
								</div>
								<div class="d-flex flex-column">
									<span class="text-gray-900 fw-bold">{{ $request->student_name }}</span>
									<span class="text-gray-500 fs-7">{{ $request->student_email }}</span>
								</div>
							</div>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $request->session_name }}</span>
							<span class="text-gray-500 fs-7 d-block">{{ $request->course_name }}</span>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $request->new_date }}</span>
							<span class="text-gray-500 fs-7 d-block">{{ $request->new_time }}</span>
						</td>
						<td>
							<div class="d-flex gap-2">
								<form action="{{ route('instructor.reschedule.approve', $request->request_id) }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui reschedule ini?')">
										Approve
									</button>
								</form>
								<form action="{{ route('instructor.reschedule.reject', $request->request_id) }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak reschedule ini?')">
										Reject
									</button>
								</form>
							</div>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="4" class="text-center">
							<div class="empty-state">
								<div class="text">Tidak ada permintaan reschedule pending</div>
							</div>
						</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<!-- If no requests -->
		<div class="empty-state d-none">
			<div class="icon">
				<i class="ki-duotone ki-check-circle fs-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
			<div class="text">Tidak ada permintaan reschedule pending</div>
		</div>
	</div>
</div>

