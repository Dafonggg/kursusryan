@extends('student.layouts.master')

@section('title', 'Materi Online | Kursus Ryan Komputer')
@section('description', 'Daftar kursus dan materi yang tersedia')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Materi Online</h1>
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
				<li class="breadcrumb-item text-gray-900">Materi Online</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
@if($enrollments->count() > 0)
	<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
		@foreach($enrollments as $enrollment)
			<div class="col-12 col-md-6 col-lg-4">
				<div class="card card-flush h-100">
					<div class="card-header pt-5">
						<div class="card-title">
							<div class="symbol symbol-50px me-3">
								@if($enrollment->course->image)
									<img src="{{ asset('storage/' . $enrollment->course->image) }}" alt="{{ $enrollment->course->title }}" class="w-100" />
								@else
									<div class="symbol-label bg-light-primary">
										<i class="ki-duotone ki-book fs-2x text-primary">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</div>
								@endif
							</div>
							<div class="d-flex flex-column">
								<span class="text-gray-900 fw-bold fs-5">{{ $enrollment->course->title }}</span>
								<span class="text-gray-500 fs-7">{{ $enrollment->course->materials->count() }} materi tersedia</span>
							</div>
						</div>
					</div>
					<div class="card-body pt-0">
						<p class="text-gray-600 fs-6 mb-4">{{ \Illuminate\Support\Str::limit($enrollment->course->description, 100) }}</p>
						<div class="d-flex justify-content-between align-items-center">
							<span class="badge badge-light-primary">{{ ucfirst($enrollment->modality) }}</span>
							<a href="{{ route('student.materials.course', $enrollment->course->slug) }}" class="btn btn-sm btn-primary">
								Lihat Materi
								<i class="ki-duotone ki-arrow-right fs-3 ms-1">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</a>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
@else
	<div class="card card-flush">
		<div class="card-body text-center py-10">
			<div class="mb-5">
				<i class="ki-duotone ki-information-5 fs-3x text-gray-400">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
				</i>
			</div>
			<h3 class="text-gray-900 fw-bold mb-3">Belum Ada Kursus</h3>
			<p class="text-gray-500 mb-5">Anda belum terdaftar di kursus manapun. Silakan daftar kursus terlebih dahulu.</p>
			<a href="{{ route('daftar-kursus') }}" class="btn btn-primary">Lihat Kursus Tersedia</a>
		</div>
	</div>
@endif
@endsection

