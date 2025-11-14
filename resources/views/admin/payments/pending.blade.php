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
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($payments as $payment)
						<tr>
							<td>{{ $payment->id }}</td>
							<td>{{ $payment->created_at->format('d M Y H:i') }}</td>
							<td>
								<div>{{ $payment->enrollment->user->name ?? '-' }}</div>
								<small class="text-muted">{{ $payment->enrollment->user->email ?? '-' }}</small>
							</td>
							<td>{{ $payment->enrollment->course->title ?? '-' }}</td>
							<td class="fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
							<td>
								<span class="badge badge-info">{{ ucfirst($payment->method->value ?? '-') }}</span>
							</td>
							<td>
								<small class="text-muted">{{ $payment->reference ?? '-' }}</small>
							</td>
							<td>
								<form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" name="action" value="approve" class="btn btn-sm btn-success" 
										onclick="return confirm('Setujui pembayaran ini?')">
										<i class="ki-duotone ki-check fs-5"></i> Setujui
									</button>
								</form>
								<form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" name="action" value="reject" class="btn btn-sm btn-danger"
										onclick="return confirm('Tolak pembayaran ini?')">
										<i class="ki-duotone ki-cross fs-5"></i> Tolak
									</button>
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="8" class="text-center text-muted">Tidak ada pembayaran pending</td>
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

