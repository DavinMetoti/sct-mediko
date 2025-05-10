@php
    use Illuminate\Support\Str;
    $softColors = ['#E57373', '#F06292', '#BA68C8', '#64B5F6', '#4DB6AC', '#81C784', '#FFD54F', '#FF8A65'];
@endphp

@extends('quiz.content.index')

@section('quiz-content')
    <h3 class="fw-bold">Daftar Pembahasan</h3>
    <div class="row mt-4" id="list-question">
        @foreach ($quizResult as $result)
            @php
                $randomColor = $softColors[array_rand($softColors)];
            @endphp
            <div class="col-md-3 col-sm-6 mb-4 quiz-item" data-title="{{ strtolower($result->session->title) }}">
                <div class="quiz-card card shadow-lg border-0"
                    style="border-radius: 12px; overflow: hidden; cursor: pointer;">

                    <div class="position-relative" style="background: {{ $randomColor }}; height: 150px; border-radius: 12px 12px 0 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
                            style="position: absolute; bottom: 0; left: 0; width: 100%;">
                            <path fill="#ffffff" fill-opacity="1"
                                d="M0,128L120,144C240,160,480,192,720,197.3C960,203,1200,181,1320,170.7L1440,160L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z">
                            </path>
                        </svg>
                    </div>

                    <span class="badge bg-light text-dark position-absolute top-0 start-0 m-2 px-2 py-1">
                        {{ $result->created_at->diffForHumans() }}
                    </span>
                    <span class="badge bg-light text-dark position-absolute top-0 end-0 m-2 px-2 py-1">
                        {{ $result->name }}
                    </span>

                    <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                        <h6 class="fw-bold text-dark mb-0">{{ $result->session->title }}</h6>
                        <div class="badge bg-success rounded-pill px-2 py-1">
                            score {{ $result->session->questions->count() > 0 ? number_format(($result->score / $result->session->questions->count()) * 100, 1) : 0 }}
                        </div>
                    </div>

                    <div class="pb-3 px-3">
                        <a href="{{ route('quiz-play.edit', ['quiz_play' => $result->id]) }}" class="btn btn-primary w-full rounded-pill">Pembahasan</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
