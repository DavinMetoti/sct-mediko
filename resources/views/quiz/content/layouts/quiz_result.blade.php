@extends('quiz.content.play')
@section('quiz-content')
    <div class="quiz-container">
        <div class="container">
            <style>
                @media print {
                    button, .card-quiz {
                        display: none !important;
                    }
                }
            </style>
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
                    <button onclick="window.print()" class="btn btn-primary mt-3">Print</button>
                </div>

                <h3 class="mt-4">Detail Jawaban:</h3>
                <ul class="list-group pb-5">
                @php
                    $allSelectedAnswers = $attempt->userAnswer->groupBy('quiz_question_id')->map(function ($answers) {
                        return $answers->pluck('quiz_answer_id')->toArray();
                    })->flatten()->toArray();
                @endphp
                @foreach ($attempt->session->questions as $idx => $question)
                    <li class="list-group-item">
                        <strong>Pertanyaan {{ $idx + 1 }}:</strong>
                        <div style="color: black !important;">
                            {!! preg_replace('/style="[^"]*"/i', '', $question->clinical_case) !!}
                        </div>
                        <strong>{{$question->columnTitle->column_1}}</strong>
                        <div style="color: black !important;">
                            {!! preg_replace('/style="[^"]*"/i', '', $question->initial_hypothesis) !!}
                        </div>
                        <strong>{{$question->columnTitle->column_2}}</strong>
                        <div style="color: black !important;">
                            {!! preg_replace('/style="[^"]*"/i', '', $question->new_information) !!}
                            @if ($question->uploaded_image_base64)
                                <img src="{{ $question->uploaded_image_base64 }}" width="400rem" alt="Informasi Baru Gambar">
                            @endif
                        </div>

                        {{-- Rationale dan Skor Likert Table --}}
                        @php
                            // Group answers by value
                            $valueCounts = collect([-2, -1, 0, 1, 2])->mapWithKeys(function($v) use ($question) {
                                return [$v => $question->answers->where('value', $v)->sum('panelist')];
                            });
                            $totalPanelist = $valueCounts->max(); // jumlah tertinggi valueCounts
                            $valueBobot = $valueCounts->map(function($count) use ($totalPanelist) {
                                return $totalPanelist ? ($count . '/' . $totalPanelist) : '0/0';
                            });
                            $valueSkor = collect([-2, -1, 0, 1, 2])->mapWithKeys(function($v) use ($question) {
                                $answer = $question->answers->where('value', $v)->first();
                                return [$v => $answer ? $answer->score : 0];
                            });
                            // User answer & score
                            $userAnswer = $question->answers->first(function($a) use ($allSelectedAnswers) {
                                return in_array($a->id, $allSelectedAnswers);
                            });
                        @endphp
                        <div class="mt-3 mb-2">
                            <strong>Rationale dan Skor Likert</strong>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Keterangan</th>
                                            <th colspan="5">Pilihan Jawaban</th>
                                            <th rowspan="2">Jawaban Anda</th>
                                            <th rowspan="2">Skor Anda</th>
                                        </tr>
                                        <tr>
                                            <th>-2</th>
                                            <th>-1</th>
                                            <th>0</th>
                                            <th>1</th>
                                            <th>2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Jawaban Panel</td>
                                            <td>{{ $valueCounts[-2] }}</td>
                                            <td>{{ $valueCounts[-1] }}</td>
                                            <td>{{ $valueCounts[0] }}</td>
                                            <td>{{ $valueCounts[1] }}</td>
                                            <td>{{ $valueCounts[2] }}</td>
                                            <td rowspan="3" class="align-middle">
                                                {{ $userAnswer ? $userAnswer->value : '-' }}
                                            </td>
                                            <td rowspan="3" class="align-middle">
                                                {{ $userAnswer ? $userAnswer->score : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bobot</td>
                                            <td>{{ $valueBobot[-2] }}</td>
                                            <td>{{ $valueBobot[-1] }}</td>
                                            <td>{{ $valueBobot[0] }}</td>
                                            <td>{{ $valueBobot[1] }}</td>
                                            <td>{{ $valueBobot[2] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Skor</td>
                                            <td>{{ $valueSkor[-2] }}</td>
                                            <td>{{ $valueSkor[-1] }}</td>
                                            <td>{{ $valueSkor[0] }}</td>
                                            <td>{{ $valueSkor[1] }}</td>
                                            <td>{{ $valueSkor[2] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Distribusi Jawaban Peserta --}}
                        <div class="mb-2">
                            <strong>Distribusi Jawaban Peserta</strong>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Skala</th>
                                            <th>Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($question->answers->sortBy('value') as $answer)
                                            <tr>
                                                <td>{{ $answer->value }}</td>
                                                <td>{{ $answer->answer }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Jawaban peserta --}}
                        <div class="mt-2" id="question_{{ $question->id }}">
                            @foreach ($question->answers as $answer)
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="question_{{ $question->id }}[]"
                                        class="form-check-input"
                                        data-id="{{ $answer->id }}"
                                        {{ in_array($answer->id, $allSelectedAnswers) ? 'checked' : 'disabled' }}
                                    >
                                    <label class="form-check-label"
                                        style="@if ($answer->score == 1) color: green; font-weight: bold; @endif">
                                        {{ $answer->answer }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-1 mb-2">
                            <span class="badge bg-info">
                                Skor anda: {{ $userAnswer ? $userAnswer->score : '-' }}
                            </span>
                        </div>
                    </li>
                @endforeach

                </ul>
                <div class="mb-2 card">
                    <strong>Penjelasan Skoring:</strong>
                    <ul style="margin-bottom:0;">
                        <li>Skor dihitung berdasarkan distribusi jawaban panel ahli</li>
                        <li>Bobot merupakan proporsi jawaban panel terhadap nilai tertinggi panelis</li>
                        <li>Skor akhir dihitung berdasarkan bobot jawaban yang dipilih peserta</li>
                    </ul>
                </div>
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
