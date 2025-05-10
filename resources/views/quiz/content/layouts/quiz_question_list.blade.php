@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="mb-4">
            <input type="text" id="searchQuestion" class="form-control" placeholder="Cari soal...">
        </div>

        @if ($quizQuestions->isEmpty())
            <p class="text-center">Tidak ada pertanyaan dalam kuis ini.</p>
        @else
            <div id="quizQuestionsContainer">
                @foreach ($quizQuestions as $question)
                    <div class="card-quiz mb-4 quiz-item">
                        <div class="row">
                            <div class="col-md-10">
                                {!! $question->clinical_case !!}
                            </div>
                            <div class="col-md-2 text-md-end text-start mt-2 mt-md-0">
                                <a href="{{ route('quiz-question.edit', $question->id) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-pencil me-2"></i>Edit
                                </a>
                                <button class="btn btn-danger btn-sm deleteQuiz" data-id="{{ $question->id }}">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </div>
                        </div>

                        @if ($question->answers->isNotEmpty())
                            <ul class="list-group mt-2">
                                @foreach ($question->answers as $answer)
                                    <li class="list-group-item d-flex justify-content-between align-items-center
                                        @if($answer->score == 1) list-group-item-success fw-bold @endif">
                                        <span>{{ $answer->answer }}</span>
                                        @if ($answer->score == 1)
                                            <i class="fas fa-check-circle text-success ms-2"></i>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
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
