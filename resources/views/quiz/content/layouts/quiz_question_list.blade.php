@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="row mt-2">
            <div class="col-md-9">
                <h4 class="fw-semibold mt-4" style="color: #5E5E5E;">Daftar kuis dalam bank</h4>
            </div>
            <div class="col-md-3">
                <div class="input-group mt-4" style="border: 1px solid #E7E7E7 !important; border-radius: 8px !important;">
                    <span class="input-group-text bg-white border-0" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-search text-muted" style="opacity: 0.6;"></i>
                    </span>
                    <input type="text" id="searchQuestion" class="form-control border-0 input-placeholder"
                        placeholder="Cari kuis ..."
                        style="background: #FFFFFF; border-radius: 8px !important;">
                </div>
            </div>
        </div>

        @if ($quizQuestions->isEmpty())
            <p class="text-center">Tidak ada pertanyaan dalam kuis ini.</p>
        @else
            <div id="quizQuestionsContainer" class="mt-4">
                @foreach ($quizQuestions as $question)
                    <div class="card p-4 rounded-4 mb-4 quiz-item">
                        <div class="row">
                            <div class="col-md-10">
                                {!! $question->clinical_case !!}
                            </div>
                            <div class="col-md-2 text-md-end text-start mt-2 mt-md-0">
                                <a href="{{ route('quiz-question.edit', $question->id) }}" class="btn btn-orange btn-sm me-2 rounded-2">
                                    <i class="fas fa-pencil me-2"></i>Edit
                                </a>
                                <button class="btn btn-danger btn-sm deleteQuiz" data-id="{{ $question->id }}">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </div>
                        </div>

                        @if ($question->answers->isNotEmpty())
                            <div class="row row-cols-2 row-cols-md-5 g-2 mt-2 text-center">
                                @foreach ($question->answers as $answer)
                                    <div class="col">
                                        <div class="list-group-item d-flex justify-content-center align-items-center rounded-4 w-100 h-100
                                            @if($answer->score == 1) fw-bold @endif"
                                            style="aspect-ratio:1/1; min-height:80px; border:1px solid #e3e3e3; background:{{ $answer->score == 1 ? '#E9F6EA' : '#f9f9f9' }};">
                                            <span>{{ $answer->answer }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada jawaban untuk pertanyaan ini.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        $(document).ready(function () {
            const quizQuestionApi = new HttpClient('{{ route("quiz-question.index") }}');

            function debounce(func, delay) {
                let timer;
                return function () {
                    clearTimeout(timer);
                    timer = setTimeout(func, delay);
                };
            }

            function searchQuestions() {
                let filter = $('#searchQuestion').val().toLowerCase();
                $('.quiz-item').each(function () {
                    let text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(filter));
                });
            }

            $('#searchQuestion').on('input', debounce(searchQuestions, 300));

            $(document).off('click', '.deleteQuiz').on('click', '.deleteQuiz', function (e) {
                let id = $(this).data('id');

                if (!id) {
                    toastr.warning("⚠️ ID pertanyaan tidak ditemukan. Silakan coba lagi.", "", { timeOut: 3000 });
                    return;
                }
                data = { _token: "{{ csrf_token() }}" };

                confirmationModal.open({
                    message: 'Apakah Anda yakin ingin menghapus quiz ini?',
                    severity: 'warn',
                    onAccept: function () {
                        quizQuestionApi.request('DELETE',`${id}`, data)
                            .then(response => {
                                toastr.success("✅ Pertanyaan berhasil dihapus! Data telah diperbarui.", "", { timeOut: 3000 });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            })
                            .catch(error => {
                                toastr.error("❌ Gagal menghapus pertanyaan. Silakan coba lagi.", "", { timeOut: 3000 });
                                console.error('Error:', error);
                            });

                    },
                    onReject: function () {
                        toastr.info("ℹ️ Penghapusan pertanyaan dibatalkan.", "", { timeOut: 3000 });
                    }
                });
            });
        });
    </script>

@endsection
