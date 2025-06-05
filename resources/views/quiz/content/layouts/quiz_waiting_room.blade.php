@extends('quiz.content.play')

@section('quiz-content')
    <div class="quiz-header p-3 d-flex align-items-center justify-content-between">
        <button class="btn" style="background-color: #699AF5;color: #fff;font-size: 1rem;font-weight:bold" onclick="confirmExit()"><i class="fas fa-times"></i></button>

        <div class="header-right d-flex gap-2">
            <button class="btn" style="background-color: #699AF5;color: #fff;align-items: center;font-weight:bold"><i class="bi bi-key-fill me-2 text-lg"></i><span>{{ $session->access_code }}</span></button>
            <button class="btn" style="background-color: #699AF5;color: #fff;font-size: 0.8rem;font-weight:bold"><i class="fas fa-expand"></i></button>
        </div>
    </div>
    <div class="quiz-container p-3">
        <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                <div class="text-center mb-5">
                    <h3 class="fw-bold">{{ $session->title }}</h3>
                </div>
                <div class="card-quiz p-3" style="background-color: #305FB5 !important; width:100%; max-width:400px;">
                    <label for="username" class="mb-2" style="font-weight: 400 !important;">Masukkan Nama Anda</label>
                    <div class="input-group">
                        <input type="text" id="username" class="form-control text-uppercase border-0" style="border-top-left-radius:12px;border-bottom-left-radius:12px;background-color: #5A8DEB !important;color: #fff !important;" value="{{ auth()->user()->name??'' }}">
                        <div class="input-group-append">
                            <button class="btn" id="shuffle-btn" style="border-top-left-radius:0;border-bottom-left-radius:0;border-top-right-radius:12px;border-bottom-right-radius:12px;background-color: #5A8DEB !important;color: #fff !important;" onclick="shuffleName()">
                                <i id="shuffle-icon" class="fas fa-repeat text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="quiz-token" value="{{ $token }}">

                    <div class="mt-3 mb-3">
                        <label for="classroom_id" class="mb-2" style="font-weight: 400 !important;">Pilih Kelas</label>
                        <select id="classroom_id" class="form-control border-0 py-2" style="border-radius:12px;background-color: #5A8DEB !important;color: #fff !important;">
                            <option value="">-- Pilih Kelas --</option>
                        </select>
                    </div>

                    <button class="btn btn-orange w-full" style="border-radius:12px;" onclick="startQuiz()"><i class="fas fa-play me-2"></i>Mulai Mengerjakan</button>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <div class="flex align-items-center gap-2" style="background-color:#528BF5; color:#fff; padding: 0.5rem 1rem; border-radius: 12px; font-size: 0.9rem; margin-top: 1rem;">
                        <div>
                            <i class="bi bi-stopwatch-fill me-2 text-2xl"></i>
                        </div>
                        <div>
                            <div style="font-size: 16px;font-weight: 600;">Waktu</div>
                            <div style="font-size: 12px;font-weight: 400;">{{ $session->timer }} menit</div>
                        </div>
                    </div>
                    <div class="flex align-items-center gap-2" style="background-color:#528BF5; color:#fff; padding: 0.5rem 1rem; border-radius: 12px; font-size: 0.9rem; margin-top: 1rem;">
                        <div>
                            <i class="bi bi-question-square me-2 text-2xl"></i>
                        </div>
                        <div>
                            <div style="font-size: 16px;font-weight: 600;">Pertanyaan</div>
                            <div style="font-size: 12px;font-weight: 400;">{{ $session->questions_count }} soal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Countdown Overlay -->
    <div id="countdown-overlay" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.8);justify-content:center;align-items:center;">
        <span id="countdown-number" style="color:#fff;font-size:8rem;font-weight:bold;display:block;text-align:center;width:100vw;"></span>
    </div>

    <script>
        let classrooms = [];

        $(document).ready(function() {
            // Ambil classroom berdasarkan quiz_session_id dari token
            let token = $("#quiz-token").val();
            $.ajax({
                url: "{{ route('quiz-play.show', ['quiz_play' => 'temp']) }}".replace('temp', token),
                type: "GET",
                success: function(response) {
                    // response.attempt.session.classrooms (pastikan relasi classrooms sudah dimuat di controller)
                    let classroomList = [];
                    // if (response && response.session && response.session.classrooms) {
                    //     classroomList = response.session.classrooms;
                    // } else if (response && response.classrooms) {
                    //     classroomList = response.classrooms;
                    // }

                    classroomList = response.classrooms;

                    classrooms = classroomList;
                    let $dropdown = $("#classroom_id");
                    $dropdown.empty();
                    $dropdown.append('<option value="">-- Pilih Classroom --</option>');
                    classroomList.forEach(function(c) {
                        $dropdown.append('<option value="'+c.id+'">'+c.name+'</option>');
                    });
                }
            });
        });

        function shuffleName() {
            let shuffleBtn = $("#shuffle-btn");
            let shuffleIcon = $("#shuffle-icon");

            shuffleBtn.prop("disabled", true);
            shuffleIcon.addClass("fa-spin");

            $.ajax({
                url: 'https://randomuser.me/api/?nat=id',
                dataType: 'json',
                success: function(data) {
                    let firstName = data.results[0].name.first;
                    let lastName = data.results[0].name.last;
                    let randomName = `${firstName} ${lastName}`;

                    $("#username").val(randomName);
                    toastr.success("✅ Nama berhasil diacak! Nama baru telah diisi.", "", { timeOut: 3000 });
                },
                error: function() {
                    toastr.error("❌ Gagal mengambil nama acak. Silakan coba lagi.", "", { timeOut: 3000 });
                },
                complete: function() {
                    // Kembalikan ikon ke normal dan aktifkan tombol
                    shuffleIcon.removeClass("fa-spin");
                    shuffleBtn.prop("disabled", false);
                }
            });
        }

        function startQuiz() {
            let username = $("#username").val();
            let token = $("#quiz-token").val();
            let classroom_id = $("#classroom_id").val();

            // Pastikan nama tidak kosong
            if (username.trim() === "") {
                toastr.warning("⚠️ Harap masukkan nama sebelum memulai quiz.", "", { timeOut: 3000 });
                return;
            }

            // Pastikan classroom dipilih jika ada classroom
            if (classrooms.length > 0 && !classroom_id) {
                toastr.warning("⚠️ Silakan pilih classroom terlebih dahulu.", "", { timeOut: 3000 });
                return;
            }

            // Kirim AJAX untuk update userAttempts->name dan classroom_id
            $.ajax({
                url: "{{ route('quiz-play.update', ['quiz_play' => 'temp']) }}".replace('temp', token),
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: username,
                    classroom_id: classroom_id
                },
                success: function(response) {
                    showCountdownAndRedirect();
                },
                error: function() {
                    toastr.error("❌ Gagal memulai quiz. Pastikan semua data sudah benar.", "", { timeOut: 3000 });
                }
            });
        }

        function showCountdownAndRedirect() {
            let overlay = $("#countdown-overlay");
            let number = $("#countdown-number");
            overlay.css("display", "flex");
            let count = 3;
            number.text(count);
            let token = $("#quiz-token").val();
            let interval = setInterval(function() {
                count--;
                if (count > 0) {
                    number.text(count);
                } else {
                    clearInterval(interval);
                    number.text("1");
                    setTimeout(function() {
                        window.location.href = "{{ route('quiz-play.show', ['quiz_play' => 'temp']) }}".replace('temp', token);
                    }, 700);
                }
            }, 900);
        }

        function confirmExit() {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda akan menyelesaikan quiz ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, keluar!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    sessionStorage.removeItem("quiz_timer"); // Hapus hanya timer, bukan semua sessionStorage
                    sessionStorage.removeItem("quiz_start_time");
                    window.location.href = "{{ route('quiz.index') }}";
                }
            });
        }
    </script>
@endsection
