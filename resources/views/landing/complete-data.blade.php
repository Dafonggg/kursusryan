@extends('layouts.app')

@section('title', 'Lengkapi Data Diri')

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h3 class="mb-4 text-center">Lengkapi Data Diri</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="alert alert-info mb-4">
                        <h6>Pendaftaran yang Menunggu Verifikasi:</h6>
                        <ul class="mb-0">
                            @foreach($pendingEnrollments as $enrollment)
                                <li>{{ $enrollment->course->title }} - Rp {{ number_format($enrollment->course->price, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <form action="{{ route('enrollment.store-complete-data') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $profile->phone ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $profile->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg">
                            @if($profile && $profile->photo_path)
                                <small class="text-muted">Foto saat ini: <a href="{{ asset('storage/' . $profile->photo_path) }}" target="_blank">Lihat</a></small>
                            @endif
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">KTP <span class="text-danger">*</span></label>
                            <input type="file" name="ktp" class="form-control @error('ktp') is-invalid @enderror" 
                                   accept=".pdf,image/jpeg,image/png,image/jpg" required>
                            @if($profile && $profile->ktp_path)
                                <small class="text-muted">KTP saat ini: <a href="{{ asset('storage/' . $profile->ktp_path) }}" target="_blank">Lihat</a></small>
                            @endif
                            @error('ktp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                            <input type="file" name="kk" class="form-control @error('kk') is-invalid @enderror" 
                                   accept=".pdf,image/jpeg,image/png,image/jpg" required>
                            @if($profile && $profile->kk_path)
                                <small class="text-muted">KK saat ini: <a href="{{ asset('storage/' . $profile->kk_path) }}" target="_blank">Lihat</a></small>
                            @endif
                            @error('kk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                        </div>

                        <div class="alert alert-warning">
                            <small>
                                <strong>Penting:</strong> Pastikan semua data yang Anda isi benar dan dokumen yang diupload jelas dan valid. 
                                Data ini akan digunakan untuk verifikasi pendaftaran Anda.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn custom-btn">Simpan Data</button>
                            <a href="{{ route('student.my-courses') }}" class="btn btn-outline-secondary">Lewati (Nanti)</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

