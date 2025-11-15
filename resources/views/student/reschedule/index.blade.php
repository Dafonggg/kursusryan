@extends('student.layouts.master')

@section('title', 'Reschedule | Kursus Ryan Komputer')
@section('description', 'Ajukan perubahan jadwal kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Reschedule</h1>
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
				<li class="breadcrumb-item text-gray-900">Reschedule</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
@endif

@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		{{ session('error') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
@endif

<!--begin::Row - Form Reschedule & History-->
<div class="row gx-5 gx-xl-10 mb-5 mb-xl-10">
	<!-- Form Reschedule -->
	<div class="col-12 col-lg-6">
		<div class="card card-flush h-100">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Ajukan Reschedule</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Pilih sesi yang ingin diubah jadwalnya</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<form action="{{ route('student.reschedule.store') }}" method="POST" id="rescheduleForm">
					@csrf
					<div class="mb-5">
						<label class="form-label required">Pilih Sesi</label>
						<select name="course_session_id" id="course_session_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih sesi kursus" required>
							<option value="">-- Pilih Sesi --</option>
							@foreach($enrollments as $enrollment)
								@if($enrollment->course->sessions->count() > 0)
									<optgroup label="{{ $enrollment->course->title }}">
										@foreach($enrollment->course->sessions as $session)
											<option value="{{ $session->id }}" data-scheduled="{{ $session->scheduled_at->format('Y-m-d H:i') }}">
												{{ $session->title }} - {{ $session->scheduled_at->format('d M Y, H:i') }}
											</option>
										@endforeach
									</optgroup>
								@endif
							@endforeach
						</select>
						@error('course_session_id')
							<div class="text-danger mt-1">{{ $message }}</div>
						@enderror
					</div>
					
					<div class="mb-5">
						<label class="form-label required">Jadwal Baru</label>
						<input type="datetime-local" name="proposed_at" id="proposed_at" class="form-control form-control-solid" required min="{{ date('Y-m-d\TH:i') }}" />
						<div class="form-text">Pilih tanggal dan waktu untuk jadwal baru</div>
						@error('proposed_at')
							<div class="text-danger mt-1">{{ $message }}</div>
						@enderror
					</div>
					
					<div class="mb-5">
						<label class="form-label required">Alasan</label>
						<textarea name="reason" id="reason" class="form-control form-control-solid" rows="4" placeholder="Jelaskan alasan mengapa Anda perlu mengubah jadwal..." required maxlength="500"></textarea>
						<div class="form-text">Maksimal 500 karakter</div>
						@error('reason')
							<div class="text-danger mt-1">{{ $message }}</div>
						@enderror
					</div>
					
					<div class="d-flex justify-content-end">
						<button type="submit" class="btn btn-primary">
							<i class="ki-duotone ki-check fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							Ajukan Reschedule
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- History Reschedule -->
	<div class="col-12 col-lg-6">
		<div class="card card-flush h-100">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Riwayat Reschedule</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $rescheduleRequests->count() }} permintaan</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				@if($rescheduleRequests->count() > 0)
					<div class="table-responsive">
						<table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
							<thead>
								<tr class="fw-bold text-muted">
									<th class="min-w-150px">Sesi</th>
									<th class="min-w-120px">Jadwal Baru</th>
									<th class="min-w-100px">Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach($rescheduleRequests as $request)
									<tr>
										<td>
											<span class="text-gray-900 fw-semibold d-block">{{ $request->session->title }}</span>
											<span class="text-gray-500 fs-7">{{ $request->session->course->title }}</span>
										</td>
										<td>
											<span class="text-gray-900 fw-semibold">{{ $request->proposed_at->format('d M Y, H:i') }}</span>
										</td>
										<td>
											@if($request->status->value === 'pending')
												<span class="badge badge-warning">Pending</span>
											@elseif($request->status->value === 'approved')
												<span class="badge badge-success">Disetujui</span>
											@else
												<span class="badge badge-danger">Ditolak</span>
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@else
					<div class="text-center py-10">
						<div class="text-gray-500">Belum ada permintaan reschedule</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
<!--end::Row - Form Reschedule & History-->
@endsection


