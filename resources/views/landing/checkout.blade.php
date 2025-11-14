@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 mb-4">
                <h3 class="mb-4">Checkout</h3>
            </div>

            <div class="col-lg-8 col-12">
                <div class="custom-block bg-white shadow-lg p-4 mb-4">
                    <h5 class="mb-3">Kursus yang Dipilih</h5>
                    @foreach($courses as $course)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                 class="img-fluid me-3" 
                                 style="max-width: 80px; height: 80px; object-fit: cover;"
                                 alt="{{ $course->title }}">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $course->title }}</h6>
                                <p class="mb-0 text-muted small">{{ ucfirst($course->mode->value) }}</p>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h5 class="mb-4">Metode Pembayaran</h5>
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('enrollment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Pilih Metode Pembayaran <span class="text-danger">*</span></label>
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                       type="radio" name="payment_method" id="qris" value="qris" 
                                       {{ old('payment_method') == 'qris' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="qris">
                                    <strong>QRIS</strong>
                                    <p class="small text-muted mb-0">Scan QR code untuk pembayaran cepat</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                       type="radio" name="payment_method" id="transfer" value="transfer" 
                                       {{ old('payment_method') == 'transfer' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="transfer">
                                    <strong>Transfer Bank</strong>
                                    <p class="small text-muted mb-0">Transfer ke rekening bank kami</p>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="transfer-details" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Nama Bank</label>
                                <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" 
                                       placeholder="Contoh: BCA, Mandiri, BNI" value="{{ old('bank_name') }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                                       placeholder="Nomor rekening pengirim" value="{{ old('account_number') }}">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Pemilik Rekening</label>
                                <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" 
                                       placeholder="Nama sesuai rekening" value="{{ old('account_name') }}">
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                            <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg" required>
                            <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                            @error('payment_proof')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <strong>Catatan:</strong> Setelah mengirim bukti pembayaran, Anda akan diminta untuk melengkapi data diri. 
                                Setelah admin memverifikasi pembayaran, Anda akan mendapatkan akses ke kursus.
                            </small>
                        </div>

                        <button type="submit" class="btn custom-btn w-100">Kirim Bukti Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrisRadio = document.getElementById('qris');
    const transferRadio = document.getElementById('transfer');
    const transferDetails = document.getElementById('transfer-details');

    qrisRadio.addEventListener('change', function() {
        if (this.checked) {
            transferDetails.style.display = 'none';
        }
    });

    transferRadio.addEventListener('change', function() {
        if (this.checked) {
            transferDetails.style.display = 'block';
        }
    });
});
</script>
@endsection

