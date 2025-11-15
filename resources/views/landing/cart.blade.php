@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3 class="mb-4">Keranjang Belanja</h3>
            </div>

            @if(session('success'))
                <div class="col-lg-12">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="col-lg-12">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(isset($removedCourses) && !empty($removedCourses))
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        <strong>Perhatian:</strong> Kursus berikut sudah terdaftar dan telah dihapus dari keranjang: 
                        <strong>{{ implode(', ', $removedCourses) }}</strong>
                    </div>
                </div>
            @endif

            @if(empty($courses))
                <div class="col-lg-12">
                    <div class="alert alert-info text-center">
                        <p class="mb-0">Keranjang Anda kosong. <a href="{{ route('daftar-kursus') }}">Lihat kursus</a></p>
                    </div>
                </div>
            @else
                <div class="col-lg-8 col-12">
                    @foreach($courses as $course)
                        <div class="custom-block bg-white shadow-lg mb-3 p-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ $course['image'] ? asset('storage/' . $course['image']) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                     class="img-fluid me-3" 
                                     style="max-width: 100px; height: 100px; object-fit: cover;"
                                     alt="{{ $course['title'] }}">
                                <div class="flex-grow-1">
                                    <h5 class="mb-2">{{ $course['title'] }}</h5>
                                    <p class="mb-0 text-muted">Rp {{ number_format($course['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="text-end">
                                    <p class="mb-2 fw-bold">Rp {{ number_format($course['subtotal'], 0, ',', '.') }}</p>
                                    <form action="{{ route('cart.remove', $course['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-4 col-12">
                    <div class="custom-block bg-white shadow-lg p-4">
                        <h5 class="mb-3">Ringkasan</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout') }}" class="btn custom-btn">Checkout</a>
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-outline-danger w-100">Kosongkan Keranjang</button>
                            </form>
                            <a href="{{ route('daftar-kursus') }}" class="btn btn-outline-primary">Lanjut Belanja</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

