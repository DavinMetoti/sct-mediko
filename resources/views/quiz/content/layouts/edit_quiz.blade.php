@extends('quiz.content.index')  {{-- Menggunakan layout utama quiz --}}

@section('quiz-content')
    <div class="quiz-container">
        <div class="row">
            <div class="col-md-6">
                <h4 class="fw-semibold" style="color: #5E5E5E;">Edit Soal Kuis</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3 flex-wrap">
                <div class="input-group shadow-sm" style="max-width: 300px; border-radius: 8px; border: 1px solid #E7E7E7;">
                    <span class="input-group-text bg-white border-0" style="border-radius: 8px 0 0 8px;">
                        <i class="bi bi-stopwatch-fill text-muted" style="opacity: 0.6;"></i>
                    </span>
                    <input type="text" class="form-control border-0" id="timer" name="timer" placeholder="Timer (detik)" style="border-radius: 0 8px 8px 0; font-size: 0.95rem;">
                </div>
                <button class="btn btn-green d-flex align-items-center" id="save-question">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </div>
        </div>
        <div class="card shadow-sm border-0 p-3 rounded-4 mb-4 mt-3">
            <div>
                <div class="row">
                    <div class="col-12">
                        <label for="editor" class="mb-1 text-muted">Pertanyaan</label>
                        <div id="editor"></div>
                    </div>
                    <div class="col-12 mt-5"></div>
                    <div class="col-12 mt-3"></div>
                    <div class="col-12 mt-3">
                        <label for="rationale-editor" class="mb-1 text-muted">Rationale</label>
                        <div id="rationale-editor"></div>
                    </div>
                    <div class="col-12 mt-5"></div>
                    <div class="col-12 mt-5">
                        <label for="bank-soal" class="mb-1 text-muted">Bank Soal</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <select class="form-control rounded-3" id="bank-soal" name="bank_soal">
                                    <option value="" disabled selected>Pilih Bank Soal</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success w-100" id="btn-new-bank"><i class="fas fa-plus me-2"></i>New Bank</button>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control rounded-3 d-none" id="new-bank" name="new_bank" placeholder="Tambahkan nama bank disini" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="medical-field" class="mb-1 text-muted">Bidang</label>
                        <select class="form-control rounded-3" id="medical-field" name="medical_field">
                            <option value="" disabled selected>Pilih Bidang</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="column-title" class="mb-1 text-muted">Judul Kolom</label>
                        <select name="column-title" id="column-title" class="form-control rounded-3">
                            <option value="">Pilih Judul Kolom</option>
                            @foreach($columnTitle as $column)
                                <option value="{{ $column->id }}" class="text-black">{{ $column->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="initial-hypothesis" class="mb-1 text-muted">Hipotesis Awal</label>
                        <input class="form-control rounded-3" id="initial-hypothesis" name="initial_hypothesis" />

                        <label for="panelist-desc" class="fw-bold mt-4 text-muted">Keterangan Panelis</label>
                        <select id="panelist-desc" class="form-control rounded-3">
                            <option value="">Pilih Keterangan Panelis</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="new-information" class="mb-1 text-muted">Informasi Baru</label>
                        <input class="form-control rounded-3" id="new-information" name="new_information" />

                        <label for="upload-file" class="mt-3 mb-1 text-muted">Unggah File</label>
                        <input type="file"
                            class="form-control rounded-3"
                            id="upload-file"
                            name="uploaded_file"
                            accept=".jpg, .jpeg, .png" />

                        <small class="form-text text-muted" style="font-size: 0.7rem;">
                            Hanya file dengan format <strong>.jpg</strong>, <strong>.jpeg</strong>, atau <strong>.png</strong> yang diizinkan.
                        </small>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="text-end mb-2">Sisa panelis : <span id="panelis">10</span></div>
                        <div class="row">
                            @for($i = 1; $i <= 5; $i++)
                            <div class="col-md-6 col-12 mb-4">
                                <div class="card text-white card-hover" style="background-color: {{ ['#2D70AE','#2E9DA6','#EFA929','#D5546D','rgb(84, 157, 213)'][$i-1] }}; width: 100%;">
                                    <div class="card-header border-0 shadow-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="fw-medium text-md">Jawaban dengan nilai {{ -3 + $i }}</div>
                                            <input type="number" class="form-control d-none" id="value_{{ $i }}" value="{{ -3 + $i }}" placeholder="Type Here">
                                        </div>
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <input type="text" class="form-control custom-input text-white bg-transparent border-0" id="answer_{{ $i }}" placeholder="Type Here">
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <h6 class="text-sm">Masukan pilihan panelis</h6>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-primary btn-decrease">-</button>
                                            <input type="text" class="form-control text-center mx-2 counter-input" id="score_{{ $i }}" value="0" style="width: 50px;" readonly>
                                            <button class="btn btn-primary btn-increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        $(document).ready(function() {
            const questions = @json($questions);
            const question = new QuillEditor('#editor', {}, (content) => {
                // No localStorage
            });
            const rationaleEditor = new QuillEditor('#rationale-editor', {}, (content) => {
                // No localStorage
            });

            // Restore form values from questions only (no localStorage)
            const fields = [
                'bank-soal',
                'medical-field',
                'column-title',
                'initial-hypothesis',
                'new-information',
                'timer'
            ];
            fields.forEach(id => {
                if (questions) {
                    switch(id) {
                        case 'bank-soal':
                            $(`#${id}`).val(questions.quiz_question_bank_id);
                            break;
                        case 'medical-field':
                            $(`#${id}`).val(questions.medical_field_id);
                            break;
                        case 'column-title':
                            $(`#${id}`).val(questions.column_title_id);
                            break;
                        case 'initial-hypothesis':
                            $(`#${id}`).val(questions.initial_hypothesis);
                            break;
                        case 'new-information':
                            $(`#${id}`).val(questions.new_information);
                            break;
                        case 'timer':
                            $(`#${id}`).val(questions.timer);
                            break;
                    }
                }
            });

            // Restore Quill editor content from questions only
            if (questions) {
                question.setContent(questions.clinical_case);
                rationaleEditor.setContent(questions.rationale || '');
            }

            // Restore answer fields from questions only
            for (let i = 1; i <= 5; i++) {
                if (questions && questions.answers && questions.answers[i-1]) {
                    $(`#answer_${i}`).val(questions.answers[i-1].answer || '');
                }
            }
            for (let i = 1; i <= 5; i++) {
                if (questions && questions.answers && questions.answers[i-1]) {
                    $(`#score_${i}`).val(questions.answers[i-1].panelist || 0);
                }
            }

            $('#column-title').select2({
                placeholder: 'Pilih judul kolom',
                width: '100%',
                theme: 'bootstrap-5'
            });

            // Ensure only plain text is pasted into the editor
            document.querySelector('#editor').addEventListener('paste', function(e) {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text/plain');
                question.insertText(text);
            });

            const quizQuestionBankApi = new HttpClient('{{ route("quiz-question-bank.index") }}');
            const medicalFieldApi = new HttpClient('{{ route("medical-field.index") }}');
            const quizQuestionApi = new HttpClient('{{ route("quiz-question.index") }}');
            const medicalFieldData = {
                _token: "{{ csrf_token() }}"
            };
            let maxPanelis = 10;

            const firstLoadAPI = () => {
                quizQuestionBankApi.request('GET', '')
                    .then(response => {
                        let allBanks = response.response.data;
                        let selectElement = document.getElementById('bank-soal');
                        selectElement.innerHTML = '';
                        let placeholderOption = document.createElement('option');
                        placeholderOption.disabled = true;
                        placeholderOption.selected = true;
                        placeholderOption.textContent = 'Pilih bank soal';
                        selectElement.appendChild(placeholderOption);
                        allBanks.forEach(bank => {
                            let option = document.createElement('option');
                            option.value = bank.id;
                            option.textContent = bank.name;
                            option.classList.add('text-black');
                            selectElement.appendChild(option);
                        });
                        $('#bank-soal').select2({
                            placeholder: 'Pilih bank soal',
                            width: '100%',
                            theme: 'bootstrap-5'
                        });
                        if (questions) {
                            $('#bank-soal').val(questions.quiz_question_bank_id).trigger('change');
                        }
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                    });

                medicalFieldApi.request('GET', '', medicalFieldData)
                    .then(response => {
                        let allField = response.response.data;
                        let selectElement = document.getElementById('medical-field');
                        selectElement.innerHTML = '';
                        let placeholderOption = document.createElement('option');
                        placeholderOption.disabled = true;
                        placeholderOption.selected = true;
                        placeholderOption.textContent = 'Pilih bidang medis';
                        selectElement.appendChild(placeholderOption);
                        allField.forEach(field => {
                            let option = document.createElement('option');
                            option.value = field.id;
                            option.textContent = field.name;
                            option.classList.add('text-black');
                            selectElement.appendChild(option);
                        });
                        $('#medical-field').select2({
                            placeholder: 'Pilih bidang medis',
                            theme: 'bootstrap-5',
                            width: '100%'
                        });
                        if (questions) {
                            $('#medical-field').val(questions.medical_field_id).trigger('change');
                        }
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                    });
            };

            function updateTotalPanelis(isSuccess = false) {
                let total = 0;
                if (isSuccess) {
                    $(".counter-input").each(function () {
                        $(this).val(0);
                    });
                    total = 0;
                } else {
                    $(".counter-input").each(function () {
                        total += parseInt($(this).val());
                    });
                }
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
                const rationale = rationaleEditor.getContent();
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
                    let value = $(`#value_${i}`).val() || (i - 3);
                    let panelist = parseInt($(`#score_${i}`).val()) || 0;
                    let score = (max_score > 0) ? (panelist / max_score) : 0;
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
                    'rationale': rationale,
                    'answer': answers
                };
                const pathSegments = window.location.pathname.split('/');
                const id = pathSegments[2];
                quizQuestionApi.put(id, data)
                    .then(response => {
                        toastr.success(response.response.message, { timeOut: 5000 });
                        // Clear form fields
                        $('#bank-soal, #medical-field, #column-title, #initial-hypothesis, #new-information, #timer').val('');
                        for (let i = 1; i <= 5; i++) {
                            $(`#answer_${i}`).val('');
                            $(`#score_${i}`).val(0);
                        }
                        updateTotalPanelis(true);
                        if (typeof question.setContent === 'function') {
                            question.setContent('');
                        }
                        // Kembali ke halaman sebelumnya setelah berhasil simpan
                        window.history.back();
                    })
                    .catch(error => {
                        toastr.error(error.response?.error || 'Terjadi kesalahan', { timeOut: 5000 });
                        console.error('Gagal mengambil data:', error);
                    });
            });

            function isFormComplete() {
                const requiredFields = [
                    '#bank-soal',
                    '#medical-field',
                    '#column-title',
                    '#initial-hypothesis',
                    '#new-information',
                    '#timer'
                ];
                for (let selector of requiredFields) {
                    if (!$(selector).val()) {
                        return false;
                    }
                }
                for (let i = 1; i <= 5; i++) {
                    if (!$(`#answer_${i}`).val()) {
                        return false;
                    }
                }
                return true;
            }

            function autoSave() {
                if (isFormComplete()) {
                    $('#save-question').click();
                }
            }

            setInterval(autoSave, 60000); // Auto-save every 1 minute

            // Mapping keterangan panelis per judul kolom
            const panelistDescriptions = {
                'DIAGNOSIS': [
                    { value: -2, text: 'Sangat tidak mungkin' },
                    { value: -1, text: 'Tidak mungkin' },
                    { value: 0, text: 'Tidak mendukung ataupun menyingkirkan' },
                    { value: 1, text: 'Mungkin' },
                    { value: 2, text: 'Sangat mungkin' }
                ],
                'MASALAH KEGUNAAN (PENAPISAN)': [
                    { value: -2, text: 'Tidak berguna' },
                    { value: -1, text: 'Sedikit berguna' },
                    { value: 0, text: 'Di antara berguna dan tidak berguna' },
                    { value: 1, text: 'Berguna' },
                    { value: 2, text: 'Sangat berguna' }
                ],
                'MASALAH KEGUNAAN (TATALAKSANA)': [
                    { value: -2, text: 'Tidak berguna' },
                    { value: -1, text: 'Sedikit berguna' },
                    { value: 0, text: 'Di antara berguna dan tidak berguna' },
                    { value: 1, text: 'Berguna' },
                    { value: 2, text: 'Sangat berguna' }
                ],
                'UNTUNG RUGI (PENAPISAN)': [
                    { value: -2, text: 'Kontraindikasi kuat' },
                    { value: -1, text: 'Kontraindikasi lemah' },
                    { value: 0, text: 'Bukan kontraindikasi maupun indikasi' },
                    { value: 1, text: 'Indikasi lemah' },
                    { value: 2, text: 'Indikasi kuat' }
                ],
                'UNTUNG RUGI (TATALAKSANA)': [
                    { value: -2, text: 'Kontraindikasi kuat' },
                    { value: -1, text: 'Kontraindikasi lemah' },
                    { value: 0, text: 'Bukan kontraindikasi maupun indikasi' },
                    { value: 1, text: 'Indikasi lemah' },
                    { value: 2, text: 'Indikasi kuat' }
                ]
            };

            function getColumnTitleTextById(id) {
                let text = '';
                $('#column-title option').each(function() {
                    if ($(this).val() == id) {
                        text = $(this).text().trim().toUpperCase();
                    }
                });
                return text;
            }

            function updatePanelistDescOptions(columnTitleId) {
                const titleText = getColumnTitleTextById(columnTitleId);
                const descArr = panelistDescriptions[titleText] || [];
                const $desc = $('#panelist-desc');
                $desc.empty();
                $desc.append('<option value="">Pilih Keterangan Panelis</option>');
                descArr.forEach(function(item) {
                    $desc.append(`<option value="${item.value}">${item.text}</option>`);
                });
            }

            function fillAnswersFromPanelistDesc(columnTitleId) {
                const titleText = getColumnTitleTextById(columnTitleId);
                const descArr = panelistDescriptions[titleText] || [];
                for (let i = 1; i <= 5; i++) {
                    $(`#answer_${i}`).val(descArr[i-1] ? descArr[i-1].text : '');
                }
            }

            $('#column-title').on('change', function() {
                const colId = $(this).val();
                updatePanelistDescOptions(colId);
                fillAnswersFromPanelistDesc(colId);
            });

            $('#panelist-desc').on('change', function() {
                const colId = $('#column-title').val();
                const titleText = getColumnTitleTextById(colId);
                const descArr = panelistDescriptions[titleText] || [];
                const selectedVal = parseInt($(this).val());
                const idx = descArr.findIndex(item => item.value === selectedVal);
                if (idx !== -1) {
                    $(`#answer_${idx+1}`).val(descArr[idx].text);
                }
            });

            updateTotalPanelis();
            firstLoadAPI();
        });
    </script>
@endsection
