<!--
Income Chart Component
Admin Dashboard - 12 Month Income Chart
-->
<div class="card card-flush">
	<div class="card-header pt-7 pb-3">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Grafik Pendapatan 12 Bulan</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Trend pendapatan tahun ini</span>
		</h3>
		<div class="card-toolbar">
			<button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
				<i class="ki-duotone ki-dots-square fs-1">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
					<span class="path4"></span>
				</i>
			</button>
		</div>
	</div>
	<div class="card-body pt-0 pb-5">
		<div class="chart-container" style="position: relative; height: 350px;">
			<canvas id="kt_income_chart"></canvas>
		</div>
	</div>

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		@php
			$months = isset($incomeData) && isset($incomeData['months']) ? $incomeData['months'] : [];
			$income = isset($incomeData) && isset($incomeData['income']) ? $incomeData['income'] : [];
		@endphp
		
		var incomeMonths = @json($months);
		var incomeData = @json($income);
		
		if (typeof Chart !== 'undefined' && incomeMonths.length > 0 && incomeData.length > 0) {
			var ctx = document.getElementById('kt_income_chart');
			if (ctx) {
				new Chart(ctx, {
					type: 'line',
					data: {
						labels: incomeMonths,
						datasets: [{
							label: 'Pendapatan (Rp)',
							data: incomeData,
							borderColor: '#009ef7',
							backgroundColor: 'rgba(0, 158, 247, 0.1)',
							borderWidth: 2,
							fill: true,
							tension: 0.4
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: true,
						aspectRatio: 2.5,
						plugins: {
							legend: {
								display: true,
								position: 'top',
							},
							tooltip: {
								callbacks: {
									label: function(context) {
										return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
									}
								}
							}
						},
						scales: {
							y: {
								beginAtZero: true,
								ticks: {
									callback: function(value) {
										if (value >= 1000000) {
											return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
										} else if (value >= 1000) {
											return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
										}
										return 'Rp ' + value;
									}
								}
							}
						}
					}
				});
			}
		} else {
			console.warn('Chart.js not loaded or no data available');
		}
	});
</script>
@endpush

