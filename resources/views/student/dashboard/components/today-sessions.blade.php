<!--
Today Sessions Component
Student Dashboard - My Sessions Today
-->
@php
	$cardClass = 'h-md-100';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Sesi Saya Hari Ini</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">' . ($today_sessions_count ?? 0) . ' sesi</span>
	';
	ob_start();
@endphp

@if(isset($today_sessions) && count($today_sessions) > 0)
<div class="d-flex flex-column">
	<div class="table-responsive">
		<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
			<thead>
				<tr>
					<th>Kursus</th>
					<th>Waktu</th>
					<th>Mode</th>
					<th>Status</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach($today_sessions as $session)
				<tr>
					<td>
						<div class="d-flex align-items-center">
							<div class="symbol symbol-40px me-3">
								<img src="{{ $session->course_image }}" alt="{{ $session->course_name }}" class="rounded" />
							</div>
							<div class="d-flex flex-column">
								<span class="text-gray-900 fw-bold">{{ $session->course_name }}</span>
								<span class="text-gray-500 fs-7">{{ $session->session_name }}</span>
							</div>
						</div>
					</td>
					<td>
						<span class="text-gray-900 fw-semibold">{{ $session->session_time }}</span>
						<span class="text-gray-500 fs-7 d-block">{{ $session->session_duration }}</span>
					</td>
					<td>
						<span class="text-gray-900 fw-semibold">{{ $session->session_mode }}</span>
						@if($session->is_online && $session->session_link && $session->session_link !== '#')
							<span class="text-gray-500 fs-7 d-block">{{ $session->session_location }}</span>
						@elseif(!$session->is_online)
							<span class="text-gray-500 fs-7 d-block">{{ $session->session_location }}</span>
						@endif
					</td>
					<td>
						<span class="badge badge-light-{{ $session->status_badge }}">{{ $session->session_status }}</span>
					</td>
					<td>
						@if($session->is_online && $session->session_link && $session->session_link !== '#' && $session->can_join)
							<a href="{{ $session->session_link }}" class="btn btn-sm btn-primary me-2" target="_blank">
								Bergabung
							</a>
						@elseif($session->is_online && $session->session_link && $session->session_link !== '#' && !$session->can_join)
							<button type="button" class="btn btn-sm btn-light me-2" disabled>
								Bergabung
							</button>
						@endif
						<a href="{{ route('student.sessions.show', $session->session_id) }}" class="btn btn-sm btn-light-primary">
							Detail
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@else
<div class="text-center py-5">
	<div class="text-gray-500">Tidak ada sesi hari ini</div>
</div>
@endif

@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')

