@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="d-flex justify-content-between align-items-center w-100 rounded shadow-sm mb-4">
            <h3 class="fw-bold m-0">Buat Sesi Quiz</h3>
        </div>
        <div class="card-purple p-4">
            <div class="form-group">
                <label for="title" class="mb-2">Judul</label>
                <input type="text" name="title" id="title" class="form-control-purple w-full" placeholder="Masukan judul">
            </div>
            <div class="form-group mt-4">
                <label for="description" class="mb-2">Deskripsi</label>
                <textarea name="description" id="description" class="form-control-purple w-full" placeholder="Masukan deskripsi"></textarea>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="start_time" class="mb-2">Mulai</label>
                        <input type="text" name="start_time" id="start_time" class="form-control-purple w-full" placeholder="Pilih waktu mulai">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="end_time" class="mb-2">Berakhir</label>
                        <input type="text" name="end_time" id="end_time" class="form-control-purple w-full" placeholder="Pilih waktu berakhir">
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="timer" class="mb-2">Waktu Persoal (Detik)</label>
                        <input type="number" name="timer" id="timer" class="form-control-purple w-full" placeholder="Masukan durasi kuis">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="access_code" class="mb-2">Kode Akses</label>
                        <div class="d-flex">
                            <input type="text" name="access_code" id="access_code" class="form-control-purple w-full" readonly>
                            <button type="button" id="refresh_code" class="btn btn-light ms-2"><i class="fas fa-refresh"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="apply_timer" name="apply_timer">
                        <label class="form-check-label" for="apply_timer">Apakah Anda ingin menerapkan timer di semua soal?</label>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="is_public" name="is_public">
                        <label class="form-check-label" for="is_public">
                            Apakah sesi ini akan terlihat oleh semua peserta?
                        </label>
                    </div>
                </div>
                <div class="text-muted">
                    <strong>ID Sesi:</strong> <span id="session_id"></span>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-5">
                <button class="btn btn-secondary" id="cancel_button"><i class="fas fa-times me-2"></i> Cencel</button>
                <button class="btn btn-success" id="save_button"><i class="fas fa-check me-2"></i> Simpan</button>
                <button class="btn btn-warning" hidden id="edit_button"><i class="fas fa-check me-2"></i> Simpan</button>
            </div>
        </div>
        <div id="list-quiz-sessions" class="mt-4">

        </div>
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        const apiClient = new HttpClient('{{ route("quiz-session.index") }}');
        let selectedSession ;

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
                            renderCard(response.response);
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
                                toastr.success("Quiz berhasil dihapus", "", { timeOut: 3000 });
                                fetchApi();
                            })
                            .catch(error => {
                                toastr.error("Gagal menghapus Quiz");
                                console.error('Error:', error);
                            });

                    },
                    onReject: function () {
                        toastr.info("Penghapusan dibatalkan");
                    }
                });
            }

            function copySessionInfo(session) {
                let textToCopy = `
        ${session.title}
Ayo bergabung dan bermain quiz di MedikoQuiz! Mari bersenang-senang bersama temanmu.

        ✨ Gabung Sekarang!
        🔑 Kode Akses: ${session.access_code}
        📅 Waktu Mulai: ${session.start_time}
        ⌛ Selesai: ${session.end_time}

Nikmati keseruan menjawab pertanyaan dan uji kemampuanmu bersama! 🚀`;

                navigator.clipboard.writeText(textToCopy)
                    .then(() => alert("✅ Informasi sesi telah disalin ke clipboard!"))
                    .catch(err => console.error("❌ Gagal menyalin teks:", err));
            }

            function renderCard(data) {
                let container = document.getElementById("list-quiz-sessions");
                container.innerHTML = ""; // Kosongkan kontainer sebelum menambahkan data baru

                data.forEach(session => {
                    let card = document.createElement("div");
                    card.classList.add("quiz-session", "card-purple", "d-flex", "flex-wrap", "align-items-center",
                        "justify-content-between", "p-3", "rounded", "mb-3");

                    let baseUrl = "{{ url('/quiz-session') }}";

                    card.innerHTML = `
                        <div class="quiz-session card-purple d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between p-3 rounded w-full">
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold text-white mb-2">
                                    <span class="mr-2">${session.title}</span>
                                    <span class="ml-2 me-2 badge ${session.is_public == 1 ? 'bg-success' : 'bg-danger'}">
                                        ${session.is_public == 1 ? 'Public' : 'Private'}
                                    </span>
                                </h5>
                                <p class="text-light mb-2">${session.description}</p>
                                <div class="d-flex flex-wrap gap-2 gap-md-3 text-white text-sm">
                                    <span>
                                        <i class="bi bi-calendar"></i>
                                        ${session.start_time.split(" ")[0]} ${session.start_time.split(" ")[1].substring(0, 5)} -
                                        ${session.end_time.split(" ")[0]} ${session.end_time.split(" ")[1].substring(0, 5)}
                                    </span>
                                    <span><i class="bi bi-clock"></i> ${session.timer} per soal</span>
                                    <span><i class="bi bi-people"></i> 0 peserta</span>
                                </div>
                            </div>
                            <div class="text-end mt-3 mt-md-0">
                                <div class="d-flex gap-2">
                                    <!-- Copy Link Button -->
                                    <button class="btn btn-secondary mb-2 w-md-auto copy-btn">
                                        <i class="bi bi-link-45deg me-2"></i> Copy
                                    </button>

                                    <!-- Quiz Button (diperbaiki URL-nya) -->
                                    <a href="${baseUrl}/${session.id}" class="btn btn-primary mb-2 w-md-auto quiz-btn">
                                        <i class="fas fa-file-alt me-2"></i> Quiz
                                    </a>

                                    <!-- Edit Button -->
                                    <button class="btn btn-warning mb-2 w-md-auto edit-btn">
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger mb-2 w-md-auto delete-btn">
                                        <i class="bi bi-trash me-2"></i> Delete
                                    </button>
                                </div>
                                <p class="text-muted mt-1 mb-0"><strong>Code:</strong> ${session.access_code}</p>
                                <p class="text-muted text-sm mb-0"><strong>ID:</strong> ${session.session_id}</p>
                            </div>
                        </div>
                    `;

                    // Tambahkan event listener ke tombol-tombol
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
                    toastr.warning("Waktu selesai tidak boleh kurang dari waktu mulai");
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
                    toastr.warning("Waktu selesai tidak boleh kurang dari waktu mulai")
                }

                apiClient.request('POST', '', quizData)
                    .then(() => {
                        toastr.success("Sesi quiz berhasil disimpan", { timeOut: 5000 });
                        resetForm();
                        fetchApi();
                    })
                    .catch(error => {
                        let errorMsg = error.response?.message || "Terjadi kesalahan saat menyimpan data";
                        toastr.error(errorMsg);
                    });
            });

            document.getElementById("edit_button").addEventListener("click", function () {
                let startTime = document.getElementById("start_time").value;
                let endTime = document.getElementById("end_time").value;

                if (new Date(startTime) > new Date(endTime)) {
                    toastr.warning("Waktu selesai tidak boleh kurang dari waktu mulai");
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
                    toastr.warning("Waktu selesai tidak boleh kurang dari waktu mulai")
                }

                apiClient.request('PUT', `${selectedSession.id}`, quizData)
                    .then(() => {
                        toastr.success("Sesi quiz berhasil diperbarui", { timeOut: 5000 });
                        resetForm();
                        fetchApi();
                        document.getElementById("save_button").hidden = false;
                        document.getElementById("edit_button").hidden = true;
                        document.getElementById("refresh_code").disabled = false;
                    })
                    .catch(error => {
                        let errorMsg = error.response?.message || "Terjadi kesalahan saat memperbarui data";
                        toastr.error(errorMsg);
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

    </script>
@endsection
