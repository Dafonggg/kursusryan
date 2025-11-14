<!--
Payment Status Component
Student Dashboard - Last Payment Status
-->	
@php
	$cardClass = '';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Status Pembayaran Terakhir</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">Informasi pembayaran terbaru</span>
	';
	ob_start();
@endphp

@if(isset($payment_status))
<div class="d-flex flex-column">
	<div class="d-flex align-items-center justify-content-between mb-4">
		<span class="text-gray-900 fw-bold fs-4">{{ $payment_status->payment_amount }}</span>
		<span class="badge badge-light-{{ $payment_status->payment_status_badge }}">{{ $payment_status->payment_status }}</span>
	</div>
	<div class="mb-4">
		<div class="d-flex justify-content-between mb-2">
			<span class="text-gray-500 fs-7">Tanggal</span>
			<span class="text-gray-900 fw-semibold">{{ $payment_status->payment_date }}</span>
		</div>
		<div class="d-flex justify-content-between mb-2">
			<span class="text-gray-500 fs-7">Metode</span>
			<span class="text-gray-900 fw-semibold">{{ $payment_status->payment_method }}</span>
		</div>
		<div class="d-flex justify-content-between">
			<span class="text-gray-500 fs-7">Invoice</span>
			<a href="{{ $payment_status->invoice_url }}" class="text-primary fw-semibold" target="_blank">Lihat Invoice</a>
		</div>
	</div>
	<!-- Show CTA if payment is pending -->
	@if($payment_status->payment_status == 'Pending')
	<div>
		<a href="#" class="btn btn-sm btn-primary w-100" onclick="payNow({{ $payment_status->payment_id }})">
			Bayar Sekarang
		</a>
	</div>
	@endif
</div>
@else
<div class="text-center py-5">
	<div class="text-gray-500">Tidak ada data pembayaran</div>
</div>
@endif

@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')
