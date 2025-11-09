@extends('layouts.admin')

@section('title', 'Kursus | Sistem Kursus Komputer')

@section('content')
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                  <span class="text-muted fw-light">Katalog /</span> Kursus
                </h4>
                <button
                  type="button"
                  class="btn btn-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#modalTambahKursus">
                  <i class="icon-base ri ri-add-line me-2"></i>
                  Tambah Kursus
                </button>
              </div>

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

<!-- Kursus Cards -->
<div class="row g-4 mb-4">
  @forelse($kursus as $item)
    <div class="col-md-6 col-lg-4">
      <div class="card h-100">
        @if($item->gambar && $item->gambar !== 'default.jpg')
          <img
            class="card-img-top"
            src="{{ asset($item->gambar) }}"
            alt="{{ $item->nama_kursus }}"
            style="height: 200px; object-fit: cover;" />
        @endif
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <h5 class="card-title mb-0">{{ $item->nama_kursus }}</h5>
              @if($item->kode_kursus)
                <small class="text-muted">Kode: {{ $item->kode_kursus }}</small>
              @endif
            </div>
            <span class="badge {{ $item->status === 'aktif' ? 'bg-label-success' : 'bg-label-warning' }}">
              {{ ucfirst($item->status) }}
            </span>
          </div>
          <p class="card-text text-body-secondary">
            {{ Str::limit($item->deskripsi, 100) }}
          </p>
          <div class="mb-3">
            <div class="d-flex align-items-center mb-1">
              <i class="icon-base ri ri-time-line me-2"></i>
              <small class="text-body-secondary">Durasi: {{ $item->durasi }}</small>
            </div>
            <div class="d-flex align-items-center">
              <i class="icon-base ri ri-money-dollar-circle-line me-2"></i>
              <small class="text-body-secondary fw-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</small>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-label-primary btn-sm flex-fill" 
                    data-bs-toggle="modal" 
                    data-bs-target="#modalEditKursus"
                    onclick="editKursus({{ $item->id_kursus }}, '{{ $item->nama_kursus }}', '{{ addslashes($item->deskripsi) }}', {{ $item->harga }}, '{{ $item->durasi }}', '{{ $item->status }}', '{{ $item->gambar }}')">
              <i class="icon-base ri ri-pencil-line me-1"></i>
              Edit
            </button>
            <form action="{{ route('dashboard.kursus.destroy', $item->id_kursus) }}" method="POST" class="d-inline" id="deleteForm{{ $item->id_kursus }}">
              @csrf
              @method('DELETE')
              <button type="button" class="btn btn-label-danger btn-sm" onclick="confirmDelete({{ $item->id_kursus }}, '{{ $item->nama_kursus }}')">
                <i class="icon-base ri ri-delete-bin-line"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12">
      <div class="alert alert-info text-center">
        <i class="icon-base ri ri-information-line me-2"></i>
        Belum ada kursus yang ditambahkan. Silakan tambah kursus baru.
      </div>
    </div>
  @endforelse
