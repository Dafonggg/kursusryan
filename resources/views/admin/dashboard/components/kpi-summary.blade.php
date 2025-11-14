<!--
KPI Summary Component
Admin Dashboard - KPI Summary Widget
-->
<div class="card card-flush">
	<div class="card-header pt-7 pb-3">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">KPI Summary</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Ringkasan kinerja bulan ini</span>
		</h3>
	</div>
	<div class="card-body pt-0 pb-5">
		<div class="row g-5">
			<!-- Total Pendapatan -->
			<div class="col-md-6 col-lg-3">
				<div class="card card-flush kpi-card kpi-success dashboard-widget">
					<div class="card-header pt-5">
						<div class="card-title d-flex flex-column">
							@php
								$income = isset($kpiData) ? $kpiData['current_month_income'] : 0;
								$incomeText = $income >= 1000000 ? number_format($income / 1000000, 1) . 'juta' : number_format($income / 1000, 0) . 'rb';
								$incomeChange = isset($kpiData) ? $kpiData['income_change'] : 0;
								$incomeChangeClass = $incomeChange >= 0 ? 'success' : 'danger';
								$incomeChangeIcon = $incomeChange >= 0 ? 'arrow-up' : 'arrow-down';
							@endphp
							<span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">Rp {{ $incomeText }}</span>
							<span class="text-gray-500 pt-1 fw-semibold fs-6">Total Pendapatan (Bulan Ini)</span>
						</div>
					</div>
					<div class="card-body pt-0 pb-5">
						@if($incomeChange != 0)
							<span class="badge badge-light-{{ $incomeChangeClass }} fs-base">
								<i class="ki-duotone ki-{{ $incomeChangeIcon }} fs-5 text-{{ $incomeChangeClass }} ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
								</i>{{ abs($incomeChange) }}%
						</span>
						<span class="text-gray-500 fw-semibold fs-7 ms-2">vs bulan lalu</span>
						@else
							<span class="text-gray-500 fw-semibold fs-7">Tidak ada perubahan</span>
						@endif
					</div>
				</div>
			</div>
			<!-- Pembayaran Pending -->
			<div class="col-md-6 col-lg-3">
				<div class="card card-flush kpi-card kpi-warning dashboard-widget">
					<div class="card-header pt-5">
						<div class="card-title d-flex flex-column">
							@php
								$pendingCount = isset($kpiData) ? $kpiData['pending_payments_count'] : 0;
								$pendingTotal = isset($kpiData) ? $kpiData['pending_payments_total'] : 0;
							@endphp
							<span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ $pendingCount }}</span>
							<span class="text-gray-500 pt-1 fw-semibold fs-6">Pembayaran Pending</span>
						</div>
					</div>
					<div class="card-body pt-0 pb-5">
						<span class="text-gray-500 fw-semibold fs-7">Total: Rp {{ number_format($pendingTotal, 0, ',', '.') }}</span>
					</div>
				</div>
			</div>
			<!-- Enrol Aktif & Kadaluarsa -->
			<div class="col-md-6 col-lg-3">
				<div class="card card-flush kpi-card kpi-primary dashboard-widget">
					<div class="card-header pt-5">
						<div class="card-title d-flex flex-column">
							@php
								$activeEnroll = isset($kpiData) ? $kpiData['active_enrollments'] : 0;
								$expiringWeek = isset($kpiData) ? $kpiData['expiring_this_week'] : 0;
							@endphp
							<span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ $activeEnroll }}</span>
							<span class="text-gray-500 pt-1 fw-semibold fs-6">Enrol Aktif</span>
						</div>
					</div>
					<div class="card-body pt-0 pb-5">
						@if($expiringWeek > 0)
							<span class="text-danger fw-semibold fs-7">{{ $expiringWeek }} kadaluarsa minggu ini</span>
						@else
							<span class="text-success fw-semibold fs-7">Tidak ada yang kadaluarsa</span>
						@endif
					</div>
				</div>
			</div>
			<!-- Kursus, Instruktur, User Baru -->
			<div class="col-md-6 col-lg-3">
				<div class="card card-flush kpi-card kpi-danger dashboard-widget">
					<div class="card-header pt-5">
						<div class="card-title d-flex flex-column">
							@php
								$activeCourses = isset($kpiData) ? $kpiData['active_courses'] : 0;
								$activeInstructors = isset($kpiData) ? $kpiData['active_instructors'] : 0;
								$newUsers = isset($kpiData) ? $kpiData['new_users'] : 0;
							@endphp
							<span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ $activeCourses }}</span>
							<span class="text-gray-500 pt-1 fw-semibold fs-6">Kursus Aktif</span>
						</div>
					</div>
					<div class="card-body pt-0 pb-5">
						<div class="d-flex flex-column">
							<span class="text-gray-500 fw-semibold fs-7">{{ $activeInstructors }} Instruktur Aktif</span>
							<span class="text-gray-500 fw-semibold fs-7">{{ $newUsers }} User Baru (7 hari)</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

