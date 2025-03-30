@extends('quiz.content.index')

@section('quiz-content')
    <div class="quiz-container">
        <div class="card-purple p-3">
            <div class="row">
                <div class="col-md-8">
                    <select class="form-control-purple w-full" id="bank-soal" name="bank_soal">
                        <option value="" disabled selected>Pilih Bank Soal</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-between mt-2 mt-md-0">
                        <button class="btn btn-primary" id="search-button">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                        <button class="btn btn-success" id="attach-button">
                            <i class="fas fa-check me-2"></i>Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4" id="list-question">

        </div>
    </div>

    <!-- Modal untuk Menampilkan Soal -->
    <div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="quizModalLabel">Daftar Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="search-medical-field" class="form-control" placeholder="Cari Medical Field...">
                    </div>
                    <div id="check-all-container" class="mb-3 d-none">
                        <input type="checkbox" id="check-all" class="form-check-input">
                        <label for="check-all" class="form-check-label text-black">Pilih Semua</label>
                    </div>
                    <div id="quiz-card-container" class="d-flex flex-column gap-2">
                        <!-- Data Soal akan diisi via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="submit-quiz">Simpan Pilihan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>

    <script>
        const apiQuizQuestionBank = new HttpClient("{{ route('quiz-question-bank.index') }}");
        let pathSegments = window.location.pathname.split('/');
        let quizSessionId = pathSegments[pathSegments.length - 1];
        let apiQuizSession = new HttpClient(`{{ url('quiz-session') }}/${quizSessionId}`);
        let listQuestionDiv = document.getElementById('list-question');

        function stripHTML(html) {
            let doc = new DOMParser().parseFromString(html, 'text/html');
            return doc.body.textContent || "";
        }

        let initialSnapshot = [];

        apiQuizQuestionBank.request('GET', '')
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
            .catch(error => console.error('Gagal mengambil data:', error));

            apiQuizSession.request('GET', '')
                .then(response => {

                    if (response.response.questions && response.response.questions.length > 0) {
                        let existingIds = new Set();

                        response.response.questions.forEach((question, index) => {
                            if (!existingIds.has(question.id)) {
                                existingIds.add(question.id);

                                let questionCard = document.createElement('div');
                                questionCard.classList.add('card-purple', 'mt-3', 'p-3', 'd-flex', 'flex-column');
                                questionCard.setAttribute('data-index', index);
                                questionCard.setAttribute('data-id', question.id);
                                questionCard.innerHTML = `
                                    <strong>${question.medical_field.name}</strong>
                                    <p>${question.clinical_case}</p>
                                    <div class="d-flex justify-content-end mt-2">
                                        <button class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                `;

                                listQuestionDiv.appendChild(questionCard);
                            }
                        });
                    initialSnapshot = getSnapshot();

                    } else {
                        console.warn("Tidak ada pertanyaan dalam sesi kuis.");
                    }
                })
                .catch(error => console.error('Gagal mengambil data:', error));

            document.getElementById('search-button').addEventListener('click', function () {
                let bankSelect = document.getElementById('bank-soal');
                let bankId = bankSelect.value;
                let bankName = bankSelect.options[bankSelect.selectedIndex].text;

                if (!bankId) {
                    toastr.warning('Silakan pilih bank soal terlebih dahulu.');
                    return;
                }

                let apiQuizQuestions = new HttpClient(`{{ url('quiz-question-bank') }}/${bankId}`);

                apiQuizQuestions.request('GET', '')
                    .then(response => {
                        let questions = response.response.quizQuestions;
                        let container = document.getElementById('quiz-card-container');
                        let checkAllContainer = document.getElementById('check-all-container');
                        let checkAll = document.getElementById('check-all');
                        let modalTitle = document.getElementById('quizModalLabel');
                        modalTitle.innerText = `Soal dari Bank Soal: ${bankName}`;

                        container.innerHTML = '';
                        checkAllContainer.classList.add('d-none');

                        if (questions.length === 0) {
                            toastr.error('Soal tidak ditemukan.');
                            return;
                        }


                        let selectedQuestions = new Set(
                            [...document.querySelectorAll('#list-question .card-purple')]
                                .map(el => el.getAttribute('data-id'))
                        );

                        questions.forEach(question => {
                            let isChecked = selectedQuestions.has(String(question.id)) ? 'checked' : '';

                            let card = `
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <input type="checkbox" class="quiz-checkbox form-check-input me-2" value="${question.id}" data-field="${question.medical_field.name}" ${isChecked}>
                                        <div class="text-muted">${question.medical_field.name}</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="clinical-case">${stripHTML(question.clinical_case)}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.innerHTML += card;
                        });

                        checkAllContainer.classList.remove('d-none');

                        let quizModal = new bootstrap.Modal(document.getElementById('quizModal'));
                        quizModal.show();
                    })
                    .catch(error => {
                        console.error('Gagal mengambil soal:', error);
                        toastr.error('Soal untuk quiz ini tidak ditemukan');
                    });
            });


        document.getElementById('search-medical-field').addEventListener('input', function () {
            let searchTerm = this.value.toLowerCase();
            let allCards = document.querySelectorAll('#quiz-card-container .card');
            let checkboxes = document.querySelectorAll('.quiz-checkbox');
            let checkAllContainer = document.getElementById('check-all-container');

            let found = false;
            allCards.forEach((card, index) => {
                let field = checkboxes[index].dataset.field.toLowerCase();
                if (field.includes(searchTerm)) {
                    card.style.display = "block";
                    found = true;
                } else {
                    card.style.display = "none";
                }
            });

            if (!found) {
                checkAllContainer.classList.add('d-none');
            } else {
                checkAllContainer.classList.remove('d-none');
            }
        });

        document.getElementById('check-all').addEventListener('change', function () {
            let allCheckboxes = document.querySelectorAll('.quiz-checkbox');
            allCheckboxes.forEach(checkbox => {
                if (checkbox.closest('.card').style.display !== 'none') {
                    checkbox.checked = this.checked;
                }
            });
        });

        document.getElementById('submit-quiz').addEventListener('click', function () {
            let selectedQuestions = [];
            let listQuestionDiv = document.getElementById('list-question');

            document.querySelectorAll('.quiz-checkbox:checked').forEach(checkbox => {
                selectedQuestions.push({
                    id: checkbox.value,
                    medical_field: checkbox.closest('.card').querySelector('.text-muted').innerText,
                    clinical_case: checkbox.closest('.card').querySelector('.clinical-case').innerText
                });
            });

            if (selectedQuestions.length === 0) {
                toastr.warning('Silakan pilih setidaknya satu soal.');
                return;
            }


            let existingIds = new Set([...listQuestionDiv.querySelectorAll('.card-purple')].map(el => el.getAttribute('data-id')));

            selectedQuestions.forEach((question, index) => {
                if (!existingIds.has(question.id)) {
                    let questionCard = document.createElement('div');
                    questionCard.classList.add('card-purple', 'mt-3', 'p-3', 'd-flex', 'flex-column');
                    questionCard.setAttribute('data-index', index);
                    questionCard.setAttribute('data-id', question.id);
                    questionCard.innerHTML = `
                        <strong>${question.medical_field}</strong>
                        <p>${question.clinical_case}</p>
                        <div class="d-flex justify-content-end mt-2">
                            <button class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                    listQuestionDiv.appendChild(questionCard);
                }
            });

            alert('Soal berhasil disimpan!');
            let quizModal = bootstrap.Modal.getInstance(document.getElementById('quizModal'));
            quizModal.hide();
        });



        document.getElementById('list-question').addEventListener('click', function (event) {
            let target = event.target;

            if (target.classList.contains('btn-delete')) {
                let questionCard = target.closest('.card-purple');

                if (questionCard) {
                    let questionId = questionCard.getAttribute('data-id');

                    let selectedCard = document.querySelector(`[data-id="${questionId}"]`);
                    if (selectedCard) {
                        selectedCard.remove();
                    }

                    let parentDiv = document.getElementById('list-question');
                    if (parentDiv.children.length === 0) {
                    }
                }
            }
        });


        document.getElementById('attach-button').addEventListener('click', function () {
            let selectedQuestionIds = [];


            document.querySelectorAll('#list-question .card-purple').forEach(card => {
                selectedQuestionIds.push(card.getAttribute('data-id'));
            });

            if (selectedQuestionIds.length === 0) {
                toastr.warning('Tidak ada soal yang dipilih untuk disimpan.');
                return;
            }

            let pathSegments = window.location.pathname.split('/');
            let quizSessionId = pathSegments[pathSegments.length - 1];

            if (!quizSessionId) {
                toastr.error('ID sesi quiz tidak ditemukan.');
                return;
            }


            fetch(`{{ route('quiz-session.attach') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    _token : "{{ csrf_token() }}",
                    quiz_sessions_id: quizSessionId,
                    quiz_question_id: selectedQuestionIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Quiz session updated successfully') {
                    toastr.success('Soal berhasil disimpan ke sesi quiz!');
                    saveData();
                } else {
                    toastr.error(data.message || 'Terjadi kesalahan saat menyimpan soal.');
                }
            })
            .catch(error => {
                console.error('Gagal menyimpan soal:', error);
                toastr.error('Terjadi kesalahan saat menyimpan soal.');
            });
        });

        let isDataChanged = false;

        function getSnapshot() {
            return Array.from(listQuestionDiv.children).map(el => el.getAttribute('data-id'));
        }

        const observer = new MutationObserver(() => {
            const currentSnapshot = getSnapshot();
            if (JSON.stringify(initialSnapshot) !== JSON.stringify(currentSnapshot)) {
                isDataChanged = true;
            }
        });

        observer.observe(listQuestionDiv, { childList: true });

        window.addEventListener('beforeunload', function (event) {
            if (isDataChanged) {
                event.preventDefault();
                event.returnValue = "Data belum disimpan. Apakah Anda yakin ingin keluar?";
            }
        });


        function saveData() {
            isDataChanged = false;
        }

    </script>
@endsection
