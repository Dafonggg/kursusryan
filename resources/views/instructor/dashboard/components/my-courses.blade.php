<!--
My Courses Component
Instructor Dashboard - Courses I'm Teaching
-->
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Kursus yang Saya Pegang</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $my_courses_count }} kursus aktif</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Kursus</th>
						<th>Peserta Aktif</th>
						<th>Sesi</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($my_courses ?? [] as $course)
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<div class="symbol symbol-40px me-3">
									<img src="{{ $course->course_image }}" alt="{{ $course->course_name }}" />
								</div>
								<div class="d-flex flex-column">
									<span class="text-gray-900 fw-bold">{{ $course->course_name }}</span>
									<span class="text-gray-500 fs-7">{{ $course->course_category }}</span>
								</div>
							</div>
						</td>
						<td>
							<span class="text-gray-900 fw-bold">{{ $course->active_participants }}</span>
							<span class="text-gray-500 fs-7">peserta</span>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $course->total_sessions }}</span>
							<span class="text-gray-500 fs-7">sesi</span>
						</td>
						<td>
							<div class="d-flex gap-2">
								<a href="{{ route('instructor.materials', $course->course_slug) }}" class="btn btn-sm btn-light-primary" title="Materi">
									<i class="ki-duotone ki-file fs-5">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</a>
								<a href="{{ route('instructor.students', $course->course_slug) }}" class="btn btn-sm btn-light-info" title="Peserta">
									<i class="ki-duotone ki-people fs-5">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</a>
							</div>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="4" class="text-center">
							<div class="empty-state">
								<div class="text">Tidak ada kursus aktif</div>
							</div>
						</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<!-- If no courses -->
		<div class="empty-state d-none">
			<div class="icon">
				<i class="ki-duotone ki-book fs-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
			<div class="text">Tidak ada kursus aktif</div>
		</div>
	</div>
</div>

