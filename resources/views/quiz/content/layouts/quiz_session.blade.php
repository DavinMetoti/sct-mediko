@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="row">
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

                <!-- Add Button -->
                <a href="{{ route('quiz-session.create') }}" class="btn btn-green d-flex align-items-center">
                    <i class="fas fa-plus me-2"></i>Tambah
                </a>
            </div>

        </div>


        <div id="list-quiz-sessions" class="mt-2 row g-3"></div>
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        const apiClient = new HttpClient('{{ route("quiz-session.index") }}');
        let allSessions = []; // Simpan semua sesi quiz untuk pencarian

        document.addEventListener("DOMContentLoaded", function () {
            function fetchApi() {
                apiClient.request('GET', '')
                    .then(response => {
                        if (response.response && response.response.length > 0) {
                            allSessions = response.response;
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

            function deleteSession(sessionId) {
                const data = { _token: "{{ csrf_token() }}" };

                confirmationModal.open({
                    message: 'Apakah Anda yakin ingin menghapus quiz ini?',
                    severity: 'warn',
                    onAccept: function () {
                        apiClient.request('DELETE', `${sessionId}`, data)
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
        .then(() => toastr.success("✅ Informasi sesi dan link telah disalin ke clipboard!"))
        .catch(err => toastr.error("❌ Gagal menyalin teks:", err));
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

                    card.classList.add(
                        "col-12", "col-md-6"
                    );
                    card.innerHTML = `
                        <div class="quiz-session text-start d-flex justify-content-between p-3 w-full h-100" style="background-color: white;border-radius:16px">
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
                                                <button class="dropdown-item text-danger delete-btn">
                                                    <i class="bi bi-trash me-2"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="fw-bold text-dark mb-2">${session.title}</h6>
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
                                    <span><i class="bi bi-people me-2"></i> ${session.attempts_count} peserta</span>
                                </div>
                            </div>
                        </div>
                    `;


                    card.querySelector(".copy-btn").addEventListener("click", function () {
                        copySessionInfo(session);
                    });

                    card.querySelector(".delete-btn").addEventListener("click", function () {
                        deleteSession(session.id);
                    });

                    container.appendChild(card);
                });
            }

            // Fungsi pencarian quiz
            window.filterSessions = function () {
                let searchText = document.getElementById("searchQuizSession").value.toLowerCase();
                let filteredSessions = allSessions.filter(session =>
                    session.title.toLowerCase().includes(searchText) ||
                    session.description.toLowerCase().includes(searchText) ||
                    session.access_code.toLowerCase().includes(searchText)
                );

                renderCard(filteredSessions);
            };

            fetchApi();
        });
    </script>
@endsection
