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
@php
	$otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
	use App\Models\User;
	use App\Models\Conversation;
	$user = auth()->user();
	$conversations = Conversation::whereHas('participants', function($query) use ($user) {
			$query->where('user_id', $user->id);
		})
		->with(['participants', 'latestMessage.user'])
		->withCount('messages')
		->orderBy('updated_at', 'desc')
		->get();
	$admins = User::where('role', 'admin')->get();
	$instructors = User::where('role', 'instructor')->get();
@endphp

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
						@foreach($conversations as $conv)
							@php
								$otherPart = $conv->participants->where('id', '!=', auth()->id())->first();
								$latestMsg = $conv->latestMessage;
							@endphp
							<a href="{{ route('student.chat.show', $conv->id) }}" class="d-flex align-items-center p-3 rounded mb-2 {{ $conversation->id == $conv->id ? 'bg-light-primary' : 'bg-light' }}">
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
					<a href="{{ route('student.chat') }}" class="btn btn-sm btn-light me-3">
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
				<form action="{{ route('student.chat.send-message', $conversation->id) }}" method="POST" class="d-flex align-items-center">
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


