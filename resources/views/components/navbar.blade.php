<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logokrk.png') }}" alt="Ryan Computer" style="height: 40px; margin-right: 10px;">
            <span class="d-none d-lg-inline">Kursus Ryan</span>
        </a>

        <!-- Cart Button, Bookmark Button and Hamburger Menu for Mobile -->
        <div class="d-lg-none d-flex align-items-center gap-2">
            <a href="{{ url('/cart') }}" 
               class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-3 py-2 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center">
                <span class="material-symbols-outlined" style="font-size: 30px;">shopping_cart</span>
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
            <ul class="navbar-nav ms-lg-5 me-lg-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link click-scroll {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ url('/') }}#section_1">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="{{ url('/') }}#section_2">Kursus</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="{{ url('/') }}#section_3">How it works</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="{{ url('/') }}#section_4">FAQs</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="{{ url('/') }}#section_5">Contact</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#section_5" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>

                    <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                        <li><a class="dropdown-item {{ request()->routeIs('daftar-kursus') ? 'active' : '' }}" href="{{ url('/daftar-kursus') }}">Daftar Kursus</a></li>

                        <li><a class="dropdown-item {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact Form</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Auth Buttons for Mobile (Hamburger Menu) -->
            <div class="d-lg-none mt-3 mb-3">
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
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline text-center">
                            Sign Up
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Auth Buttons / User Dropdown -->
            <div class="d-none d-lg-flex gap-2 align-items-center ms-lg-4">
                @auth
                    <!-- Cart Button -->
                    <a href="{{ url('/cart') }}" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-4 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center" style="min-width: 44px; height: 44px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">shopping_cart</span>
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
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-4 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center justify-content-center" style="min-width: 44px; height: 44px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">shopping_cart</span>
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
                        Login
                    </a>
                    <span class="text-white mx-1" style="font-size: 18px;">|</span>
                    <a href="{{ route('register') }}" 
                       class="bg-[#81d0c7] hover:bg-[#4f98a4] text-white font-semibold px-6 py-2.5 rounded-full transition-all duration-300 transform hover:scale-105 no-underline d-flex align-items-center" style="height: 44px;">
                        Sign Up
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>