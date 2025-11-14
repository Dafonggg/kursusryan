<!--
Certificate Ready Component
Student Dashboard - Ready Certificates
-->
@php
	$cardClass = '';
	$cardHeader = '
		<span class="card-label fw-bold text-gray-900">Sertifikat Siap Diunduh</span>
		<span class="text-gray-500 mt-1 fw-semibold fs-6">' . $ready_certificates_count . ' sertifikat tersedia</span>
	';
	ob_start();
@endphp

@if(isset($ready_certificates) && count($ready_certificates) > 0)
<div class="table-responsive">
	<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
		<thead>
			<tr>
				<th>Kursus</th>
				<th>Tanggal</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			@foreach($ready_certificates as $certificate)
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<div class="symbol symbol-40px me-3">
							<img src="{{ $certificate->course_image }}" alt="{{ $certificate->course_name }}" />
						</div>
						<div class="d-flex flex-column">
							<span class="text-gray-900 fw-bold">{{ $certificate->course_name }}</span>
							<span class="text-gray-500 fs-7">{{ $certificate->course_category }}</span>
						</div>
					</div>
				</td>
				<td>
					<span class="text-gray-500 fs-7">{{ $certificate->certificate_date }}</span>
				</td>
				<td>
					<a href="{{ $certificate->certificate_url }}" class="btn btn-sm btn-primary" download>
						Unduh
					</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@else
<div class="text-center py-5">
	<div class="icon mb-3">
		<i class="ki-duotone ki-award fs-1 text-gray-400">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>
	</div>
	<div class="text-gray-500">Belum ada sertifikat tersedia</div>
</div>
@endif

@php
	$cardBody = ob_get_clean();
@endphp

@include('student.dashboard.components.layouts.component-card')
