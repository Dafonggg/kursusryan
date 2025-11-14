<!--
Continue Learning Component
Student Dashboard - Last Learned Material
-->
@php
	$cardClass = 'h-md-100';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Lanjut Belajar</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">Materi terakhir yang Anda pelajari</span>
	';
	ob_start();
@endphp

@if(isset($continue_learning))
<div class="d-flex align-items-center mb-5">
	<div class="symbol symbol-60px me-4">
		<img src="{{ $continue_learning->course_image }}" alt="{{ $continue_learning->course_name }}" />
	</div>
	<div class="d-flex flex-column">
		<span class="text-gray-900 fw-bold fs-4">{{ $continue_learning->course_name }}</span>
		<span class="text-gray-500 fs-7">{{ $continue_learning->lesson_name }}</span>
	</div>
</div>
<div class="progress progress-custom mb-3">
	<div class="progress-bar bg-primary" role="progressbar" style="width: {{ $continue_learning->progress_percentage }}%" aria-valuenow="{{ $continue_learning->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<div class="d-flex justify-content-between align-items-center">
	<span class="text-gray-500 fs-7">Progress: {{ $continue_learning->progress_percentage }}%</span>
	<a href="#" class="btn btn-sm btn-primary" onclick="continueLearning({{ $continue_learning->course_id }}, {{ $continue_learning->lesson_id }})">
		Lanjut Belajar
	</a>
</div>
@else
<div class="text-center py-5">
	<div class="text-gray-500">Belum ada materi yang dipelajari</div>
</div>
@endif

@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')

