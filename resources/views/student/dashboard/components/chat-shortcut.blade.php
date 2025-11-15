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
	@if(isset($chat_shortcut))
	<div class="mb-4">
		<div class="d-flex align-items-center mb-3">
			<div class="symbol symbol-40px me-3">
				<img src="{{ $chat_shortcut->instructor_avatar }}" alt="{{ $chat_shortcut->instructor_name }}" class="rounded" />
			</div>
			<div class="d-flex flex-column">
				<span class="text-gray-900 fw-bold">{{ $chat_shortcut->instructor_name }}</span>
				<span class="text-gray-500 fs-7">Instruktur</span>
			</div>
			<a href="#" class="btn btn-sm btn-primary ms-auto" onclick="chatInstructor({{ $chat_shortcut->instructor_id }})">
				Chat
			</a>
		</div>
	</div>
	<div class="separator my-4"></div>
	@endif
	<div>
		<div class="d-flex align-items-center">
			<div class="symbol symbol-40px me-3">
				@if(isset($admin_shortcut) && $admin_shortcut->admin_avatar)
					<img src="{{ $admin_shortcut->admin_avatar }}" alt="{{ $admin_shortcut->admin_name ?? 'Admin Support' }}" class="rounded" />
				@else
					<i class="ki-duotone ki-user fs-2 text-primary">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				@endif
			</div>
			<div class="d-flex flex-column">
				<span class="text-gray-900 fw-bold">{{ isset($admin_shortcut) ? $admin_shortcut->admin_name : 'Admin Support' }}</span>
				<span class="text-gray-500 fs-7">Tim Admin</span>
			</div>
			<a href="#" class="btn btn-sm btn-primary ms-auto" onclick="chatAdmin()">
				Chat
			</a>
		</div>
	</div>
</div>
@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')
