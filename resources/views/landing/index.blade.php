@extends('layouts.app')

@section('title', 'Kursus Ryan Komputer')

@section('content')
@include('partials.hero-section')


<section class="featured-section">
    <div class="container">
        <div class="row justify-content-center">

            @if($allCourses->isNotEmpty())
                @php
                    $featuredCourse = $allCourses->first();
                @endphp
                <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                    <div class="custom-block bg-white shadow-lg">
                        <a href="{{ route('detail-kursus', $featuredCourse->slug) }}">
                            <div class="d-flex">
                                <div>
                                    <h5 class="mb-2">{{ $featuredCourse->title }}</h5>
                                    <p class="mb-0">{{ Str::limit($featuredCourse->description, 80) }}</p>
                                </div>
                                <span class="badge bg-design rounded-pill ms-auto">{{ $featuredCourse->enrollments->count() }}</span>
                            </div>
                            <img src="{{ $featuredCourse->image ? asset('storage/' . $featuredCourse->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                 class="custom-block-image img-fluid" 
                                 alt="{{ $featuredCourse->title }}">
                        </a>
                    </div>
                </div>
            @else
                <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                    <div class="custom-block bg-white shadow-lg">
                        <a href="{{ route('daftar-kursus') }}">
                            <div class="d-flex">
                                <div>
                                    <h5 class="mb-2">Kursus Tersedia</h5>
                                    <p class="mb-0">Lihat semua kursus yang tersedia di halaman daftar kursus.</p>
                                </div>
                                <span class="badge bg-design rounded-pill ms-auto">0</span>
                            </div>
                            <img src="{{ asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" class="custom-block-image img-fluid" alt="">
                        </a>
                    </div>
                </div>
            @endif

            <div class="col-lg-6 col-12">
                <div class="custom-block custom-block-overlay">
                    <div class="d-flex flex-column h-100">
                        @if($allCourses->isNotEmpty())
                            @php
                                $secondCourse = $allCourses->skip(1)->first() ?? $allCourses->first();
                            @endphp
                            <img src="{{ $secondCourse->image ? asset('storage/' . $secondCourse->image) : asset('images/businesswoman-using-tablet-analysis.jpg') }}" 
                                 class="custom-block-image img-fluid" 
                                 alt="{{ $secondCourse->title }}">

                            <div class="custom-block-overlay-text d-flex">
                                <div>
                                    <h5 class="text-white mb-2">{{ $secondCourse->title }}</h5>
                                    <p class="text-white">{{ Str::limit($secondCourse->description, 120) }}</p>
                                    <a href="{{ route('detail-kursus', $secondCourse->slug) }}" class="btn custom-btn mt-2 mt-lg-3">Learn More</a>
                                </div>
                                <span class="badge bg-finance rounded-pill ms-auto">{{ $secondCourse->enrollments->count() }}</span>
                            </div>
                        @else
                            <img src="{{ asset('images/businesswoman-using-tablet-analysis.jpg') }}" 
                                 class="custom-block-image img-fluid" 
                                 alt="Kursus Komputer">

                            <div class="custom-block-overlay-text d-flex">
                                <div>
                                    <h5 class="text-white mb-2">Kursus Komputer</h5>
                                    <p class="text-white">Temukan berbagai kursus komputer yang sesuai dengan kebutuhan Anda. Dari dasar hingga tingkat lanjut, semua tersedia untuk Anda.</p>
                                    <a href="{{ route('daftar-kursus') }}" class="btn custom-btn mt-2 mt-lg-3">Lihat Kursus</a>
                                </div>
                                <span class="badge bg-finance rounded-pill ms-auto">0</span>
                            </div>
                        @endif

                        <div class="social-share d-flex">
                            <p class="text-white me-4">Share:</p>

                            <ul class="social-icon">
                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-twitter"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-facebook"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-pinterest"></a>
                                </li>
                            </ul>

                            <a href="#" class="custom-icon bi-bookmark ms-auto"></a>
                        </div>

                        <div class="section-overlay"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="explore-section section-padding" id="section_2">
    <div class="container">
        <div class="row">

            <div class="col-12 text-center">
                <h2 class="mb-4">Kursus yang tersedia</h1>
            </div>

        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane" type="button" role="tab" aria-controls="all-tab-pane" aria-selected="true">Semua</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="online-tab" data-bs-toggle="tab" data-bs-target="#online-tab-pane" type="button" role="tab" aria-controls="online-tab-pane" aria-selected="false">Online</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="offline-tab" data-bs-toggle="tab" data-bs-target="#offline-tab-pane" type="button" role="tab" aria-controls="offline-tab-pane" aria-selected="false">Offline</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="hybrid-tab" data-bs-toggle="tab" data-bs-target="#hybrid-tab-pane" type="button" role="tab" aria-controls="hybrid-tab-pane" aria-selected="false">Hybrid</button>
                </li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="row">

            <div class="col-12">
                <div class="tab-content" id="myTabContent">
                    {{-- Tab Semua Courses --}}
                    <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab" tabindex="0">
                        <div class="row">
                            @forelse($allCourses as $course)
                                <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                    <div class="custom-block bg-white shadow-lg">
                                        <a href="{{ route('detail-kursus', $course->slug) }}">
                                            <div class="d-flex">
                                                <div>
                                                    <h5 class="mb-2">{{ $course->title }}</h5>
                                                    <p class="mb-0">{{ Str::limit($course->description, 80) }}</p>
                                                </div>
                                                <span class="badge bg-design rounded-pill ms-auto">{{ $course->enrollments->count() }}</span>
                                            </div>
                                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                                 class="custom-block-image img-fluid" 
                                                 alt="{{ $course->title }}">
                                            <div class="mt-2">
                                                <small class="text-muted">Durasi: {{ $course->duration_months }} bulan</small>
                                                <span class="badge bg-primary ms-2">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-center">Belum ada kursus yang tersedia.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tab Online Courses --}}
                    <div class="tab-pane fade" id="online-tab-pane" role="tabpanel" aria-labelledby="online-tab" tabindex="0">
                        <div class="row">
                            @forelse($onlineCourses as $course)
                                <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                    <div class="custom-block bg-white shadow-lg">
                                        <a href="{{ route('detail-kursus', $course->slug) }}">
                                            <div class="d-flex">
                                                <div>
                                                    <h5 class="mb-2">{{ $course->title }}</h5>
                                                    <p class="mb-0">{{ Str::limit($course->description, 80) }}</p>
                                                </div>
                                                <span class="badge bg-success rounded-pill ms-auto">{{ $course->enrollments->count() }}</span>
                                            </div>
                                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                                 class="custom-block-image img-fluid" 
                                                 alt="{{ $course->title }}">
                                            <div class="mt-2">
                                                <small class="text-muted">Durasi: {{ $course->duration_months }} bulan</small>
                                                <span class="badge bg-primary ms-2">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-center">Belum ada kursus online yang tersedia.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tab Offline Courses --}}
                    <div class="tab-pane fade" id="offline-tab-pane" role="tabpanel" aria-labelledby="offline-tab" tabindex="0">
                        <div class="row">
                            @forelse($offlineCourses as $course)
                                <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                    <div class="custom-block bg-white shadow-lg">
                                        <a href="{{ route('detail-kursus', $course->slug) }}">
                                            <div class="d-flex">
                                                <div>
                                                    <h5 class="mb-2">{{ $course->title }}</h5>
                                                    <p class="mb-0">{{ Str::limit($course->description, 80) }}</p>
                                                </div>
                                                <span class="badge bg-warning rounded-pill ms-auto">{{ $course->enrollments->count() }}</span>
                                            </div>
                                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                                 class="custom-block-image img-fluid" 
                                                 alt="{{ $course->title }}">
                                            <div class="mt-2">
                                                <small class="text-muted">Durasi: {{ $course->duration_months }} bulan</small>
                                                <span class="badge bg-primary ms-2">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-center">Belum ada kursus offline yang tersedia.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tab Hybrid Courses --}}
                    <div class="tab-pane fade" id="hybrid-tab-pane" role="tabpanel" aria-labelledby="hybrid-tab" tabindex="0">
                        <div class="row">
                            @forelse($hybridCourses as $course)
                                <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                    <div class="custom-block bg-white shadow-lg">
                                        <a href="{{ route('detail-kursus', $course->slug) }}">
                                            <div class="d-flex">
                                                <div>
                                                    <h5 class="mb-2">{{ $course->title }}</h5>
                                                    <p class="mb-0">{{ Str::limit($course->description, 80) }}</p>
                                                </div>
                                                <span class="badge bg-info rounded-pill ms-auto">{{ $course->enrollments->count() }}</span>
                                            </div>
                                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                                 class="custom-block-image img-fluid" 
                                                 alt="{{ $course->title }}">
                                            <div class="mt-2">
                                                <small class="text-muted">Durasi: {{ $course->duration_months }} bulan</small>
                                                <span class="badge bg-primary ms-2">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-center">Belum ada kursus hybrid yang tersedia.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="timeline-section section-padding" id="section_3">
    <div class="section-overlay"></div>

    <div class="container">
        <div class="row">

            <div class="col-12 text-center">
                <h2 class="text-white mb-4">How does it work?</h1>
            </div>

            <div class="col-lg-10 col-12 mx-auto">
                <div class="timeline-container">
                    <ul class="vertical-scrollable-timeline" id="vertical-scrollable-timeline">
                        <div class="list-progress">
                            <div class="inner"></div>
                        </div>

                        <li>
                            <h4 class="text-white mb-3">Course Your Course</h4>

                            <p class="text-white">Pilih kursus komputer yang kamu mau — dari dasar hingga tingkat lanjut. Ada pilihan desain grafis, pemrograman, Office, dan banyak lagi!</p>

                            <div class="icon-holder">
                              <i class="bi-search"></i>
                            </div>
                        </li>
                        
                        <li>
                            <h4 class="text-white mb-3">Learn Anywhere</h4>

                            <p class="text-white">Belajar dengan cara yang paling nyaman buat kamu! Bisa online lewat platform kami atau datang langsung ke kelas offline.</p>

                            <div class="icon-holder">
                              <i class="bi-bookmark"></i>
                            </div>
                        </li>

                        <li>
                            <h4 class="text-white mb-3">Get Certified</h4>

                            <p class="text-white">Setelah selesai kursus, kamu akan mendapatkan sertifikat resmi dari Kursus Ryan Komputer!</p>

                            <div class="icon-holder">
                              <i class="bi-book"></i>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-12 text-center mt-5">
                <p class="text-white">
                    Want to learn more?
                    <a href="#" class="btn custom-btn custom-border-btn ms-3">Check out Youtube</a>
                </p>
            </div>
        </div>
    </div>
