<!--
Dashboard Summary Component
Student Dashboard - Ringkasan: Jumlah Kursus Aktif, Masa Berlaku, Status Pembayaran
-->
@php
	$cardClass = '';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Ringkasan</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">Informasi kursus dan pembayaran Anda</span>
	';
	ob_start();
@endphp

@if(isset($summary))
<div class="row g-3">
	<!-- Jumlah Kursus Aktif -->
	<div class="col-12 col-md-4">
		<div class="d-flex flex-column align-items-center text-center p-4 bg-light-primary rounded">
			<div class="symbol symbol-50px mb-3">
				<i class="ki-duotone ki-book-open fs-2x text-primary">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
			<div class="fs-2hx fw-bold text-gray-900 mb-1">{{ $summary->active_courses_count }}</div>
			<div class="text-gray-600 fw-semibold fs-7">Kursus Aktif</div>
		</div>
	</div>
	
	<!-- Masa Berlaku -->
	<div class="col-12 col-md-4">
		<div class="d-flex flex-column align-items-center text-center p-4 bg-light-info rounded">
			<div class="symbol symbol-50px mb-3">
				<i class="ki-duotone ki-calendar-tick fs-2x text-info">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
				</i>
			</div>
			@if($summary->expiry_date)
				<div class="fs-2hx fw-bold text-gray-900 mb-1">{{ $summary->remaining_days }}</div>
				<div class="text-gray-600 fw-semibold fs-7">Hari Tersisa</div>
				<div class="text-gray-500 fs-8 mt-1">Berlaku hingga: {{ $summary->expiry_date }}</div>
			@else
				<div class="text-gray-600 fw-semibold fs-7">Tidak Terbatas</div>
			@endif
		</div>
	</div>
	
	<!-- Status Pembayaran -->
	<div class="col-12 col-md-4">
		<div class="d-flex flex-column align-items-center text-center p-4 bg-light-{{ $summary->payment_status_badge }} rounded">
			<div class="symbol symbol-50px mb-3">
				<i class="ki-duotone ki-wallet fs-2x text-{{ $summary->payment_status_badge }}">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
				</i>
			</div>
			<div class="badge badge-{{ $summary->payment_status_badge }} mb-2 fs-6">{{ $summary->payment_status }}</div>
			<div class="text-gray-600 fw-semibold fs-7">Status Pembayaran</div>
		</div>
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


