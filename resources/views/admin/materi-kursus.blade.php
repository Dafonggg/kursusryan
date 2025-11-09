@extends('layouts.admin')

@section('title', 'Materi Kursus | Sistem Kursus Komputer')

@section('content')
<h4 class="fw-bold mb-4">
  <span class="text-muted fw-light">Konten /</span> Materi Kursus
</h4>

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

@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- Upload Materi Card -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-0">Tambah Materi Baru</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('dashboard.materi-kursus.store') }}" method="POST" enctype="multipart/form-data" id="formTambahMateri">
      @csrf
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="id_kursus" class="form-label">Assign ke Kursus <span class="text-danger">*</span></label>
          <select class="form-select" id="id_kursus" name="id_kursus" required>
            <option value="">Pilih Kursus</option>
            @foreach($kursus as $k)
              <option value="{{ $k->id_kursus }}" {{ old('id_kursus') == $k->id_kursus ? 'selected' : '' }}>{{ $k->nama_kursus }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label for="jenis_file" class="form-label">Jenis File <span class="text-danger">*</span></label>
          <select class="form-select" id="jenis_file" name="jenis_file" required>
            <option value="pdf" {{ old('jenis_file') == 'pdf' ? 'selected' : '' }}>PDF</option>
            <option value="doc" {{ old('jenis_file') == 'doc' ? 'selected' : '' }}>DOC/DOCX</option>
            <option value="ppt" {{ old('jenis_file') == 'ppt' ? 'selected' : '' }}>PPT/PPTX</option>
            <option value="video" {{ old('jenis_file') == 'video' ? 'selected' : '' }}>Video Link</option>
            <option value="link" {{ old('jenis_file') == 'link' ? 'selected' : '' }}>Link Eksternal</option>
          </select>
        </div>
        <div class="col-md-6 mb-3" id="fileUploadGroup">
          <label for="file_materin" class="form-label">Upload File <span class="text-danger">*</span></label>
          <input type="file" class="form-control" id="file_materin" name="file_materin" accept=".pdf,.doc,.docx,.ppt,.pptx" />
          <small class="text-body-secondary">Format: PDF, DOC, PPT. Max 10MB</small>
        </div>
        <div class="col-12 mb-3" id="linkVideoGroup" style="display: none;">
          <label for="link_video" class="form-label">Link Video (YouTube/Vimeo) <span class="text-danger">*</span></label>
          <input type="url" class="form-control" id="link_video" name="link_video" value="{{ old('link_video') }}" placeholder="https://www.youtube.com/watch?v=..." />
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ri ri-save-line me-2"></i>
            Simpan Materi
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
<!--/ Upload Materi Card -->

<!-- Materi Kursus Table -->
<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between gap-3">
    <div class="card-title mb-0 me-1">
      <h5 class="mb-1">Daftar Materi Kursus</h5>
      <p class="text-body-secondary mb-0">Kelola semua materi pembelajaran</p>
    </div>
    <div class="d-flex align-items-center">
      <input
        type="text"
        class="form-control"
        placeholder="Cari materi..."
        id="searchInput" />
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Kursus</th>
            <th>Jenis File</th>
            <th>File/Link</th>
            <th>Tanggal Upload</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($materi as $index => $item)
            <tr>
              <td>{{ $materi->firstItem() + $index }}</td>
              <td>
                <div class="d-flex align-items-center">
                  @if($item->jenis_file == 'pdf')
                    <i class="icon-base ri ri-file-pdf-line text-danger me-2"></i>
                  @elseif($item->jenis_file == 'doc')
                    <i class="icon-base ri ri-file-word-2-line text-primary me-2"></i>
                  @elseif($item->jenis_file == 'ppt')
                    <i class="icon-base ri ri-presentation-line text-warning me-2"></i>
                  @elseif(in_array($item->jenis_file, ['video', 'link']))
                    <i class="icon-base ri ri-youtube-line text-danger me-2"></i>
                  @endif
                  <div>
                    <h6 class="mb-0">{{ $item->kursus->nama_kursus ?? 'N/A' }}</h6>
                    <small class="text-body-secondary">{{ ucfirst($item->jenis_file) }}</small>
                  </div>
                </div>
              </td>
              <td>
                @if($item->jenis_file == 'pdf')
                  <span class="badge bg-label-danger">PDF</span>
                @elseif($item->jenis_file == 'doc')
                  <span class="badge bg-label-primary">DOC</span>
                @elseif($item->jenis_file == 'ppt')
                  <span class="badge bg-label-warning">PPT</span>
                @elseif($item->jenis_file == 'video')
                  <span class="badge bg-label-info">Video</span>
                @elseif($item->jenis_file == 'link')
                  <span class="badge bg-label-secondary">Link</span>
                @endif
              </td>
              <td>
                @if($item->file_materin)
                  <a href="{{ asset($item->file_materin) }}" target="_blank" class="text-primary">
                    <i class="icon-base ri ri-download-line me-1"></i>
                    {{ basename($item->file_materin) }}
                  </a>
                @elseif($item->link_video)
                  <a href="{{ $item->link_video }}" target="_blank" class="text-primary">
                    <i class="icon-base ri ri-external-link-line me-1"></i>
                    {{ Str::limit($item->link_video, 30) }}
                  </a>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>{{ $item->created_at->format('d M Y') }}</td>
              <td>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-label-primary" 
                          data-bs-toggle="modal" 
                          data-bs-target="#modalEditMateri"
                          onclick="editMateri({{ $item->id_materin }}, {{ $item->id_kursus }}, '{{ $item->jenis_file }}', '{{ $item->link_video ?? '' }}')">
                    <i class="icon-base ri ri-pencil-line"></i>
                  </button>
                  <form action="{{ route('dashboard.materi-kursus.destroy', $item->id_materin) }}" method="POST" class="d-inline" id="deleteForm{{ $item->id_materin }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-label-danger" onclick="confirmDelete({{ $item->id_materin }})">
                      <i class="icon-base ri ri-delete-bin-line"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">
                <div class="alert alert-info mb-0">
                  <i class="icon-base ri ri-information-line me-2"></i>
                  Belum ada materi yang ditambahkan. Silakan tambah materi baru.
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($materi->hasPages())
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          @if($materi->onFirstPage())
            <li class="page-item disabled">
              <span class="page-link"><i class="icon-base ri ri-arrow-left-s-line"></i></span>
            </li>
          @else
            <li class="page-item">
              <a class="page-link" href="{{ $materi->previousPageUrl() }}"><i class="icon-base ri ri-arrow-left-s-line"></i></a>
            </li>
          @endif

          @for($page = 1; $page <= $materi->lastPage(); $page++)
            @if($page == $materi->currentPage())
              <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link" href="{{ $materi->url($page) }}">{{ $page }}</a>
              </li>
            @endif
          @endfor

          @if($materi->hasMorePages())
            <li class="page-item">
              <a class="page-link" href="{{ $materi->nextPageUrl() }}"><i class="icon-base ri ri-arrow-right-s-line"></i></a>
            </li>
          @else
            <li class="page-item disabled">
              <span class="page-link"><i class="icon-base ri ri-arrow-right-s-line"></i></span>
            </li>
          @endif
        </ul>
      </nav>
    @endif
  </div>
</div>
<!--/ Materi Kursus Table -->

<!-- Modal Edit Materi -->
<div class="modal fade" id="modalEditMateri" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Materi Kursus</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditMateri" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" id="editIdMaterin" name="id_materin">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="editIdKursus" class="form-label">Assign ke Kursus <span class="text-danger">*</span></label>
              <select class="form-select" id="editIdKursus" name="id_kursus" required>
                <option value="">Pilih Kursus</option>
                @foreach($kursus as $k)
                  <option value="{{ $k->id_kursus }}">{{ $k->nama_kursus }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="editJenisFile" class="form-label">Jenis File <span class="text-danger">*</span></label>
              <select class="form-select" id="editJenisFile" name="jenis_file" required>
                <option value="pdf">PDF</option>
                <option value="doc">DOC/DOCX</option>
                <option value="ppt">PPT/PPTX</option>
                <option value="video">Video Link</option>
                <option value="link">Link Eksternal</option>
              </select>
            </div>
            <div class="col-md-6 mb-3" id="editFileUploadGroup">
              <label for="editFileMaterin" class="form-label">Upload File Baru (Opsional)</label>
              <input type="file" class="form-control" id="editFileMaterin" name="file_materin" accept=".pdf,.doc,.docx,.ppt,.pptx" />
              <small class="text-body-secondary">Format: PDF, DOC, PPT. Max 10MB</small>
            </div>
            <div class="col-12 mb-3" id="editLinkVideoGroup" style="display: none;">
              <label for="editLinkVideo" class="form-label">Link Video (Jika jenis Video) <span class="text-danger">*</span></label>
              <input type="url" class="form-control" id="editLinkVideo" name="link_video" placeholder="https://www.youtube.com/watch?v=..." />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="button" class="btn btn-primary" onclick="updateMateri()">
          Update
        </button>
      </div>
    </div>
  </div>
</div>
<!--/ Modal Edit Materi -->
@endsection

@push('scripts')
<script>
  // Toggle file upload / video link based on file type (Create Form)
  document.getElementById('jenis_file')?.addEventListener('change', function(e) {
    const fileUploadGroup = document.getElementById('fileUploadGroup');
    const linkVideoGroup = document.getElementById('linkVideoGroup');
    
    if (e.target.value === 'video' || e.target.value === 'link') {
      fileUploadGroup.style.display = 'none';
      linkVideoGroup.style.display = 'block';
      document.getElementById('file_materin').removeAttribute('required');
      document.getElementById('link_video').setAttribute('required', 'required');
    } else {
      fileUploadGroup.style.display = 'block';
      linkVideoGroup.style.display = 'none';
      document.getElementById('file_materin').setAttribute('required', 'required');
      document.getElementById('link_video').removeAttribute('required');
    }
  });

  // Toggle file upload / video link based on file type (Edit Form)
  document.getElementById('editJenisFile')?.addEventListener('change', function(e) {
    const editFileUploadGroup = document.getElementById('editFileUploadGroup');
    const editLinkVideoGroup = document.getElementById('editLinkVideoGroup');
    
    if (e.target.value === 'video' || e.target.value === 'link') {
      editFileUploadGroup.style.display = 'none';
      editLinkVideoGroup.style.display = 'block';
      document.getElementById('editLinkVideo').setAttribute('required', 'required');
    } else {
      editFileUploadGroup.style.display = 'block';
      editLinkVideoGroup.style.display = 'none';
      document.getElementById('editLinkVideo').removeAttribute('required');
    }
  });

  // Edit Materi Function
  function editMateri(idMaterin, idKursus, jenisFile, linkVideo) {
    document.getElementById('editIdMaterin').value = idMaterin;
    document.getElementById('editIdKursus').value = idKursus;
    document.getElementById('editJenisFile').value = jenisFile;
    
    const editFileUploadGroup = document.getElementById('editFileUploadGroup');
    const editLinkVideoGroup = document.getElementById('editLinkVideoGroup');
    
    if (jenisFile === 'video' || jenisFile === 'link') {
      editFileUploadGroup.style.display = 'none';
      editLinkVideoGroup.style.display = 'block';
      document.getElementById('editLinkVideo').value = linkVideo || '';
      document.getElementById('editLinkVideo').setAttribute('required', 'required');
    } else {
      editFileUploadGroup.style.display = 'block';
      editLinkVideoGroup.style.display = 'none';
      document.getElementById('editLinkVideo').removeAttribute('required');
    }
    
    // Update form action
    const form = document.getElementById('formEditMateri');
    const baseUrl = '{{ url("/dashboard/materi-kursus") }}';
    form.action = baseUrl + '/' + idMaterin;
  }

  // Update Materi Function
  function updateMateri() {
    const form = document.getElementById('formEditMateri');
    form.submit();
  }

  // Confirm Delete Function
  function confirmDelete(idMaterin) {
    if (confirm('Apakah Anda yakin ingin menghapus materi ini?')) {
      const form = document.getElementById('deleteForm' + idMaterin);
      if (form) {
        form.submit();
      }
    }
  }

  // Search Function
  document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  });
</script>
@endpush

