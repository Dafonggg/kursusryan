@extends('instructor.layouts.master')

@section('title', 'Riwayat Transaksi - Instructor')
@section('description', 'Riwayat transaksi peserta')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Riwayat Transaksi</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Riwayat Transaksi</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Transaksi</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $payments->count() }} transaksi</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>Peserta</th>
						<th>Kursus</th>
						<th>Jumlah</th>
						<th>Metode</th>
						<th>Status</th>
						<th>Tanggal</th>
						<th>Referensi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($payments as $payment)
						<tr>
							<td>
								<span class="text-gray-900 fw-bold">{{ $payment->enrollment->user->name ?? 'N/A' }}</span>
								<div class="text-gray-500 fs-7">{{ $payment->enrollment->user->email ?? 'N/A' }}</div>
							</td>
							<td>
								<span class="text-gray-900">{{ $payment->enrollment->course->title ?? 'N/A' }}</span>
							</td>
							<td>
								<span class="text-gray-900 fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
							</td>
							<td>
								@php
									$methodLabel = ucfirst($payment->method->value);
								@endphp
								<span class="badge badge-light-info">{{ $methodLabel }}</span>
							</td>
							<td>
								@php
									$statusClass = match($payment->status->value) {
										'pending' => 'warning',
										'paid' => 'success',
										'failed' => 'danger',
										'refunded' => 'secondary',
										default => 'secondary'
									};
									$statusLabel = ucfirst($payment->status->value);
								@endphp
								<span class="badge badge-light-{{ $statusClass }}">{{ $statusLabel }}</span>
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $payment->created_at->format('d M Y H:i') }}</span>
								@if($payment->paid_at)
									<div class="text-gray-400 fs-8">Dibayar: {{ $payment->paid_at->format('d M Y H:i') }}</div>
								@endif
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $payment->reference ?? '-' }}</span>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center text-muted">Belum ada transaksi</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