</div>
              <!--/ Kursus Cards -->

              <!-- Pagination -->
              @if($kursus->hasPages())
                <nav aria-label="Page navigation">
                  <ul class="pagination justify-content-center">
                    {{ $kursus->links() }}
                  </ul>
                </nav>
              @endif
              <!--/ Pagination -->

    <!-- Modal Tambah Kursus -->
    <div class="modal fade" id="modalTambahKursus" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Kursus</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formTambahKursus" action="{{ route('dashboard.kursus.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="namaKursus" class="form-label">Nama Kursus <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="namaKursus" name="nama_kursus" placeholder="Masukkan nama kursus" required />
                  @error('nama_kursus')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12 mb-3">
                  <label for="deskripsiKursus" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="deskripsiKursus" name="deskripsi" rows="4" placeholder="Masukkan deskripsi kursus" required></textarea>
                  @error('deskripsi')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="hargaKursus" class="form-label">Harga <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" id="hargaKursus" name="harga" placeholder="2500000" required />
                  </div>
                  @error('harga')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="durasiKursus" class="form-label">Durasi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="durasiKursus" name="durasi" placeholder="Contoh: 3 bulan" required />
                  @error('durasi')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="gambarKursus" class="form-label">Gambar <span class="text-danger">*</span></label>
                  <input type="file" class="form-control" id="gambarKursus" name="gambar" accept="image/*" required />
                  <small class="text-body-secondary">Format: JPG, PNG, Max 2MB</small>
                  @error('gambar')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="statusKursus" class="form-label">Status</label>
                  <select class="form-select" id="statusKursus" name="status">
                    <option value="aktif" selected>Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                  </select>
                  @error('status')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="submit" form="formTambahKursus" class="btn btn-primary">
              Simpan
            </button>
          </div>
        </div>
      </div>
    </div>
    <!--/ Modal Tambah Kursus -->

    <!-- Modal Edit Kursus -->
    <div class="modal fade" id="modalEditKursus" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Kursus</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formEditKursus" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" id="editIdKursus" name="id_kursus">
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="editNamaKursus" class="form-label">Nama Kursus <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="editNamaKursus" name="nama_kursus" required />
                  @error('nama_kursus')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12 mb-3">
                  <label for="editDeskripsiKursus" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="editDeskripsiKursus" name="deskripsi" rows="4" required></textarea>
                  @error('deskripsi')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editHargaKursus" class="form-label">Harga <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" id="editHargaKursus" name="harga" required />
                  </div>
                  @error('harga')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editDurasiKursus" class="form-label">Durasi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="editDurasiKursus" name="durasi" required />
                  @error('durasi')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editGambarKursus" class="form-label">Gambar</label>
                  <input type="file" class="form-control" id="editGambarKursus" name="gambar" accept="image/*" />
                  <small class="text-body-secondary">Format: JPG, PNG, Max 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                  <div id="currentImage" class="mt-2"></div>
                  @error('gambar')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="editStatusKursus" class="form-label">Status</label>
                  <select class="form-select" id="editStatusKursus" name="status">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                  </select>
                  @error('status')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="submit" form="formEditKursus" class="btn btn-primary">
              Update
            </button>
          </div>
        </div>
      </div>
    </div>
    <!--/ Modal Edit Kursus -->
@endsection

@push('scripts')
    <script>
      // Reset form when modal is closed
      document.getElementById('modalTambahKursus').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formTambahKursus').reset();
      });

      // Function to populate edit form
      function editKursus(id, nama, deskripsi, harga, durasi, status, gambar) {
        document.getElementById('editIdKursus').value = id;
        document.getElementById('editNamaKursus').value = nama;
        document.getElementById('editDeskripsiKursus').value = deskripsi;
        document.getElementById('editHargaKursus').value = harga;
        document.getElementById('editDurasiKursus').value = durasi;
        document.getElementById('editStatusKursus').value = status;
        
        // Set form action
        document.getElementById('formEditKursus').action = '{{ route("dashboard.kursus.update", ":id") }}'.replace(':id', id);
        
        // Show current image if exists
        const currentImageDiv = document.getElementById('currentImage');
        if (gambar && gambar !== 'default.jpg') {
          const imagePath = gambar.startsWith('images/') ? gambar : 'images/' + gambar;
          currentImageDiv.innerHTML = `
            <small class="text-body-secondary">Gambar saat ini:</small><br>
            <img src="{{ url('/') }}/${imagePath}" alt="Current Image" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 4px; margin-top: 5px;">
          `;
        } else {
          currentImageDiv.innerHTML = '';
        }
      }

      // Function to confirm delete
      function confirmDelete(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus kursus "${nama}"?`)) {
          document.getElementById('deleteForm' + id).submit();
        }
      }
    </script>
@endpush
