<div class="sidebar sidebar-mediko-quiz" style="overflow: hidden;" id="sidebar">
    <div class="flex justify-content-center mb-3 header-icon w-full h-full py-2">
        <a class="flex items-center">
            <div class="flex justify-content-center gap-2 p-2 rounded-lg bg-white shadow-md">
                <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="63%">
            </div>
        </a>
    </div>
    <div class="menu">
        <div class="header-menu mt-2">
            <div class="header-menu-title mb-2">
                <h5>Beranda</h5>
            </div>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('quiz.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz.index') ? 'active' : '' }}">
                <i class="fas fa-home me-3" style="width: 24px;text-align:center;"></i>
                <span>Dashboard</span>
            </a>
        </div>
        @can('viewAny', [App\Models\User::class,'quiz-session.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-session.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-session.index') ? 'active' : '' }}">
                <i class="fas fa-calendar me-3" style="width: 24px;text-align:center;"></i>
                <span>Quiz Sesi</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-question-bank.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-question-bank.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-question-bank.index') ? 'active' : '' }}">
                <i class="fas fa-bank me-3" style="width: 24px;text-align:center;"></i>
                <span>Bank Soal</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-question.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-question.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-question.index') ? 'active' : '' }}">
                <i class="fas fa-bullseye me-3" style="width: 24px;text-align:center;"></i>
                <span>Buat Quiz</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-result.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-result.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-result.index') ? 'active' : '' }}">
                <i class="fas fa-check me-3" style="width: 24px;text-align:center;"></i>
                <span>Pembahasaan</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'quiz-classroom.index'])
        <div class="w-full menu-title">
            <a href="{{ route('quiz-classroom.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-classroom.index') ? 'active' : '' }}">
                <i class="fas fa-chalkboard me-3" style="width: 24px;text-align:center;"></i>
                <span>Classroom</span>
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
                <i class="bi bi-collection me-3" style="width: 24px; text-align:center;"></i>
                <span>Semua</span>
            </a>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('library.index', ['filter' => 'like']) }}"
            class="flex align-items-center justify-content-start {{ request()->get('filter') === 'like' ? 'active' : '' }}">
                <i class="bi bi-heart me-3" style="width: 24px; text-align:center;"></i>
                <span>Suka</span>
            </a>
        </div>

        @if ($collections->isNotEmpty())
            @foreach ($collections as $collection)
                <div class="w-full menu-title">
                    <a href="{{ route('library.show', $collection->id) }}"
                    class="flex align-items-center justify-content-start {{ request()->segment(2) == $collection->id ? 'active' : '' }}">
                        <i class="bi bi-folder me-3" style="width: 24px; text-align:center;"></i>
                        <span>{{ $collection->folder_name }}</span>
                    </a>
                </div>
            @endforeach
        @else
            <div class="text-center text-muted mt-3">
                <i class="bi bi-folder-x" style="font-size: 1.5rem;"></i>
                <p class="mt-2">Belum ada koleksi</p>
                <button class="btn btn-success px-3 py-2" data-bs-toggle="modal" data-bs-target="#addCollectionModal">
                    <i class="fas fa-folder-plus me-2"></i>Tambah Koleksi
                </button>
            </div>
        @endif
    </div>

    <div class="footer">
        <div class="text-center text-white" style="font-size: 12px">© 2024 MEDIKO.ID All rights reserved.</div>
    </div>
</div>

<!-- Modal Tambah Koleksi -->
<div class="modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addCollectionModalLabel">Tambah Koleksi Baru</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="collectionForm">
                        <div class="mb-3">
                            <label for="folderName" class="form-label fw-bold">Nama Koleksi</label>
                            <input type="text" class="form-control" id="folderName" placeholder="Masukkan nama koleksi">
                            <div class="invalid-feedback">Nama koleksi tidak boleh kosong.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="saveCollection">Simpan</button>
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
                        console.log('Error Response:', error.response);
                        toastr.error(error.response.data.message || "Terjadi kesalahan saat menyimpan data", "Error");
                    } else {
                        toastr.error("Terjadi kesalahan jaringan atau server tidak merespons", "Error");
                    }
                });
        });
    </script>