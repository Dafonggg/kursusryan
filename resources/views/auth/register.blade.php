<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<base href="{{ asset('/') }}" />
		<title>Register | Kursus Ryan Komputer</title>
		<meta charset="utf-8" />
		<meta name="description" content="Daftar ke Kursus Ryan Komputer" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/logos/favicon.ico') }}" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - Sign-up -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Body-->
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
					<!--begin::Form-->
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<!--begin::Wrapper-->
						<div class="w-lg-500px p-10">
							<!--begin::Form-->
							<form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" action="{{ route('register.post') }}" method="POST">
								@csrf
								<!--begin::Heading-->
								<div class="text-center mb-11">
									<!--begin::Title-->
									<h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
									<!--end::Title-->
									<!--begin::Subtitle-->
									<div class="text-gray-500 fw-semibold fs-6">Buat akun baru untuk memulai pembelajaran</div>
									<!--end::Subtitle=-->
								</div>
								<!--begin::Heading-->
								<!--begin::Login options-->
								<div class="row g-3 mb-9">
									<!--begin::Col-->
									<div class="col-md-12">
										<!--begin::Google link=-->
										<a href="{{ route('auth.google') }}" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
										<img alt="Logo" src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/svg/brand-logos/google-icon.svg') }}" class="h-15px me-3" />Sign up with Google</a>
										<!--end::Google link=-->
									</div>
									<!--end::Col-->
								</div>
								<!--end::Login options-->
								<!--begin::Separator-->
								<div class="separator separator-content my-14">
									<span class="w-125px text-gray-500 fw-semibold fs-7">Atau dengan email</span>
								</div>
								<!--end::Separator-->
								<!--begin::Error Messages-->
								@if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('email_confirmation') && !$errors->has('phone') && !$errors->has('password') && !$errors->has('password_confirmation'))
									<div class="alert alert-danger d-flex align-items-center p-5 mb-8">
										<i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
										<div class="d-flex flex-column">
											<h4 class="mb-1 text-danger">Error</h4>
											<ul class="mb-0">
												@foreach($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									</div>
								@endif
								<!--end::Error Messages-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Name-->
									<input type="text" 
										   placeholder="Nama Lengkap" 
										   name="name" 
										   autocomplete="off" 
										   class="form-control bg-transparent @error('name') is-invalid @enderror" 
										   value="{{ old('name') }}" 
										   required />
									<!--end::Name-->
									@error('name')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<!--end::Input group=-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Email-->
									<input type="email" 
										   placeholder="Email" 
										   name="email" 
										   autocomplete="off" 
										   class="form-control bg-transparent @error('email') is-invalid @enderror" 
										   value="{{ old('email') }}" 
										   required />
									<!--end::Email-->
									@error('email')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<!--end::Input group=-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Email Confirmation-->
									<input type="email" 
										   placeholder="Konfirmasi Email" 
										   name="email_confirmation" 
										   autocomplete="off" 
										   class="form-control bg-transparent @error('email_confirmation') is-invalid @enderror" 
										   required />
									<!--end::Email Confirmation-->
									@error('email_confirmation')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<!--end::Input group=-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Phone-->
									<input type="tel" 
										   placeholder="Nomor Handphone" 
										   name="phone" 
										   autocomplete="off" 
										   class="form-control bg-transparent @error('phone') is-invalid @enderror" 
										   value="{{ old('phone') }}" 
										   required />
									<!--end::Phone-->
									@error('phone')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<!--end::Input group=-->
								<!--begin::Input group-->
								<div class="fv-row mb-8" data-kt-password-meter="true">
									<!--begin::Wrapper-->
									<div class="mb-1">
										<!--begin::Input wrapper-->
										<div class="position-relative mb-3">
											<input class="form-control bg-transparent @error('password') is-invalid @enderror" 
												   type="password" 
												   placeholder="Kata Sandi" 
												   name="password" 
												   autocomplete="off" 
												   required />
											<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
												<i class="ki-duotone ki-eye-slash fs-2"></i>
												<i class="ki-duotone ki-eye fs-2 d-none"></i>
											</span>
										</div>
										<!--end::Input wrapper-->
										<!--begin::Meter-->
										<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
										</div>
										<!--end::Meter-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Hint-->
									<div class="text-muted">Gunakan 8 karakter atau lebih dengan kombinasi huruf, angka & simbol.</div>
									<!--end::Hint-->
									@error('password')
										<div class="invalid-feedback d-block">{{ $message }}</div>
									@enderror
								</div>
								<!--end::Input group=-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Repeat Password-->
									<div class="position-relative">
										<input placeholder="Konfirmasi Kata Sandi" 
											   name="password_confirmation" 
											   type="password" 
											   autocomplete="off" 
											   class="form-control bg-transparent @error('password_confirmation') is-invalid @enderror" 
											   required />
										<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
											<i class="ki-duotone ki-eye-slash fs-2"></i>
											<i class="ki-duotone ki-eye fs-2 d-none"></i>
										</span>
									</div>
									<!--end::Repeat Password-->
									@error('password_confirmation')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<!--end::Input group=-->
								<!--begin::Accept-->
								<div class="fv-row mb-8">
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="toc" value="1" required />
										<span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">Saya Menerima 
										<a href="#" class="ms-1 link-primary">Syarat & Ketentuan</a></span>
									</label>
								</div>
								<!--end::Accept-->
								<!--begin::Submit button-->
								<div class="d-grid mb-10">
									<button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
										<!--begin::Indicator label-->
										<span class="indicator-label">Sign up</span>
										<!--end::Indicator label-->
										<!--begin::Indicator progress-->
										<span class="indicator-progress">Please wait... 
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										<!--end::Indicator progress-->
									</button>
								</div>
								<!--end::Submit button-->
								<!--begin::Sign up-->
								<div class="text-gray-500 text-center fw-semibold fs-6">Sudah punya akun? 
								<a href="{{ route('login') }}" class="link-primary fw-semibold">Sign in</a></div>
								<!--end::Sign up-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Form-->
					<!--begin::Footer-->
					<div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
						<!--begin::Languages-->
						<div class="me-10">
							<!--begin::Toggle-->
							<button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
							</button>
						</div>
						<!--end::Languages-->
						<!--begin::Links-->
						<div class="d-flex fw-semibold text-primary fs-base gap-5">
							<a href="#" target="_blank">Terms</a>
							<a href="#" target="_blank">Plans</a>
							<a href="#" target="_blank">Contact Us</a>
						</div>
						<!--end::Links-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Body-->
				<!--begin::Aside-->
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset('images/bgtoska1.jpg') }})">
					<!--begin::Content-->
					<div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
						<!--begin::Logo-->
						<a href="{{ route('home') }}" class="mb-0 mb-lg-12">
							<img alt="Logo" src="{{ asset('images/bgloginputih1.png') }}" class="h-60px h-lg-75px" />
						</a>
						<!--end::Logo-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Authentication - Sign-up-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/') }}";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/js/custom/authentication/sign-up/general.js') }}"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
