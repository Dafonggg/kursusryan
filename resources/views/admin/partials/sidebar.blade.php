<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
		<a href="{{ route('admin.dashboard') }}">
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
					<div class="menu-item {{ request()->routeIs('admin.dashboard') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
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
					<div class="menu-item {{ request()->routeIs('admin.courses.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-book fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Kursus</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('admin.sessions.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}" href="{{ route('admin.sessions.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-calendar fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Sesi</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('admin.reschedules.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.reschedules.*') ? 'active' : '' }}" href="{{ route('admin.reschedules.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-arrows-circle fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Reschedule</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('admin.users.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-users fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Pengguna</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('admin.financial.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.financial.*') ? 'active' : '' }}" href="{{ route('admin.financial.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-wallet fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Keuangan</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('admin.chat.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" href="{{ route('admin.chat.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-chat fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Chat</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('admin.analytics.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-chart fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Analisis Peserta</span>
						</a>
					</div>
					<div class="menu-item {{ request()->routeIs('admin.settings.*') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-setting-2 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Pengaturan</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
		<a href="{{ route('admin.quick-actions') }}" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100">
			<span class="btn-label">Quick Actions</span>
			<i class="ki-duotone ki-rocket btn-icon fs-2 m-0">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</a>
	</div>
</div>
<!--end::Sidebar-->

