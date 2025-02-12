@extends('layouts.app')

@section('title', config('app.name') . ' | Hasil Tryout')

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
                                <td>: {{ $user->userDetail->univ??'Belum dipilih' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h6 class="fw-bold">Pembahasan Soal</h6>
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
                    <div>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" style="max-height: calc(100vh - 60px);overflow: auto;">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold">Hasil Tryout</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body bg-light">
                                    <div class="fw-medium text-sm text-muted mb-2">Skor Total</div>
                                    <div class="text-xl fw-bold mb-2">{{$tryout->score}}/{{$question->questionDetail->count()}}</div>
                                    <small class="text-muted text-md">{{$tryout->score / $question->questionDetail->count() * 100}} %</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body bg-light">
                                    <div class="fw-medium text-sm text-muted mb-2">Total Soal Terjawab</div>
                                    @php
                                        $answeredCount = $tryout->TaskHistory->filter(fn($task) => $task->value !== null)->count();
                                        $totalQuestions = $question->questionDetail->count();
                                        $percentage = $totalQuestions > 0 ? ($answeredCount / $totalQuestions * 100) : 0;
                                    @endphp

                                    <div class="text-xl fw-bold mb-2">{{ $answeredCount }}/{{ $totalQuestions }}</div>
                                    <small class="text-muted text-md">{{ number_format($percentage, 2) }} %</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body bg-light">
                                    <div class="fw-medium text-sm text-muted mb-2">Status</div>
                                    <div class="text-xl fw-bold mb-2">
                                        @php
                                            $percentage = $tryout->score / $question->questionDetail->count() * 100;
                                        @endphp
                                        <span class="{{ $percentage >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ $percentage >= 70 ? 'Lulus' : 'Tidak Lulus' }}
                                        </span>
                                    </div>
                                    <small class="text-muted text-md">
                                        <span>
                                            {{ $percentage >= 70 ? 'Selamat' : 'Mohon Maaf' }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6 class="fw-bold mt-5">Penjelasan Rationale</h6>
                    <p id="rationale" class="text-sm"></p>
                    <h6 class="fw-bold">Rationale dan Skor Likert</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="fw-bold text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Keterangan</th>
                                    <th class="fw-bold text-center" style="vertical-align : middle;text-align:center;" colspan="5">Pilihan Jawaban</th>
                                    <th class="fw-bold text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Jawaban Anda</th>
                                    <th class="fw-bold text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Skor Anda</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="fw-bold text-center">-2</th>
                                    <th class="fw-bold text-center">-1</th>
                                    <th class="fw-bold text-center">0</th>
                                    <th class="fw-bold text-center">1</th>
                                    <th class="fw-bold text-center">2</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-left">Jawaban Panel</td>
                                    <td class="text-center" id="panel_answer_1"></td>
                                    <td class="text-center" id="panel_answer_2"></td>
                                    <td class="text-center" id="panel_answer_3"></td>
                                    <td class="text-center" id="panel_answer_4"></td>
                                    <td class="text-center" id="panel_answer_5"></td>
                                    <td class="text-center fw-bold" style="font-size: 24px;vertical-align : middle;text-align:center;" id="your_answer" rowspan="3"></td>
                                    <td class="text-center fw-bold" style="font-size: 24px;vertical-align : middle;text-align:center;" id="your_score" rowspan="3"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-left">Bobot</td>
                                    <td class="text-center" id="panel_bobot_1"></td>
                                    <td class="text-center" id="panel_bobot_2"></td>
                                    <td class="text-center" id="panel_bobot_3"></td>
                                    <td class="text-center" id="panel_bobot_4"></td>
                                    <td class="text-center" id="panel_bobot_5"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-left">Skor</td>
                                    <td class="text-center" id="panel_score_1"></td>
                                    <td class="text-center" id="panel_score_2"></td>
                                    <td class="text-center" id="panel_score_3"></td>
                                    <td class="text-center" id="panel_score_4"></td>
                                    <td class="text-center" id="panel_score_5"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-sm text-muted mb-3">Panelis adalah tutor Mediko Indonesia, silahkan cek daftar panelis pada list panelis.</div>
                    <h6 class="fw-bold">Distribusi Jawaban Peserta</h6>
                    <div class="table-responsive mb-3">
                        <table class="min-w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="border border-gray-200 px-4 py-2 text-center w-15">Skala</th>
                                    <th class="border border-gray-200 px-4 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="column_selected_1">
                                    <td class="border border-gray-200 px-4 py-2 text-center font-medium">-2</td>
                                    <td class="border border-gray-200 px-4 py-2" id="minus_two">Sangat tidak mungkin</td>
                                </tr>
                                <tr id="column_selected_2">
                                    <td class="border border-gray-200 px-4 py-2 text-center font-medium">-1</td>
                                    <td class="border border-gray-200 px-4 py-2" id="minus_one">Tidak mungkin</td>
                                </tr>
                                <tr id="column_selected_3">
                                    <td class="border border-gray-200 px-4 py-2 text-center font-medium">0</td>
                                    <td class="border border-gray-200 px-4 py-2" id="zero">Tidak mendukung ataupun menyingkirkan</td>
                                </tr>
                                <tr id="column_selected_4">
                                    <td class="border border-gray-200 px-4 py-2 text-center font-medium">1</td>
                                    <td class="border border-gray-200 px-4 py-2" id="one">Mungkin</td>
                                </tr>
                                <tr id="column_selected_5">
                                    <td class="border border-gray-200 px-4 py-2 text-center font-medium">2</td>
                                    <td class="border border-gray-200 px-4 py-2" id="two">Sangat mungkin</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-sm" style="font-weight: 500;">Penjelasan Skoring:</div>
                    <ol style="padding-left: 0;">
                        <li class="text-sm text-muted">- Skor dihitung berdasarkan distribusi jawaban panel ahli</li>
                        <li class="text-sm text-muted">- Bobot merupakan proporsi jawaban panel terhadap nilai tertinggi panelis</li>
                        <li class="text-sm text-muted">- Skor akhir dihitung berdasarkan bobot jawaban yang dipilih</li>
                    </ol>
                    <h6 class="fw-bold">Pembahasan Soal</h6>
                    <div class="text-md fw-bold text-muted">Kasus Klinik</div>
                    <p id="clinic-case" class="text-sm"></p>
                    <div class="text-md fw-bold text-muted">Hipotesis Awal</div>
                    <p id="initial_hypothesis" class="text-sm"></p>
                    <img id="img_initial_hypothesis" class="mb-3" width="50%"></img>
                    <div class="text-md fw-bold text-muted">Informasi Baru</div>
                    <p id="new_information" class="text-sm"></p>
                    <div class="text-md fw-bold text-muted">Pembahasan</div>
                    <iframe
                        src="https://sct-mediko.test/storage/uploads/file/TOWIa1s7dVTvapf8xxAjnf4X1AVY5dkF5mpxiRgY.pdf#toolbar=0&navpanes=0&scrollbar=0"
                        width="100%"
                        height="600px"
                        oncontextmenu="return false;"
                        id="iframe-pdf"
                        >
                        Your browser does not support iframes.
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center">
    <img id="modalImage" src="" alt="Enlarged Image" class="max-w-full max-h-full">
    <button id="closeModal" class="absolute top-5 right-5 text-white text-2xl font-bold">&times;</button>
</div>

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

    })

    function checkStatusButton() {
        const url = window.location.href;
        const regex = /\/task-history\/(\d+)/;
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
                            $('#question-' + answer[i].question_detail_id).addClass('bg-success text-white');
                            $('#question-' + answer[i].question_detail_id).removeClass('text-muted');
                        } else if (answer[i].status == "mark" && activeQuestion != answer[i].question_detail_id) {
                            $('#question-' + answer[i].question_detail_id).addClass('bg-warning text-white');
                            $('#question-' + answer[i].question_detail_id).removeClass('text-muted');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.error("Error: " + error);
                }
            });
        } else {
            console.error("Task history ID not found in URL.");
        }
    }

    function changeQuestion(id) {
        const url = window.location.href;
        const regex = /\/task-history\/(\d+)/;
        const match = url.match(regex);
        const currentNumber = $('#question-'+id).data('number');
        const answerList = tryout.task_history;
        const currentAnswer = answerList.find(item => item.question_detail_id === id);

        $.ajax({
            url: '{{ route('task-history.edit', ':id') }}'.replace(':id', id),
            method: 'GET',
            data:{
                task_history_id: match[1],
            },
            success: function(response) {
                let question_tryout = response.data;

                const defaultImage = 'https://dummyimage.com/100/fff/0011ff.png&text=Image+Not+Found';
                const imageSrc = question_tryout.discussion_image || defaultImage;
                const selectedRadio = $('input[name="question-1-1"]:checked');

                selectedRadio.prop('checked', false);

                panel_distribution = JSON.parse(question_tryout.panelist_answers_distribution);
                const maxValue = Math.max(...Object.values(panel_distribution).map(Number));
                const valueToColumnMap = {
                    "-2": 1,
                    "-1": 2,
                    "0": 3,
                    "1": 4,
                    "2": 5
                };

                const selectedColumn = currentAnswer && currentAnswer.value
                                        ? valueToColumnMap[currentAnswer.value] ?? null
                                        : null;

                    for (let i = 1; i <= 5; i++) {
                        const column = $(`#column_selected_${i}`);

                        if (i == selectedColumn) {
                            column.addClass('bg-primary text-white');
                        } else {
                            column.removeClass('bg-primary text-white');
                        }
                    }


                $('#your_answer').text(currentAnswer?.value ?? "-");
                $('#your_score').text(
                    typeof currentAnswer?.value !== 'undefined' &&
                    panel_distribution[currentAnswer.value] !== undefined &&
                    maxValue
                        ? panel_distribution[currentAnswer.value] / maxValue
                        : "-"
                );
                $('#panel_answer_1').text(panel_distribution["-2"]);
                $('#panel_answer_2').text(panel_distribution["-1"]);
                $('#panel_answer_3').text(panel_distribution["0"]);
                $('#panel_answer_4').text(panel_distribution["1"]);
                $('#panel_answer_5').text(panel_distribution["2"]);
                $('#panel_bobot_1').text(`${panel_distribution["-2"]}/${maxValue}`);
                $('#panel_bobot_2').text(`${panel_distribution["-1"]}/${maxValue}`);
                $('#panel_bobot_3').text(`${panel_distribution["0"]}/${maxValue}`);
                $('#panel_bobot_4').text(`${panel_distribution["1"]}/${maxValue}`);
                $('#panel_bobot_5').text(`${panel_distribution["2"]}/${maxValue}`);
                $('#panel_score_1').text(panel_distribution["-2"]/maxValue);
                $('#panel_score_2').text(panel_distribution["-1"]/maxValue);
                $('#panel_score_3').text(panel_distribution["0"]/maxValue);
                $('#panel_score_4').text(panel_distribution["1"]/maxValue);
                $('#panel_score_5').text(panel_distribution["2"]/maxValue);
                $('#column_1').text(question_tryout.column_title.column_1);
                $('#column_2').text(question_tryout.column_title.column_2);
                $('#column_3').text(question_tryout.column_title.column_3);
                $('#category').text(question_tryout.medical_field.name);
                $('#rationale').html(question_tryout.rationale);
                $('#clinic-case').text(question_tryout.clinical_case);
                $('#initial_hypothesis').text(question_tryout.initial_hypothesis);
                if (question_tryout.discussion_image) {
                    $('#img_initial_hypothesis').attr('src', question_tryout.discussion_image).show();
                } else {
                    $('#img_initial_hypothesis').hide();
                };
                $('#new_information').text(question_tryout.new_information);
                $('#discussion_image').attr('src', imageSrc);
                $('#iframe-pdf').attr('src', question_tryout.sub_topic.path);
                $('#minus_one').text(question_tryout.question_type.minus_one);
                $('#minus_two').text(question_tryout.question_type.minus_two);
                $('#zero').text(question_tryout.question_type.zero);
                $('#one').text(question_tryout.question_type.one);
                $('#two').text(question_tryout.question_type.two);
                var pdfPath = question_tryout.sub_topic.path;
                $('#pdf-show').attr('src', pdfPath + '#toolbar=0&navpanes=0&scrollbar=0');

                if (activeQuestion !== null) {
                    $('#question-' + activeQuestion).removeClass('bg-primary text-white');
                }

                checkStatusButton();
                $('#mark-button').attr('disabled',true)

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
        const regex = /\/task-history\/(\d+)/;
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
        const regex = /\/task-history\/(\d+)/;
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


    function finishExam() {
        const url = window.location.href;
        const regex = /\/task-history\/(\d+)/;
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
                    toastError('Task history updated successfully:');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating task history:', error);
                }
            });
        } else {
            console.error('Failed to extract task history ID from the URL');
        }
    }



</script>
