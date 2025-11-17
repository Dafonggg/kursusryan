@extends('layouts.app')

@section('title', 'Topic Detail Page')

@section('content')
<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center align-items-center">

            <div class="col-lg-5 col-12 mb-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Homepage</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('daftar-kursus') }}">Daftar Kursus</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
                    </ol>
                </nav>

                <h2 class="text-white">{{ $course->title }}</h2>
                <p class="text-white mt-3">{{ Str::limit($course->description, 100) }}</p>

                <div class="d-flex align-items-center mt-4">
                    <span class="badge bg-primary me-2">{{ ucfirst($course->mode->value) }}</span>
                    <span class="text-white fs-5 fw-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex align-items-center mt-5">
                    <form action="{{ route('cart.add', $course->id) }}" method="POST" class="me-3">
                        @csrf
                        <button type="submit" class="btn custom-btn custom-border-btn">Tambah ke Keranjang</button>
                    </form>
                    <a href="#enrollment" class="btn custom-btn smoothscroll">Daftar Sekarang</a>
                </div>
            </div>

            <div class="col-lg-5 col-12">
                <div class="topics-detail-block bg-white shadow-lg">
                    <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                         class="topics-detail-block-image img-fluid"
                         alt="{{ $course->title }}">
                </div>
            </div>

        </div>
    </div>
</header>


<section class="topics-detail-section section-padding" id="topics-detail">
    <div class="container">
        <div class="row">

            <div class="col-lg-8 col-12 m-auto">
                <h3 class="mb-4">{{ $course->title }}</h3>

                <p>{{ $course->description }}</p>

                <div class="row my-4">
                    <div class="col-md-6">
                        <h5>Informasi Kursus</h5>
                        <ul class="list-unstyled">
                            <li><strong>Mode:</strong> {{ ucfirst($course->mode->value) }}</li>
                            <li><strong>Durasi:</strong> {{ $course->duration_months }} Bulan</li>
                            <li><strong>Harga:</strong> Rp {{ number_format($course->price, 0, ',', '.') }}</li>
                            <li><strong>Peserta:</strong> {{ $course->enrollments->count() }} orang</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Instruktur</h5>
                        <p>{{ $course->owner->name ?? 'Tidak ada instruktur' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="section-padding section-bg" id="enrollment">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h3 class="mb-4 text-center">Form Pendaftaran</h3>
                    
                    @if(!Auth::check())
                        <div class="alert alert-warning">
                            <p class="mb-2">Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk mendaftar.</p>
                            <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="mb-4">Gunakan tombol <strong>"Tambah ke Keranjang"</strong> di bagian atas halaman untuk menambahkan kursus ini ke keranjang.</p>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">Lihat Keranjang</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

