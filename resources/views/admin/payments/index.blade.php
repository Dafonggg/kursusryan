@extends('admin.layouts.master')

@section('title', 'Semua Transaksi - Admin')
@section('description', 'Daftar semua transaksi pembayaran')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Semua Transaksi</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Transaksi</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.payments.pending') }}" class="btn btn-sm fw-bold btn-warning">Pembayaran Pending</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Semua Transaksi</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Daftar semua transaksi pembayaran</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<!-- Filter Form -->
		<div class="card mb-5">
			<div class="card-body">
				<form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
							<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
							<option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
							<option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
							<option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label">Cari (Nama/Email)</label>
						<input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
					</div>
					<div class="col-md-2 d-flex align-items-end">
						<button type="submit" class="btn btn-primary w-100">Filter</button>
					</div>
				</form>
			</div>
		</div>

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
						<th>Status</th>
						<th>Tanggal Bayar</th>
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
							</td>
							<td class="fw-bold text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
							<td>
								<span class="badge badge-{{ $payment->method->value == 'transfer' ? 'primary' : ($payment->method->value == 'qris' ? 'success' : ($payment->method->value == 'cash' ? 'warning' : 'info')) }}">
									{{ ucfirst($payment->method->value ?? '-') }}
								</span>
							</td>
							<td>
								@php
									$statusClass = match($payment->status->value) {
										'pending' => 'warning',
										'paid' => 'success',
										'failed' => 'danger',
										'refunded' => 'info',
										default => 'secondary',
									};
								@endphp
								<span class="badge badge-{{ $statusClass }}">
									{{ ucfirst($payment->status->value) }}
								</span>
							</td>
							<td>
								@if($payment->paid_at)
									{{ $payment->paid_at->format('d M Y H:i') }}
								@else
									<span class="text-muted">-</span>
								@endif
							</td>
							<td>
								@php
									$meta = $payment->meta ?? [];
									$proof = $meta['proof'] ?? $meta['proof_image'] ?? $meta['bukti'] ?? null;
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
									@if($payment->status->value == 'pending')
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
									@endif
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
										<div class="row mb-3">
											<div class="col-4 fw-bold">Status:</div>
											<div class="col-8">
												@php
													$statusClass = match($payment->status->value) {
														'pending' => 'warning',
														'paid' => 'success',
														'failed' => 'danger',
														'refunded' => 'info',
														default => 'secondary',
													};
												@endphp
												<span class="badge badge-{{ $statusClass }}">
													{{ ucfirst($payment->status->value) }}
												</span>
											</div>
										</div>
										@if($payment->reference)
										<div class="row mb-3">
											<div class="col-4 fw-bold">Referensi:</div>
											<div class="col-8">{{ $payment->reference }}</div>
										</div>
										@endif
										<div class="row mb-3">
											<div class="col-4 fw-bold">Tanggal:</div>
											<div class="col-8">{{ $payment->created_at->format('d M Y H:i:s') }}</div>
										</div>
										@if($payment->paid_at)
										<div class="row mb-3">
											<div class="col-4 fw-bold">Tanggal Bayar:</div>
											<div class="col-8">{{ $payment->paid_at->format('d M Y H:i:s') }}</div>
										</div>
										@endif
										@php
											$meta = $payment->meta ?? [];
											$proof = $meta['proof'] ?? $meta['proof_image'] ?? $meta['bukti'] ?? null;
											$bank = $meta['bank'] ?? $meta['bank_name'] ?? null;
											$accountNumber = $meta['account_number'] ?? $meta['no_rekening'] ?? null;
										@endphp
										@if($proof)
										<div class="row mb-3">
											<div class="col-4 fw-bold">Bukti Pembayaran:</div>
											<div class="col-8">
												<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#proofModal{{ $payment->id }}">
													<i class="ki-duotone ki-file fs-5">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													Lihat Bukti
												</button>
											</div>
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
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Modal Bukti Pembayaran -->
						@php
							$meta = $payment->meta ?? [];
							$proof = $meta['proof'] ?? $meta['proof_image'] ?? $meta['bukti'] ?? null;
						@endphp
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
										@if(str_starts_with($proof, 'http') || str_starts_with($proof, '/'))
											<a href="{{ $proof }}" target="_blank" class="btn btn-primary">Buka di Tab Baru</a>
										@else
											<a href="{{ asset('storage/' . $proof) }}" target="_blank" class="btn btn-primary">Buka di Tab Baru</a>
										@endif
										<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
									</div>
								</div>
							</div>
						</div>
						@endif
					@empty
						<tr>
							<td colspan="10" class="text-center text-muted py-10">
								<div class="d-flex flex-column align-items-center">
									<i class="ki-duotone ki-information-5 fs-3x text-muted mb-3">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
									<p class="text-muted">Tidak ada transaksi</p>
								</div>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-5">
			{{ $payments->appends(request()->query())->links() }}
		</div>
	</div>
</div>
@endsection

