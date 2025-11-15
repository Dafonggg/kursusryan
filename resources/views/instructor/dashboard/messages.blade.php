@extends('instructor.layouts.master')

@section('title', 'Chat - Metronic')
@section('description', 'Halaman Chat untuk Instructor')

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
				<li class="breadcrumb-item text-muted">Instructor</li>
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
	use App\Models\User;
	$students = User::where('role', 'student')->orWhere('role', 'user')->get();
	$admins = User::where('role', 'admin')->get();
@endphp

<!--begin::Row - Chat List & Latest Messages-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Latest Messages / Conversations -->
	<div class="col-12 col-lg-8">
		@include('instructor.dashboard.components.latest-messages')
	</div>
	
	<!-- Start New Chat -->
	<div class="col-12 col-lg-4">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Mulai Chat Baru</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Pilih peserta atau admin</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				@if($students->count() > 0 || $admins->count() > 0)
					<form action="{{ route('instructor.chat.create') }}" method="POST">
						@csrf
						@if($students->count() > 0)
							<label class="form-label fw-bold mb-3">Peserta</label>
							@foreach($students as $student)
								<button type="submit" name="user_id" value="{{ $student->id }}" class="btn btn-light w-100 mb-2 text-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-40px me-3">
											@if($student->profile && $student->profile->photo_path)
												<img src="{{ asset('storage/' . $student->profile->photo_path) }}" alt="{{ $student->name }}" />
											@else
												<div class="symbol-label bg-light-primary">
													<span class="text-primary fw-bold">{{ substr($student->name, 0, 1) }}</span>
												</div>
											@endif
										</div>
										<span class="text-gray-900 fw-bold">{{ $student->name }}</span>
									</div>
								</button>
							@endforeach
						@endif
						
						@if($admins->count() > 0)
							<label class="form-label fw-bold mb-3 mt-5">Admin</label>
							@foreach($admins as $admin)
								<button type="submit" name="user_id" value="{{ $admin->id }}" class="btn btn-light w-100 mb-2 text-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-40px me-3">
											@if($admin->profile && $admin->profile->photo_path)
												<img src="{{ asset('storage/' . $admin->profile->photo_path) }}" alt="{{ $admin->name }}" />
											@else
												<div class="symbol-label bg-light-danger">
													<span class="text-danger fw-bold">{{ substr($admin->name, 0, 1) }}</span>
												</div>
											@endif
										</div>
										<span class="text-gray-900 fw-bold">{{ $admin->name }}</span>
									</div>
								</button>
							@endforeach
						@endif
					</form>
				@else
					<div class="text-center text-muted">
						<p>Tidak ada peserta atau admin yang tersedia</p>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
<!--end::Row - Chat List & Latest Messages-->
@endsection

