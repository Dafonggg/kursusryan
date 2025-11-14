<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
		<a href="{{ route('instructor.dashboard') }}">
			<img alt="Logo" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/logos/default-dark.svg') }}" class="h-25px app-sidebar-logo-default" />
			<img alt="Logo" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/logos/default-small.svg') }}" class="h-20px app-sidebar-logo-minimize" />
		</a>
		<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
			<i class="ki-duotone ki-black-left-line fs-3 rotate-180">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
	</div>
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
			<div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
				<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
					<div class="menu-item {{ request()->routeIs('instructor.dashboard') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}" href="{{ route('instructor.dashboard') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-element-11 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</span>
							<span class="menu-title">Dashboard</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('instructor.sessions') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.sessions') ? 'active' : '' }}" href="{{ route('instructor.sessions') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-calendar fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Sesi Saya</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('instructor.courses') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.courses') ? 'active' : '' }}" href="{{ route('instructor.courses') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-book fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Kursus Saya</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('instructor.attendance') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.attendance') ? 'active' : '' }}" href="{{ route('instructor.attendance') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-user-check fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Absensi</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('instructor.reschedule') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.reschedule') ? 'active' : '' }}" href="{{ route('instructor.reschedule') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-calendar-tick fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Reschedule Request</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('instructor.messages') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.messages') ? 'active' : '' }}" href="{{ route('instructor.messages') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-message-text-2 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
							<span class="menu-title">Chat</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('instructor.transactions') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.transactions') ? 'active' : '' }}" href="{{ route('instructor.transactions') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-wallet fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Riwayat Transaksi</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('instructor.certificates') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('instructor.certificates') ? 'active' : '' }}" href="{{ route('instructor.certificates') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-award fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Sertifikat</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
		<a href="{{ route('instructor.quick-actions') }}" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100">
			<span class="btn-label">Quick Actions</span>
			<i class="ki-duotone ki-rocket btn-icon fs-2 m-0">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</a>
	</div>
</div>
<!--end::Sidebar-->

