@extends('quiz.content.play')
@section('quiz-content')
    <div class="quiz-container">
        <div class="md-container">
            <style>
                @media print {
                    button, .card-quiz {
                        display: none !important;
                    }
                }
                .custom-table-quiz th,
                .custom-table-quiz td {
                    border: solid 1px rgba(172, 194, 234, 0.71) !important;
                    font-size: 0.92rem !important;
                }
            </style>
            <div class="quiz-header py-3 d-flex align-items-center justify-content-between">
                    <button class="btn" style="background-color: #699AF5;color: #fff;font-size: 1rem;font-weight:bold" onclick="confirmExit()">
                        <i class="fas fa-times"></i>
                    </button>
                <span class="quiz-title font-weight-bold">
                    @if($attempt)
                        {{ $attempt->session->title }}
                    @else
                        <span class="text-muted">Creating a game...</span>
                    @endif
                </span>

                <div class="header-right d-md-flex d-none gap-2">
                    <button class="btn" style="background-color: #699AF5;color: #fff;font-size: 1rem;font-weight:bold" onclick="toggleFullscreen()"><i class="fas fa-expand"></i></button>
                </div>
            </div>
            @if($attempt)
                <div class="row">
                    <div class="col-12">
                        <!-- message print success -->
                        <div id="print-success-message" class="alert alert-success d-none" role="alert">
                            Quiz print berhasil! File PDF telah diunduh.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background-color: #204281; padding: 20px; border-radius: 10px;">
                            <p style="color: #ACC2EA;">Nama Peserta <br><span class="fw-semibold text-white"> {{ $attempt->name ?? 'Tidak Diketahui' }}</span></p>
                            <p style="color: #ACC2EA;">Total Soal <br><span class="fw-semibold text-white"> {{ $attempt->session->questions->count() }}</span></p>
                            <p style="color: #ACC2EA;">Skor Akhir <br><span class="fw-semibold text-white"> {{ number_format(($attempt->score / $attempt->session->questions->count()) * 100, 2) }}</span></p>
                            <button onclick="startPrint()" class="btn btn-orange w-full mt-3" style="border-radius: 8px; position: relative;">
                                <i class="fas fa-print me-2"></i>
                                <span id="print-btn-text">Print</span>
                                <span id="print-spinner" style="display:none;position:absolute;right:16px;top:50%;transform:translateY(-50%);">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>
                            <button onclick="startPrintRationale()" class="btn btn-danger w-full mt-3" style="border-radius: 8px; position: relative;">
                                <i class="fas fa-file-alt me-2"></i>
                                <span id="print-btn-text">Print Rationale</span>
                                <span id="print-spinner" style="display:none;position:absolute;right:16px;top:50%;transform:translateY(-50%);">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-9  mt-4 mt-md-0" style="max-height: 90vh; overflow-y: auto;">
                        <div>
                            @php
                                $allSelectedAnswers = $attempt->userAnswer->groupBy('quiz_question_id')->map(function ($answers) {
                                    return $answers->pluck('quiz_answer_id')->toArray();
                                })->flatten()->toArray();
                            @endphp
                            @foreach ($attempt->session->questions as $idx => $question)
                                <ul class="list-group pb-3 mb-2">
                                    <li class="list-group-item border-0" style="background-color: #204281; padding: 20px; border-radius: 10px;">
                                        <div>
                                            <p class="mb-2 text-white fw-semibold">Pertanyaan {{ $idx + 1 }}</p>
                                        </div>
                                        <div class="text-white mb-3">
                                            {!! preg_replace('/style="[^"]*"/i', '', $question->clinical_case) !!}
                                        </div>
                                        <p class="mb-2 text-white fw-semibold">{{$question->columnTitle->column_1}}</p>
                                        <div class="text-white mb-3 p-2" style="background-color: #23488E;border-radius: 8px;">
                                            {!! preg_replace('/style="[^"]*"/i', '', $question->initial_hypothesis) !!}
                                        </div>
                                        <p class="mb-2 text-white fw-semibold">{{$question->columnTitle->column_2}}</p>
                                        <div class="text-white mb-3 p-2" style="background-color: #23488E;border-radius: 8px;">
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
                                        <strong class="text-white">Rationale dan Skor Likert</strong>
                                            <div class="table-responsive">
                                                <table class="table table-sm text-center align-middle custom-table-quiz">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2" class="text-white">Keterangan</th>
                                                            <th colspan="5" class="text-white">Pilihan Jawaban</th>
                                                            <th rowspan="2" class="text-white">Jawaban Anda</th>
                                                            <th rowspan="2" class="text-white">Skor Anda</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-white">-2</th>
                                                            <th class="text-white">-1</th>
                                                            <th class="text-white">0</th>
                                                            <th class="text-white">1</th>
                                                            <th class="text-white">2</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-white">Jawaban Panel</td>
                                                            <td class="text-white">{{ $valueCounts[-2] }}</td>
                                                            <td class="text-white">{{ $valueCounts[-1] }}</td>
                                                            <td class="text-white">{{ $valueCounts[0] }}</td>
                                                            <td class="text-white">{{ $valueCounts[1] }}</td>
                                                            <td class="text-white">{{ $valueCounts[2] }}</td>
                                                            <td class="text-white" rowspan="3" class="align-middle">
                                                                {{ $userAnswer ? $userAnswer->value : '-' }}
                                                            </td>
                                                            <td class="text-white" rowspan="3" class="align-middle">
                                                                {{ $userAnswer ? $userAnswer->score : '-' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-white">Bobot</td>
                                                            <td class="text-white">{{ $valueBobot[-2] }}</td>
                                                            <td class="text-white">{{ $valueBobot[-1] }}</td>
                                                            <td class="text-white">{{ $valueBobot[0] }}</td>
                                                            <td class="text-white">{{ $valueBobot[1] }}</td>
                                                            <td class="text-white">{{ $valueBobot[2] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-white">Skor</td>
                                                            <td class="text-white">{{ $valueSkor[-2] }}</td>
                                                            <td class="text-white">{{ $valueSkor[-1] }}</td>
                                                            <td class="text-white">{{ $valueSkor[0] }}</td>
                                                            <td class="text-white">{{ $valueSkor[1] }}</td>
                                                            <td class="text-white">{{ $valueSkor[2] }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if($question->rationale)
                                            <strong class="text-white">Rationale:</strong>
                                                <div class="mt-3 p-3" style="background-color: #23488E; border-radius: 8px;">
                                                    <div class="text-white">{!! preg_replace('/style="[^"]*"/i', '', $question->rationale) !!}</div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Distribusi Jawaban Peserta --}}
                                        <div class="mb-2">
                                            <strong class="text-white">Distribusi Jawaban Peserta</strong>
                                                @foreach ($question->answers as $answer)
                                                    <div class="text-white mb-2 p-2" style="{{ $answer->score == 1 ? 'background-color: #E9F6EA;border-radius: 8px;' : 'background-color: #23488E;border-radius: 8px;' }}">
                                                        <div class="d-flex align-items-center justify-content-between" style="@if ($answer->score == 1) color: #44A047 !important; font-weight: 500; @endif">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div style="background-color: #699AF5;color: #fff;font-size: 14px;width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                                    {{ $answer->value }}
                                                                </div>
                                                                <span>{{ $answer->answer }}</span>
                                                            </div>
                                                            @if( in_array($answer->id, $allSelectedAnswers) )
                                                                <div>
                                                                    <span class="badge bg-info text-white">
                                                                        Pilihan Anda
                                                                    </span>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </div>
                                                @endforeach
                                    </li>
                                </ul>
                            @endforeach

                            <div class="mb-2" style="background-color: #204281; padding: 20px; border-radius: 10px;">
                                <strong>Penjelasan Skoring:</strong>
                                <ul style="margin-bottom:0;padding-left: 10px;">
                                    <li>1. Skor dihitung berdasarkan distribusi jawaban panel ahli</li>
                                    <li>2. Bobot merupakan proporsi jawaban panel terhadap nilai tertinggi panelis</li>
                                    <li>3. Skor akhir dihitung berdasarkan bobot jawaban yang dipilih peserta</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <p class="text-center mt-4">Tidak ada data hasil kuis.</p>
            @endif
        </div>
    </div>

    <!-- Modal Bootstrap untuk pesan rationale tidak tersedia -->
    <div class="modal fade" id="noRationaleModal" tabindex="-1" aria-labelledby="noRationaleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
          <div class="modal-body text-dark text-center  py-4 px-4" style="background: #f8fafc; border-radius: 18px;">
            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/images/Feeling sorry-pana.png') }}" alt="Rationale Not Available" style="width: 120px; margin-bottom: 18px; opacity: 0.85;text-align: center;">
            </div>
            <h5 class="fw-bold mb-2" style="color: #23488E;">Rationale Belum Tersedia</h5>
            <p class="mb-3" style="color: #555; font-size: 1.05rem;">
                Mohon maaf, rationale untuk soal-soal pada sesi ini belum tersedia.<br>
                Silakan hubungi admin atau pembuat soal untuk informasi lebih lanjut.
            </p>
            <button type="button" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-dismiss="modal" style="font-weight: 500;">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </button>
          </div>
        </div>
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

        function startPrint() {
            var btn = document.querySelector('button[onclick="startPrint()"]');
            var spinner = document.getElementById('print-spinner');
            var btnText = document.getElementById('print-btn-text');
            var successMsg = document.getElementById('print-success-message');
            btn.disabled = true;
            spinner.style.display = 'inline-block';
            btnText.textContent = 'Printing...';

            fetch('{{ route('quiz-play.print', ['id' => $attempt->id]) }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                if (!response.ok) {
                    throw new Error('Gagal generate PDF');
                }
                const data = await response.json();
                if (data.success && data.file) {
                    // Download PDF dari base64
                    const link = document.createElement('a');
                    link.href = 'data:application/pdf;base64,' + data.file;
                    link.download = data.filename || 'quiz-result.pdf';
                    document.body.appendChild(link);
                    link.click();
                    window.open(link.href, '_blank'); // Membuka file PDF di tab baru
                    document.body.removeChild(link);
                    btnText.textContent = 'Print';
                    spinner.style.display = 'none';
                    btn.disabled = false;
                    // Tampilkan pesan sukses print
                    if(successMsg) {
                        successMsg.classList.remove('d-none');
                        setTimeout(() => {
                            successMsg.classList.add('d-none');
                        }, 3500);
                    }
                    toastr.success("Quiz print berhasil", "Sukses", {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });
                } else {
                    toastr.error("Quiz print gagal", "Error", {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });
                    throw new Error(data.message || 'Gagal generate PDF');
                }
            })
            .catch(function(err) {
                toastr.error("Quiz print gagal", "Error", {
                    timeOut: 3000,
                    progressBar: true,
                    positionClass: "toast-top-right"
                });
                btnText.textContent = 'Print';
                spinner.style.display = 'none';
                btn.disabled = false;
                alert(err.message || 'Gagal generate PDF');
            });
        }

        function startPrintRationale() {
            const questions = @json($attempt->session->questions);
            let hasRationale = false;
            if (questions && Array.isArray(questions)) {
                hasRationale = questions.some(q => q.rationale && q.rationale.trim() !== "");
            } else if (questions && typeof questions === 'object') {
                hasRationale = Object.values(questions).some(q => q.rationale && q.rationale.trim() !== "");
            }
            if (!hasRationale) {
                var modal = new bootstrap.Modal(document.getElementById('noRationaleModal'));
                modal.show();
                return;
            }

            // Download file PDF dengan fetch
            var btn = document.querySelector('button[onclick="startPrintRationale()"]');
            var spinner = btn ? btn.querySelector('#print-spinner') : null;
            var btnText = btn ? btn.querySelector('#print-btn-text') : null;
            if (btn && spinner && btnText) {
                btn.disabled = true;
                spinner.style.display = 'inline-block';
                btnText.textContent = 'Printing...';
            }

            fetch("{{ route('quiz-play.print-rationale', ['id' => $attempt->id]) }}", {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                if (!response.ok) {
                    throw new Error('Gagal generate PDF');
                }
                const data = await response.json();
                if (data.success && data.file) {
                    // Download PDF dari base64
                    const link = document.createElement('a');
                    link.href = 'data:application/pdf;base64,' + data.file;
                    link.download = data.filename || 'quiz-rationale.pdf';
                    document.body.appendChild(link);
                    link.click();
                    window.open(link.href, '_blank');
                    document.body.removeChild(link);
                    if (btn && spinner && btnText) {
                        btnText.textContent = 'Print Rationale';
                        spinner.style.display = 'none';
                        btn.disabled = false;
                    }
                    toastr.success("Print rationale berhasil", "Sukses", {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });
                } else {
                    toastr.error("Print rationale gagal", "Error", {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });
                    throw new Error(data.message || 'Gagal generate PDF');
                }
            })
            .catch(function(err) {
                toastr.error("Print rationale gagal", "Error", {
                    timeOut: 3000,
                    progressBar: true,
                    positionClass: "toast-top-right"
                });
                if (btn && spinner && btnText) {
                    btnText.textContent = 'Print Rationale';
                    spinner.style.display = 'none';
                    btn.disabled = false;
                }
                alert(err.message || 'Gagal generate PDF');
            });
        }
    </script>
@endsection
