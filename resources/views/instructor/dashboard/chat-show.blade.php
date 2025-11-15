@extends('instructor.layouts.master')

@section('title', 'Chat Detail - Metronic')
@section('description', 'Detail chat untuk Instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Chat</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.messages') }}" class="text-muted text-hover-primary">Chat</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Detail</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
@php
	$otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
@endphp

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<!-- Header Chat -->
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<a href="{{ route('instructor.messages') }}" class="btn btn-sm btn-light me-3">
						<i class="ki-duotone ki-arrow-left fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</a>
					<div class="symbol symbol-40px me-3">
						@if($otherParticipant && $otherParticipant->profile && $otherParticipant->profile->photo_path)
							<img src="{{ asset('storage/' . $otherParticipant->profile->photo_path) }}" alt="{{ $otherParticipant->name }}" />
						@else
							<div class="symbol-label bg-light-primary">
								<span class="text-primary fw-bold">{{ substr($otherParticipant->name ?? 'U', 0, 1) }}</span>
							</div>
						@endif
					</div>
					<div class="d-flex flex-column">
						<span class="text-gray-900 fw-bold">{{ $otherParticipant->name ?? 'Unknown' }}</span>
						<span class="text-gray-500 fs-7">{{ ucfirst($otherParticipant->role ?? 'User') }}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Messages -->
	<div class="col-12">
		<div class="card card-flush" style="height: 500px;">
			<div class="card-body d-flex flex-column">
				@if(session('success'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{ session('success') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				@endif

				<!-- Messages Container -->
				<div class="scroll-y flex-grow-1 mb-5" id="messagesContainer" style="max-height: 400px; overflow-y: auto;">
					@foreach($conversation->messages as $message)
						<div class="d-flex mb-5 {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
							<div class="d-flex flex-column {{ $message->user_id === auth()->id() ? 'align-items-end' : 'align-items-start' }}" style="max-width: 70%;">
								@if($message->user_id !== auth()->id())
									<div class="d-flex align-items-center mb-1">
										<div class="symbol symbol-25px me-2">
											@if($message->user->profile && $message->user->profile->photo_path)
												<img src="{{ asset('storage/' . $message->user->profile->photo_path) }}" alt="{{ $message->user->name }}" />
											@else
												<div class="symbol-label bg-light-primary">
													<span class="text-primary fw-bold fs-8">{{ substr($message->user->name, 0, 1) }}</span>
												</div>
											@endif
										</div>
										<span class="text-gray-600 fs-7">{{ $message->user->name }}</span>
									</div>
								@endif
								<div class="rounded p-4 {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
									<p class="mb-0 {{ $message->user_id === auth()->id() ? 'text-white' : 'text-gray-900' }}">{{ $message->body }}</p>
								</div>
								<span class="text-gray-500 fs-8 mt-1">{{ $message->created_at->format('d M Y, H:i') }}</span>
							</div>
						</div>
					@endforeach
				</div>
				
				<!-- Message Form -->
				<form action="{{ route('instructor.chat.send', $conversation->id) }}" method="POST" class="d-flex align-items-center">
					@csrf
					<div class="flex-grow-1 me-3">
						<textarea name="body" class="form-control form-control-solid" rows="2" placeholder="Ketik pesan..." required maxlength="5000"></textarea>
					</div>
					<button type="submit" class="btn btn-primary">
						<i class="ki-duotone ki-send fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	// Auto scroll to bottom after page load
	document.addEventListener('DOMContentLoaded', function() {
		const container = document.getElementById('messagesContainer');
		if (container) {
			container.scrollTop = container.scrollHeight;
		}
	});
</script>
@endsection


