@php
    use Illuminate\Support\Str;
    $softColors = ['#E57373', '#F06292', '#BA68C8', '#64B5F6', '#4DB6AC', '#81C784', '#FFD54F', '#FF8A65'];
@endphp

@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="d-flex justify-content-between align-items-center w-100 p-3 rounded shadow-sm mb-4">
            <h3 class="fw-bold m-0 text-white"><i class="fas fa-book me-2"></i>Perpustakaan</h3>
            <input type="text" id="searchInput" class="form-control w-25 border-success shadow-sm w-80" placeholder="Cari koleksi..." style="border-radius: 10px;">
            <div class="flex justify-content-between align-items-center gap-2 mb-3">
                <button class="btn btn-success px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCollectionModal" style="border-radius: 8px; transition: all 0.3s;">
                    <i class="fas fa-folder-plus me-2"></i>Tambah Koleksi
                </button>
                @if($folderId)
                    <button class="btn btn-danger px-4 py-2 btn-delete" ><i class="fas fa-trash me-2"></i> Hapus Koleksi</button>
                @endif
            </div>
        </div>

        @if($groupedLibraries->isEmpty())
            <div class="alert alert-warning text-center" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> Belum ada koleksi yang ditambahkan.
            </div>
        @endif

        @foreach($groupedLibraries as $folderName => $libraries)
        @php
            $folderId = $libraries->first()->folder_id ?? null;
        @endphp
        <div class="flex justify-content-between align-items-center mb-3">
            <h3 class="mt-4">{{ $folderName }}</h3>
        </div>
            <div class="row">
                @foreach($libraries as $library)
                    @php
                        $randomColor = $softColors[array_rand($softColors)];
                        $session = $library->session;
                        $hasBookmark = $session->libraries->contains(fn($lib) => !is_null($lib->folder_id));
                        $hasLike = $session->libraries->contains(fn($lib) => is_null($lib->folder_id));
                    @endphp

                    <div class="col-md-3 col-sm-6 mb-4 quiz-item" data-title="{{ strtolower($session->title) }}">
                        <div class="quiz-card card shadow-lg border-0" style="border-radius: 12px; overflow: hidden; cursor: pointer;">
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
                                {{ number_format($session->plays) }} plays
                            </span>

                            <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                                <h6 class="fw-bold text-dark mb-0">{{ $session->title }}</h6>
                                <div>
                                    @if($hasLike)
                                    <i class="bi like-icon {{ $hasLike ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                                        data-id="{{ $session->id }}"
                                        data-library-id="{{ $session->libraries->whereNull('folder_id')->pluck('id')->implode(',') }}"
                                        style="cursor: pointer; font-size: 1.2rem;">
                                    </i>
                                    @endif
                                    @if($hasBookmark)
                                    <i class="bi save-icon {{ $hasBookmark ? 'bi-bookmark-fill text-secondary' : 'bi-bookmark' }}"
                                        data-id="{{ $session->id }}"
                                        data-library-id="{{ $session->libraries->whereNotNull('folder_id')->pluck('id')->implode(',') }}"
                                        style="cursor: pointer; font-size: 1.2rem;">
                                    </i>
                                    @endif
                                </div>
                            </div>

                            <div class="pb-3 px-3">
                                <button class="btn btn-primary w-full rounded-pill start-btn" data-id="{{$session->access_code}}">Start</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>

    <!-- Tambahkan Script -->
    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        const id = @json($id);

        document.getElementById('saveCollection').addEventListener('click', function() {
            let folderNameInput = document.getElementById('folderName');
            let folderName = folderNameInput.value.trim();

            if (folderName === "") {
                folderNameInput.classList.add('is-invalid');
                return;
            } else {
                folderNameInput.classList.remove('is-invalid');
            }

            let data = {
                folder_name: folderName,
                _token:"{{ csrf_token() }}"
             };

        });

        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            document.querySelectorAll('.quiz-item').forEach(item => {
                let title = item.getAttribute('data-title');
                if (title.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        $(document).ready(function() {
            function toggleIcon(icon, addClass, removeClass, newLibraryId, successMessage) {
                toastr.success(successMessage, "Sukses");
                icon.removeClass(removeClass).addClass(addClass);
                icon.data('library-id', newLibraryId);
            }

            function handleAction(icon, url, type, data, successCallback, errorMessage) {
                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    success: function(response) {
                        successCallback(response);
                    },
                    error: function() {
                        toastr.error(errorMessage, "Error");
                    }
                });
            }

            $('.like-icon, .save-icon').click(function() {
                let icon = $(this);
                let sessionId = icon.data('id');
                let libraryId = icon.data('library-id');
                let isLikedOrBookmarked = icon.hasClass(icon.hasClass('like-icon') ? 'bi-heart-fill' : 'bi-bookmark-fill');
                let url = "/library/" + libraryId;
                let data = { _token: "{{ csrf_token() }}" };

                if (!libraryId && isLikedOrBookmarked) {
                    toastr.error("Data tidak valid!", "Error");
                    return;
                }

                if (isLikedOrBookmarked) {
                    handleAction(icon, url, "DELETE", data, function() {
                        toggleIcon(icon,
                            icon.hasClass('like-icon') ? 'bi-heart' : 'bi-bookmark',
                            icon.hasClass('like-icon') ? 'bi-heart-fill text-danger' : 'bi-bookmark-fill text-secondary',
                            "", "Bookmark berhasil dihapus"
                        );
                    }, "Gagal menghapus bookmark");
                } else {
                    $.post("{{ route('library.store') }}", {
                        quiz_session_id: sessionId,
                        folder_id: null,
                        _token: "{{ csrf_token() }}"
                    }).done(function(response) {
                        toggleIcon(icon,
                            icon.hasClass('like-icon') ? 'bi-heart-fill text-danger' : 'bi-bookmark-fill text-secondary',
                            icon.hasClass('like-icon') ? 'bi-heart' : 'bi-bookmark',
                            response.id, "Quiz berhasil disimpan"
                        );
                    }).fail(function() {
                        toastr.error("Gagal menyimpan quiz", "Error");
                    });
                }
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

            $('.btn-delete').click(function() {
                $.ajax({
                    url: '{{ route("delete_folder") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        setTimeout(() => {
                            if (response.status === 'success') {
                                toastr.success(response.message, 'Success', {
                                    timeOut: 2000,
                                    progressBar: true,
                                    positionClass: "toast-top-right"
                                });

                                // Redirect ke /library?filter=all
                                setTimeout(() => {
                                    window.location.href = "{{ route('library.index') }}?filter=all";
                                }, 2000);
                            } else {
                                toastr.error(response.message, 'Error', {
                                    timeOut: 3000,
                                    progressBar: true,
                                    positionClass: "toast-top-right"
                                });
                            }
                        }, 500);
                    },
                    error: function(error) {
                        setTimeout(() => {
                            toastr.error(error.responseJSON.message || "Terjadi kesalahan!", 'Error', {
                                timeOut: 3000,
                                progressBar: true,
                                positionClass: "toast-top-right"
                            });
                        }, 500);
                    }
                });
            });


        });


    </script>
@endsection
