@extends('layouts.admin')

@section('title', 'Katalog Produk | Sistem Kursus Komputer')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  {{ session('error') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0">
    <span class="text-muted fw-light">Katalog /</span> Produk
  </h4>
  <button
    type="button"
    class="btn btn-primary"
    data-bs-toggle="modal"
    data-bs-target="#modalTambahProduk">
    <i class="icon-base ri ri-add-line me-2"></i>
    Tambah Produk
  </button>
</div>

<!-- Produk Cards -->
@if($katalogs->count() > 0)
<div class="row g-4 mb-4">
  @foreach($katalogs as $katalog)
  <div class="col-md-6 col-lg-4">
    <div class="card h-100">
      <img
        class="card-img-top"
        src="{{ $katalog->gambar && $katalog->gambar !== 'default.jpg' ? asset($katalog->gambar) : asset('assets/img/elements/2.png') }}"
        alt="{{ $katalog->nama_katalog }}"
        style="height: 200px; object-fit: cover;" />
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h5 class="card-title mb-0">{{ $katalog->nama_katalog }}</h5>
          <span class="badge {{ $katalog->status === 'Tersedia' ? 'bg-label-success' : 'bg-label-warning' }}">
            {{ $katalog->status }}
          </span>
        </div>
        <p class="card-text text-body-secondary">
          {{ Str::limit($katalog->deskripsi, 100) }}
        </p>
        <div class="mb-3">
          <div class="d-flex align-items-center mb-1">
            <i class="icon-base ri ri-money-dollar-circle-line me-2"></i>
            <small class="text-body-secondary fw-bold">Rp {{ number_format($katalog->harga, 0, ',', '.') }}</small>
          </div>
          <div class="d-flex align-items-center">
            <i class="icon-base ri ri-box-3-line me-2"></i>
            <small class="text-body-secondary">Stok: {{ $katalog->stok }} unit</small>
          </div>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-label-primary btn-sm flex-fill" onclick="editProduk({{ $katalog->id_katalog }})" data-bs-toggle="modal" data-bs-target="#modalEditProduk">
            <i class="icon-base ri ri-pencil-line me-1"></i>
            Edit
          </button>
          <form action="{{ route('dashboard.katalog-produk.destroy', $katalog->id_katalog) }}" method="POST" class="d-inline" id="deleteForm{{ $katalog->id_katalog }}">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-label-danger btn-sm" onclick="confirmDelete({{ $katalog->id_katalog }})">
              <i class="icon-base ri ri-delete-bin-line"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>
<!--/ Produk Cards -->

<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">
    @if($katalogs->onFirstPage())
      <li class="page-item prev disabled">
        <span class="page-link"><i class="icon-base ri ri-arrow-left-s-line"></i></span>
      </li>
    @else
      <li class="page-item prev">
        <a class="page-link" href="{{ $katalogs->previousPageUrl() }}"><i class="icon-base ri ri-arrow-left-s-line"></i></a>
      </li>
    @endif

    @foreach($katalogs->getUrlRange(1, $katalogs->lastPage()) as $page => $url)
      @if($page == $katalogs->currentPage())
        <li class="page-item active">
          <span class="page-link">{{ $page }}</span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
      @endif
    @endforeach

    @if($katalogs->hasMorePages())
      <li class="page-item next">
        <a class="page-link" href="{{ $katalogs->nextPageUrl() }}"><i class="icon-base ri ri ri-arrow-right-s-line"></i></a>
      </li>
    @else
      <li class="page-item next disabled">
        <span class="page-link"><i class="icon-base ri ri-arrow-right-s-line"></i></span>
      </li>
    @endif
  </ul>
</nav>
@else
<div class="alert alert-info text-center">
  <p class="mb-0">Belum ada produk dalam katalog. Silakan tambah produk baru.</p>
</div>
@endif
<!-- Modal Tambah Produk -->
    <div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Produk</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formTambahProduk" action="{{ route('dashboard.katalog-produk.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="namaProduk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="namaProduk" name="nama_katalog" placeholder="Contoh: Buku Panduan Web Development" required />
                </div>
                <div class="col-12 mb-3">
                  <label for="deskripsiProduk" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="deskripsiProduk" name="deskripsi" rows="3" placeholder="Jelaskan tentang produk ini..." required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="hargaProduk" class="form-label">Harga <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" id="hargaProduk" name="harga" placeholder="125000" step="0.01" required />
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="stokProduk" class="form-label">Stok <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="stokProduk" name="stok" placeholder="45" min="0" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="gambarProduk" class="form-label">Gambar</label>
                  <input type="file" class="form-control" id="gambarProduk" name="gambar" accept="image/*" />
                  <small class="text-body-secondary">Format: JPG, PNG, Max 2MB</small>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="statusProduk" class="form-label">Status</label>
                  <select class="form-select" id="statusProduk" name="status">
                    <option value="Tersedia" selected>Tersedia</option>
                    <option value="Tidak Tersedia">Tidak Tersedia</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="submit" form="formTambahProduk" class="btn btn-primary">
              Simpan
            </button>
          </div>
        </div>
      </div>
    </div>
    <!--/ Modal Tambah Produk -->

    <!-- Modal Edit Produk -->
    <div class="modal fade" id="modalEditProduk" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Produk</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formEditProduk" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" id="editIdProduk" name="id_katalog">
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="editNamaProduk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="editNamaProduk" name="nama_katalog" required />
                </div>
                <div class="col-12 mb-3">
                  <label for="editDeskripsiProduk" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="editDeskripsiProduk" name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editHargaProduk" class="form-label">Harga <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" id="editHargaProduk" name="harga" step="0.01" required />
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editStokProduk" class="form-label">Stok <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="editStokProduk" name="stok" min="0" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editGambarProduk" class="form-label">Gambar</label>
                  <input type="file" class="form-control" id="editGambarProduk" name="gambar" accept="image/*" />
                  <small class="text-body-secondary">Format: JPG, PNG, Max 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editStatusProduk" class="form-label">Status</label>
                  <select class="form-select" id="editStatusProduk" name="status">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Tidak Tersedia">Tidak Tersedia</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="submit" form="formEditProduk" class="btn btn-primary">
              Update
            </button>
          </div>
        </div>
      </div>
    </div>
<!--/ Modal Edit Produk -->
@endsection

@push('scripts')
<script>
  let katalogsData = @json($katalogs->items());

  function editProduk(id) {
    const katalog = katalogsData.find(k => k.id_katalog == id);
    if (!katalog) {
      // Fetch data if not in current page
      fetch(`/dashboard/katalog-produk/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          populateEditForm(data);
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Gagal memuat data produk');
        });
      return;
    }
    populateEditForm(katalog);
  }

  function populateEditForm(katalog) {
    document.getElementById('editIdProduk').value = katalog.id_katalog;
    document.getElementById('editNamaProduk').value = katalog.nama_katalog;
    document.getElementById('editDeskripsiProduk').value = katalog.deskripsi;
    document.getElementById('editHargaProduk').value = katalog.harga;
    document.getElementById('editStokProduk').value = katalog.stok;
    document.getElementById('editStatusProduk').value = katalog.status;
    
    // Set form action
    const form = document.getElementById('formEditProduk');
    form.action = `/dashboard/katalog-produk/${katalog.id_katalog}`;
  }

  function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
      document.getElementById('deleteForm' + id).submit();
    }
  }

  // Reset form when modal is closed
  document.getElementById('modalTambahProduk').addEventListener('hidden.bs.modal', function () {
    document.getElementById('formTambahProduk').reset();
  });

  document.getElementById('modalEditProduk').addEventListener('hidden.bs.modal', function () {
    document.getElementById('formEditProduk').reset();
  });
</script>
@endpush
