<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Quiz</title>
    <style>
        body { background: #fff; color: #222; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; padding: 32px 16px 24px 16px; background: #fff; }
        .header {
            border-bottom: 2px solid #222;
            padding-bottom: 14px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-logo {
            height: 42px;
            width: auto;
        }
        .header-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #222;
            margin: 0;
        }
        .header-right {
            text-align: right;
            font-size: 1.08rem;
            color: #222;
            font-weight: 600;
            max-width: 350px;
            word-break: break-word;
        }
        .footer {
            border-top: 1px solid #222;
            margin-top: 32px;
            padding-top: 12px;
            text-align: center;
            color: #444;
            font-size: 0.95rem;
        }
        .card {
            background: #fff;
            color: #222;
            border-radius: 8px;
            box-shadow: 0 2px 8px #00000014;
            padding: 18px 18px;
            margin-bottom: 22px;
            border: 1px solid #222;
        }
        .custom-table-quiz th, .custom-table-quiz td {
            border: 1px solid #222 !important;
            font-size: 0.95rem !important;
            color: #222 !important;
            background: #fff !important;
        }
        .custom-table-quiz th {
            background: #f7f7f7 !important;
            font-weight: 600;
        }
        .bg-panel {
            background: #f7f7f7;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }
        .answer-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }
        .answer-value {
            background: #fff;
            color: #222;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #222;
            font-size: 0.98rem;
            font-weight: 600;
        }
        .answer-correct {
            background: #f7f7f7;
            color: #222;
            border-radius: 6px;
            padding: 5px 8px 5px 0px;
        }
        .answer-selected {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-left: 8px;
            font-size: 0.93rem;
            color: #222;
            font-weight: 500;
        }
        .answer-selected .checkmark {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 1.5px solid #222;
            border-radius: 50%;
            text-align: center;
            line-height: 16px;
            font-size: 1rem;
            margin-right: 2px;
        }
        .section-title {
            font-weight: 600;
            color: #222;
            margin-bottom: 8px;
            margin-top: 16px;
        }
        ul {
            margin: 0 0 0 18px;
            padding: 0;
        }
        @media print {
            .footer, .no-print { display: none !important; }
            body { background: #fff !important; }
        }
        .card, .card > .section-title, .card > .bg-panel, .card > .table-responsive, .card > div[style*="page-break-inside: avoid;"] {
            page-break-inside: avoid;
        }
        @media print {
            .card {
                page-break-inside: avoid;
            }
            .card > .section-title,
            .card > .bg-panel,
            .card > .table-responsive,
            .card > div[style*="page-break-inside: avoid;"] {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-left">
            <img src="{{ secure_asset('assets/images/logo-mediko.webp') }}" alt="Logo Mediko" class="header-logo">
        </div>
        <div class="header-right">
            {{ $attempt->session->title ?? '-' }}
        </div>
    </div>
    <div class="card" style="margin-bottom: 20px;">
        <div><b>Nama Peserta:</b> {{ $attempt->name ?? 'Tidak Diketahui' }}</div>
        <div><b>Total Soal:</b> {{ $attempt->session->questions->count() }}</div>
        <div><b>Skor Akhir:</b> {{ number_format(($attempt->score / $attempt->session->questions->count()) * 100, 2) }}</div>
    </div>
    @php
        $allSelectedAnswers = $attempt->userAnswer->groupBy('quiz_question_id')->map(function ($answers) {
            return $answers->pluck('quiz_answer_id')->toArray();
        })->flatten()->toArray();
    @endphp
    @foreach ($attempt->session->questions as $idx => $question)
        <div class="card" style="page-break-inside: avoid;">
            <div class="section-title">Pertanyaan {{ $idx + 1 }}</div>
            <div>{!! preg_replace('/style="[^"]*"/i', '', $question->clinical_case) !!}</div>
            <div class="section-title" style="margin-top:12px;">{{ $question->columnTitle->column_1 }}</div>
            <div class="bg-panel">{!! preg_replace('/style="[^"]*"/i', '', $question->initial_hypothesis) !!}</div>
            <div class="section-title">{{ $question->columnTitle->column_2 }}</div>
            <div class="bg-panel">
                {!! preg_replace('/style="[^"]*"/i', '', $question->new_information) !!}
                @if ($question->uploaded_image_base64)
                    <img src="{{ $question->uploaded_image_base64 }}" width="200rem" alt="Informasi Baru Gambar" style="margin-top:8px;">
                @endif
            </div>
            @php
                $valueCounts = collect([-2, -1, 0, 1, 2])->mapWithKeys(function($v) use ($question) {
                    return [$v => $question->answers->where('value', $v)->sum('panelist')];
                });
                $totalPanelist = $valueCounts->max();
                $valueBobot = $valueCounts->map(function($count) use ($totalPanelist) {
                    return $totalPanelist ? ($count . '/' . $totalPanelist) : '0/0';
                });
                $valueSkor = collect([-2, -1, 0, 1, 2])->mapWithKeys(function($v) use ($question) {
                    $answer = $question->answers->where('value', $v)->first();
                    return [$v => $answer ? $answer->score : 0];
                });
                $userAnswer = $question->answers->first(function($a) use ($allSelectedAnswers) {
                    return in_array($a->id, $allSelectedAnswers);
                });
            @endphp
            <div class="section-title" style="margin-top:14px;">Rationale dan Skor Likert</div>
            <div class="table-responsive">
                <table class="table table-sm text-center align-middle custom-table-quiz" style="width:100%;">
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
                            <td rowspan="3" class="align-middle">{{ $userAnswer ? $userAnswer->value : '-' }}</td>
                            <td rowspan="3" class="align-middle">{{ $userAnswer ? $userAnswer->score : '-' }}</td>
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
            @if($question->rationale)
                <div class="section-title" style="margin-top:14px;">Rationale</div>
                <div class="bg-panel">{!! preg_replace('/style="[^"]*"/i', '', $question->rationale) !!}</div>
            @endif
            <div class="section-title" style="margin-top:14px;">Distribusi Jawaban Peserta</div>
            <div style="page-break-inside: avoid;">
                @foreach ($question->answers as $answer)
                    <div class="answer-box {{ $answer->score == 1 ? 'answer-correct' : '' }}">
                        <div class="answer-value">{{ $answer->value }}</div>
                        <span>{{ $answer->answer }}</span>
                        @if( in_array($answer->id, $allSelectedAnswers) )
                            <span class="answer-selected">
                                <span class="checkmark">&#10003;</span> Pilihan Anda
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-2 mb-1">
                <span style="font-size: 0.98rem; font-weight: 500; color: #222;">
                    Skor anda: {{ $userAnswer ? $userAnswer->score : '-' }}
                </span>
            </div>
        </div>
    @endforeach

    <div class="card" style="margin-bottom: 0;">
        <div class="section-title" style="margin-bottom: 8px;">Penjelasan Skoring:</div>
        <ul>
            <li>Skor dihitung berdasarkan distribusi jawaban panel ahli</li>
            <li>Bobot merupakan proporsi jawaban panel terhadap nilai tertinggi panelis</li>
            <li>Skor akhir dihitung berdasarkan bobot jawaban yang dipilih peserta</li>
        </ul>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} SCT Mediko | Hasil Quiz
    </div>
</div>
</body>
</html>
