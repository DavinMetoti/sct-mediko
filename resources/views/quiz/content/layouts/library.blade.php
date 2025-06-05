@php
    use Illuminate\Support\Str;
    $softColors = ['#E57373', '#F06292', '#BA68C8', '#64B5F6', '#4DB6AC', '#81C784', '#FFD54F', '#FF8A65'];
@endphp

@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <h4 class="fw-semibold" style="color: #5E5E5E;">Perpustakaan</h4>
            </div>
            <div class="col-md-8 d-flex flex-wrap justify-content-end align-items-center gap-2">
                <div class="input-group" style="max-width: 260px; border: 1px solid #E7E7E7 !important; border-radius: 8px !important;">
                    <span class="input-group-text bg-white border-0" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-search text-muted" style="opacity: 0.6;"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-0 input-placeholder"
                        placeholder="Cari kuis ..."
                        style="background: #FFFFFF; border-radius: 8px !important;">
                </div>
                <button class="btn btn-green shadow-sm" data-bs-toggle="modal" data-bs-target="#addCollectionModal" style="border-radius: 8px; transition: all 0.3s;">
                    <i class="fas fa-plus me-2"></i>Tambah Koleksi
                </button>
                @if($folderId)
                <button class="btn btn-danger btn-delete" ><i class="fas fa-trash me-2"></i> Hapus Koleksi</button>
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
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-semibold mb-0" style="color: #A8A8A8;">{{ $folderName }}</h6>
        </div>
            <div class="row">
                @foreach($libraries as $library)
                    @php
                        $session = $library->session;
                        $dataHasBookmark = $session->libraries->filter(fn($lib) => !is_null($lib->folder_id));
                        $dataHasLike = $session->libraries->filter(fn($lib) => is_null($lib->folder_id));
                        $hasBookmark = $dataHasBookmark->isNotEmpty();
                        $hasLike = $dataHasLike->isNotEmpty();
                    @endphp

                    <div class="col-md-3 col-sm-6 mb-4 quiz-item" data-title="{{ strtolower($session->title) }}">
                        <div class="quiz-card card shadow-lg border-0 h-100"
                            style="border-radius: 12px; overflow: hidden; cursor: pointer; max-height: 320px; min-height: 320px; display: flex; flex-direction: column;">
                            <div class="position-relative" style="background-image: url('{{ secure_asset('assets/images/petern.png') }}'); background-size: cover; min-height: 140px; border-radius: 12px 12px 0 0;">
                            </div>
                            <div class="row mt-3 px-3">
                                <div class="col-8">
                                    <h6 class="fw-semibold text-dark mb-0 text-truncate">{{ $session->title }}</h6>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <i class="bi like-icon {{ $hasLike ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                                            data-id="{{ $session->id }}"
                                            data-library-id="{{ $dataHasLike->pluck('id')->implode(',') }}"
                                            style="cursor: pointer; font-size: 1.2rem;">
                                        </i>
                                        <i class="bi save-icon {{ $hasBookmark ? 'bi-bookmark-fill text-warning' : 'bi-bookmark' }}"
                                            data-id="{{ $session->id }}"
                                            data-library-id="{{ $dataHasBookmark->pluck('id')->implode(',') }}"
                                            style="cursor: pointer; font-size: 1.2rem;">
                                        </i>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-3 px-3 mt-1">
                                <small style="font-size: 12px;" class="text-muted"><i class="fas fa-file-alt me-1"></i>{{ $session->questions_count }} Soal</small>
                            </div>
                            <div class="pb-3 px-3 mt-5" style="margin-top: auto !important;">
                                <button class="btn btn-orange w-100 start-btn" data-id="{{$session->access_code}}">Mulai Mengerjakan</button>
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
                            icon.hasClass('like-icon') ? 'bi-heart-fill text-danger' : 'bi-bookmark-fill text-warning',
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
                            icon.hasClass('like-icon') ? 'bi-heart-fill text-danger' : 'bi-bookmark-fill text-warning',
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
