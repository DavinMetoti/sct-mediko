@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="d-flex justify-content-between align-items-center w-100 rounded shadow-sm mb-4">
            <h3 class="fw-bold m-0">Daftar Sesi Quiz</h3>
            <a href="{{ route('quiz-session.create') }}" class="btn btn-success px-4 py-2" id="save-question">
                <i class="fas fa-plus me-2"></i>Tambah
            </a>
        </div>

        <!-- Input pencarian -->
        <div class="mb-3">
            <input type="text" id="searchQuizSession" class="form-control" placeholder="Cari sesi quiz..." onkeyup="filterSessions()">
        </div>

        <div id="list-quiz-sessions" class="mt-4"></div>
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
📅 Waktu Mulai: {{ \Carbon\Carbon::parse($session->start_time)->format('d-m-Y') }}
⌛ Selesai: ${session.end_time}

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

                    card.classList.add("quiz-session", "card-purple", "d-flex", "flex-wrap", "align-items-center",
                        "justify-content-between", "p-3", "rounded", "mb-3");

                    card.innerHTML = `
                        <div class="quiz-session card-purple d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between p-3 rounded w-full">
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold text-white mb-2">${session.title}</h5>
                                <p class="text-light mb-2">${session.description}</p>
                                <div class="d-flex flex-wrap gap-2 gap-md-3 text-white text-sm">
                                    <span>
                                        <i class="bi bi-calendar"></i>
                                        ${(() => {
                                            const [date, time] = session.start_time.split(" ");
                                            const [year, month, day] = date.split("-");
                                            return `${day}-${month}-${year} ${time.substring(0, 5)}`;
                                        })()}
                                        ${(() => {
                                            const [date, time] = session.end_time.split(" ");
                                            const [year, month, day] = date.split("-");
                                            return `${day}-${month}-${year} ${time.substring(0, 5)}`;
                                        })()}
                                    </span>
                                    <span><i class="bi bi-clock"></i> ${session.timer} per soal</span>
                                    <span><i class="bi bi-people"></i> ${session.attempts_count} peserta</span>
                                </div>
                            </div>
                            <div class="text-end mt-3 mt-md-0">
                                <div class="dropdown d-flex justify-content-end mb-2">
                                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="${baseUrl}/${session.id}/rank" class="dropdown-item">
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
                                <p class="text-muted mt-1 mb-0"><strong>Code:</strong> ${session.access_code}</p>
                                <p class="text-muted text-sm mb-0"><strong>ID:</strong> ${session.session_id}</p>
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
