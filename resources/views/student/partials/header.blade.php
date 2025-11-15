<!--begin::Header-->
<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
	<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
		<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
			<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
				<i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
		</div>
		<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
			<a href="{{ route('student.dashboard') }}" class="d-flex align-items-center text-decoration-none">
				<img alt="Logo" src="{{ asset('images/logokrk.png') }}" class="h-35px d-lg-none" />
				<img alt="Logo" src="{{ asset('images/logokrk.png') }}" class="h-30px d-none d-lg-block" />
			</a>
		</div>
		<div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1 ms-auto" id="kt_app_header_wrapper">
			<div class="app-navbar flex-shrink-0 d-flex align-items-center gap-2">
				<!-- Profile Button -->
				<div class="app-navbar-item" id="kt_header_user_menu_toggle">
					@php
						$userAvatar = (Auth::user()->profile && Auth::user()->profile->photo_path)
							? asset('storage/' . Auth::user()->profile->photo_path)
							: asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
					@endphp
					<div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
						<img src="{{ $userAvatar }}" class="rounded-3" alt="{{ Auth::user()->name }}" />
					</div>
					<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
						<div class="menu-item px-3">
							<div class="menu-content d-flex align-items-center px-3">
								<div class="symbol symbol-50px me-5">
									<img alt="{{ Auth::user()->name }}" src="{{ $userAvatar }}" />
								</div>
								<div class="d-flex flex-column">
									<div class="fw-bold d-flex align-items-center fs-5">
										{{ Auth::user()->name }}
									</div>
									<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
										{{ Auth::user()->email }}
									</a>
									<span class="text-muted fs-8 mt-1">
										@if(Auth::user()->role == 'admin')
											Administrator
										@elseif(Auth::user()->role == 'instructor')
											Instructor
										@elseif(Auth::user()->role == 'student' || Auth::user()->role == 'user')
											Student
										@endif
									</span>
								</div>
							</div>
						</div>
						<div class="separator my-2"></div>
						<div class="menu-item px-5">
							<a href="{{ route('student.dashboard') }}" class="menu-link px-5">
								<i class="ki-duotone ki-element-11 fs-6 me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								Dashboard
							</a>
						</div>
						<div class="menu-item px-5">
							<a href="{{ route('student.profile') }}" class="menu-link px-5">
								<i class="ki-duotone ki-profile-user fs-6 me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								Profil Saya
							</a>
						</div>
						<div class="menu-item px-5">
							<a href="{{ route('student.my-courses') }}" class="menu-link px-5">
								<i class="ki-duotone ki-book fs-6 me-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								Kursus Saya
							</a>
						</div>
						<div class="menu-item px-5">
							<a href="{{ route('home') }}" class="menu-link px-5">
								<i class="ki-duotone ki-home-2 fs-6 me-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								Kembali ke Beranda
							</a>
						</div>
						<div class="separator my-2"></div>
						<div class="menu-item px-5">
							<form action="{{ route('logout') }}" method="POST" class="w-100 m-0">
								@csrf
								<button type="submit" class="menu-link px-5 w-100 text-start border-0 bg-transparent text-gray-700 fw-semibold fs-6" style="cursor: pointer; padding: 0.75rem 1.25rem; transition: all 0.2s ease;" onmouseover="this.style.color='#009ef7'; this.style.backgroundColor='rgba(0, 158, 247, 0.1)';" onmouseout="this.style.color='#5e6278'; this.style.backgroundColor='transparent';">
									Sign Out
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end::Header-->