</section>


<section class="faq-section section-padding" id="section_4">
    <div class="container">
        <div class="row">

            <div class="col-lg-6 col-12">
                <h2 class="mb-4">Frequently Asked Questions</h2>
            </div>

            <div class="clearfix"></div>

            <div class="col-lg-5 col-12">
                <img src="{{ asset('images/faq_graphic.jpg') }}" class="img-fluid" alt="FAQs">
            </div>

            <div class="col-lg-6 col-12 m-auto">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            1. Apakah kursus ini bisa dilakukan secara online?
                            </button>
                        </h2>

                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Tentu! Kamu bisa ikut kelas secara online lewat platform kami atau datang langsung ke kelas offline sesuai jadwal yang tersedia.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            2. Apakah peserta akan mendapat sertifikat?
                        </button>
                        </h2>

                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Ya, setiap peserta yang menyelesaikan kursus akan mendapatkan sertifikat resmi sebagai bukti keahlianmu.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            3. Apakah ada biaya pendaftaran?
                        </button>
                        </h2>

                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Beberapa kursus kami gratis, namun untuk kelas tertentu ada biaya pendaftaran dan materi. Semua informasinya bisa kamu lihat di halaman detail kursus.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            4. Apakah saya perlu membawa laptop sendiri untuk kelas offline?
                        </button>
                        </h2>

                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Disarankan membawa laptop sendiri agar bisa langsung praktik. Tapi kalau tidak punya, kami juga menyediakan komputer di ruang kelas.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            5. Apakah ada batas usia untuk mengikuti kursus?
                        </button>
                        </h2>

                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Tidak ada batasan usia — semua orang boleh belajar, baik pelajar, mahasiswa, maupun profesional yang ingin meningkatkan skill komputer!
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@include('partials.contact-section')
@endsection

