<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Rationale</title>
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
        .section-title {
            font-weight: 600;
            color: #222;
            margin-bottom: 8px;
            margin-top: 16px;
        }
        .bg-panel {
            background: #f7f7f7;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }
        .panelist-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .panelist-table th, .panelist-table td {
            border: 1px solid #222;
            padding: 6px 10px;
            text-align: center;
            font-size: 0.98rem;
        }
        .panelist-table th {
            background: #f7f7f7;
        }
        @media print {
            .footer, .no-print { display: none !important; }
            body { background: #fff !important; }
        }
        .card, .card > .section-title, .card > .bg-panel {
            page-break-inside: avoid;
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
        </div>
        @foreach ($attempt->session->questions as $idx => $question)
            <div class="card" style="page-break-inside: avoid;">
                <div class="section-title">SOAL {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</div>
                @if($question->rationale)
                    <div class="section-title" style="margin-top:8px;">Rationale</div>
                    <div class="bg-panel">{!! preg_replace('/style="[^"]*"/i', '', $question->rationale) !!}</div>
                @endif
                @php
                    $panelistCounts = collect([-2, -1, 0, 1, 2])->mapWithKeys(function($v) use ($question) {
                        return [$v => $question->answers->where('value', $v)->sum('panelist')];
                    });
                @endphp
                <div class="section-title" style="margin-top:8px;">Sebaran Panelis</div>
                <table class="panelist-table">
                    <thead>
                        <tr>
                            <th>-2</th>
                            <th>-1</th>
                            <th>0</th>
                            <th>+1</th>
                            <th>+2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $panelistCounts[-2] }}</td>
                            <td>{{ $panelistCounts[-1] }}</td>
                            <td>{{ $panelistCounts[0] }}</td>
                            <td>{{ $panelistCounts[1] }}</td>
                            <td>{{ $panelistCounts[2] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
        <div class="footer">
            &copy; {{ date('Y') }} SCT Mediko | Hasil Rationale
        </div>
    </div>
</body>
</html>