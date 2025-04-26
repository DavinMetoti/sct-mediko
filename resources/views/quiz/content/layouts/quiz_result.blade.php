@extends('quiz.content.play')

@section('quiz-content')
    <div class="quiz-container">
        <div class="container">
            <div class="quiz-header py-3 d-flex align-items-center justify-content-between">
                <div class="card-quiz p-2 rounded-sm">
                    <button class="h-100 px-2" onclick="confirmExit()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <span class="quiz-title font-weight-bold">
                    @if($attempt)
                        Quiz: {{ $attempt->session->title }}
                    @else
                        <span class="text-muted">Creating a game...</span>
                    @endif
                </span>

                <div class="header-right d-md-flex d-none gap-2">
                    <div class="card-quiz p-2 text-center rounded-sm">
                        <button class="h-100 px-2" onclick="toggleFullscreen()"><i class="fas fa-expand"></i></button>
                    </div>
                </div>
            </div>
            @if($attempt)
                <div class="card p-4 shadow">
                    <p><strong>Nama Peserta:</strong> {{ $attempt->name ?? 'Tidak Diketahui' }}</p>
                    <p><strong>Total Soal:</strong> {{ $attempt->session->questions->count() }}</p>
                    <p><strong>Skor Akhir:</strong> {{ number_format(($attempt->score / $attempt->session->questions->count()) * 100, 2) }}</p>
                </div>

                <h3 class="mt-4">Detail Jawaban:</h3>
                <ul class="list-group pb-5">
                @php
                    $allSelectedAnswers = $attempt->userAnswer->groupBy('quiz_question_id')->map(function ($answers) {
                        return $answers->pluck('quiz_answer_id')->toArray();
                    });
                @endphp
                @foreach ($attempt->session->questions as $question)
                    <li class="list-group-item">
                        <strong>Pertanyaan:</strong>
                        <div style="color: black !important;">
                            {!! preg_replace('/style="[^"]*"/i', '', $question->clinical_case) !!}
                        </div>

                        <div class="mt-2">
                            @foreach ($question->answers as $answer)
                                <div class="form-check">
                                    <input
                                        type="radio"
                                        name="question_{{ $question->id }}"
                                        class="form-check-input"
                                        data-id="{{ $answer->id }}"
                                        {{ in_array($answer->id, $allSelectedAnswers[$question->id] ?? []) ? 'checked' : 'disabled' }}
                                    >
                                    <label class="form-check-label"
                                        style="@if ($answer->score == 1) color: green; font-weight: bold; @endif">
                                        {{ $answer->answer }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </li>
                @endforeach

                </ul>
            @else
                <p class="text-center mt-4">Tidak ada data hasil kuis.</p>
            @endif
        </div>
    </div>

    <script>
        function confirmExit() {
            window.location.href = "{{ route('quiz.index') }}";
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
@endsection
