<div class="sidebar sidebar-mediko-quiz" style="overflow: hidden;" id="sidebar">
    <div class="flex justify-content-start header-icon w-full h-full pt-4">
        <a class="flex items-center">
            <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="63%">
        </a>
    </div>
    <div class="menu">
        <div class="header-menu mt-2">
            <div class="header-menu-title mb-2">
                <h5>Menu Utama</h5>
            </div>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('quiz.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz.index') ? 'active' : '' }}">
                <i class="fas fa-home me-2" style="width: 24px;text-align:center;"></i>
                <span>Beranda</span>
            </a>
        </div>
        @can('viewAny', [App\Models\User::class,'quiz-session.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-session.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-session.index') ? 'active' : '' }}">
                <i class="fas fa-calendar me-2" style="width: 24px;text-align:center;"></i>
                <span>Sesi Kuis</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-question-bank.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-question-bank.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-question-bank.index') ? 'active' : '' }}">
                <i class="fas fa-bank me-2" style="width: 24px;text-align:center;"></i>
                <span>Bank Soal</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-question.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-question.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-question.index') ? 'active' : '' }}">
                <i class="fas fa-bullseye me-2" style="width: 24px;text-align:center;"></i>
                <span>Buat Kuis</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-result.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-result.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-result.index') ? 'active' : '' }}">
                <i class="bi bi-card-checklist me-2" style="width: 24px;text-align:center;"></i>
                <span>Pembahasan</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-classroom.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-classroom.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-classroom.index') ? 'active' : '' }}">
                <i class="fas fa-chalkboard me-2" style="width: 24px;text-align:center;"></i>
                <span>Kelas</span>
            </a>
        </div>
        @endcan
        <div class="header-menu mt-2">
            <div class="header-menu-title mb-2">
                <h5>Perpustakaan</h5>
            </div>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('library.index', ['filter' => 'all']) }}"
            class="flex align-items-center justify-content-start {{ request('filter') === 'all' ? 'active' : '' }}">
                <i class="bi bi-grid-fill me-2" style="width: 24px; text-align:center;"></i>
                <span>Semua</span>
            </a>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('library.index', ['filter' => 'like']) }}"
            class="flex align-items-center justify-content-start {{ request()->get('filter') === 'like' ? 'active' : '' }}">
                <i class="bi bi-heart me-2" style="width: 24px; text-align:center;"></i>
                <span>Disukai</span>
            </a>
        </div>

        @if ($collections->isNotEmpty())
            @foreach ($collections as $collection)
                <div class="w-full menu-title">
                    <a href="{{ route('library.show', $collection->id) }}"
                    class="flex align-items-center justify-content-start {{ request()->segment(2) == $collection->id ? 'active' : '' }}">
                        <i class="bis bi-folder-fill me-2" style="width: 24px; text-align:center;"></i>
                        <span>{{ $collection->folder_name }}</span>
                    </a>
                </div>
            @endforeach
        @else
            <div class="text-center text-muted mt-3">
                <button class="btn btn-green w-full" data-bs-toggle="modal" data-bs-target="#addCollectionModal">
                    <i class="fas fa-folder-plus me-2"></i>Tambah Koleksi
                </button>
            </div>
        @endif
    </div>

    <div class="footer">
        <div class="text-center" style="font-size: 12px">© 2024 MEDIKO.ID Hak cipta dilindungi undang-undang.</div>
    </div>
</div>

<!-- Modal Tambah Koleksi -->
<div class="modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <div class="modal-header bg-white border-0 rounded-top-4 px-4 pt-4">
        <h5 class="modal-title fw-semibold text-dark" id="addCollectionModalLabel">Tambah Koleksi Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body px-4 pt-0 pb-3">
        <form id="collectionForm" novalidate>
          <div class="mb-3">
            <label for="folderName" class="form-label fw-semibold">Nama Koleksi</label>
            <input type="text" class="form-control form-control-lg rounded-3" id="folderName" placeholder="Masukkan nama koleksi" required>
            <div class="invalid-feedback">Nama koleksi tidak boleh kosong.</div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 px-4 pb-4">
        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success px-4" id="saveCollection">Simpan</button>
      </div>
    </div>
  </div>
</div>


<script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        document.getElementById('saveCollection').addEventListener('click', function() {
            let folderNameInput = document.getElementById('folderName');
            let folderName = folderNameInput.value.trim();

            if (folderName === "") {
                folderNameInput.classList.add('is-invalid');
                return;
            } else {
                folderNameInput.classList.remove('is-invalid');
            }

            let data = {
                folder_name: folderName,
                _token:"{{ csrf_token() }}"
             };

            const apiClient = new HttpClient('{{ route("library-folder.index") }}');

            apiClient.request('POST', '', data)
                .then(response => {

                    toastr.success("Koleksi berhasil ditambahkan!", "Sukses", {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });

                    document.getElementById('collectionForm').reset();
                    let modal = bootstrap.Modal.getInstance(document.getElementById('addCollectionModal'));
                    modal.hide();
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

                })
                .catch(error => {
                    console.error('Error:', error);

                    if (error.response) {
                        toastr.error(error.response.data.message || "Terjadi kesalahan saat menyimpan data", "Error");
                    } else {
                        toastr.error("Terjadi kesalahan jaringan atau server tidak merespons", "Error");
                    }
                });
        });
    </script>