<!--
Active Days Counter Component
Student Dashboard - Remaining Active Days
-->
@php
	$cardClass = '';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Sisa Hari Aktif</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">Hari tersisa untuk akses kursus</span>
	';
	ob_start();
@endphp

@if(isset($active_days))
<div class="d-flex flex-column align-items-center text-center">
	<div class="fs-2hx fw-bold text-gray-900 mb-3">{{ $active_days->remaining_days }}</div>
	<div class="text-gray-500 fw-semibold fs-6 mb-5">Hari Tersisa</div>
	<div class="progress progress-custom w-100 mb-3" style="height: 20px;">
		<div class="progress-bar bg-primary" role="progressbar" style="width: {{ $active_days->active_days_percentage }}%" aria-valuenow="{{ $active_days->active_days_percentage }}" aria-valuemin="0" aria-valuemax="100">
			<span class="text-white fw-bold">{{ $active_days->active_days_percentage }}%</span>
		</div>
	</div>
	<div class="d-flex justify-content-between w-100">
		<span class="text-gray-500 fs-7">Mulai: {{ $active_days->enrollment_date }}</span>
		<span class="text-gray-500 fs-7">Berakhir: {{ $active_days->expiry_date }}</span>
	</div>
</div>
@else
<div class="text-center py-5">
	<div class="text-gray-500">Data tidak tersedia</div>
</div>
@endif

@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')

