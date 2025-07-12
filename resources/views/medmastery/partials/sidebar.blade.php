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
            <a href="{{ route('medmastery.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('medmastery.index') ? 'active' : '' }}">
                <i class="fas fa-home me-2" style="width: 24px;text-align:center;"></i>
                <span>Beranda</span>
            </a>
        </div>
        @if(auth()->check() && auth()->user()->accessRole && auth()->user()->accessRole->access === 'public')
        <div class="w-full menu-title">
            <a href="{{ route('medmastery.history') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('medmastery.history*') ? 'active' : '' }}">
                <i class="fas fa-history me-2" style="width: 24px;text-align:center;"></i>
                <span>Riwayat Quiz</span>
            </a>
        </div>
        @endif
        @if(auth()->check() && auth()->user()->accessRole && auth()->user()->accessRole->access === 'private')
        <div class="w-full menu-title">
            <a href="{{ route('medmastery-segmentation.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('medmastery-segmentation.index') ? 'active' : '' }}">
                <i class="fas fa-layer-group me-2" style="width: 24px;text-align:center;"></i>
                <span>Bidang Kedokteran</span>
            </a>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('medmastery-category.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('medmastery-category.*') ? 'active' : '' }}">
                <i class="fas fa-tags me-2" style="width: 24px;text-align:center;"></i>
                <span>Kategori Medmastery</span>
            </a>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('medmastery-question.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('medmastery-question.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle me-2" style="width: 24px;text-align:center;"></i>
                <span>Pertanyaan Medmastery</span>
            </a>
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