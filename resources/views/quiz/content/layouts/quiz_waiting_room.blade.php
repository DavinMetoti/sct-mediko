@extends('quiz.content.play')

@section('quiz-content')
    <div class="quiz-header p-3 d-flex align-items-center justify-content-between">
        <div class="card-quiz p-2 rounded-sm">
            <button class="header-btn" onclick="confirmExit()"><i class="fas fa-times"></i></button>
        </div>

        <span class="quiz-title">Creating a game...</span>

        <div class="header-right d-flex gap-2">
            <div class="card-quiz p-2 rounded-sm">
                <button class="header-btn"><i class="fas fa-cog"></i></button>
            </div>
            <div class="card-quiz p-2 rounded-sm">
                <button class="header-btn"><i class="fas fa-expand"></i></button>
            </div>
        </div>
    </div>

    <div class="quiz-container p-3">
        <div class="row">
            <div class="col-md-4 m-auto">
                <div class="card-quiz p-3" style="background-color: rgba(255, 255, 255, 0.22)  !important;">
                    <label for="username">Masukkan Nama Anda</label>
                    <div class="input-group">
                        <input type="text" id="username" class="form-control text-uppercase" style="font-size: 1.5rem;" value="{{ auth()->user()->name??'' }}">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" id="shuffle-btn" style="height: 100% !important;" onclick="shuffleName()">
                                <i id="shuffle-icon" class="fas fa-refresh text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="quiz-token" value="{{ $token }}">

                    <button class="btn btn-primary mt-3 w-full" style="font-size: 1.5rem;" onclick="startQuiz()">Start</button>
                </div>
            </div>
        </div>
    </div>

    <script>
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

                    console.log(randomName);
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

            // Pastikan nama tidak kosong
            if (username.trim() === "") {
                toastr.warning("⚠️ Harap masukkan nama sebelum memulai quiz.", "", { timeOut: 3000 });
                return;
            }

            // Kirim AJAX untuk update userAttempts->name
            $.ajax({
                url: "{{ route('quiz-play.update', ['quiz_play' => 'temp']) }}".replace('temp', token),
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: username
                },
                success: function(response) {
                    window.location.href = "{{ route('quiz-play.show', ['quiz_play' => 'temp']) }}".replace('temp', token);
                },
                error: function() {
                    toastr.error("❌ Gagal memulai quiz. Pastikan semua data sudah benar.", "", { timeOut: 3000 });
                }
            });
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
