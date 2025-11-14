@extends('admin.layouts.master')

@section('title', 'Verifikasi Pembayaran - Admin')
@section('description', 'Verifikasi pembayaran pending')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Verifikasi Pembayaran</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Pembayaran Pending</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Pembayaran Pending</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Verifikasi pembayaran yang menunggu persetujuan</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>ID</th>
						<th>Tanggal</th>
						<th>Siswa</th>
						<th>Kursus</th>
						<th>Jumlah</th>
						<th>Metode</th>
						<th>Referensi</th>
						<th>Bukti</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($payments as $payment)
						<tr>
							<td>{{ $payment->id }}</td>
							<td>{{ $payment->created_at->format('d M Y H:i') }}</td>
							<td>
								<div class="fw-bold">{{ $payment->enrollment->user->name ?? '-' }}</div>
								<small class="text-muted">{{ $payment->enrollment->user->email ?? '-' }}</small>
							</td>
							<td>
								<div>{{ $payment->enrollment->course->title ?? '-' }}</div>
								<small class="text-muted">ID: {{ $payment->enrollment->course->id ?? '-' }}</small>
							</td>
							<td class="fw-bold text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
							<td>
								<span class="badge badge-{{ $payment->method->value == 'transfer' ? 'primary' : ($payment->method->value == 'qris' ? 'success' : ($payment->method->value == 'cash' ? 'warning' : 'info')) }}">
									{{ ucfirst($payment->method->value ?? '-') }}
								</span>
							</td>
							<td>
								@if($payment->reference)
									<small class="text-dark fw-semibold">{{ $payment->reference }}</small>
								@else
									<small class="text-muted">-</small>
								@endif
							</td>
							<td>
								@php
									$meta = $payment->meta ?? [];
									$proof = $meta['proof'] ?? $meta['proof_image'] ?? $meta['bukti'] ?? null;
									$bank = $meta['bank'] ?? $meta['bank_name'] ?? null;
									$accountNumber = $meta['account_number'] ?? $meta['no_rekening'] ?? null;
								@endphp
								@if($proof)
									<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#proofModal{{ $payment->id }}">
										<i class="ki-duotone ki-file fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										Lihat Bukti
									</button>
								@else
									<span class="text-muted">-</span>
								@endif
							</td>
							<td>
								<div class="d-flex gap-2">
									<button type="button" class="btn btn-sm btn-light-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $payment->id }}" title="Detail">
										<i class="ki-duotone ki-information fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
										</i>
									</button>
									<form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
										@csrf
										<button type="submit" name="action" value="approve" class="btn btn-sm btn-success" 
											onclick="return confirm('Setujui pembayaran ini? Enrollment akan diaktifkan.')" title="Setujui">
											<i class="ki-duotone ki-check fs-5">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</button>
									</form>
									<form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
										@csrf
										<button type="submit" name="action" value="reject" class="btn btn-sm btn-danger"
											onclick="return confirm('Tolak pembayaran ini?')" title="Tolak">
											<i class="ki-duotone ki-cross fs-5">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</button>
									</form>
								</div>
							</td>
						</tr>
						
						<!-- Modal Detail Pembayaran -->
						<div class="modal fade" id="detailModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h2 class="modal-title">Detail Pembayaran #{{ $payment->id }}</h2>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
										<div class="row mb-3">
											<div class="col-4 fw-bold">Siswa:</div>
											<div class="col-8">{{ $payment->enrollment->user->name ?? '-' }}</div>
										</div>
										<div class="row mb-3">
											<div class="col-4 fw-bold">Email:</div>
											<div class="col-8">{{ $payment->enrollment->user->email ?? '-' }}</div>
										</div>
										<div class="row mb-3">
											<div class="col-4 fw-bold">Kursus:</div>
											<div class="col-8">{{ $payment->enrollment->course->title ?? '-' }}</div>
										</div>
										<div class="row mb-3">
											<div class="col-4 fw-bold">Jumlah:</div>
											<div class="col-8 fw-bold text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
										</div>
										<div class="row mb-3">
											<div class="col-4 fw-bold">Metode:</div>
											<div class="col-8">
												<span class="badge badge-{{ $payment->method->value == 'transfer' ? 'primary' : ($payment->method->value == 'qris' ? 'success' : ($payment->method->value == 'cash' ? 'warning' : 'info')) }}">
													{{ ucfirst($payment->method->value ?? '-') }}
												</span>
											</div>
										</div>
										@if($payment->reference)
										<div class="row mb-3">
											<div class="col-4 fw-bold">Referensi:</div>
											<div class="col-8">{{ $payment->reference }}</div>
										</div>
										@endif
										@if($bank)
										<div class="row mb-3">
											<div class="col-4 fw-bold">Bank:</div>
											<div class="col-8">{{ $bank }}</div>
										</div>
										@endif
										@if($accountNumber)
										<div class="row mb-3">
											<div class="col-4 fw-bold">No. Rekening:</div>
											<div class="col-8">{{ $accountNumber }}</div>
										</div>
										@endif
										<div class="row mb-3">
											<div class="col-4 fw-bold">Tanggal:</div>
											<div class="col-8">{{ $payment->created_at->format('d M Y H:i:s') }}</div>
										</div>
										<div class="row mb-3">
											<div class="col-4 fw-bold">Status Enrollment:</div>
											<div class="col-8">
												<span class="badge badge-{{ $payment->enrollment->status->value == 'pending' ? 'warning' : ($payment->enrollment->status->value == 'active' ? 'success' : 'secondary') }}">
													{{ ucfirst($payment->enrollment->status->value ?? '-') }}
												</span>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Modal Bukti Pembayaran -->
						@if($proof)
						<div class="modal fade" id="proofModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h2 class="modal-title">Bukti Pembayaran #{{ $payment->id }}</h2>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body text-center">
										@if(str_starts_with($proof, 'http') || str_starts_with($proof, '/'))
											<img src="{{ $proof }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 500px;">
										@else
											<img src="{{ asset('storage/' . $proof) }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 500px;" onerror="this.src='{{ asset('images/placeholder.png') }}'">
										@endif
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
									</div>
								</div>
							</div>
						</div>
						@endif
					@empty
						<tr>
							<td colspan="9" class="text-center text-muted py-10">
								<div class="d-flex flex-column align-items-center">
									<i class="ki-duotone ki-information-5 fs-3x text-muted mb-3">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
									<p class="text-muted">Tidak ada pembayaran pending</p>
								</div>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-5">
			{{ $payments->links() }}
		</div>
	</div>
</div>
@endsection

