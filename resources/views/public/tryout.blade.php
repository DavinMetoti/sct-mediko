@extends('layouts.app')

@section('title', config('app.name') . ' | Tryout')

@section('content')
<div class="min-h-screen p-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold">Data Diri</h6>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted w-20">Nama</td>
                                <td>: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted w-20">Email</td>
                                <td>: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted w-20">Universitas</td>
                                <td>: {{ $user->userDetail->univ ?? 'Belum dipilih' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h6 class="fw-bold">Navigasi Soal</h6>
                    <div class="overflow-auto" style="min-height: calc(100vh - 250px); max-height: calc(100vh - 250px);">
                        <ul class="grid grid-cols-5 gap-3 p-0" id="question-list">
                            @foreach ($question->questionDetail as $index => $detail)
                                <li class="border text-muted fw-bold rounded-lg text-center h-12 flex items-center justify-center aspect-square cursor-pointer"
                                    data-id="{{ $detail->id }}"
                                    id="question-{{ $detail->id }}"
                                    data-number="{{ $index + 1 }}"
                                    onclick="changeQuestion({{ $detail->id }})"
                                    role="button"
                                    aria-label="Go to question {{ $index + 1 }}"
                                    :class="{'bg-primary text-white': activeQuestion === {{ $detail->id }}}">
                                    {{ $index + 1 }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="flex justify-content-between">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="1"></circle>
                            </svg>
                            <span id="questionTitle" class="font-medium">Soal <span id="current-no"></span> dari {{$question->questionDetail->count()}}</span>
                        </div>
                        <div class="flex gap-2 align-items-center">
                            <div class="text-gray-600">
                                Sisa Waktu: <span id="timer" class="font-medium"></span>
                            </div>
                            <a href="{{ $formReport->first()->value ?? '#' }}" target="_blank" class="btn btn-danger d-inline-flex align-items-center">
                                <i class="fas fa-flag"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope w-5 h-5 text-blue-600">
                            <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"></path>
                            <path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"></path>
                            <circle cx="20" cy="10" r="2"></circle>
                        </svg>
                        <span class="text-sm font-medium text-blue-600" id="category"></span>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Kasus Klinis:</h3>
                        <p class="text-gray-700" id="clinic-case"></p>
                    </div>
                    <div class="space-y-6">
                        <div class="border-t pt-4">
                            <div class="hidden md:block">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="w-1/3 p-3 text-left bg-gray-50" id="column_1">Bila anda mempertimbangkan "tipe soal" "tindakan" berikut...</th>
                                            <th class="w-1/3 p-3 text-left bg-gray-50" id="column_2">Dan tersedia informasi baru berikut ini</th>
                                            <th class="w-1/3 p-3 text-left bg-gray-50" id="column_3">Hipotesis ini menjadi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-3 border" id="initial_hypothesis"></td>
                                            <td class="p-3 border">
                                                <p class="mb-2" id="new_information"></p>
                                                <div class="relative">
                                                    <img id="discussion_image" src="" alt="Clinical finding" class="max-w-xs mx-auto cursor-pointer hover:opacity-90 transition-opacity">
                                                    <p id="descussion_image_text" class="text-center text-sm text-gray-500 mt-1">Klik untuk perbesar</p>
                                                </div>
                                            </td>
                                            <td class="p-3 border">
                                                <div class="space-y-2">
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input oninput="getSelectedRadioValue()" type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="-2">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">-2</div>
                                                            <div class="text-sm text-gray-600" id="minus_two"></div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary flashcard-btn d-flex align-items-center gap-1" data-panelist="-2" title="Lihat Flashcard PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                                <span class="d-none d-sm-inline">Flashcard</span>
                                                            </button>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input oninput="getSelectedRadioValue()" type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="-1">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">-1</div>
                                                            <div class="text-sm text-gray-600" id="minus_one"></div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary flashcard-btn d-flex align-items-center gap-1" data-panelist="-1" title="Lihat Flashcard PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                                <span class="d-none d-sm-inline">Flashcard</span>
                                                            </button>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input oninput="getSelectedRadioValue()" type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="0">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">0</div>
                                                            <div class="text-sm text-gray-600" id="zero"></div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary flashcard-btn d-flex align-items-center gap-1" data-panelist="0" title="Lihat Flashcard PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                                <span class="d-none d-sm-inline">Flashcard</span>
                                                            </button>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input oninput="getSelectedRadioValue()" type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="1">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">+1</div>
                                                            <div class="text-sm text-gray-600" id="one"></div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary flashcard-btn d-flex align-items-center gap-1" data-panelist="1" title="Lihat Flashcard PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                                <span class="d-none d-sm-inline">Flashcard</span>
                                                            </button>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input oninput="getSelectedRadioValue()" type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="2">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-medium">+2</div>
                                                            <div class="text-sm text-gray-600" id="two"></div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary flashcard-btn d-flex align-items-center gap-1" data-panelist="2" title="Lihat Flashcard PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                                <span class="d-none d-sm-inline">Flashcard</span>
                                                            </button>
                                                        </div>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-content-between mt-6">
                        <button class="btn btn-outline-secondary" id="mark-button" onclick="markQuestion()">
                            <i class="fas fa-flag"></i>
                            <span>Tandai Soal</span>
                        </button>
                        <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors" id="finish-button" data-bs-toggle="modal" data-bs-target="#finishModal" onclick="showAllert()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-4 h-4">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                            <span>Selesai</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center">
    <img id="modalImage" src="" alt="Enlarged Image" class="max-w-70vw max-h-70vh object-contain rounded-lg">
    <button id="closeModal" class="absolute top-5 right-5 text-white text-2xl font-bold">&times;</button>
</div>

<div class="modal fade" id="finishModal" tabindex="-1" aria-labelledby="finishModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="text-muted fw-bold">Menyelesaikan tryout?</h5>
        <div class="border-2 rounded p-3 shadow-sm bg-light mb-2">
            <p class="mb-0">
                <strong>Anda yakin ingin mengumpulkan tryout?</strong>
                Anda belum mengerjakan nomor soal: <span id="unanswered-questions" class="fw-bold text-danger"></span>
            </p>
        </div>
        <div class="border-2 rounded p-3 shadow-sm bg-light">
            <p class="mb-0">
                <strong>Anda yakin ingin mengumpulkan tryout?</strong>
                Anda masih memiliki soal yang ditandai: <span id="mark-questions" class="fw-bold text-warning"></span>
            </p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outlined-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="finishExam()">Selesaikan</button>
      </div>
    </div>
  </div>
</div>

<!-- Flashcard PDF Modal -->
<div class="modal fade" id="flashcardPdfModal" tabindex="-1" aria-labelledby="flashcardPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="flashcardPdfModalLabel">Flashcard</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center" style="min-height: 400px;">
                <!-- Loading spinner with card flip animation -->
                <div id="loadingSpinner" class="flashcard-loading">
                    <div class="card-flip">
                        <div class="card-front">
                            <i class="fas fa-file-pdf fa-3x text-primary"></i>
                            <p class="mt-2">Loading PDF...</p>
                        </div>
                        <div class="card-back">
                            <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                            <p class="mt-2">Please wait...</p>
                        </div>
                    </div>
                </div>
                <!-- PDF Viewer with protection -->
                <object id="pdfViewer" 
                        data="" 
                        type="application/pdf" 
                        style="width: 100%; height: 600px; display: none;"
                        oncontextmenu="return false;">
                    <p>Browser Anda tidak mendukung tampilan PDF. <a href="#" onclick="alert('Download tidak diizinkan'); return false;">Coba browser lain</a></p>
                </object>
            </div>
        </div>
    </div>
</div>

<style>
/* Flashcard loading animation */
.flashcard-loading {
    perspective: 1000px;
}

.card-flip {
    width: 200px;
    height: 200px;
    position: relative;
    transform-style: preserve-3d;
    animation: flip 2s infinite linear;
}

.card-front, .card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border: 2px solid #007bff;
    border-radius: 10px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card-back {
    transform: rotateY(180deg);
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

@keyframes flip {
    0% { transform: rotateY(0deg); }
    50% { transform: rotateY(180deg); }
    100% { transform: rotateY(360deg); }
}

/* Button animation */
.flashcard-btn {
    transition: all 0.3s ease;
    display: none !important; /* Hidden by default */
}

.flashcard-btn.show {
    display: inline-flex !important; /* Show when needed */
}

.flashcard-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

/* PDF Protection */
#pdfViewer {
    pointer-events: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Disable print for the modal */
@media print {
    #flashcardPdfModal {
        display: none !important;
    }
}

/* Disable right click on PDF viewer */
#flashcardPdfModal .modal-body {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
</style>

@endsection

@include('partials.script')

<script>
    let questions = @json($question);
    let tryout = @json($tryout);
    let activeQuestion = null;
    let currentNumber = 0;
    let totalMinutesSave;

    $(document).ready(function(){
        changeQuestion(questions.question_detail[0].id);

        const discussionImage = $('#discussion_image');
        const modal = $('#imageModal');
        const modalImage = $('#modalImage');
        const closeModal = $('#closeModal');

        discussionImage.on('click', function () {
            const imageSrc = $(this).attr('src');
            modalImage.attr('src', imageSrc);
            modal.addClass('active');
        });

        closeModal.on('click', function () {
            modal.removeClass('active');
        });

        modal.on('click', function (e) {
            if (e.target === modal[0]) {
                modal.removeClass('active');
            }
        });

        let timeParts = questions.time.split(':');
        let totalMinutes = parseInt(timeParts[0]) * 60 + parseInt(timeParts[1]);
        let targetTime = moment().add(tryout.sisa_waktu, 'minutes');

        function updateCountdown() {
            let now = moment();
            let duration = moment.duration(targetTime.diff(now));
            totalMinutesSave = Math.floor(duration.asMinutes());

            if (duration.seconds() > 0) {
                totalMinutesSave += 1;
            }

            if (duration._milliseconds < 0) {
                finishExam()
            }

            let hours = String(duration.hours()).padStart(2, '0');
            let minutes = String(duration.minutes()).padStart(2, '0');
            let seconds = String(duration.seconds()).padStart(2, '0');
            document.getElementById('timer').innerHTML = `${hours}:${minutes}:${seconds}`;

        }

        setInterval(updateCountdown, 1000);
    })

    function checkStatusButton() {
        const url = window.location.href;
        const regex = /\/tryout\/(\d+)\//;
        const match = url.match(regex);

        if (match && match[1]) {
            $.ajax({
                url: "{{ route('tryout.history.answer') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    task_history_id: match[1]
                },
                success: function(response) {
                    const answer = response.data.task_history;
                    const result = answer.filter(item => item.question_detail_id === activeQuestion);
                    const selectedRadio = $('input[name="question-1-1"]');

                    selectedRadio.each(function() {
                        const radioValue = $(this).val();
                        const radioId = $(this).attr('id');

                        const isSelected = result.some(item => item.value == radioValue);

                        if (isSelected) {
                            $(this).prop('checked', true);
                            $('#mark-button').removeAttr('disabled')
                        }
                    });

                    for (let i = 0; i < answer.length; i++) {
                        if (answer[i].status == "completed" && activeQuestion != answer[i].question_detail_id) {
                            $('#question-' + answer[i].question_detail_id).addClass('bg-success text-white completed');
                            $('#question-' + answer[i].question_detail_id).removeClass('text-muted mark');
                        } else if (answer[i].status == "mark" && activeQuestion != answer[i].question_detail_id) {
                            $('#question-' + answer[i].question_detail_id).addClass('bg-warning text-white mark');
                            $('#question-' + answer[i].question_detail_id).removeClass('text-muted completed');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        } else {
            console.error("Task history ID not found in URL.");
        }
    }

    function changeQuestion(id) {
        const url = window.location.href;
        const regex = /\/tryout\/(\d+)\//;
        const match = url.match(regex);
        const currentNumber = $('#question-'+id).data('number');
        $.ajax({
            url: '{{ route('tryout.show', ':id') }}'.replace(':id', id),
            method: 'GET',
            data:{
                task_history_id: match[1],
            },
            success: function(response) {
                let question_tryout = response.data;

                const defaultImage = '{{ secure_asset('assets/images/No_Image_Available.jpg') }}';
                const imageSrc = question_tryout.discussion_image || null;
                const selectedRadio = $('input[name="question-1-1"]:checked');

                selectedRadio.prop('checked', false);

                $('#column_1').text(question_tryout.column_title.column_1);
                $('#column_2').text(question_tryout.column_title.column_2);
                $('#column_3').text(question_tryout.column_title.column_3);
                $('#category').text(question_tryout.medical_field.name);
                $('#clinic-case').text(question_tryout.clinical_case);
                $('#initial_hypothesis').text(question_tryout.initial_hypothesis);
                $('#new_information').text(question_tryout.new_information);
                if (imageSrc) {
                    $('#discussion_image').attr('src', imageSrc).show();
                    $('#descussion_image_text').show();
                } else {
                    $('#discussion_image').hide();
                    $('#descussion_image_text').hide();
                }
                $('#minus_two').text(question_tryout.question_type.minus_two);
                $('#minus_one').text(question_tryout.question_type.minus_one);
                $('#zero').text(question_tryout.question_type.zero);
                $('#one').text(question_tryout.question_type.one);
                $('#two').text(question_tryout.question_type.two);

                // Handle flashcard buttons - check for both flash_cards and flashCards
                const flashcards = question_tryout.flash_cards || question_tryout.flashCards || [];
                console.log('Question data:', question_tryout);
                console.log('Flashcards found:', flashcards);
                handleFlashcardButtons(flashcards);

                if (activeQuestion !== null) {
                    $('#question-' + activeQuestion).removeClass('bg-primary text-white');
                }

                checkStatusButton();

                $('#current-no').text(currentNumber)

                activeQuestion = id;
                $('#question-' + id).addClass('bg-primary text-white');
                $('#question-' + id).removeClass('bg-success bg-warning text-muted');

            },
            error: function(xhr, status, error) {
                console.error('An error occurred: ' + error);
            }
        });
    }

    const discussionImage = $('#discussion_image');
        discussionImage.on('click',function (){
            alert('Image clicked!');
    });

    function getSelectedRadioValue() {
        const selectedRadio = $('input[name="question-1-1"]:checked');
        const selectedValue = selectedRadio.val();

        const url = window.location.href;
        const regex = /\/tryout\/(\d+)\//;
        const match = url.match(regex);

        $('#mark-button').removeAttr('disabled')

        if (match) {
            const number = match[1];
            $.ajax({
                url: "{{ route('tryout.store') }}",
                method: "POST",
                data: {
                    _token:'{{ csrf_token() }}',
                    task_history_id: number,
                    question_detail_id: activeQuestion,
                    value: selectedValue,
                    status: 'completed'
                },
                success: function(response) {
                    toastSuccess('Jawaban berhasil disimpan');
                },
                error: function(xhr, status, error) {
                    toastError(`Gagal menyimpan jawaban : ${xhr.responseJSON.message}`,);
                    console.error('Error while sending data:', error);
                }
            });

            $.ajax({

                url: "{{ route('task-history.update',':id') }}".replace(':id',number),
                method: "PUT",
                data: {
                    _token:'{{ csrf_token() }}',
                    sisa_waktu: totalMinutesSave,
                },
                success: function(response) {

                },
                error: function(xhr, status, error) {
                    console.error('Error while sending data:', error);
                }
            });
        }

    }

    function markQuestion() {
        const selectedRadio = $('input[name="question-1-1"]:checked');
        const selectedValue = selectedRadio.val()??null;

        const url = window.location.href;
        const regex = /\/tryout\/(\d+)\//;
        const match = url.match(regex);

        if (match) {
            const number = match[1];

            $.ajax({
                url: "{{ route('tryout.store') }}",
                method: "POST",
                data: {
                    _token:'{{ csrf_token() }}',
                    task_history_id: number,
                    question_detail_id: activeQuestion,
                    value: selectedValue,
                    status: 'mark'
                },
                success: function(response) {
                    toastSuccess('Jawaban berhasil disimpan');
                },
                error: function(xhr, status, error) {
                    console.error('Error while sending data:', error);
                }
            });
        }

    }

    function confirmFinishExam() {
        const confirmationModal = new ConfirmationModal();

        confirmationModal.open({
            message: 'Apakah anda yakin ingin menyelesaikan tryout ini?',
            severity: 'error',
            onAccept: () => {
                finishExam();
            },
            onReject: () => {

            },
        });
    }

    function showAllert() {
        // Ambil pertanyaan yang belum dijawab (tidak memiliki class .mark atau .completed)
        let itemsUnanswer = $('#question-list li').not('.mark, .completed').map(function() {
            return $(this).text().trim();
        }).get();

        // Ambil pertanyaan yang ditandai (hanya yang memiliki class .mark)
        let itemsMarked = $('#question-list li.mark').map(function() {
            return $(this).text().trim();
        }).get();

        // Gabungkan menjadi string dengan pemisah koma
        let unanswer = itemsUnanswer.join(', ');
        let marked = itemsMarked.join(', ');

        // Masukkan hasil ke dalam elemen yang sesuai
        $('#unanswered-questions').text(unanswer);
        $('#mark-questions').text(marked);
    }




    function finishExam() {
        const url = window.location.href;
        const regex = /\/tryout\/(\d+)\//;
        const match = url.match(regex);

        if (match && match[1]) {
            $.ajax({
                url: "{{ route('task-history.update', ':id') }}".replace(':id', match[1]),
                method: "PUT",
                data: {
                    status: "completed",
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    window.location.href = "{{ route('task-history.show', ':id') }}".replace(':id', match[1]);
                },
                error: function(xhr, status, error) {
                    console.error('Error updating task history:', error);
                }
            });
        } else {
            console.error('Failed to extract task history ID from the URL');
        }
    }

    // Function to handle flashcard buttons visibility
    function handleFlashcardButtons(flashcards) {
        // Hide all flashcard buttons first and remove previous data
        $('.flashcard-btn').removeClass('show').removeData('flashcard-path');
        
        // Only show buttons for panelists that have flashcards
        if (flashcards && flashcards.length > 0) {
            console.log('Processing flashcards:', flashcards);
            
            flashcards.forEach(function(flashcard) {
                // Convert panelist to string for consistent comparison
                const panelistStr = String(flashcard.panelist);
                const btn = $(`.flashcard-btn[data-panelist="${panelistStr}"]`);
                console.log(`Looking for button with panelist "${panelistStr}":`, btn.length);
                
                if (btn.length > 0) {
                    btn.addClass('show');
                    btn.data('flashcard-path', flashcard.path);
                    console.log(`Showing button for panelist ${panelistStr} with path: ${flashcard.path}`);
                }
            });
        } else {
            console.log('No flashcards available for this question');
        }
        
        // Debug: Log which buttons are currently visible
        const visibleButtons = $('.flashcard-btn.show');
        console.log(`${visibleButtons.length} flashcard buttons are now visible`);
    }

    // Handle flashcard button click
    $(document).on('click', '.flashcard-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const flashcardPath = $(this).data('flashcard-path');
        const panelist = $(this).data('panelist');
        
        console.log('Button clicked for panelist:', panelist);
        console.log('Flashcard path:', flashcardPath);
        
        if (flashcardPath) {
            showFlashcardPdf(flashcardPath, panelist);
        } else {
            console.error('No flashcard path found for panelist:', panelist);
        }
    });

    // Function to show flashcard PDF with loading animation
    function showFlashcardPdf(flashcardPath, panelist) {
        const modal = $('#flashcardPdfModal');
        const pdfViewer = $('#pdfViewer');
        const loadingSpinner = $('#loadingSpinner');
        
        // Update modal title
        $('#flashcardPdfModalLabel').text(`Flashcard PDF - Panelist ${panelist}`);
        
        // Show modal with loading animation
        modal.modal('show');
        loadingSpinner.show();
        pdfViewer.hide();
        
        // Construct PDF URL with disable download parameter
        const pdfUrl = `{{ asset('storage') }}/${flashcardPath}#toolbar=0&navpanes=0&scrollbar=0&view=FitH`;
        
        // Add protection against keyboard shortcuts
        modal.off('keydown').on('keydown', function(e) {
            // Disable Ctrl+S (Save), Ctrl+P (Print), F12 (DevTools)
            if ((e.ctrlKey && (e.key === 's' || e.key === 'p')) || e.key === 'F12') {
                e.preventDefault();
                return false;
            }
        });
        
        // Simulate loading delay and load PDF
        setTimeout(function() {
            pdfViewer.attr('data', pdfUrl);
            
            // Hide loading and show PDF after a short delay
            setTimeout(function() {
                loadingSpinner.hide();
                pdfViewer.show();
            }, 1000);
        }, 500);
    }

    // Reset modal when closed
    $('#flashcardPdfModal').on('hidden.bs.modal', function() {
        $('#pdfViewer').attr('data', '').hide();
        $('#loadingSpinner').show();
        $('.alert-danger', '#flashcardPdfModal .modal-body').remove();
        $(this).off('keydown'); // Remove event listeners
    });

    // Additional PDF protection when modal is shown
    $('#flashcardPdfModal').on('shown.bs.modal', function() {
        // Disable right-click context menu
        $('#flashcardPdfModal').on('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Disable text selection
        $('#flashcardPdfModal').on('selectstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Disable drag
        $('#flashcardPdfModal').on('dragstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Focus the modal to ensure keyboard events are captured
        $(this).focus();
    });
</script>
