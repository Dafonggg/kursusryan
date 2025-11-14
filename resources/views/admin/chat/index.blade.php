@extends('admin.layouts.master')

@section('title', 'Chat - Admin')
@section('description', 'Pesan dengan user dan instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Chat</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Chat</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.chat.create') }}" class="btn btn-sm fw-bold btn-primary">Pesan Baru</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Percakapan</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $conversations->count() }} percakapan</span>
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
						<th>Peserta</th>
						<th>Pesan Terakhir</th>
						<th>Waktu</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($conversations as $conversation)
						@php
							$otherParticipant = $conversation->participants->firstWhere('id', '!=', auth()->id());
							$avatar = ($otherParticipant && $otherParticipant->profile && $otherParticipant->profile->photo_path)
								? asset('storage/' . $otherParticipant->profile->photo_path)
								: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
							$unreadCount = $conversation->messages_count ?? 0;
						@endphp
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="symbol symbol-40px me-3">
										<img src="{{ $avatar }}" alt="{{ $otherParticipant->name ?? 'Unknown' }}" class="rounded-circle" />
										@if($unreadCount > 0)
											<span class="badge badge-circle badge-danger position-absolute translate-middle top-0 start-100">{{ $unreadCount }}</span>
										@endif
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-900 fw-bold">{{ $otherParticipant->name ?? 'Unknown' }}</span>
										<span class="text-gray-500 fs-7">{{ $otherParticipant->email ?? '-' }}</span>
									</div>
								</div>
							</td>
							<td>
								@if($conversation->latestMessage)
									<span class="text-gray-900">{{ mb_substr($conversation->latestMessage->body, 0, 50) }}{{ mb_strlen($conversation->latestMessage->body) > 50 ? '...' : '' }}</span>
								@else
									<span class="text-muted">Belum ada pesan</span>
								@endif
							</td>
							<td>
								@if($conversation->latestMessage)
									<span class="text-gray-500 fs-7">{{ $conversation->latestMessage->created_at->diffForHumans() }}</span>
								@else
									<span class="text-muted">-</span>
								@endif
							</td>
							<td>
								@if($unreadCount > 0)
									<span class="badge badge-light-danger">Pesan Baru</span>
								@else
									<span class="badge badge-light-success">Terbaca</span>
								@endif
							</td>
							<td>
								<a href="{{ route('admin.chat.show', $conversation->id) }}" class="btn btn-sm btn-primary">
									Buka Chat
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center text-muted">Belum ada percakapan</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

