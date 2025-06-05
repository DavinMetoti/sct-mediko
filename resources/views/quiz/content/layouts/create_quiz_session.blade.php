@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="d-flex justify-content-between align-items-center w-100 mb-3">
            <h4 class="fw-semibold" style="color: #5E5E5E;">Buat sesi kuis</h4>
        </div>
        <div class="card shadow-sm border-0 p-3 rounded-4 mb-4">
            <div>
                <div class="row">
                    <div class="col-12">
                        <label for="title" class="mb-1 text-muted">Judul</label>
                        <input type="text" name="title" id="title" class="form-control rounded-3" placeholder="Masukan judul">
                    </div>
                    <div class="col-12 mt-3">
                        <label for="description" class="mb-1 text-muted">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control rounded-3" placeholder="Masukan deskripsi" style="height: 80px"></textarea>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="start_time" class="mb-1 text-muted">Mulai</label>
                        <input type="text" name="start_time" id="start_time" class="form-control rounded-3" placeholder="Pilih waktu mulai">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="end_time" class="mb-1 text-muted">Berakhir</label>
                        <input type="text" name="end_time" id="end_time" class="form-control rounded-3" placeholder="Pilih waktu berakhir">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="timer" class="mb-1 text-muted">Waktu Persoal (Detik)</label>
                        <input type="number" name="timer" id="timer" class="form-control rounded-3" placeholder="Masukan durasi kuis">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="access_code" class="mb-1 text-muted">Kode Akses</label>
                        <div class="input-group">
                            <input type="text" name="access_code" id="access_code" class="form-control rounded-start-3" readonly>
                            <button type="button" id="refresh_code" class="btn btn-outline-secondary rounded-end-3" title="Generate kode baru">
                                <i class="fas fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-wrap gap-3 align-items-center mt-3">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" id="apply_timer" name="apply_timer">
                            <label class="form-check-label text-muted" for="apply_timer">Terapkan timer di semua soal</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public">
                            <label class="form-check-label text-muted" for="is_public">Sesi terlihat oleh semua peserta</label>
                        </div>
                        <div class="ms-auto text-muted small">
                            <strong>ID Sesi:</strong> <span id="session_id"></span>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-danger border" id="cancel_button"><i class="fas fa-times me-2"></i>Cancel</button>
                        <button class="btn btn-success" id="save_button"><i class="fas fa-check me-2"></i>Simpan</button>
                        <button class="btn btn-warning" hidden id="edit_button"><i class="fas fa-check me-2"></i>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h4 class="fw-semibold" style="color: #5E5E5E;">Daftar Sesi Kuis</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3 flex-wrap">
                <!-- Search Bar -->
                <div class="input-group shadow-sm" style="max-width: 300px; border-radius: 8px; border: 1px solid #E7E7E7;">
                    <span class="input-group-text bg-white border-0" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-search text-muted" style="opacity: 0.6;"></i>
                    </span>
                    <input type="text" id="searchQuizSession" class="form-control border-0"
                        placeholder="Cari kuis ..." onkeyup="filterSessions()"
                        style="border-radius: 0 8px 8px 0; font-size: 0.95rem;">
                </div>
            </div>

        </div>
        <div id="list-quiz-sessions" class="mt-3 row g-3">
            <!-- List sesi quiz akan di-render di sini -->
        </div>
    </div>

    <div class="modal fade" id="sessionModal" tabindex="-1" role="dialog" aria-labelledby="sessionModalLabel">
        <div class="modal-dialog" role="document">
            <form id="sessionForm">
                @csrf
                <input type="hidden" id="idsession" name="id" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title color-dark" style="color: black;" id="sessionModalLabel">Kelola Sesi Classroom</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sessionSelect" class="text-dark">Tambah Sesi ke Classroom</label>
                            <select id="sessionSelect" name="sessions[]" multiple="multiple" class="form-control" style="width: 100%">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="text-dark">Daftar Sesi yang Terhubung</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="attachedSessionTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kelas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Classroom -->
    <div class="modal fade" id="classroomModal" tabindex="-1" role="dialog" aria-labelledby="classroomModalLabel">
        <div class="modal-dialog" role="document">
            <form id="classroomForm">
                @csrf
                <input type="hidden" id="sessionIdForClassroom" name="session_id" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title color-dark" style="color: black;" id="classroomModalLabel">Kelola Classroom untuk Sesi Ini</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="classroomSelect" class="text-dark">Tambah Classroom ke Sesi</label>
                            <select id="classroomSelect" name="classrooms[]" multiple="multiple" class="form-control" style="width: 100%">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="text-dark">Daftar Classroom yang Terhubung</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="attachedClassroomTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Classroom</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        const apiClient = new HttpClient('{{ route("quiz-session.index") }}');
        let selectedSession;
        let classrooms = [];
        let allSessions = []; // Untuk pencarian

        document.addEventListener("DOMContentLoaded", function () {
            // Inisialisasi Datepicker
            flatpickr("#start_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
            });

            flatpickr("#end_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
            });

            function generateAccessCode() {
                return Math.random().toString(36).substring(2, 8).toUpperCase();
            }

            function generateSessionID() {
                return moment().format("YYYYMMDDHHmmss");
            }

            function fetchApi() {
                apiClient.request('GET', '')
                    .then(response => {
                        if (response.response && response.response.length > 0) {
                            allSessions = response.response; // simpan semua sesi
                            renderCard(allSessions);
                        } else {
                            document.getElementById("list-quiz-sessions").innerHTML =
                                "<p class='text-center text-muted'>Tidak ada sesi kuis.</p>";
                        }
                    })
                    .catch(() => {
                        document.getElementById("list-quiz-sessions").innerHTML =
                            "<p class='text-danger text-center'>Gagal mengambil data.</p>";
                    });
            }

            function editSession(session) {
                document.getElementById("title").value = session.title;
                document.getElementById("description").value = session.description;
                document.getElementById("start_time").value = session.start_time;
                document.getElementById("end_time").value = session.end_time;
                document.getElementById("timer").value = session.timer;
                document.getElementById("access_code").value = session.access_code;
                document.getElementById("apply_timer").checked = session.apply_all_timer === 1;
                document.getElementById("is_public").checked = session.is_public === 1;
                document.getElementById("session_id").textContent = session.session_id;

                selectedSession = {...session};
                document.getElementById("save_button").hidden = true;
                document.getElementById("edit_button").hidden = false;
                document.getElementById("refresh_code").disabled = true;
            }

            function deleteSession(session) {
                data = { _token: "{{ csrf_token() }}" };

                confirmationModal.open({
                    message: 'Apakah Anda yakin ingin menghapus quiz ini?',
                    severity: 'warn',
                    onAccept: function () {
                        apiClient.request('DELETE',`${session.id}`, data)
                            .then(response => {
                                toastr.success("✅ Quiz berhasil dihapus! Data telah diperbarui.", "", { timeOut: 3000 });
                                fetchApi();
                            })
                            .catch(error => {
                                toastr.error("❌ Gagal menghapus Quiz. Silakan coba lagi.", "", { timeOut: 3000 });
                                console.error('Error:', error);
                            });

                    },
                    onReject: function () {
                        toastr.info("Penghapusan dibatalkan");
                    }
                });
            }

            function copySessionInfo(session) {
    let baseUrl = "{{ route('start.quiz') }}"; // Gunakan route Laravel
    let textToCopy = `
${session.title}
Ayo bergabung dan bermain quiz di MedikoQuiz! Mari bersenang-senang bersama temanmu.

✨ Gabung Sekarang!
🔑 Kode Akses: ${session.access_code}
📅 Waktu Mulai: ${(() => {
    const [date, time] = session.start_time.split(" ");
    const [year, month, day] = date.split("-");
    return `${day.padStart(2, '0')}-${month.padStart(2, '0')}-${year}`;
})()}
⌛ Selesai: ${(() => {
    const [date, time] = session.end_time.split(" ");
    const [year, month, day] = date.split("-");
    return `${day.padStart(2, '0')}-${month.padStart(2, '0')}-${year}`;
})()}

🚀 Klik link ini untuk langsung masuk: ${baseUrl}?access_code=${session.access_code}
`;

    navigator.clipboard.writeText(textToCopy)
        .then(() => toastr.success("✅ Informasi sesi dan link telah disalin ke clipboard!", "", { timeOut: 3000 }))
        .catch(err => toastr.error("❌ Gagal menyalin teks. Silakan coba lagi.", "", { timeOut: 3000 }));
}

            function renderCard(data) {
                let container = document.getElementById("list-quiz-sessions");
                container.innerHTML = ""; // Kosongkan kontainer sebelum menambahkan data baru

                if (data.length === 0) {
                    container.innerHTML = "<p class='text-center text-muted'>Tidak ada sesi kuis yang ditemukan.</p>";
                    return;
                }

                data.forEach(session => {
                    let card = document.createElement("div");
                    let baseUrl = "{{ url('/quiz-session') }}";
                    let rankUrl = "{{ url('') }}";

                    card.classList.add("col-12", "col-md-6");
                    card.innerHTML = `
                        <div class="quiz-session text-start d-flex justify-content-between p-3 w-full h-100 shadow-sm bg-white rounded-4 border" style="border-radius:16px">
                            <div class="d-flex flex-column w-full">
                                <div class="d-flex justify-content-between w-full mb-2">
                                    <p class="mt-1 mb-0" style="color:#577DC5;font-size:0.7rem"><strong>CODE:</strong> ${session.access_code}<br><strong>ID:</strong> ${session.session_id}</p>
                                    <div class="dropdown d-flex justify-content-end mb-2">
                                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots text-dark"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="${rankUrl}/quiz-rank/${session.id}" class="dropdown-item">
                                                    <i class="fas fa-chart-bar me-2"></i> Rank
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item copy-btn">
                                                    <i class="bi bi-link-45deg me-2"></i> Copy
                                                </button>
                                            </li>
                                            <li>
                                                <a href="${baseUrl}/${session.id}" class="dropdown-item">
                                                    <i class="fas fa-file-alt me-2"></i> Quiz
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-warning edit-btn">
                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger delete-btn">
                                                    <i class="bi bi-trash me-2"></i> Delete
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" onclick="openClassroomModal(${session.id})">
                                                    <i class="fas fa-chalkboard me-2"></i> Class
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                    <h5 class="fw-bold text-dark mb-2">${session.title}</h5>
                                    <span class="badge ${session.is_public == 1 ? 'bg-success' : 'bg-secondary'} align-self-center" style="font-size:0.85em;">
                                        ${session.is_public == 1 ? 'Public' : 'Private'}
                                    </span>
                                </div>
                                <p class="text-muted mb-2 text-sm">${session.description}</p>
                                <div class="d-flex flex-wrap gap-2 gap-md-3 text-muted text-sm" style="font-size: 0.7rem;">
                                    <span class="text-start w-100">
                                        <i class="bi bi-calendar me-2"></i>
                                        ${(() => {
                                            const [date, time] = session.start_time.split(" ");
                                            const [year, month, day] = date.split("-");
                                            return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year} ${time ? time.substring(0, 5) : 'N/A'}`;
                                        })()}
                                        -
                                        ${(() => {
                                            const [date, time] = session.end_time.split(" ");
                                            const [year, month, day] = date.split("-");
                                            return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year} ${time ? time.substring(0, 5) : 'N/A'}`;
                                        })()}
                                    </span>
                                    <span><i class="bi bi-clock me-2"></i> ${session.timer} per soal</span>
                                    <span><i class="bi bi-people me-2"></i> ${session.attempts_count ?? 0} peserta</span>
                                </div>
                            </div>
                        </div>
                    `;

                    card.querySelector(".copy-btn").addEventListener("click", function () {
                        copySessionInfo(session);
                    });

                    card.querySelector(".edit-btn").addEventListener("click", function () {
                        editSession(session);
                    });

                    card.querySelector(".delete-btn").addEventListener("click", function () {
                        deleteSession(session.id);
                    });

                    container.appendChild(card);
                });

            }

            document.getElementById("access_code").value = generateAccessCode();
            document.getElementById("session_id").textContent = generateSessionID();

            document.getElementById("refresh_code").addEventListener("click", function () {
                document.getElementById("access_code").value = generateAccessCode();
            });

            document.getElementById("cancel_button").addEventListener("click", function () {
                resetForm();
            });

            document.getElementById("save_button").addEventListener("click", function () {
                let startTime = document.getElementById("start_time").value;
                let endTime = document.getElementById("end_time").value;

                if (new Date(startTime) > new Date(endTime)) {
                    toastr.warning("⚠️ Waktu selesai tidak boleh kurang dari waktu mulai. Periksa kembali data Anda.", "", { timeOut: 5000 });
                    return;
                }

                let quizData = {
                    _token: "{{ csrf_token() }}",
                    title: document.getElementById("title").value,
                    description: document.getElementById("description").value,
                    start_time: document.getElementById("start_time").value,
                    end_time: document.getElementById("end_time").value,
                    timer: document.getElementById("timer").value,
                    access_code: document.getElementById("access_code").value,
                    apply_all_timer: document.getElementById("apply_timer").checked ? 1 : 0,
                    is_public: document.getElementById("is_public").checked ? 1 : 0,
                    session_id: document.getElementById("session_id").textContent
                };

                if (start_time > end_time) {
                    toastr.warning("⚠️ Waktu selesai tidak boleh kurang dari waktu mulai. Periksa kembali data Anda.", "", { timeOut: 5000 });
                }

                apiClient.request('POST', '', quizData)
                    .then(() => {
                        toastr.success("✅ Sesi quiz berhasil disimpan! Anda dapat melihatnya di daftar sesi.", "", { timeOut: 5000 });
                        resetForm();
                        fetchApi();
                    })
                    .catch(error => {
                        let errorMsg = error.response?.message || "❌ Terjadi kesalahan saat menyimpan sesi quiz. Silakan coba lagi.";
                        toastr.error(errorMsg, "", { timeOut: 5000 });
                    });
            });

            document.getElementById("edit_button").addEventListener("click", function () {
                let startTime = document.getElementById("start_time").value;
                let endTime = document.getElementById("end_time").value;

                if (new Date(startTime) > new Date(endTime)) {
                    toastr.warning("⚠️ Waktu selesai tidak boleh kurang dari waktu mulai. Periksa kembali data Anda.", "", { timeOut: 5000 });
                    return;
                }

                let quizData = {
                    _token: "{{ csrf_token() }}",
                    title: document.getElementById("title").value,
                    description: document.getElementById("description").value,
                    start_time: document.getElementById("start_time").value,
                    end_time: document.getElementById("end_time").value,
                    timer: document.getElementById("timer").value,
                    access_code: document.getElementById("access_code").value,
                    apply_all_timer: document.getElementById("apply_timer").checked ? 1 : 0,
                    is_public: document.getElementById("is_public").checked ? 1 : 0,
                    session_id: document.getElementById("session_id").textContent
                };

                if (start_time > end_time) {
                    toastr.warning("⚠️ Waktu selesai tidak boleh kurang dari waktu mulai. Periksa kembali data Anda.", "", { timeOut: 5000 });
                }

                apiClient.request('PUT', `${selectedSession.id}`, quizData)
                    .then(() => {
                        toastr.success("✅ Sesi quiz berhasil diperbarui! Data telah diperbarui.", "", { timeOut: 5000 });
                        resetForm();
                        fetchApi();
                        document.getElementById("save_button").hidden = false;
                        document.getElementById("edit_button").hidden = true;
                        document.getElementById("refresh_code").disabled = false;
                    })
                    .catch(error => {
                        let errorMsg = error.response?.message || "❌ Terjadi kesalahan saat memperbarui sesi quiz. Silakan coba lagi.";
                        toastr.error(errorMsg, "", { timeOut: 5000 });
                    });
            });

            function resetForm() {
                document.getElementById("title").value = "";
                document.getElementById("description").value = "";
                document.getElementById("start_time").value = "";
                document.getElementById("end_time").value = "";
                document.getElementById("timer").value = "";
                document.getElementById("access_code").value = generateAccessCode(); // 🔄 Generate kode akses baru
                document.getElementById("apply_timer").checked = false;
                document.getElementById("is_public").checked = false;
                document.getElementById("session_id").textContent = generateSessionID(); // 🔄 Generate ID sesi baru

                document.getElementById("save_button").hidden = false;
                document.getElementById("edit_button").hidden = true;
                document.getElementById("refresh_code").disabled = false;
            }

            fetchApi();
        });

        // Fungsi pencarian quiz
        function filterSessions() {
            let searchText = document.getElementById("searchQuizSession").value.toLowerCase();
            let filteredSessions = allSessions.filter(session =>
                (session.title && session.title.toLowerCase().includes(searchText)) ||
                (session.description && session.description.toLowerCase().includes(searchText)) ||
                (session.access_code && session.access_code.toLowerCase().includes(searchText))
            );
            renderCard(filteredSessions);
        }

        $(document).ready(function () {
            // Ambil data classroom
            $.ajax({
                url: "{{ route('quiz-classroom.index') }}",
                method: "GET",
                success: function (data) {
                    // DataTables response, ambil data classroom saja
                    if (data.data) {
                        classrooms = data.data;
                    } else {
                        classrooms = data;
                    }
                }
            });

            $('#classroomForm').on('submit', function (e) {
                e.preventDefault();
                attachClassroomToSession();
            });
        });

        // Buka modal classroom untuk session tertentu
        function openClassroomModal(sessionId) {
            $('#sessionIdForClassroom').val(sessionId);
            const $select = $('#classroomSelect');
            $select.empty();

            // Ambil classroom yang sudah terhubung ke session
            $.get("{{ url('quiz-session') }}/" + sessionId, function(data) {
                const attachedClassrooms = data.classrooms || [];
                const attachedIds = attachedClassrooms.map(c => c.id);

                // Populate select2 dengan classroom yang belum terhubung
                classrooms.forEach(classroom => {
                    if (!attachedIds.includes(classroom.id)) {
                        const option = new Option(classroom.name, classroom.id, false, false);
                        $select.append(option);
                    }
                });

                $select.select2({
                    placeholder: "Pilih satu atau lebih classroom",
                    allowClear: true,
                    theme: 'bootstrap-5',
                    dropdownParent: $('#classroomModal')
                });

                renderAttachedClassrooms(attachedClassrooms);
                $('#classroomModal').modal('show');
            });
        }

        function renderAttachedClassrooms(attachedClassrooms) {
            const $tbody = $('#attachedClassroomTable tbody');
            $tbody.empty();
            if (attachedClassrooms.length === 0) {
                $tbody.append('<tr><td colspan="3" class="text-center">Belum ada classroom terhubung</td></tr>');
                return;
            }
            attachedClassrooms.forEach((classroom, idx) => {
                $tbody.append(`
                    <tr>
                        <td>${idx + 1}</td>
                        <td>${classroom.name}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="detachClassroomFromSession(${classroom.pivot.quiz_session_id}, ${classroom.id})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                `);
            });
        }

        function attachClassroomToSession() {
            const sessionId = $('#sessionIdForClassroom').val();
            const selectedClassroomIds = $('#classroomSelect').val() || [];

            // Ambil classroom yang sudah terhubung sebelumnya dari tabel
            let previousClassroomIds = [];
            $('#attachedClassroomTable tbody tr').each(function() {
                const onclickAttr = $(this).find('button[onclick^="detachClassroomFromSession"]').attr('onclick');
                if (onclickAttr) {
                    const match = onclickAttr.match(/detachClassroomFromSession\(\s*\d+\s*,\s*(\d+)\s*\)/);
                    if (match) {
                        previousClassroomIds.push(match[1]);
                    }
                }
            });

            // Gabungkan classroom sebelumnya dan yang baru dipilih, lalu hilangkan duplikat
            const allClassroomIds = Array.from(new Set([...previousClassroomIds, ...selectedClassroomIds]));

            $.ajax({
                url: '{{ route("session.attachClassroom") }}',
                method: 'POST',
                data: {
                    session_id: sessionId,
                    classroom_ids: allClassroomIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#classroomModal').modal('hide');
                    // reload data jika perlu
                    toastSuccess(response.message);
                },
                error: function(xhr) {
                    toastError(xhr.responseJSON?.message || 'Gagal memperbarui classroom.');
                }
            });
        }

        function detachClassroomFromSession(sessionId, classroomId) {
            if (!confirm('Yakin ingin menghapus classroom dari sesi ini?')) return;
            $.ajax({
                url: '{{ route("session.detachClassroom") }}',
                method: 'POST',
                data: {
                    session_id: sessionId,
                    classroom_id: classroomId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Refresh attached classrooms table
                    $.get("{{ url('quiz-session') }}/" + sessionId, function(data) {
                        const attachedClassrooms = data.classrooms || [];
                        renderAttachedClassrooms(attachedClassrooms);
                    });
                    toastSuccess(response.message);
                },
                error: function(xhr) {
                    toastError(xhr.responseJSON?.message || 'Gagal menghapus classroom.');
                }
            });
        }
    </script>
@endsection
