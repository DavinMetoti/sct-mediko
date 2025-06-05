<div>
    {{-- Header Quiz --}}
    <div id="loading-overlay" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 1.5rem;
    ">
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="quiz-header p-3 d-flex align-items-center justify-content-between">
            <button class="btn" style="background-color: #699AF5;color: #fff;font-size: 1rem;font-weight:bold" onclick="confirmExit()">
                <i class="fas fa-times"></i>
            </button>
        <span class="quiz-title font-weight-bold">
            @if($attempt)
                {{ $session->title }} |
                <span style="border-radius:24px;background-color: #6695EB;color: #fff;font-size: 0.8rem;font-weight:bold;padding: 4px 10px">
                    {{ $currentQuestion ? (array_search($currentQuestion->id, $shownQuestions ?? []) + 1) : ($totalQuestions ?? 0) }}/{{ $totalQuestions ?? 0 }}
                </span>
            @else
                <span class="text-muted">Creating a game...</span>
            @endif
        </span>

        <div class="header-right d-md-flex d-none gap-2">
            <button class="btn" style="background-color: #699AF5;color: #fff;font-size: 1rem;font-weight:bold">
                <i class="bi bi-stopwatch-fill me-2"></i>
                <span id="quiz-timer"></span>
            </button>
            <button class="btn" style="background-color: #699AF5;color: #fff;font-size: 1rem;font-weight:bold" onclick="toggleFullscreen()"><i class="fas fa-expand"></i></button>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9" id="quiz-question" data-id="{{ $currentQuestion->id }}" wire:key="quiz-question">
                    <div class="p-4 rounded shadow-sm" style="background-color: #0000001A;">
                        @if ($currentQuestion)
                            <div class="text-start" style="color: white !important;">{!! $currentQuestion->clinical_case !!}</div>
                            @foreach (['initial_hypothesis', 'new_information', 'answers'] as $key => $type)
                                <p class="text-sm mt-3 fw-bold">
                                    {{ $currentQuestion->columnTitle->{'column_' . ($key + 1)} }}
                                </p>
                                <div class="p-2 d-flex align-items-center justify-content-center flex-column" style="background-color: #23488E;border-radius: 8px; color: white;">
                                    @if ($type === 'answers')
                                        <div class="d-flex justify-content-center align-items-stretch w-100" style="gap: 12px;">
                                            @foreach ($currentQuestion->answers as $index => $answer)
                                                <label class="d-flex flex-column align-items-center justify-content-center m-0 p-0"
                                                       style="flex: 1 1 0; max-width: 20%; aspect-ratio: 1 / 1; min-width: 0;">
                                                    <div class="card text-white card-hover text-center w-100 h-100"
                                                        style="background-color: {{ ['#2D70AE', '#2E9DA6', '#EFA929', '#D5546D', 'rgb(84, 157, 213)'][$index] }};
                                                               border-radius: 12px; cursor:pointer; display: flex; align-items: center; justify-content: center; height: 100%;">
                                                        <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2 p-3 w-100 h-100"
                                                             style="height: 100%; width: 100%;">
                                                            <input type="radio" name="answer" data-id="{{ $answer->id }}" value="{{ $answer->score }}" class="answer-input d-none">
                                                            <span style="font-size: 1rem; word-break: break-word;">{{ $answer->answer }}</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="w-100">
                                            {{ $currentQuestion->$type }}
                                        </div>
                                        @if ($type === 'new_information' && !empty($currentQuestion->uploaded_image_base64))
                                            <div class="mt-2 w-100">
                                                <img
                                                    src="{{ $currentQuestion->uploaded_image_base64 }}"
                                                    alt="Informasi Baru Gambar"
                                                    style="max-width:100%;max-height:200px;border-radius:8px;cursor:zoom-in;"
                                                    onclick="showZoomImageModal(this.src)"
                                                >
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">All questions have been displayed.</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 mt-md-0 mt-5 mb-3 p-4 rounded shadow-sm" style="background-color: #0000001A;">
                    <h6 class="fw-bold text-center"><i class="fa-solid fa-ranking-star me-2 text-warning"></i> Live Leaderboard</h6>
                    <div class="quiz-container-rank"></div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-primary d-none" id="next-question" wire:click="nextQuestion">Next Question</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="zoomImageModal" tabindex="-1" aria-labelledby="zoomImageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
          <div class="modal-body text-center p-0" style="position:relative;">
            <img id="zoomImageModalImg" src="" style="max-width:90vw;max-height:80vh;border-radius:12px;box-shadow:0 0 20px #000;">
            <button type="button" class="btn btn-light position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close" style="z-index:10;">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
</div>

{{-- Modal Zoom Image --}}

