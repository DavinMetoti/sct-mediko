@php
    use Illuminate\Support\Str;
    $softColors = ['#E57373', '#F06292', '#BA68C8', '#64B5F6', '#4DB6AC', '#81C784', '#FFD54F', '#FF8A65'];
@endphp

@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="row g-4 align-items-stretch">
            @if ($user->accessRole->access != 'private')
                <div class="{{ $user->accessRole->access != 'private' && isset($user) ? 'col-md-6' : 'col-md-12' }} d-flex">
                    <div class="card shadow-lg border-0 w-100 p-4 d-flex flex-column justify-content-center" style="border-radius: 15px; background-color: #fff;">
                        <h5 class="text-dark">Join a Quiz</h5>
                        <div class="d-flex align-items-center border rounded-pill px-3 py-2 shadow-sm bg-light">
                            <i class="fas fa-key me-2 text-primary"></i>
                            <input type="text" id="access_code" class="form-control border-0 shadow-none bg-transparent text-uppercase" placeholder="Enter a join code" style="flex: 1; text-transform: uppercase;" maxlength="6">
                            <button id="join-quiz" class="btn btn-primary px-4 rounded-pill shadow-sm">Join <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($user))
                <div class="{{ $user->accessRole->access != 'private' && isset($user) ? 'col-md-6' : 'col-md-12' }} d-flex">
                    <div class="card shadow-lg border-0 w-100 p-4 text-white position-relative overflow-hidden"
                        style="background: linear-gradient(135deg, #6a11cb, #2575fc); border-radius: 15px; height: 200px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Welcome,</h5>
                                <h1 class="fw-bold mt-2">{{ Str::title(auth()->user()->name) }}</h1>
                            </div>
                            <div class="position-absolute bottom-0 end-0">
                                <img src="{{ secure_asset('assets/images/person.svg') }}" alt="person" width="180"
                                    style="display: block; transform: translateY(30%);">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <h3 class="fw-bold mt-4">⭐️ Daftar Quiz Tersedia</h3>
            </div>
            <div class="col-md-4">
                <div class="input-group mt-4">
                    <input type="text" id="searchQuiz" class="form-control border-0 shadow-sm"
                        placeholder="Search quiz..."
                        style="background: #f8f9fa; border-radius: 20px;">
                </div>
            </div>
        </div>


        <div class="row mt-4" id="list-question">
            @foreach ($sessions_list as $session)
                @php
                    $randomColor = $softColors[array_rand($softColors)];
                @endphp
                <div class="col-md-3 col-sm-6 mb-4 quiz-item" data-title="{{ strtolower($session->title) }}">
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
                            {{ $session->questions_count }} Qs
                        </span>

                        <span class="badge bg-light text-primary position-absolute top-0 end-0 m-2 px-2 py-1">
                            {{ $session->attempts_count >= 1000 ? number_format($session->attempts_count / 1000, $session->attempts_count % 1000 !== 0 ? 1 : 0) . 'k' : $session->attempts_count }} plays
                        </span>


                        <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                            <h6 class="fw-bold text-dark mb-0">{{ $session->title }}</h6>
                            <div>
                                @php
                                    $dataHasBookmark = $session->libraries->filter(fn($lib) => !is_null($lib->folder_id));
                                    $dataHasLike = $session->libraries->filter(fn($lib) => is_null($lib->folder_id));

                                    $hasBookmark = $dataHasBookmark->isNotEmpty();
                                    $hasLike = $dataHasLike->isNotEmpty();
                                @endphp

                                <i class="bi like-icon {{ $hasLike ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                                data-id="{{ $session->id }}"
                                data-library-id="{{ $dataHasLike->pluck('id')->implode(',') }}"
                                style="cursor: pointer; font-size: 1.2rem;">
                                </i>

                                <i class="bi save-icon {{ $hasBookmark ? 'bi-bookmark-fill text-secondary' : 'bi-bookmark' }}"
                                data-id="{{ $session->id }}"
                                data-library-id="{{ $dataHasBookmark->pluck('id')->implode(',') }}"
                                style="cursor: pointer; font-size: 1.2rem;">
                                </i>
                            </div>
                        </div>

                        <div class="pb-3 px-3">
                            @if ($user->accessRole->access == 'private')
                                <a href="{{ route('quiz-rank', ['id' => $session->id]) }}" class="btn btn-secondary w-full rounded-pill">View Rank</a>
                            @else
                                <button class="btn btn-primary w-full rounded-pill start-btn" data-id="{{$session->access_code}}">Start</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="quizModalLabel">Tambah Quiz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form id="quizForm">
                        <input type="text" class="d-none" id="session_id" name="quiz_session_id">
                        <div class="mb-3">
                            <label for="collectionSelect" class="form-label text-dark" >Pilih Koleksi</label>
                            <select class="form-control select2" id="collectionSelect" name="folder_id" required>
                                @foreach ($collections as $collection)
                                    <option value="{{ $collection->id }}">{{$collection->folder_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.like-icon').click(function() {
                let icon = $(this);
                let sessionId = icon.data('id');
                let libraryId = icon.data('library-id'); // Ambil ID library jika ada
                let isLiked = icon.hasClass('bi-heart-fill');

                if (isLiked) {
                    // Jika ikon aktif (liked), hapus dari library
                    $.ajax({
                        url: "/library/" + libraryId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            toastr.success("Bookmark berhasil dihapus", "Sukses");
                            icon.removeClass('bi-heart-fill text-danger').addClass('bi-heart');
                            icon.data('library-id', "");
                        },
                        error: function() {
                            toastr.error("Quiz gagal disimpan", "Gagal", {
                                timeOut: 3000,
                                progressBar: true,
                                positionClass: "toast-top-right"
                            });
                        }
                    });
                } else {
                    $.post("{{ route('library.store') }}", {
                        quiz_session_id: sessionId,
                        folder_id: null,
                        _token: "{{ csrf_token() }}"
                    }).done(function(response) {
                        toastr.success("Quiz berhasil disimpan", "Sukses", {
                            timeOut: 3000,
                            progressBar: true,
                            positionClass: "toast-top-right"
                        });
                        icon.addClass('bi-heart-fill text-danger').removeClass('bi-heart');
                        icon.data('library-id', response.id);
                    }).fail(function() {
                        toastr.error("Anda sudah menambahkan", "Gagal");
                    });
                }
            });

            $('.save-icon').click(function() {
                let icon = $(this);
                let sessionId = icon.data('id');
                let libraryId = icon.data('library-id');
                let isBookmarked = icon.hasClass('bi-bookmark-fill');

                if (isBookmarked) {
                    $.ajax({
                        url: "/library/" + libraryId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            toastr.success("Bookmark berhasil dihapus", "Sukses");
                            icon.removeClass('bi-bookmark-fill text-secondary').addClass('bi-bookmark');
                            icon.data('library-id', "");
                        },
                        error: function() {
                            toastr.error("Anda sudah menambahkan", "Gagal");
                        }
                    });
                } else {
                    $('#session_id').val(sessionId);
                    $("#quizModal").modal("show");
                }
            });

            $('#searchQuiz').on('keyup', function() {
                let filter = $(this).val().toLowerCase();
                $('.quiz-item').each(function() {
                    $(this).toggle($(this).data('title').includes(filter));
                });
            });

            $("#quizForm").submit(function (e) {
                e.preventDefault();
                let formData = $(this).serializeArray();

                let quizSessionId = formData.find(item => item.name === "quiz_session_id")?.value;

                $.post("{{ route('library.store') }}", $.param(formData) + "&_token=" + "{{ csrf_token() }}")
                    .done(function (response) {
                        toastr.success("Quiz berhasil disimpan", "Sukses", {
                            timeOut: 3000,
                            progressBar: true,
                            positionClass: "toast-top-right"
                        });

                        $("#quizModal").modal("hide");
                        $(".modal-backdrop").remove();

                        let icon = $(".save-icon[data-id='" + quizSessionId + "']");
                        if (icon.length) {
                            icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill text-secondary');
                        }
                    })
                    .fail(function () {
                        alert("Terjadi kesalahan, coba lagi.");
                    });
            });

            $('#join-quiz').click(function() {
                let access_code = $('#access_code').val().trim();

                if (access_code === '') {
                    toastr.warning('Kode akses wajib diisi', 'Warning', {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });
                    return;
                }

                if (access_code.length < 6) {
                    toastr.warning('Kode akses harus terdiri dari 6 karakter', 'Warning', {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });
                    return;
                }

                $('#join-quiz').prop('disabled', true).text('Memeriksa...');

                $.ajax({
                    url: '/start-quiz',
                    type: 'POST',
                    data: {
                        access_code: access_code,
                        _token: "{{ csrf_token() }}"
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        setTimeout(() => {
                            if (response.success) {
                                sessionStorage.setItem('quiz_attempt_token', response.quiz_attempt.attempt_token);
                                sessionStorage.setItem('quiz_session_id', response.quiz_attempt.session_id);
                                sessionStorage.setItem('quiz_user_id', response.quiz_attempt.user_id);

                                toastr.success(response.message, 'Success', {
                                    timeOut: 3000,
                                    progressBar: true,
                                    positionClass: "toast-top-right"
                                });

                                // Redirect ke halaman quiz jika diperlukan
                                window.location.href = "{{ route('quiz-play.index') }}";
                            } else {
                                toastr.error(response.message, 'Error', {
                                    timeOut: 3000,
                                    progressBar: true,
                                    positionClass: "toast-top-right"
                                });
                            }
                        }, 3000);
                    },
                    error: function(error) {
                        setTimeout(() => {
                            toastr.error(error.responseJSON.message, 'Error', {
                                timeOut: 3000,
                                progressBar: true,
                                positionClass: "toast-top-right"
                            });
                        }, 3000);
                    },
                    complete: function() {
                        setTimeout(() => {
                            $('#join-quiz').prop('disabled', false).text('Join Quiz');
                        }, 3000);
                    }
                });
            });

            $('.start-btn').click(function() {
                let access_code = $(this).attr('data-id'); // Ambil kode akses dari atribut data-id

                if (!access_code) {
                    toastr.warning('Kode akses tidak valid!', 'Warning', {
                        timeOut: 3000,
                        progressBar: true,
                        positionClass: "toast-top-right"
                    });
                    return;
                }

                $('.start-btn').prop('disabled', true).text('Memulai...');

                $.ajax({
                    url: '/start-quiz',
                    type: 'POST',
                    data: {
                        access_code: access_code,
                        _token: "{{ csrf_token() }}"
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        setTimeout(() => {
                            if (response.success) {
                                sessionStorage.setItem('quiz_attempt_token', response.quiz_attempt.attempt_token);
                                sessionStorage.setItem('quiz_session_id', response.quiz_attempt.session_id);
                                sessionStorage.setItem('quiz_user_id', response.quiz_attempt.user_id);

                                toastr.success(response.message, 'Success', {
                                    timeOut: 3000,
                                    progressBar: true,
                                    positionClass: "toast-top-right"
                                });

                                // Redirect ke halaman quiz
                                window.location.href = "{{ route('quiz-play.index') }}";
                            } else {
                                toastr.error(response.message, 'Error', {
                                    timeOut: 3000,
                                    progressBar: true,
                                    positionClass: "toast-top-right"
                                });
                            }
                        }, 3000);
                    },
                    error: function(error) {
                        setTimeout(() => {
                            toastr.error(error.responseJSON.message || "Terjadi kesalahan!", 'Error', {
                                timeOut: 3000,
                                progressBar: true,
                                positionClass: "toast-top-right"
                            });
                        }, 3000);
                    },
                    complete: function() {
                        setTimeout(() => {
                            $('.start-btn').prop('disabled', false).text('Start');
                        }, 3000);
                    }
                });
            });


        });
    </script>
@endsection
