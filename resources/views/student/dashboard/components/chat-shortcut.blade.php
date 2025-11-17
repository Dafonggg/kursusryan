<!--
Chat Shortcut Component
Student Dashboard - Chat with Instructor/Admin
-->
@php
	$cardClass = '';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Chat dengan Instruktur/Admin</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">Hubungi instruktur atau admin</span>
	';
	ob_start();
@endphp
<div class="d-flex flex-column">
	@if(isset($chat_shortcut) && $chat_shortcut)
	<div class="mb-4">
		<div class="d-flex align-items-center mb-3">
			<div class="symbol symbol-40px me-3">
				@if($chat_shortcut->instructor_avatar)
					<img src="{{ $chat_shortcut->instructor_avatar }}" alt="{{ $chat_shortcut->instructor_name }}" class="rounded" />
				@else
					<div class="symbol-label bg-light-primary">
						<i class="ki-duotone ki-user fs-2 text-primary">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				@endif
			</div>
			<div class="d-flex flex-column flex-grow-1">
				<span class="text-gray-900 fw-bold">{{ $chat_shortcut->instructor_name }}</span>
				<span class="text-gray-500 fs-7">Instruktur</span>
			</div>
			<form action="{{ route('student.chat.create') }}" method="POST" class="ms-auto">
				@csrf
				<input type="hidden" name="user_id" value="{{ $chat_shortcut->instructor_id }}">
				<button type="submit" class="btn btn-sm btn-primary">
					Chat
				</button>
			</form>
		</div>
	</div>
	@if(isset($admin_shortcut) && $admin_shortcut)
	<div class="separator my-4"></div>
	@endif
	@endif
	@if(isset($admin_shortcut) && $admin_shortcut)
	<div>
		<div class="d-flex align-items-center">
			<div class="symbol symbol-40px me-3">
				@if($admin_shortcut->admin_avatar)
					<img src="{{ $admin_shortcut->admin_avatar }}" alt="{{ $admin_shortcut->admin_name }}" class="rounded" />
				@else
					<div class="symbol-label bg-light-primary">
						<i class="ki-duotone ki-user fs-2 text-primary">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				@endif
			</div>
			<div class="d-flex flex-column flex-grow-1">
				<span class="text-gray-900 fw-bold">{{ $admin_shortcut->admin_name }}</span>
				<span class="text-gray-500 fs-7">Tim Admin</span>
			</div>
			<form action="{{ route('student.chat.create') }}" method="POST" class="ms-auto">
				@csrf
				<input type="hidden" name="user_id" value="{{ $admin_shortcut->admin_id }}">
				<button type="submit" class="btn btn-sm btn-primary">
					Chat
				</button>
			</form>
		</div>
	</div>
	@else
	<div>
		<div class="d-flex align-items-center">
			<div class="symbol symbol-40px me-3">
				<div class="symbol-label bg-light-primary">
					<i class="ki-duotone ki-user fs-2 text-primary">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="d-flex flex-column flex-grow-1">
				<span class="text-gray-900 fw-bold">Admin Support</span>
				<span class="text-gray-500 fs-7">Tim Admin</span>
			</div>
			<a href="{{ route('student.chat') }}" class="btn btn-sm btn-primary ms-auto">
				Chat
			</a>
		</div>
	</div>
	@endif
</div>
@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')
