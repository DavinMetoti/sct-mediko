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

        <div class="row card-purple">
            <div class="col-md-12 mb-3 pb-3 pb-md-0">
                <div class="card-purple">
                    <div class="form-group">
                        <label for="editor" class="form-label fw-bold">Pertanyaan</label>
                        <div id="editor"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5 pt-5"></div>
            <div class="col-md-12 mb-4 pt-5 pt-md-0 mt-5">
                <div class="card-purple">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Bank Soal</label>
                        <select class="form-control-purple w-full" id="bank-soal" name="bank_soal">
                            <option value="" disabled selected>Pilih Bank Soal</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-purple">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Bidang</label>
                        <select class="form-control-purple w-full" id="medical-field" name="medical_field">
                            <option value="" disabled selected>Pilih Bidang</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-purple">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Judul Kolom</label>
                        <select name="column-title" id="column-title" class="form-control-purple w-full">
                            <option value="">Pilih Judul Kolom</option>
                            @foreach($columnTitle as $column)
                                <option value="{{ $column->id }}" class="text-black">{{ $column->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-purple">
                    <div class="form-group">
                        <label for="bank-soal mb-2 fw-bold">Hipotesis Awal</label>
                        <input class="form-control-purple w-full" id="initial-hypothesis" name="initial_hypothesis" />

                        <label for="panelist-desc" class="fw-bold mt-4">Keterangan Panelis</label>
                        <select id="panelist-desc" class="form-control-purple w-full">
                            <option value="">Pilih Keterangan Panelis</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-purple">
                    <div class="form-group">
                        <label for="new-information" class="mb-2 fw-bold">Informasi Baru</label>
                        <input class="form-control-purple w-full" id="new-information" name="new_information" />

                        <label for="upload-file" class="mt-3 fw-bold">Unggah File</label>
                        <input type="file"
                            class="form-control-purple w-full"
                            id="upload-file"
                            name="uploaded_file"
                            accept=".jpg, .jpeg" />

                        <small class="form-text">
                            Hanya file dengan format <strong>.jpg</strong> atau <strong>.jpeg</strong> yang diizinkan.
                        </small>

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
                                        <div class="fw-medium text-md">Jawaban dengan nilai -2</div>
                                    </div>
                                    <div>
                                        <input type="number" class="form-control d-none" id="value_1" value="-2" placeholder="Type Here">
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
                    <div class="col-md-6 col-12 mb-4">
                        <div class="card text-white card-hover" style="background-color: #2E9DA6; width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai -1</div>
                                    </div>
                                    <div>
                                        <input type="number" class="form-control d-none" id="value_2" value="-1" placeholder="Type Here">
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
                    <div class="col-md-6 col-12 mb-4">
                        <div class="card text-white card-hover" style="background-color: #EFA929; width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai 0</div>
                                    </div>
                                    <div>
                                        <input type="number" class="form-control d-none" id="value_3" value="0" placeholder="Type Here">
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
                    <div class="col-md-6 col-12 mb-4">
                        <div class="card text-white card-hover" style="background-color: #D5546D; width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai 1</div>
                                    </div>
                                    <div>
                                        <input type="number" class="form-control d-none" id="value_4" value="1" placeholder="Type Here">
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
                    <div class="col-md-6 col-12 mb-4">
                        <div class="card text-white card-hover" style="background-color:rgb(84, 157, 213); width: 100%;">
                            <div class="card-header border-0 shadow-0">
                                <div class ="flex justify-content-between align-items-center">
                                    <div class="flex align-items-center">
                                        <div class="fw-medium text-md">Jawaban dengan nilai 2</div>
                                    </div>
                                    <div>
                                        <input type="number" class="form-control d-none" id="value_5" value="2" placeholder="Type Here">
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

            // Inisialisasi dropdown panelist-desc dengan semua opsi dari seluruh kategori
            function initPanelistDescDropdown() {
                const $panelistDesc = $('#panelist-desc');
                $panelistDesc.empty();
                $panelistDesc.append($('<option>', { value: '', text: 'Pilih Keterangan Panelis' }));

                // Tambahkan kategori utama sebagai opsi
                Object.keys(panelistDescriptions).forEach(key => {
                    $panelistDesc.append($('<option>', {
                        value: key,
                        text: key
                    }));
                });
            }

            // Panggil saat halaman dimuat
            initPanelistDescDropdown();

            // Isi otomatis answer_1 - answer_5 sesuai pilihan kategori panelist-desc
            $('#panelist-desc').on('change', function() {
                const selectedCategory = $(this).val();
                if (panelistDescriptions[selectedCategory]) {
                    panelistDescriptions[selectedCategory].forEach((opt, idx) => {
                        $(`#answer_${idx + 1}`).val(opt.text);
                    });
                    // Kosongkan jika kurang dari 5
                    for (let i = panelistDescriptions[selectedCategory].length + 1; i <= 5; i++) {
                        $(`#answer_${i}`).val('');
                    }
                } else {
                    // Jika tidak ada kategori, kosongkan semua
                    for (let i = 1; i <= 5; i++) {
                        $(`#answer_${i}`).val('');
                    }
                }
            });

            // Restore form values from localStorage if exist
            const fields = [
                'bank-soal',
                'medical-field',
                'column-title',
                'initial-hypothesis',
                'new-information',
                'timer'
            ];
            fields.forEach(id => {
                const val = localStorage.getItem(id);
                if (val !== null) {
                    $(`#${id}`).val(val);
                }
            });

            // Restore Quill editor content
            if (localStorage.getItem('editorContent')) {
                question.setContent(localStorage.getItem('editorContent'));
            }

            // Restore answer fields
            for (let i = 1; i <= 5; i++) {
                const ans = localStorage.getItem(`answer_${i}`);
                if (ans !== null) {
                    $(`#answer_${i}`).val(ans);
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
                const text = (e.clipboardData || window.clipboardData).getData('text/plain'); // Get plain text
                question.insertText(text); // Insert plain text into the editor
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

                        // Set value from localStorage if exists
                        const bankSoalVal = localStorage.getItem('bank-soal');
                        if (bankSoalVal !== null) {
                            $('#bank-soal').val(bankSoalVal).trigger('change');
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

                        // Set value from localStorage if exists
                        const medicalFieldVal = localStorage.getItem('medical-field');
                        if (medicalFieldVal !== null) {
                            $('#medical-field').val(medicalFieldVal).trigger('change');
                        }
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                    });
            };

            // Save input/select/textarea changes to localStorage
            $('input, select, textarea').on('input change', function() {
                localStorage.setItem($(this).attr('id'), $(this).val());
            });

            // Save answer fields to localStorage
            for (let i = 1; i <= 5; i++) {
                $(`#answer_${i}`).on('input', function() {
                    localStorage.setItem(`answer_${i}`, $(this).val());
                });
            }

            function updateTotalPanelis(isSuccess = false) {
                let total = 0;

                if (isSuccess) {
                    // Reset all panelist counters to 0
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
                const fileInput = document.getElementById('upload-file');
                const file = fileInput.files[0];

                if (file && !['image/jpeg', 'image/jpg'].includes(file.type)) {
                    toastr.error('Hanya file .jpg atau .jpeg yang diizinkan', { timeOut: 5000 });
                    return;
                }

                const convertFileToBase64 = (file) => {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => resolve(reader.result);
                        reader.onerror = (error) => reject(error);
                        reader.readAsDataURL(file);
                    });
                };

                const collectAndSendData = (base64Image = null) => {
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
                        let value = $(`#value_${i}`).val();
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
                        'answer': answers,
                        'uploaded_image_base64': base64Image
                    };

                    quizQuestionApi.request('POST', '', data)
                        .then(response => {
                            toastr.success(response.response.message, { timeOut: 5000 });

                            // Clear form fields
                            $('#bank-soal, #medical-field, #column-title, #initial-hypothesis, #new-information, #timer').val('');
                            $('#upload-file').val('');
                            for (let i = 1; i <= 5; i++) {
                                $(`#answer_${i}`).val('');
                                $(`#score_${i}`).val(0);
                            }
                            updateTotalPanelis(true);
                            if (typeof question.setContent === 'function') {
                                question.setContent('');
                            }

                            // Clear select2 selections
                            $('#bank-soal').val(null).trigger('change');
                            $('#medical-field').val(null).trigger('change');
                            $('#column-title').val(null).trigger('change');

                            // Clear panelist-desc selection
                            $('#panelist-desc').val('').trigger('change');

                            // Clear localStorage for all concerned fields after save
                            const fields = [
                                'bank-soal',
                                'medical-field',
                                'column-title',
                                'initial-hypothesis',
                                'new-information',
                                'timer',
                                'editorContent'
                            ];
                            fields.forEach(id => localStorage.removeItem(id));
                            for (let i = 1; i <= 5; i++) {
                                localStorage.removeItem(`answer_${i}`);
                            }
                        })
                        .catch(error => {
                            toastr.error(error.response?.error || 'Terjadi kesalahan', { timeOut: 5000 });
                            console.error('Gagal mengambil data:', error);
                        });
                };

                if (file) {
                    convertFileToBase64(file)
                        .then(base64 => {
                            collectAndSendData(base64);
                        })
                        .catch(error => {
                            toastr.error('Gagal membaca file', { timeOut: 5000 });
                            console.error('FileReader error:', error);
                        });
                } else {
                    collectAndSendData(null);
                }
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

            // --- AUTO-SAVE LOGIC WITH INACTIVITY TIMER ---
            let autoSaveTimeout = null;
            const AUTO_SAVE_DELAY = 60000; // 1 minute

            function resetAutoSaveTimer() {
                if (autoSaveTimeout) clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    if (isFormComplete()) {
                        $('#save-question').click();
                    }
                }, AUTO_SAVE_DELAY);
            }

            // Reset timer on any user interaction with form fields
            $('input, select, textarea').on('input change', function() {
                resetAutoSaveTimer();
            });

            // Also reset timer on Quill editor changes
            if (typeof question !== 'undefined' && question && typeof question.onChange === 'function') {
                question.onChange(() => {
                    resetAutoSaveTimer();
                });
            } else {
                // fallback: listen to DOM changes in #editor
                $('#editor').on('input', function() {
                    resetAutoSaveTimer();
                });
            }

            // Initial timer start
            resetAutoSaveTimer();

            updateTotalPanelis();
            firstLoadAPI();
        });
    </script>
@endsection
