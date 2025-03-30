@extends('quiz.content.index')  {{-- Menggunakan layout utama quiz --}}

@section('quiz-content')
    <div class="quiz-container">
        <div class="d-flex justify-content-between align-items-center w-100 rounded shadow-sm mb-4">
            <h3 class="fw-bold m-0">Buat Soal</h3>
            <div class="row g-3 align-items-center">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        <input type="text" class="form-control w-full" id="timer" name="timer" placeholder="Timer (detik)">
                    </div>
                </div>
                <div class="col-md-5 text-md-end">
                    <button class="btn btn-success px-4 py-2" id="save-question"><i class="fas fa-save me-2"></i>Simpan</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3 pb-3 pb-md-0">
                <div class="card-quiz">
                    <div class="form-group">
                        <label for="editor" class="form-label fw-bold">Pertanyaan</label>
                        <div id="editor" style="color: white !important;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5 pt-5"></div>
            <div class="col-md-12 mb-4 pt-5 pt-md-0 mt-5">
                <div class="card-quiz">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Bank Soal</label>
                        <select class="custom-form-control" id="bank-soal" name="bank_soal">
                            <option value="" disabled selected>Pilih Bank Soal</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-quiz">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Bidang</label>
                        <select class="custom-form-control" id="medical-field" name="medical_field">
                            <option value="" disabled selected>Pilih Bidang</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-quiz">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Judul Kolom</label>
                        <select name="column-title" id="column-title" class="custom-form-control">
                            <option value="">Pilih Judul Kolom</option>
                            @foreach($columnTitle as $column)
                                <option value="{{ $column->id }}">{{ $column->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-quiz">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Hipotesis Awal</label>
                        <input class="custom-form-control" id="initial-hypothesis" name="initial_hypothesis" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-quiz">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Informasi Baru</label>
                        <input class="custom-form-control" id="new-information" name="new_information" />
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-right mb-2">Sisa panelis : <span id="panelis">10</span></div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card text-white card-hover" style="background-color: #2D70AE; width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai (-2)</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-trash text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <input type="text" class="form-control custom-input text-white bg-transparent border-0" id="answer_1" placeholder="Type Here">
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h6 class="text-sm">Masukan pilihan panelis</h6>
                                <div class="flex gap-1">
                                    <button class="btn btn-primary btn-decrease">-</button>
                                    <input type="text" class="form-control text-center mx-2 counter-input" id="score_1" value="0" style="width: 50px;" readonly>
                                    <button class="btn btn-primary btn-increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="card text-white card-hover" style="background-color: #2E9DA6; width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai (-1)</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-trash text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <input type="text" class="form-control custom-input text-white bg-transparent border-0" id="answer_2" placeholder="Type Here">
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h6 class="text-sm">Masukan pilihan panelis</h6>
                                <div class="flex gap-1">
                                    <button class="btn btn-primary btn-decrease">-</button>
                                    <input type="text" class="form-control text-center mx-2 counter-input" id="score_2" value="0" style="width: 50px;" readonly>
                                    <button class="btn btn-primary btn-increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="card text-white card-hover" style="background-color: #EFA929; width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai (0)</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-trash text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <input type="text" class="form-control custom-input text-white bg-transparent border-0" id="answer_3" placeholder="Type Here">
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h6 class="text-sm">Masukan pilihan panelis</h6>
                                <div class="flex gap-1">
                                    <button class="btn btn-primary btn-decrease">-</button>
                                    <input type="text" class="form-control text-center mx-2 counter-input" value="0" id="score_3" style="width: 50px;" readonly>
                                    <button class="btn btn-primary btn-increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="card text-white card-hover" style="background-color: #D5546D; width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai (1)</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-trash text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <input type="text" class="form-control custom-input text-white bg-transparent border-0" id="answer_4" placeholder="Type Here">
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h6 class="text-sm">Masukan pilihan panelis</h6>
                                <div class="flex gap-1">
                                    <button class="btn btn-primary btn-decrease">-</button>
                                    <input type="text" class="form-control text-center mx-2 counter-input" id="score_4" value="0" style="width: 50px;" readonly>
                                    <button class="btn btn-primary btn-increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="card text-white card-hover" style="background-color:rgb(84, 157, 213); width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai (2)</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-trash text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <input type="text" class="form-control custom-input text-white bg-transparent border-0" id="answer_5" placeholder="Type Here">
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h6 class="text-sm">Masukan pilihan panelis</h6>
                                <div class="flex gap-1">
                                    <button class="btn btn-primary btn-decrease">-</button>
                                    <input type="text" class="form-control text-center mx-2 counter-input" id="score_5" value="0" style="width: 50px;" readonly>
                                    <button class="btn btn-primary btn-increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        $(document).ready(function() {
            const question = new QuillEditor('#editor', {}, (content) => {
                localStorage.setItem('editorContent', content);
            });
            const quizQuestionBankApi = new HttpClient('{{ route("quiz-question-bank.index") }}');
            const medicalFieldApi = new HttpClient('{{ route("admin.medical-fields.dropdown") }}');
            const quizQuestionApi = new HttpClient('{{ route("quiz-question.index") }}');
            const medicalFieldData = {
                _token: "{{ csrf_token() }}"
            };
            let maxPanelis = 10;

            $('#bank-soal').val(localStorage.getItem('bank-soal') || '');
            $('#medical-field').val(localStorage.getItem('medical-field') || '');
            $('#column-title').val(localStorage.getItem('column-title') || '');
            $('#initial-hypothesis').val(localStorage.getItem('initial-hypothesis') || '');
            $('#new-information').val(localStorage.getItem('new-information') || '');
            $('#timer').val(localStorage.getItem('timer') || '');
            question.setContent(localStorage.getItem('editorContent'));

            $('input, select, textarea').on('input change', function() {
                localStorage.setItem($(this).attr('id'), $(this).val());
            });

            for (let i = 1; i <= 5; i++) {
                $(`#answer_${i}`).val(localStorage.getItem(`answer_${i}`) || '');
            }

            const firstLoadAPI = () => {
                quizQuestionBankApi.request('GET', '')
                    .then(response => {
                        let allBanks = response.response.data;
                        let selectElement = document.getElementById('bank-soal');

                        allBanks.forEach(bank => {
                            let option = document.createElement('option');
                            option.value = bank.id;
                            option.textContent = bank.name;
                            selectElement.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                    });

                medicalFieldApi.request('POST', '', medicalFieldData)
                    .then(response => {
                        let allField = response.response.data;
                        let selectElement = document.getElementById('medical-field');

                        allField.forEach(field => {
                            let option = document.createElement('option');
                            option.value = field.id;
                            option.textContent = field.name;
                            selectElement.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                    });
            };

            function updateTotalPanelis(isSuccess = false) {
                let total = 0;

                $(".counter-input").each(function () {
                    if (isSuccess) {
                        $(this).val(10);
                    }
                    total += parseInt($(this).val());
                });

                let remaining = maxPanelis - total;
                $("#panelis").text(remaining);

                $(".btn-increase").each(function () {
                    let input = $(this).siblings(".counter-input");
                    let currentValue = parseInt(input.val()) || 0;

                    if (remaining <= 0) {
                        $(this).prop("disabled", true);
                    } else {
                        $(this).prop("disabled", false);
                    }
                });
            }

            function getHighestScore() {
                let scores = [
                    parseInt($('#score_1').val()) || 0,
                    parseInt($('#score_2').val()) || 0,
                    parseInt($('#score_3').val()) || 0,
                    parseInt($('#score_4').val()) || 0,
                    parseInt($('#score_5').val()) || 0
                ];

                let highestScore = Math.max(...scores);
                return highestScore;
            }

            $(".btn-increase").click(function () {
                let input = $(this).siblings(".counter-input");
                let currentValue = parseInt(input.val());

                if (currentValue < maxPanelis) {
                    input.val(currentValue + 1);
                    updateTotalPanelis();
                }
            });

            $(".btn-decrease").click(function () {
                let input = $(this).siblings(".counter-input");
                let currentValue = parseInt(input.val());

                if (currentValue > 0) {
                    input.val(currentValue - 1);
                    updateTotalPanelis();
                }
            });

            $('.custom-input').on('focus', function () {
                $(this).closest('.card').addClass('active');
            });

            $('.custom-input').on('blur', function () {
                $(this).closest('.card').removeClass('active');
            });

            $('#save-question').on('click', () => {
                const clinical_case = question.getContent();
                const quiz_question_bank = $('#bank-soal').val();
                const medical_field = $('#medical-field').val();
                const column_title = $('#column-title').val();
                const initial_hypothesis = $('#initial-hypothesis').val();
                const new_information = $('#new-information').val();
                const timer = $('#timer').val();

                let max_score = getHighestScore();

                let answers = [];

                for (let i = 1; i <= 5; i++) {
                    let answer = $(`#answer_${i}`).val();
                    let score = parseInt($(`#score_${i}`).val()) / max_score || 0;
                    let value = i - 3;
                    let panelist = parseInt($(`#score_${i}`).val()) || 0

                    answers.push({
                        'answer': answer,
                        'value': value,
                        'score': score,
                        'panelist': panelist
                    });
                }

                let data = {
                    '_token': '{{ csrf_token() }}',
                    'clinical_case': clinical_case,
                    'quiz_question_bank_id': quiz_question_bank,
                    'medical_field_id': medical_field,
                    'column_title_id': column_title,
                    'initial_hypothesis': initial_hypothesis,
                    'new_information': new_information,
                    'timer': timer,
                    'answer': answers
                };

                quizQuestionApi.request('POST', '', data)
                    .then(response => {
                        toastr.success(response.response.message, { timeOut: 5000 });

                        localStorage.removeItem('bank-soal');
                        localStorage.removeItem('medical-field');
                        localStorage.removeItem('column-title');
                        localStorage.removeItem('initial-hypothesis');
                        localStorage.removeItem('new-information');
                        localStorage.removeItem('timer');

                        $('#bank-soal').val('');
                        $('#medical-field').val('');
                        $('#column-title').val('');
                        $('#initial-hypothesis').val('');
                        $('#new-information').val('');
                        $('#timer').val('');

                        for (let i = 1; i <= 5; i++) {
                            localStorage.removeItem(`answer_${i}`);
                            localStorage.removeItem(`score_${i}`);
                            $(`#answer_${i}`).val('');
                            $(`#score_${i}`).val(0);
                        }

                        updateTotalPanelis(true);

                        if (typeof question.setContent === 'function') {
                            question.setContent('');
                        }

                    })
                    .catch(error => {
                        toastr.error(error.response.error, { timeOut: 5000 });
                        console.error('Gagal mengambil data:', error);
                    });


            });

            updateTotalPanelis();
            firstLoadAPI();

        });
    </script>
@endsection
