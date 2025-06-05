@extends('quiz.content.index') {{-- Menggunakan layout utama quiz --}}

@section('quiz-content')
    <div class="quiz-container">
        <div class="row">
            <div class="col-md-6">
                <h4 class="fw-semibold" style="color: #5E5E5E;">Daftar Bank Kuis</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3 flex-wrap">
                <!-- Search Bar -->
                <div class="input-group shadow-sm" style="max-width: 300px; border-radius: 8px; border: 1px solid #E7E7E7;">
                    <span class="input-group-text bg-white border-0" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-search text-muted" style="opacity: 0.6;"></i>
                    </span>
                    <input type="text" id="searchBank" class="form-control border-0"
                        placeholder="Cari bank ..." onkeyup="filterSessions()"
                        style="border-radius: 0 8px 8px 0; font-size: 0.95rem;">
                </div>

                <!-- Add Button -->
                <button data-bs-toggle="modal" data-bs-target="#addBankModal" class="btn btn-green d-flex align-items-center">
                    <i class="fas fa-plus me-2"></i>Tambah
                </button>
            </div>

        </div>
        <div id="diplayCardQuizQuestionBank" class="row mt-3">

        </div>
    </div>

    {{-- Modal Tambah Bank Soal --}}
    <div class="modal fade" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-black text-lg fw-bold">Tambah Bank Soal</h5>
                    <form id="addBankForm">
                        <div class="mb-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="bankName" placeholder="Masukan nama bank soal">
                                <label for="bankName" class="text-black">Nama Bank Soal</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBankModal" tabindex="-1" aria-labelledby="editBankModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-black text-lg fw-bold">Perbarui Bank Soal</h5>
                    <form id="editBankForm">
                        <div class="mb-3">
                            <input type="text" id="id-bank" class="d-none">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editbankName" placeholder="Masukan nama bank soal">
                                <label for="bankName" class="text-black">Nama Bank Soal</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        $(document).ready(function () {
            const apiClient = new HttpClient('{{ route("quiz-question-bank.index") }}');

            function renderCard(filterText = '') {
                apiClient.request('GET', '')
                    .then(response => {
                        allBanks = response.response.data; // Simpan hasil API di array global
                        updateDisplay(allBanks, filterText);
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                        $('#diplayCardQuizQuestionBank').html('<p class="text-danger">Gagal memuat data.</p>');
                    });
            }

            function updateDisplay(data, filterText = '') {
                let displayDiv = $('#diplayCardQuizQuestionBank');
                displayDiv.empty();

                // Pastikan filterText diubah menjadi string kecil (lowercase)
                let filteredData = data.filter(bank =>
                    bank.name.toLowerCase().includes(filterText.toLowerCase())
                );

                if (filteredData.length === 0) {
                    displayDiv.html('<p class="text-center text-white">Tidak ada hasil ditemukan.</p>');
                    return;
                }

                let htmlContent = '';
                filteredData.forEach(bank => {
                    htmlContent += `
                        <div class="col-md-4">
                            <div class="mb-3" style="border-radius: 16px; background-color: white;padding: 20px;">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title text-dark fw-bold text-sm text-md-lg mb-1">${bank.name}</h5>
                                    </div>
                                <div class="d-md-flex gap-2 mb-3">
                                    <div class="text-muted text-sm"><i class="bi bi-calendar-date me-2"></i>${formatDate(bank.created_at)}</div>
                                    <div class="text-muted text-sm"><i class="bi bi-stopwatch-fill me-2"></i>${bank.questions_count} Soal</div>
                                </div>
                                <div class="d-flex gap-2 mt-3 mt-md-0 justify-content-end">
                                    <a href="/quiz-question-bank/${bank.id}" style="border-radius:8px" class="btn btn-success btn-sm showBank" data-id="${bank.id}" ${bank.questions_count == 0 ? 'disabled' : ''}>
                                        <i class="fas me-2 fa-list"></i> Soal
                                    </a>
                                    <button style="border-radius:8px" class="btn btn-warning btn-sm editBank" data-id="${bank.id}" data-name="${bank.name}">
                                        <i class="fas me-2 fa-pencil"></i> Edit
                                    </button>
                                    <button style="border-radius:8px" class="btn btn-danger btn-sm deleteBank" data-id="${bank.id}">
                                        <i class="fas me-2 fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                displayDiv.html(htmlContent);
            }


            $('#searchBank').on('input', function () {
                let searchText = $(this).val().trim();
                updateDisplay(allBanks, searchText);
            });


            $(document).on('submit', '#addBankForm', function (e) {
                e.preventDefault();

                let $form = $(this);
                let bankName = $form.find('#bankName').val().trim();

                if (!bankName) {
                    toastr.warning("Nama bank soal tidak boleh kosong");
                    return;
                }

                let newBank = {
                    _token: "{{ csrf_token() }}",
                    name: bankName
                };

                apiClient.request('POST', '', newBank)
                    .then(response => {

                        toastr.success("✅ Bank soal berhasil disimpan! Anda dapat melihatnya di daftar bank soal.", "", { timeOut: 5000 });

                        $('#addBankModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $form.trigger("reset");

                        renderCard();
                    })
                    .catch(error => {
                        console.error('Error:', error);  // 🔍 Cek error yang muncul
                        if (error.response) {
                            toastr.error("❌ Terjadi kesalahan saat menyimpan bank soal. Silakan coba lagi.", "", { timeOut: 5000 });
                        } else {
                            toastr.error("Terjadi kesalahan jaringan atau server tidak merespons");
                        }
                    });

            });

            $(document).on('submit', '#editBankForm', function (e) {
                e.preventDefault();

                let $form = $(this);
                let bankName = $form.find('#editbankName').val().trim();
                let id = $form.find('#id-bank').val().trim();

                if (!bankName) {
                    toastr.warning("Nama bank soal tidak boleh kosong");
                    return;
                }

                let newBank = {
                    _token: "{{ csrf_token() }}",
                    name: bankName
                };

                apiClient.request('PUT', `/${id}`, newBank)
                    .then(response => {
                        toastr.success("✅ Bank soal berhasil diperbarui! Data telah diperbarui.", "", { timeOut: 5000 });

                        $('#editBankModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $form.trigger("reset");

                        renderCard();
                    })
                    .catch(error => {
                        toastr.error("❌ Gagal memperbarui bank soal. Silakan coba lagi.", "", { timeOut: 5000 });
                        console.error('Error:', error);
                    });
            });

            $(document).on('click', '.editBank', function (e) {
                let id = $(this).data('id');
                let name = $(this).data('name');

                $('#editBankModal').modal('show');
                $('#id-bank').val(id);
                $('#editbankName').val(name);
            });

            $(document).on('click', '.deleteBank', function (e) {
                e.preventDefault();

                let id = $(this).data('id');

                if (!id) {
                    toastr.warning("ID Bank Soal tidak ditemukan!");
                    return;
                }

                confirmationModal.open({
                    message: 'Apakah anda yakin ingin menghapus bank ini?',
                    severity: 'warn',
                    onAccept: () => {
                        apiClient.request('DELETE', `/${id}`, { _token: "{{ csrf_token() }}" })
                            .then(response => {
                                toastr.success("✅ Bank soal berhasil dihapus! Data telah diperbarui.", "", { timeOut: 5000 });

                                renderCard();
                            })
                            .catch(error => {
                                toastr.error("❌ Gagal menghapus bank soal. Silakan coba lagi.", "", { timeOut: 5000 });
                                console.error('Error:', error);
                            });
                    },
                    onReject: () => {

                    },
                });
            });


            renderCard();
        });

    </script>
@endsection