<script>
    const quizSessionId = @json($quizSessionId);
    const socket = io('https://server.oscemediko.id/', {
        query: {
            channel: 'channel-set-mediko',
            secureKey: 'D4BA583B5663BDCA754B8C5448977'
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        let sessionTimer = {{ $session->timer }};
        let questionTimer = {{ $currentQuestion->timer ?? 0 }};
        let applyAllTimer = {{ $session->apply_all_timer ? 'true' : 'false' }};
        let timerElement = document.getElementById("quiz-timer");
        let progressBar = document.getElementById("progress-bar");
        let nextButton = document.getElementById("next-question");
        let token = "{{ $attempt->attempt_token }}";

        let totalTime = parseInt(sessionStorage.getItem("quiz_timer")) || (applyAllTimer ? sessionTimer : questionTimer);
        sessionStorage.setItem("quiz_timer", totalTime);
        sessionStorage.setItem("quiz_start_time", Date.now());

        const questionObserver = new MutationObserver((mutationsList) => {
            for (let mutation of mutationsList) {
                if (mutation.type === "childList" || mutation.type === "attributes") {
                    fetchSessionRank('{{ $attempt->classroom_id }}');
                    break;
                }
            }
        });

        const quizQuestionElement = document.getElementById("quiz-question");
        if (quizQuestionElement) {
            questionObserver.observe(quizQuestionElement, { childList: true, attributes: true, subtree: true });
        }

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
            clearInterval(countdown);
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
                fetchSessionRank("{{ $attempt->classroom_id }}");
                sendMessage();
            });
        }

        function sendMessage() {
            const message = {
                user: 'Admin',
                content: 'Hello from channel-set-mediko!'
            };
            socket.emit('send-message', message);
        }

        socket.on('receive-message', (data) => {
            console.log('Received:', data);
            fetchSessionRank("{{ $attempt->classroom_id }}");
        });

        fetchSessionRank("{{ $attempt->classroom_id }}");

        document.querySelector(".quiz-container").addEventListener("change", function (event) {
            if (event.target.classList.contains("answer-input")) {
                let selectedScore = parseFloat(event.target.value);
                let selectedId = event.target.getAttribute('data-id');
                const loadingOverlay = document.getElementById('loading-overlay');
                let quizQuestionElement = document.getElementById("quiz-question");
                let quizQuestionId = quizQuestionElement ? quizQuestionElement.getAttribute("data-id") : null;

                loadingOverlay.style.display = "flex";

                fetch("{{ route('quiz-play.update', ['quiz_play' => '__TEMP__']) }}".replace('__TEMP__', token), {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        score: selectedScore,
                        quiz_question_id: quizQuestionId,
                        quiz_answer_id: selectedId,
                        _token: "{{ csrf_token() }}"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelectorAll(".answer-input").forEach(input => {
                            input.checked = false;
                        });

                        setTimeout(() => {
                            if (nextButton) nextButton.click();
                            loadingOverlay.style.display = "none"; // Sembunyikan loading overlay
                        }, 500);
                    } else {
                        Swal.fire("Gagal!", "Gagal mengupdate skor, coba lagi!", "error");
                        loadingOverlay.style.display = "none"; // Sembunyikan loading overlay jika gagal
                    }
                })
                .catch(() => {
                    Swal.fire("Error!", "Terjadi kesalahan, coba lagi!", "error");
                    loadingOverlay.style.display = "none"; // Sembunyikan loading overlay jika error
                });
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
                sessionStorage.removeItem("quiz_timer");
                sessionStorage.removeItem("quiz_start_time");
                window.location.href = "{{ route('quiz.index') }}";
            }
        });
    }

    function toggleFullscreen() {
        let elem = document.documentElement;

        if (!document.fullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        } else {
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

    const apiUrlBase = "{{ route('quiz-rank', ['id' => $quizSessionId]) }}";

    function fetchSessionRank(classroomId = null) {
        let url = apiUrlBase;
        if (classroomId) {
            url += '?classroom_id=' + classroomId;
        }
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                displaySessionRank(data.sessionRankList);
            },
            error: function(xhr, status, error) {
                toastr.error("Gagal mengambil data rangking");
                console.error('Error:', error);
            }
        });
    }

    function displaySessionRank(attempts) {
        const container = document.querySelector('.quiz-container-rank');
        container.innerHTML = '';

        const currentAttemptId = @json($attempt->id);

        const rankStyles = [
            { bg: "#AB972F", shadow: "inset 8px 0 0  #FFD700" }, // Gold
            { bg: "#8B8B8B", shadow: "inset 8px 0 0  #C0C0C0" }, // Silver
            { bg: "#9A6632", shadow: "inset 8px 0 0  #CD7F32" }, // Bronze
        ];

        if (!attempts || attempts.length === 0) {
            container.innerHTML = `<p class="text-center">No rankings available.</p>`;
            return;
        }

        attempts.forEach((rank, index) => {
            let fontWeight = "normal";
            let fontColor = "#fff";
            let border = "none";
            let borderRadius = "8px";
            let bgColor = "#24437E"; // default blue
            let boxShadow = "";

            // Top 3
            if (index < 3) {
                bgColor = rankStyles[index].bg;
                boxShadow = rankStyles[index].shadow;
                fontWeight = "bold";
            }

            // Highlight user's own attempt
            if (rank.id == currentAttemptId) {
                border = "none";
                boxShadow += "inset 8px 0 0 ";
            }

            const style = `
                background: ${bgColor};
                color: ${fontColor};
                border-radius: ${borderRadius};
                margin-bottom: 10px;
                box-shadow: ${boxShadow};
                border: ${border};
                transition: box-shadow 0.2s;
                padding: 0;
                font-size: 1rem;
            `;

            const html = `
                <div class="w-100 d-flex align-items-center" style="min-height: 40px; padding: 0 24px;">
                    <span style="font-size: 1.1rem; font-weight: bold; margin-right: 16px;">${index + 1}.</span>
                    <h5 style="font-size: 0.9rem; font-weight: ${fontWeight}; flex: 1; margin: 0;">${rank.name}</h5>
                </div>
            `;

            const rankElement = document.createElement('div');
            rankElement.className = 'rank-item';
            rankElement.style = style;
            rankElement.innerHTML = html;

            container.appendChild(rankElement);
        });
    }


    function showZoomImageModal(src) {
        var modalImg = document.getElementById('zoomImageModalImg');
        modalImg.src = src;
        var modal = new bootstrap.Modal(document.getElementById('zoomImageModal'));
        modal.show();
    }
</script>

