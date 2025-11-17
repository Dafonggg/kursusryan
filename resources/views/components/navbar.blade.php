<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/bgloginputih1.png') }}" alt="Ryan Computer" style="height: 40px; margin-right: 10px;">
        </a>

        <!-- Cart Button, Bookmark Button and Hamburger Menu for Mobile -->
        <div class="d-lg-none d-flex align-items-center gap-2">
            <a href="{{ url('/cart') }}" 
               class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-3 py-2 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center position-relative">
                <span class="material-symbols-outlined" style="font-size: 30px;">shopping_cart</span>
                @php
                    $cartCount = count(session('cart', []));
                @endphp
                @if($cartCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <a href="#" 
               class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-3 py-2 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center">
                <span class="custom-icon bi-bookmark text-white" style="font-size: 24px; color: white !important;"></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-lg-5 me-lg-auto align-items-center w-100 w-lg-auto">
                <li class="nav-item">
                    <a class="nav-link click-scroll py-2 py-lg-1 {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ url('/') }}#section_1">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll py-2 py-lg-1" href="{{ url('/') }}#section_2">Kursus</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll py-2 py-lg-1" href="{{ url('/') }}#section_3">How it works</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll py-2 py-lg-1" href="{{ url('/') }}#section_4">FAQs</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll py-2 py-lg-1" href="{{ url('/') }}#section_5">Contact</a>
                </li>

                <li class="nav-item dropdown dropdown-click-only">
                    <a class="nav-link dropdown-toggle py-2 py-lg-1 d-flex align-items-center justify-content-between" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>Pages</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-light shadow-sm w-100" aria-labelledby="navbarLightDropdownMenuLink" style="min-width: 200px;">
                        <li><a class="dropdown-item py-2 px-3 {{ request()->routeIs('daftar-kursus') ? 'active bg-light' : '' }}" href="{{ url('/daftar-kursus') }}">Daftar Kursus</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2 px-3 {{ request()->routeIs('contact') ? 'active bg-light' : '' }}" href="{{ url('/contact') }}">Contact Form</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Auth Buttons for Mobile (Hamburger Menu) -->
            <div class="d-lg-none mt-3 mb-3 px-2">
                @auth
                    <!-- User Dropdown -->
                    <div class="user-dropdown mb-2">
                        <button class="user-dropdown-btn bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 w-100">
                            <span class="material-symbols-outlined"> account_circle </span>
                            <span>{{ Auth::user()->name }}</span>
                            <span class="material-symbols-outlined chevron"> expand_more </span>
                        </button>
                        <div class="user-dropdown-menu">
                            <div class="user-dropdown-menu-inner">
                                <div class="user-dropdown-main-menu">
                                    @if(Auth::user()->role == 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> dashboard </span>
                                            <span>Admin Dashboard</span>
                                        </a>
                                    @elseif(Auth::user()->role == 'instructor')
                                        <a href="{{ route('instructor.dashboard') }}" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> dashboard </span>
                                            <span>Instructor Dashboard</span>
                                        </a>
                                    @elseif(Auth::user()->role == 'student' || Auth::user()->role == 'user')
                                        <a href="{{ route('student.dashboard') }}" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> dashboard </span>
                                            <span>Student Dashboard</span>
                                        </a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST" class="user-dropdown-form">
                                        @csrf
                                        <button type="submit" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> logout </span>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Cart Button -->
                    <!-- Login & Sign Up Buttons -->
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('login') }}" 
                           class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline text-center">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline text-center">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Auth Buttons / User Dropdown -->
            <div class="d-none d-lg-flex gap-2 align-items-center ms-lg-4">
                @auth
                    <!-- Cart Button -->
                    <a href="{{ url('/cart') }}" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-4 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center position-relative" style="min-width: 44px; height: 44px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">shopping_cart</span>
                        @php
                            $cartCount = count(session('cart', []));
                        @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    <!-- Bookmark Button -->
                    <a href="#" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-4 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center" style="min-width: 44px; height: 44px;">
                        <span class="custom-icon bi-bookmark text-white" style="font-size: 18px; color: white !important;"></span>
                    </a>
                    <span class="text-white mx-1" style="font-size: 18px;">|</span>
                    <!-- User Dropdown -->
                    <div class="user-dropdown">
                        <button class="user-dropdown-btn bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 20px;"> account_circle </span>
                            <span>{{ Auth::user()->name }}</span>
                            <span class="material-symbols-outlined chevron" style="font-size: 20px;"> expand_more </span>
                        </button>
                        <div class="user-dropdown-menu">
                            <div class="user-dropdown-menu-inner">
                                <div class="user-dropdown-main-menu">
                                    @if(Auth::user()->role == 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> dashboard </span>
                                            <span>Admin Dashboard</span>
                                        </a>
                                    @elseif(Auth::user()->role == 'instructor')
                                        <a href="{{ route('instructor.dashboard') }}" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> dashboard </span>
                                            <span>Instructor Dashboard</span>
                                        </a>
                                    @elseif(Auth::user()->role == 'student' || Auth::user()->role == 'user')
                                        <a href="{{ route('student.dashboard') }}" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> dashboard </span>
                                            <span>Student Dashboard</span>
                                        </a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST" class="user-dropdown-form">
                                        @csrf
                                        <button type="submit" class="user-dropdown-item">
                                            <span class="material-symbols-outlined"> logout </span>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Cart Button -->
                    <a href="{{ url('/cart') }}" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-4 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center position-relative" style="min-width: 44px; height: 44px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">shopping_cart</span>
                        @php
                            $cartCount = count(session('cart', []));
                        @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    <!-- Bookmark Button -->
                    <a href="#" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-4 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center" style="min-width: 44px; height: 44px;">
                        <span class="custom-icon bi-bookmark text-white" style="font-size: 18px; color: white !important;"></span>
                    </a>
                    <span class="text-white mx-1" style="font-size: 18px;">|</span>
                    <!-- Login & Sign Up Buttons -->
                    <a href="{{ route('login') }}" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center" style="height: 44px;">
                        Log In
                    </a>
                    <span class="text-white mx-1" style="font-size: 18px;">|</span>
                    <a href="{{ route('register') }}" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center" style="height: 44px;">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
    /* Override hover behavior untuk dropdown click-only */
    @media screen and (min-width: 992px) {
        .navbar .dropdown-click-only:hover .dropdown-menu {
            opacity: 0 !important;
            margin-top: 20px !important;
            pointer-events: none !important;
            display: none !important;
        }
    }
    
    /* Pastikan dropdown hanya muncul saat class show ada (dari Bootstrap click) */
    .navbar .dropdown-click-only .dropdown-menu {
        opacity: 0;
        pointer-events: none;
        display: none;
        margin-top: 20px;
    }
    
    .navbar .dropdown-click-only.show .dropdown-menu,
    .navbar .dropdown-click-only .dropdown-menu.show {
        opacity: 1 !important;
        margin-top: 0 !important;
        pointer-events: auto !important;
        display: block !important;
    }
</style>

<script>
    (function() {
        'use strict';
        
        function initDropdownFix() {
            const dropdownToggle = document.getElementById('navbarLightDropdownMenuLink');
            const navbarCollapse = document.getElementById('navbarNav');
            
            if (!dropdownToggle || !navbarCollapse) return;
            
            // Mencegah navbar collapse menutup saat dropdown toggle diklik
            dropdownToggle.addEventListener('click', function(e) {
                // Stop event dari bubbling ke navbar collapse
                e.stopPropagation();
            }, true); // Gunakan capture phase
            
            // Mencegah collapse menutup saat klik di dalam dropdown menu
            const dropdownMenu = navbarCollapse.querySelector('.dropdown-click-only .dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.addEventListener('click', function(e) {
                    // Di mobile, stop propagation agar navbar tidak menutup
                    if (window.innerWidth < 992) {
                        e.stopPropagation();
                    }
                }, true);
            }
            
            // Mencegah collapse menutup saat klik pada dropdown items
            const dropdownItems = navbarCollapse.querySelectorAll('.dropdown-click-only .dropdown-item');
            dropdownItems.forEach(function(item) {
                item.addEventListener('click', function(e) {
                    // Di mobile, stop propagation
                    if (window.innerWidth < 992) {
                        e.stopPropagation();
                    }
                }, true);
            });
            
            // Prevent collapse dari menutup saat klik pada dropdown container
            const dropdownContainer = navbarCollapse.querySelector('.dropdown-click-only');
            if (dropdownContainer) {
                dropdownContainer.addEventListener('click', function(e) {
                    // Di mobile, jika klik di dalam dropdown, stop propagation
                    if (window.innerWidth < 992 && this.contains(e.target)) {
                        // Biarkan Bootstrap handle dropdown toggle
                        // Tapi stop propagation agar collapse tidak menutup
                        if (!e.target.closest('.dropdown-menu')) {
                            // Klik pada toggle button sudah di-handle di atas
                            // Tapi pastikan tidak bubble
                            setTimeout(function() {
                                e.stopPropagation();
                            }, 0);
                        }
                    }
                }, true);
            }
        }
        
        // Tunggu DOM siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDropdownFix);
        } else {
            initDropdownFix();
        }
    })();
</script>