@extends('admin.layouts.master')

@section('title', 'Chat Detail - Admin')
@section('description', 'Detail percakapan')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
				Chat dengan {{ $otherParticipants->first()->name ?? 'Unknown' }}
			</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.chat.index') }}" class="text-muted text-hover-primary">Chat</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Detail</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.chat.index') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Percakapan</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $messages->count() }} pesan</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<!-- Messages Container -->
		<div class="d-flex flex-column gap-5 mb-10" style="max-height: 500px; overflow-y: auto;">
			@forelse($messages as $message)
				<div class="d-flex {{ $message->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
					<div class="d-flex flex-column {{ $message->user_id == auth()->id() ? 'align-items-end' : 'align-items-start' }}" style="max-width: 70%;">
						<div class="d-flex align-items-center gap-2 mb-1">
							@if($message->user_id != auth()->id())
								<div class="symbol symbol-30px">
									@php
										$avatar = ($message->user->profile && $message->user->profile->photo_path)
											? asset('storage/' . $message->user->profile->photo_path)
											: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
									@endphp
									<img src="{{ $avatar }}" alt="{{ $message->user->name }}" class="rounded-circle" />
								</div>
							@endif
							<span class="text-gray-500 fs-7">{{ $message->user->name }}</span>
							<span class="text-gray-400 fs-8">{{ $message->created_at->format('d M Y H:i') }}</span>
						</div>
						<div class="p-5 rounded {{ $message->user_id == auth()->id() ? 'bg-light-primary text-primary' : 'bg-light-info text-info' }}">
							{{ $message->body }}
						</div>
					</div>
				</div>
			@empty
				<div class="text-center text-muted py-10">
					Belum ada pesan dalam percakapan ini
				</div>
			@endforelse
		</div>

		<!-- Send Message Form -->
		<form action="{{ route('admin.chat.send-message', $conversation->id) }}" method="POST" class="mt-5">
			@csrf
			<div class="d-flex gap-2">
				<textarea name="body" class="form-control @error('body') is-invalid @enderror" 
					rows="3" placeholder="Tulis pesan Anda di sini..." required>{{ old('body') }}</textarea>
				<button type="submit" class="btn btn-primary">Kirim</button>
			</div>
			@error('body')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</form>
	</div>
</div>

<script>
	// Auto scroll to bottom
	document.addEventListener('DOMContentLoaded', function() {
		const messagesContainer = document.querySelector('[style*="overflow-y: auto"]');
		if (messagesContainer) {
			messagesContainer.scrollTop = messagesContainer.scrollHeight;
		}
	});
</script>
@endsection

