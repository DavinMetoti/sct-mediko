@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="mb-0">Buat Soal Baru</h5>
                            <small>Masukkan soal dalam bentuk teks dan gambar (opsional)</small>
                        </div>
                    </div>
                </div>
            </div>
            <div id="formComponent">
                <div id="card-form" class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="id-question">Paket</label>
                                    <select name="id_question" id="id-question" class="form-control">
                                        <option value="">Pilih Paket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="medical-field">Bidang</label>
                                    <select name="medical_field" id="medical-field" class="form-control">
                                        <option value="">Pilih Bidang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="clinical-case">Kasus Klinik</label>
                                    <textarea name="clinical_case" id="clinical-case" class="form-control" rows="5" placeholder="Masukkan kasus klinis di sini"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="initial-hypothesis">Hipotesis Awal</label>
                                    <input type="text" class="form-control" id="initial-hypothesis" name="initial_hypothesis" placeholder="Masukan hipotesis awal">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="new-information">Informasi Baru</label>
                                    <textarea name="new_information" id="new-information" class="form-control" rows="2" placeholder="Masukkan informasi baru di sini"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="discussion-image">Gambar Pembahasan (opsional)</label>
                                    <input type="file" name="discussion_image" id="discussion-image" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="discussion-image" class="flex justify-content-between">
                                        <div>Distribusi Jawaban Panelis</div>
                                        <div>Sisa panelis: <span id="remaining-panelists">10</span></div>
                                    </label>
                                    <div style="border: rgba(128, 128, 128, 0.567) 1px solid;border-radius: 7px" class="p-2">
                                        <div class="d-flex justify-content-between mb-2">

                                        </div>
                                        <div id="panelist-distribution">
                                            <!-- Baris distribusi -->
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="border-bottom: rgba(128, 128, 128, 0.567) 1px solid;padding-bottom:15px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">-2</span>
                                                    <span>Sangat tidak mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="1" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="border-bottom: rgba(128, 128, 128, 0.567) 1px solid;padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">-1</span>
                                                    <span>Tidak mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="1" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="border-bottom: rgba(128, 128, 128, 0.567) 1px solid;padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">0</span>
                                                    <span>Tidak mendukung ataupun menyikirkan</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="1" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="border-bottom: rgba(128, 128, 128, 0.567) 1px solid;padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">1</span>
                                                    <span>Mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="1" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center" style="padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">2</span>
                                                    <span>Sangat mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="1" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" id="add-form"><i class="fas fa-plus me-2"></i><span>Tambah</span></button>
                        <button type="button" class="btn btn-primary" id="btn-save"><i class="fas fa-upload me-2"></i><span>Simpan</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')
<script>

    $(document).ready(function() {

        function initSelect2(question, medicalField) {
            console.log('Initializing Select2 for', question, medicalField);

            $(question).select2({
                placeholder: 'Pilih Paket',
                theme: 'bootstrap-5',
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route('question.get-questions') }}',
                    dataType: 'json',
                    delay: 250,
                    type: 'POST',
                    data: function (params) {
                        return {
                            search: params.term,
                            _token: "{{ csrf_token() }}"
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.question
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            $(medicalField).select2({
                placeholder: 'Pilih Bidang',
                theme: 'bootstrap-5',
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route('admin.medical-fields.dropdown') }}',
                    dataType: 'json',
                    delay: 250,
                    type: 'POST',
                    data: function (params) {
                        return {
                            search: params.term,
                            _token: "{{ csrf_token() }}"
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        initSelect2('#id-question', '#medical-field');

        const maxPanelists = 10;
        const remainingPanelists = document.getElementById("remaining-panelists");
        const distribution = document.querySelectorAll("#panelist-distribution .value");
        const incrementButtons = document.querySelectorAll("#panelist-distribution .increment");
        const decrementButtons = document.querySelectorAll("#panelist-distribution .decrement");

        function updateRemaining() {
            const used = Array.from(distribution).reduce((sum, input) => sum + parseInt(input.value), 0);
            remainingPanelists.textContent = maxPanelists - used;
        }

        incrementButtons.forEach((button, index) => {
            button.addEventListener("click", () => {
                const input = distribution[index];
                const currentValue = parseInt(input.value);
                const totalUsed = Array.from(distribution).reduce((sum, inp) => sum + parseInt(inp.value), 0);

                if (currentValue < maxPanelists && totalUsed < maxPanelists) {
                    input.value = currentValue + 1;
                }
                updateRemaining();
            });
        });

        decrementButtons.forEach((button, index) => {
            button.addEventListener("click", () => {
                const input = distribution[index];
                const currentValue = parseInt(input.value);

                if (currentValue > 0) {
                    input.value = currentValue - 1;
                }
                updateRemaining();
            });
        });

        updateRemaining();

        $('#btn-save').click(async function () {
            const idQuestion = $('#id-question').val();
            const medicalField = $('#medical-field').val();
            const clinicalCase = $('#clinical-case').val();
            const initialHypothesis = $('#initial-hypothesis').val();
            const newInformation = $('#new-information').val();
            const panelistDistribution = Array.from(distribution).map(input => input.value);
            const panelistJSON = {
                '-2': panelistDistribution[0],
                '-1': panelistDistribution[1],
                '0': panelistDistribution[2],
                '1': panelistDistribution[3],
                '2': panelistDistribution[4]
            };


            const discussionImageFile = $('#discussion-image')[0].files[0];
            let discussionImageBase64 = null;
            if (discussionImageFile) {
                discussionImageBase64 = await convertToBase64(discussionImageFile);
            }

            const data = {
                _token: '{{ csrf_token() }}',
                id_question: idQuestion,
                id_medical_field: medicalField,
                clinical_case: clinicalCase,
                new_information: newInformation,
                initial_hypothesis: initialHypothesis,
                discussion_image: discussionImageBase64,
                panelist_answers_distribution: JSON.stringify(panelistJSON)
            };

            console.log(data);

            $.ajax({
                url: '{{ route('question-detail.store') }}',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    console.log('Success:', response);
                    toastSuccess(response.message);
                },
                error: function (error) {
                    console.error('Error:', error);
                    toastError(response.message);
                }
            });
        });


        function convertToBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }

    });

</script>

