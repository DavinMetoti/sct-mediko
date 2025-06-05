@extends('quiz.content.index')

@section('quiz-content')
    <div class="row">
        <div class="col-md-9">
            <h4 class="fw-semibold" style="color: #5E5E5E;">Pembahasan</h4>
        </div>
        <div class="col-md-3">
            <div class="input-group" style="border: 1px solid #E7E7E7 !important; border-radius: 8px !important;">
                <span class="input-group-text bg-white border-0" style="border-radius: 8px 0 0 8px;">
                    <i class="fas fa-search text-muted" style="opacity: 0.6;"></i>
                </span>
                <input type="text" id="searchQuiz" class="form-control border-0 input-placeholder"
                    placeholder="Cari kuis ..."
                    style="background: #FFFFFF; border-radius: 8px !important;">
            </div>
        </div>
    </div>
    <div class="row mt-4" id="list-question">
        @forelse ($quizResult as $result)
            @php
                $nilai = $result->questions_count > 0 ? ($result->score / $result->questions_count) * 100 : 0;
                $nilaiColor = $nilai < 70 ? '#C73232' : '#32C75F';
            @endphp
            <div class="col-md-3 col-sm-6 mb-4 quiz-item"
                data-title="{{ strtolower($result->session->title) }}"
                data-name="{{ strtolower($result->name) }}">
                <div class="quiz-card card shadow-lg border-0 h-100 d-flex flex-column"
                    style="border-radius: 12px; overflow: hidden; cursor: pointer; max-height: 320px; min-height: 320px; display: flex; flex-direction: column;">
                    <div class="position-relative" style="background-image: url('{{ secure_asset('assets/images/petern.png') }}'); background-size: cover; min-height: 140px; border-radius: 12px 12px 0 0;">
                        <span class="badge bg-light position-absolute top-0 start-0 m-2 px-2 py-1"
                              style="color: {{ $nilaiColor }};font-size: 14px; font-weight: 700;border-radius: 8px;">
                            Nilai {{ number_format($nilai, 1) }}
                        </span>
                    </div>
                    <div class="row mt-3 px-3">
                        <h6 class="fw-semibold text-dark mb-0">{{ $result->session->title }}</h6>
                    </div>
                    <div class="d-flex flex-column gap-2 px-3 mt-1">
                        <small style="font-size: 12px;" class="text-muted"><i class="fas fa-user me-1"></i>{{ $result->name }}</small>
                        <small style="font-size: 12px;" class="text-muted"><i class="fas fa-clock me-1"></i>{{ $result->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mt-auto pb-3 px-3">
                        <a href="{{ route('quiz-play.edit', ['quiz_play' => $result->id]) }}" class="btn btn-orange w-full">Pembahasan</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                Tidak ada quiz yang tersedia.
            </div>
        @endforelse
    </div>
    <script>
        document.getElementById('searchQuiz').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            document.querySelectorAll('.quiz-item').forEach(function(item) {
                const title = item.getAttribute('data-title');
                const name = item.getAttribute('data-name');
                item.style.display = (title.includes(search) || name.includes(search)) ? '' : 'none';
            });
        });
    </script>
@endsection
