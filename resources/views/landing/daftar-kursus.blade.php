@extends('layouts.app')

@section('title', 'Daftar Kursus Page')

@section('body-class', 'daftar-kursus-page')

@section('content')
<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-5 col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Homepage</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Daftar Kursus</li>
                    </ol>
                </nav>

                <h2 class="text-white">Daftar Kursus</h2>
            </div>

        </div>
    </div>
</header>


<section class="section-padding">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12 text-center">
                <h3 class="mb-4">Daftar Kursus</h3>
            </div>

            <div class="col-lg-12 col-12 mt-3">
                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('daftar-kursus') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari kursus..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('daftar-kursus') }}">
                            <div class="input-group">
                                <select name="mode" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Mode</option>
                                    <option value="online" {{ request('mode') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ request('mode') == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="hybrid" {{ request('mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                @if($courses->isEmpty())
                    <div class="alert alert-info text-center">
                        <p>Tidak ada kursus yang ditemukan.</p>
                    </div>
                @else
                    @foreach($courses as $course)
                        <div class="custom-block custom-block-topics-listing bg-white shadow-lg mb-5">
                            <div class="d-flex">
                                <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                     class="custom-block-image img-fluid" 
                                     alt="{{ $course->title }}"
                                     style="max-width: 200px; object-fit: cover;">

                                <div class="custom-block-topics-listing-info d-flex flex-grow-1">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">{{ $course->title }}</h5>
                                        <p class="mb-0">{{ Str::limit($course->description, 150) }}</p>
                                        <div class="mt-2">
                                            <span class="badge bg-primary">{{ ucfirst($course->mode->value) }}</span>
                                            <span class="badge bg-success">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('detail-kursus', $course->slug) }}" class="btn custom-btn me-2">Detail</a>
                                            <form action="{{ route('cart.add', $course->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary">Tambah ke Keranjang</button>
                                            </form>
                                        </div>
                                    </div>
                                    <span class="badge bg-design rounded-pill ms-auto">{{ $course->enrollments->count() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $courses->links() }}
                    </div>
                @endif
            </div>


        </div>
    </div>
</section>


@if($courses->isNotEmpty())
<section class="section-padding section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3 class="mb-4">Kursus Populer</h3>
            </div>

            @php
                $popularCourses = $courses->take(2);
            @endphp

            @foreach($popularCourses as $popularCourse)
                <div class="col-lg-6 col-md-6 col-12 mt-3 {{ $loop->first ? 'mb-4 mb-lg-0' : 'mt-lg-3' }}">
                    <div class="custom-block {{ $loop->last ? 'custom-block-overlay' : 'bg-white shadow-lg' }}">
                        <a href="{{ route('detail-kursus', $popularCourse->slug) }}">
                            @if($loop->last)
                                <div class="d-flex flex-column h-100">
                                    <img src="{{ $popularCourse->image ? asset('storage/' . $popularCourse->image) : asset('images/businesswoman-using-tablet-analysis.jpg') }}" 
                                         class="custom-block-image img-fluid" 
                                         alt="{{ $popularCourse->title }}">

                                    <div class="custom-block-overlay-text d-flex">
                                        <div>
                                            <h5 class="text-white mb-2">{{ $popularCourse->title }}</h5>
                                            <p class="text-white">{{ Str::limit($popularCourse->description, 100) }}</p>
                                            <a href="{{ route('detail-kursus', $popularCourse->slug) }}" class="btn custom-btn mt-2 mt-lg-3">Daftar</a>
                                        </div>
                                        <span class="badge bg-finance rounded-pill ms-auto">{{ $popularCourse->enrollments->count() }}</span>
                                    </div>
                                    <div class="section-overlay"></div>
                                </div>
                            @else
                                <div class="d-flex">
                                    <div>
                                        <h5 class="mb-2">{{ $popularCourse->title }}</h5>
                                        <p class="mb-0">{{ Str::limit($popularCourse->description, 80) }}</p>
                                    </div>
                                    <span class="badge bg-finance rounded-pill ms-auto">{{ $popularCourse->enrollments->count() }}</span>
                                </div>
                                <img src="{{ $popularCourse->image ? asset('storage/' . $popularCourse->image) : asset('images/topics/undraw_Finance_re_gnv2.png') }}" 
                                     class="custom-block-image img-fluid" 
                                     alt="{{ $popularCourse->title }}">
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

