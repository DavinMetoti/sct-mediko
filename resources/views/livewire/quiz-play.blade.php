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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9" id="quiz-question" data-id="{{ $currentQuestion->id }}" wire:key="quiz-question">
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
                </div>
                <div class="col-md-3 mt-md-0 mt-5 mb-3">
                    <h4 class="fw-bold"><i class="fa-solid fa-ranking-star me-2 text-warning"></i> Live Rankings</h4>
                    <div class="quiz-container-rank"></div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-primary d-none" id="next-question" wire:click="nextQuestion">Next Question</button>
            </div>
        </div>
    </div>
</div>

<script>
    const quizSessionId = @json($quizSessionId);

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
                    fetchSessionRank();
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
                fetchSessionRank();
            });
        }

        fetchSessionRank();

        Pusher.logToConsole = true;

        var pusher = new Pusher('d54d62cdcd51d9a71282', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('quiz-channel');

        channel.bind('quiz-updated', function(data) {
            fetchSessionRank();
            toastr.success("Data rangking berhasil diperbarui", "", { timeOut: 3000 });
        });

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

    const apiUrl = "{{ route('quiz-rank', ['id' => $quizSessionId]) }}";

    function fetchSessionRank() {
        $.ajax({
            url: apiUrl,
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
        const prevRanks = {};

        container.querySelectorAll('.rank-item').forEach((item, i) => {
            const name = item.querySelector('h5')?.innerText;
            if (name) {
                prevRanks[name] = i + 1;
            }
        });

        if (!attempts || attempts.length === 0) {
            container.innerHTML = `<p class="text-center">No rankings available.</p>`;
            return;
        }

        container.innerHTML = '';
        attempts.forEach((rank, index) => {
            const rankElement = document.createElement('div');
            rankElement.classList.add('rank-item', 'p-2', 'rounded', 'd-flex', 'align-items-center');

            if (prevRanks[rank.name] && prevRanks[rank.name] > index + 1) {
                rankElement.classList.add('rank-up');
            }

            rankElement.style.backgroundColor = ["#FFD700", "#C0C0C0", "#CD7F32", "#888"][index] || "#888";

            rankElement.innerHTML = `
                <div class="rank-card w-100 p-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2 align-items-center">
                        <h3 class="m-0">#${index + 1}</h3>
                        <div>
                            <h5 class="m-0">${rank.name}</h5>
                            <p class="m-0">Score: ${rank.percentage_score}</p>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(rankElement);
        });
    }
</script>

