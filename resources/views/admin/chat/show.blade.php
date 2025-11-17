@extends('admin.layouts.master')

@section('title', 'Chat Detail - Admin')
@section('description', 'Detail percakapan')

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
	</div>
</div>
@endsection

@section('content')
@php
	$otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
@endphp

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<!-- Daftar Conversation -->
	<div class="col-12 col-lg-4">
		<div class="card card-flush h-100">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Percakapan</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $conversations->count() ?? 0 }} percakapan</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<!-- Mulai Chat Baru -->
				<div class="mb-5">
					<a href="{{ route('admin.chat.create') }}" class="btn btn-primary w-100">
						<i class="ki-duotone ki-plus fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Mulai Chat Baru
					</a>
				</div>
				
				<!-- List Conversation -->
				@if(isset($conversations) && $conversations->count() > 0)
					<div class="separator my-4"></div>
					<div class="scroll-y" style="max-height: 600px;">
						@foreach($conversations as $conv)
							@php
								$otherPart = $conv->participants->where('id', '!=', auth()->id())->first();
								$latestMsg = $conv->latestMessage;
							@endphp
							<a href="{{ route('admin.chat.show', $conv->id) }}" class="d-flex align-items-center p-3 rounded mb-2 {{ $conversation->id == $conv->id ? 'bg-light-primary' : 'bg-light' }}">
								<div class="symbol symbol-40px me-3">
									@if($otherPart && $otherPart->profile && $otherPart->profile->photo_path)
										<img src="{{ asset('storage/' . $otherPart->profile->photo_path) }}" alt="{{ $otherPart->name }}" />
									@else
										<div class="symbol-label bg-light-primary">
											<span class="text-primary fw-bold">{{ substr($otherPart->name ?? 'U', 0, 1) }}</span>
										</div>
									@endif
								</div>
								<div class="d-flex flex-column flex-grow-1">
									<span class="text-gray-900 fw-bold">{{ $otherPart->name ?? 'Unknown' }}</span>
									@if($latestMsg)
										<span class="text-gray-500 fs-7">{{ \Illuminate\Support\Str::limit($latestMsg->body, 50) }}</span>
									@else
										<span class="text-gray-500 fs-7">Belum ada pesan</span>
									@endif
								</div>
								@if($latestMsg)
									<span class="text-gray-500 fs-8">{{ $latestMsg->created_at->diffForHumans() }}</span>
								@endif
							</a>
						@endforeach
					</div>
				@else
					<div class="text-center py-10">
						<div class="text-gray-500">Belum ada percakapan</div>
					</div>
				@endif
			</div>
		</div>
	</div>
	
	<!-- Area Chat -->
	<div class="col-12 col-lg-8">
		<!-- Header Chat -->
		<div class="card card-flush mb-5">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<a href="{{ route('admin.chat.index') }}" class="btn btn-sm btn-light me-3">
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
		
		<!-- Messages -->
		<div class="card card-flush" style="height: 500px;">
			<div class="card-body d-flex flex-column">
				<!-- Messages Container -->
				<div class="scroll-y flex-grow-1 mb-5" id="messagesContainer" style="max-height: 400px;">
					@foreach($messages as $message)
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
				<form action="{{ route('admin.chat.send-message', $conversation->id) }}" method="POST" class="d-flex align-items-center">
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

@if(session('success'))
	<script>
		// Auto scroll to bottom after sending message
		document.addEventListener('DOMContentLoaded', function() {
			const container = document.getElementById('messagesContainer');
			if (container) {
				container.scrollTop = container.scrollHeight;
			}
		});
	</script>
@endif
@endsection

