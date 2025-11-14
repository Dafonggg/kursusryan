<!--
Next Session Component
Student Dashboard - Next Upcoming Session
-->
@php
	$cardClass = 'h-md-100';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Sesi Berikutnya</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">Sesi selanjutnya yang akan Anda ikuti</span>
	';
	ob_start();
@endphp

@if(isset($next_session))
<div class="d-flex flex-column">
	<div class="d-flex align-items-center mb-4">
		<div class="symbol symbol-50px me-4">
			<img src="{{ $next_session->course_image }}" alt="{{ $next_session->course_name }}" />
		</div>
		<div class="d-flex flex-column">
			<span class="text-gray-900 fw-bold fs-5">{{ $next_session->course_name }}</span>
			<span class="text-gray-500 fs-7">{{ $next_session->session_name }}</span>
		</div>
	</div>
	<div class="mb-4">
		<div class="d-flex align-items-center mb-2">
			<i class="ki-duotone ki-calendar fs-2 text-gray-500 me-2">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
			<span class="text-gray-900 fw-semibold">{{ $next_session->session_date }}</span>
		</div>
		<div class="d-flex align-items-center mb-2">
			<i class="ki-duotone ki-clock fs-2 text-gray-500 me-2">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
			<span class="text-gray-900 fw-semibold">{{ $next_session->session_time }}</span>
		</div>
		<div class="d-flex align-items-center mb-2">
			<i class="ki-duotone ki-geolocation fs-2 text-gray-500 me-2">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
			<span class="text-gray-900 fw-semibold">{{ $next_session->session_mode }}</span>
			<span class="text-gray-500 fs-7 ms-2">{{ $next_session->session_location }}</span>
		</div>
		<div class="d-flex align-items-center">
			<i class="ki-duotone ki-link fs-2 text-gray-500 me-2">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
			<a href="{{ $next_session->session_link }}" class="text-primary fw-semibold" target="_blank">{{ $next_session->session_link }}</a>
		</div>
	</div>
	<a href="#" class="btn btn-sm btn-primary" onclick="viewSession({{ $next_session->session_id }})">
		Lihat Detail
	</a>
</div>
@else
<div class="text-center py-5">
	<div class="text-gray-500">Tidak ada sesi berikutnya</div>
</div>
@endif

@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')
