<!DOCTYPE html>
<!--
Student Quick Actions Page
Quick actions for student users
-->
<html lang="en">
<head>
	<base href="{{ asset('/') }}" />
	<title>Student Quick Actions - Metronic</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/logos/favicon.ico') }}" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
	<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" class="app-default">
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
				<div class="d-flex flex-column flex-column-fluid">
					<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
							<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
								<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Quick Actions</h1>
							</div>
						</div>
					</div>
					<div id="kt_app_content" class="app-content flex-column-fluid">
						<div id="kt_app_content_container" class="app-container container-xxl">
							<div class="row g-5 g-xl-10">
								<div class="col-12">
									<div class="card card-flush">
										<div class="card-header pt-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bold text-gray-900">Quick Actions</span>
												<span class="text-gray-500 mt-1 fw-semibold fs-6">Aksi cepat untuk siswa</span>
											</h3>
										</div>
										<div class="card-body pt-0">
											<div class="row g-5">
												<!-- Lanjut Belajar -->
												<div class="col-md-6 col-lg-3">
													<div class="quick-action-btn" onclick="window.location.href='/student/courses'">
														<div class="icon">
															<i class="ki-duotone ki-book fs-1 text-primary">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</div>
														<div class="text">
															<span class="fw-bold text-gray-900">Lanjut Belajar</span>
														</div>
													</div>
												</div>
												<!-- Lihat Jadwal -->
												<div class="col-md-6 col-lg-3">
													<div class="quick-action-btn" onclick="window.location.href='/student/schedule'">
														<div class="icon">
															<i class="ki-duotone ki-calendar fs-1 text-success">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</div>
														<div class="text">
															<span class="fw-bold text-gray-900">Lihat Jadwal</span>
														</div>
													</div>
												</div>
												<!-- Bayar Sekarang -->
												<div class="col-md-6 col-lg-3">
													<div class="quick-action-btn" onclick="window.location.href='/student/payments'">
														<div class="icon">
															<i class="ki-duotone ki-wallet fs-1 text-warning">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</div>
														<div class="text">
															<span class="fw-bold text-gray-900">Bayar Sekarang</span>
														</div>
													</div>
												</div>
												<!-- Hubungi Instruktur -->
												<div class="col-md-6 col-lg-3">
													<div class="quick-action-btn" onclick="window.location.href='/student/chat'">
														<div class="icon">
															<i class="ki-duotone ki-message-text-2 fs-1 text-info">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
															</i>
														</div>
														<div class="text">
															<span class="fw-bold text-gray-900">Hubungi Instruktur</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/js/scripts.bundle.js') }}"></script>
	<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/src/partialsStudent/_scripts.js') }}"></script>
</body>
</html>

