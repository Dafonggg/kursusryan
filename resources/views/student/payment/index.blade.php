@extends('student.layouts.master')

@section('title', 'Pembayaran - Student')
@section('description', 'Halaman pembayaran dan upload bukti transfer')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Pembayaran</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Pembayaran</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
@endif

@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		{{ session('error') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
@endif

<!-- Payment Status Card -->
@if(isset($payment_status))
<div class="card card-flush mb-5">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Status Pembayaran Terakhir</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Informasi pembayaran terbaru</span>
		</h3>
	</div>
	<div class="card-body pt-0">
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
			</div>
		</div>
	</div>
</div>
@endif

<!-- Pending Payments -->
@if(isset($pendingPayments) && $pendingPayments->count() > 0)
<div class="card card-flush mb-5">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Pembayaran Pending</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Upload bukti pembayaran untuk verifikasi</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@foreach($pendingPayments as $pendingPayment)
			<div class="card card-flush mb-5 {{ $payment && $payment->id == $pendingPayment->id ? 'border border-primary' : '' }}">
				<div class="card-header">
					<h4 class="card-title">Pembayaran #{{ $pendingPayment->id }}</h4>
				</div>
				<div class="card-body">
					<div class="row mb-5">
						<div class="col-md-6">
							<div class="mb-3">
								<span class="text-gray-500 fs-7">Kursus:</span>
								<span class="text-gray-900 fw-semibold ms-2">{{ $pendingPayment->enrollment->course->title ?? '-' }}</span>
							</div>
							<div class="mb-3">
								<span class="text-gray-500 fs-7">Jumlah:</span>
								<span class="text-gray-900 fw-bold ms-2 text-primary">Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}</span>
							</div>
							<div class="mb-3">
								<span class="text-gray-500 fs-7">Metode:</span>
								<span class="text-gray-900 fw-semibold ms-2">{{ ucfirst($pendingPayment->method->value ?? 'Transfer') }}</span>
							</div>
							<div class="mb-3">
								<span class="text-gray-500 fs-7">Tanggal:</span>
								<span class="text-gray-900 fw-semibold ms-2">{{ $pendingPayment->created_at->format('d M Y H:i') }}</span>
							</div>
						</div>
						<div class="col-md-6">
							@php
								$meta = $pendingPayment->meta ?? [];
								$proof = $meta['proof'] ?? $meta['proof_image'] ?? $meta['bukti'] ?? null;
							@endphp
							@if($proof)
								<div class="mb-3">
									<span class="text-gray-500 fs-7">Bukti Pembayaran:</span>
									<div class="mt-2">
										<img src="{{ asset('storage/' . $proof) }}" alt="Bukti Pembayaran" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
									</div>
									<div class="mt-2">
										<span class="badge badge-light-info">Bukti sudah diupload</span>
									</div>
								</div>
							@else
								<div class="mb-3">
									<span class="text-gray-500 fs-7">Bukti Pembayaran:</span>
									<div class="mt-2">
										<span class="badge badge-light-warning">Belum ada bukti</span>
									</div>
								</div>
							@endif
						</div>
					</div>
					
					<!-- Upload Form -->
					<form action="{{ route('student.payment.upload-proof', $pendingPayment->id) }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-md-6">
								<div class="mb-5">
									<label class="form-label required">Upload Bukti Pembayaran</label>
									<input type="file" name="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg" required>
									<div class="form-text">Format: JPEG, PNG, JPG (Maks: 5MB)</div>
									@error('proof')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-5">
									<label class="form-label">Bank Pengirim</label>
									<input type="text" name="bank" class="form-control @error('bank') is-invalid @enderror" placeholder="Contoh: BCA, Mandiri, BRI" value="{{ old('bank') }}">
									@error('bank')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-5">
									<label class="form-label">No. Rekening Pengirim</label>
									<input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" placeholder="Contoh: 1234567890" value="{{ old('account_number') }}">
									@error('account_number')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-5">
									<label class="form-label">No. Referensi / No. Transaksi</label>
									<input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror" placeholder="Contoh: TRX123456789" value="{{ old('reference', $pendingPayment->reference) }}">
									@error('reference')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-end">
							<button type="submit" class="btn btn-primary">
								<i class="ki-duotone ki-upload fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								Upload Bukti Pembayaran
							</button>
						</div>
					</form>
				</div>
			</div>
		@endforeach
	</div>
</div>
@else
<div class="card card-flush">
	<div class="card-body text-center py-10">
		<i class="ki-duotone ki-information-5 fs-3x text-muted mb-5">
			<span class="path1"></span>
			<span class="path2"></span>
			<span class="path3"></span>
		</i>
		<p class="text-gray-500">Tidak ada pembayaran pending</p>
	</div>
</div>
@endif
@endsection

