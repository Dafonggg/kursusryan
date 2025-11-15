@extends('student.layouts.master')

@section('title', 'Chat | Kursus Ryan Komputer')
@section('description', 'Chat dengan instruktur atau admin')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Chat</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Student</li>
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
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
	<!-- Daftar Conversation -->
	<div class="col-12 col-lg-4">
		<div class="card card-flush h-100">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Percakapan</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $conversations->count() }} percakapan</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<!-- Mulai Chat Baru -->
				<div class="mb-5">
					<button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#newChatModal">
						<i class="ki-duotone ki-plus fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Mulai Chat Baru
					</button>
				</div>
				
				<!-- List Conversation -->
				@if($conversations->count() > 0)
					<div class="separator my-4"></div>
					<div class="scroll-y" style="max-height: 600px;">
						@foreach($conversations as $conversation)
							@php
								$otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
								$latestMessage = $conversation->latestMessage;
							@endphp
							<a href="{{ route('student.chat.show', $conversation->id) }}" class="d-flex align-items-center p-3 rounded mb-2 {{ request()->route('conversationId') == $conversation->id ? 'bg-light-primary' : 'bg-light' }}">
								<div class="symbol symbol-40px me-3">
									@if($otherParticipant && $otherParticipant->profile && $otherParticipant->profile->photo_path)
										<img src="{{ asset('storage/' . $otherParticipant->profile->photo_path) }}" alt="{{ $otherParticipant->name }}" />
									@else
										<div class="symbol-label bg-light-primary">
											<span class="text-primary fw-bold">{{ substr($otherParticipant->name ?? 'U', 0, 1) }}</span>
										</div>
									@endif
								</div>
								<div class="d-flex flex-column flex-grow-1">
									<span class="text-gray-900 fw-bold">{{ $otherParticipant->name ?? 'Unknown' }}</span>
									@if($latestMessage)
										<span class="text-gray-500 fs-7">{{ \Illuminate\Support\Str::limit($latestMessage->body, 50) }}</span>
									@else
										<span class="text-gray-500 fs-7">Belum ada pesan</span>
									@endif
								</div>
								@if($latestMessage)
									<span class="text-gray-500 fs-8">{{ $latestMessage->created_at->diffForHumans() }}</span>
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
	
	<!-- Area Chat (akan diisi oleh show.blade.php atau default message) -->
	<div class="col-12 col-lg-8">
		<div class="card card-flush h-100">
			<div class="card-body d-flex align-items-center justify-content-center" style="min-height: 500px;">
				<div class="text-center">
					<i class="ki-duotone ki-chat fs-3x text-gray-400 mb-5">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
					<h3 class="text-gray-900 fw-bold mb-3">Pilih Percakapan</h3>
					<p class="text-gray-500">Pilih percakapan dari daftar di sebelah kiri atau mulai chat baru</p>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Mulai Chat Baru -->
<div class="modal fade" id="newChatModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="fw-bold">Mulai Chat Baru</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<form action="{{ route('student.chat.create') }}" method="POST">
				@csrf
				<div class="modal-body">
					<!-- Admin -->
					@if($admins->count() > 0)
						<div class="mb-5">
							<label class="form-label fw-bold">Admin</label>
							@foreach($admins as $admin)
								<button type="submit" name="user_id" value="{{ $admin->id }}" class="btn btn-light w-100 mb-2 text-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-40px me-3">
											@if($admin->profile && $admin->profile->photo_path)
												<img src="{{ asset('storage/' . $admin->profile->photo_path) }}" alt="{{ $admin->name }}" />
											@else
												<div class="symbol-label bg-light-primary">
													<span class="text-primary fw-bold">{{ substr($admin->name, 0, 1) }}</span>
												</div>
											@endif
										</div>
										<div>
											<span class="text-gray-900 fw-bold">{{ $admin->name }}</span>
											<span class="text-gray-500 fs-7 d-block">Admin</span>
										</div>
									</div>
								</button>
							@endforeach
						</div>
					@endif
					
					<!-- Instruktur -->
					@if($instructors->count() > 0)
						<div>
							<label class="form-label fw-bold">Instruktur</label>
							@foreach($instructors as $instructor)
								<button type="submit" name="user_id" value="{{ $instructor->id }}" class="btn btn-light w-100 mb-2 text-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-40px me-3">
											@if($instructor->profile && $instructor->profile->photo_path)
												<img src="{{ asset('storage/' . $instructor->profile->photo_path) }}" alt="{{ $instructor->name }}" />
											@else
												<div class="symbol-label bg-light-info">
													<span class="text-info fw-bold">{{ substr($instructor->name, 0, 1) }}</span>
												</div>
											@endif
										</div>
										<div>
											<span class="text-gray-900 fw-bold">{{ $instructor->name }}</span>
											<span class="text-gray-500 fs-7 d-block">Instruktur</span>
										</div>
									</div>
								</button>
							@endforeach
						</div>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

