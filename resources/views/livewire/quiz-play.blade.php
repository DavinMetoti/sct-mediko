<div>
    {{-- Header Quiz --}}
    <div class="quiz-header p-3 d-flex align-items-center justify-content-between">
        <div class="card-quiz p-2 rounded-sm">
            <button class="h-100 px-2" onclick="confirmExit()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <span class="quiz-title font-weight-bold">
            @if($attempt)
                Quiz: {{ $session->title }}
            @else
                <span class="text-muted">Creating a game...</span>
            @endif
        </span>

        <div class="header-right d-md-flex d-none gap-2">
            <div class="card-quiz p-2 rounded-sm">
                <button><span id="quiz-timer" class="mr-2"></span><i class="fas fa-clock"></i></button>
            </div>
            <div class="card-quiz p-2 text-center rounded-sm">
                <button class="h-100 px-2" onclick="toggleFullscreen()"><i class="fas fa-expand"></i></button>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="header-right d-md-flex d-none gap-2">
        <div class="w-100">
            <div id="progress-bar" class="progress-bar"></div>
        </div>
    </div>

    {{-- Container Quiz --}}
    <div class="quiz-container mt-4">
        <div class="container">
            <div class="card-purple p-4 rounded shadow-sm">
                @if ($currentQuestion)
                    <div class="text-center">{!! $currentQuestion->clinical_case !!}</div>
                @else
                    <p class="text-muted">All questions have been displayed.</p>
                @endif
            </div>

            <div class="row mt-3 align-items-stretch">
                @foreach (['initial_hypothesis', 'new_information', 'answers'] as $key => $type)
                    <div class="col-md-4 mt-md-0 mt-3">
                        <p class="text-sm" style="height: 2rem;">
                            {{ $currentQuestion->columnTitle->{'column_' . ($key + 1)} }}
                        </p>
                        <div class="card-purple p-3 d-flex align-items-center justify-content-center">
                            @if ($type === 'answers')
                                <div class="row">
                                    @foreach ($currentQuestion->answers as $index => $answer)
                                        <div class="col-md-12 mb-3">
                                            <label class="w-100">
                                                <div class="card text-white card-hover"
                                                     style="background-color: {{ ['#2D70AE', '#2E9DA6', '#EFA929', '#D5546D', 'rgb(84, 157, 213)'][$index] }};">
                                                    <div class="card-body d-flex align-items-center gap-2">
                                                        <input type="radio" name="answer" data-id="{{ $answer->id }}" value="{{ $answer->score }}" class="answer-input d-none">
                                                        <span>{{ $answer->answer }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{ $currentQuestion->$type }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-primary d-none" id="next-question" wire:click="nextQuestion">Next Question</button>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let sessionTimer = {{ $session->timer }};
        let questionTimer = {{ $currentQuestion->timer ?? 0 }};
        let applyAllTimer = {{ $session->apply_all_timer ? 'true' : 'false' }};
        let timerElement = document.getElementById("quiz-timer");
        let progressBar = document.getElementById("progress-bar");
        let nextButton = document.getElementById("next-question");
        let token = "{{ $attempt->attempt_token }}";

        // Ambil timer dari sessionStorage atau set defaultnya
        let totalTime = parseInt(sessionStorage.getItem("quiz_timer")) || (applyAllTimer ? sessionTimer : questionTimer);
        sessionStorage.setItem("quiz_timer", totalTime);
        sessionStorage.setItem("quiz_start_time", Date.now());

        function updateTimerDisplay() {
            let minutes = Math.floor(totalTime / 60);
            let seconds = totalTime % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (progressBar) {
                let percentage = (totalTime / (applyAllTimer ? sessionTimer : questionTimer)) * 100;
                progressBar.style.width = `${percentage}%`;
                progressBar.style.backgroundColor = percentage > 50 ? "white" : percentage > 20 ? "orange" : "red";
            }
        }

        let countdown;

        function startCountdown() {
            clearInterval(countdown); // Pastikan timer tidak double
            countdown = setInterval(() => {
                if (totalTime <= 0) {
                    clearInterval(countdown);
                    sessionStorage.setItem("quiz_timer", applyAllTimer ? sessionTimer : questionTimer);
                    updateTimerDisplay();
                    if (nextButton) nextButton.click();
                } else {
                    totalTime--;
                    sessionStorage.setItem("quiz_timer", totalTime);
                    updateTimerDisplay();
                }
            }, 1000);
        }

        startCountdown();
        updateTimerDisplay();

        if (nextButton) {
            nextButton.addEventListener("click", function () {
                clearInterval(countdown);
                totalTime = applyAllTimer ? sessionTimer : questionTimer;
                sessionStorage.setItem("quiz_timer", totalTime);
                sessionStorage.setItem("quiz_start_time", Date.now());
                updateTimerDisplay();
                startCountdown();
            });
        }

        document.querySelector(".quiz-container").addEventListener("change", function (event) {
            if (event.target.classList.contains("answer-input")) {
                let selectedScore = parseFloat(event.target.value);
                let selectedId = event.target.getAttribute('data-id');

                setTimeout(() => {
                    if (nextButton) nextButton.click();
                }, 500);

                fetch("{{ route('quiz-play.update', ['quiz_play' => '__TEMP__']) }}".replace('__TEMP__', token), {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        score: selectedScore,
                        quiz_question_id: {{ $currentQuestion->id }},
                        quiz_answer_id: selectedId,
                        _token: "{{ csrf_token() }}"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelectorAll(".answer-input").forEach(radio => radio.checked = false);
                    } else {
                        Swal.fire("Gagal!", "Gagal mengupdate skor, coba lagi!", "error");
                    }
                })
                .catch(() => Swal.fire("Error!", "Terjadi kesalahan, coba lagi!", "error"));
            }
        });
    });

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

    function toggleFullscreen() {
        let elem = document.documentElement; // Ambil elemen utama (seluruh halaman)

        if (!document.fullscreenElement) {
            // Masuk ke fullscreen
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) { // Firefox
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, Opera
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { // IE/Edge
                elem.msRequestFullscreen();
            }
        } else {
            // Keluar dari fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }


</script>
