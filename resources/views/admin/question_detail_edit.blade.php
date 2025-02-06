@extends('layouts.app')

@section('title', config('app.name') . ' | Edit Pertanyaan')

@php
$questionDetailPanelis = json_decode($questionDetail->panelist_answers_distribution, true);

$questionDetailPanelis = array_combine(
    array_map(fn($key) => (string) $key, array_keys($questionDetailPanelis)),
    array_values($questionDetailPanelis)
);
@endphp

@section('content')
<div class="min-h-screen">
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
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="id-question">Bank Soal</label>
                                    <select name="id_question_bank" id="id-question-bank" class="form-select">
                                        <option value="">Pilih Bank Soal</option>
                                        @foreach ($questionBank as $bank)
                                            <option value="{{ $bank->id }}" {{ $questionDetail->question_bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="medical-field">Bidang</label>
                                    <select name="medical_field" id="medical-field" class="form-control">
                                        @foreach($medicalFields as $item)
                                            <option value="{{ $item->id }}" {{ $questionDetail->id_medical_field == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="questionType">Tipe Soal</label>
                                    <select name="questionType" id="questionType" class="form-control">
                                        <option value="">Pilih tipe soal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="clinical-case">Kasus Klinik</label>
                                    <textarea name="clinical_case" id="clinical-case" class="form-control" rows="5" placeholder="Masukkan kasus klinis di sini">{{ $questionDetail->clinical_case }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="initial-hypothesis">Hipotesis Awal</label>
                                    <input type="text" class="form-control" id="initial-hypothesis" name="initial_hypothesis" placeholder="Masukan hipotesis awal" value="{{ $questionDetail->initial_hypothesis }}">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="new-information">Informasi Baru</label>
                                    <textarea name="new_information" id="new-information" class="form-control" rows="2" placeholder="Masukkan informasi baru di sini">{{ $questionDetail->new_information }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="sub-topic-dropdown">Sub Topik</label>
                                    <select name="sub-topic-dropdown" id="sub-topic-dropdown" class="form-control">
                                        <option value="">Pilih Sub Topik</option>
                                        @foreach($topics as $topic)
                                            <optgroup label="{{ $topic->name }}">
                                                @foreach($topic->subTopics as $subTopic)
                                                    <option value="{{ $subTopic->id }}" {{ $questionDetail->id_sub_topic == $subTopic->id ? 'selected' : '' }}>{{ $subTopic->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="column-title-dropdown">Judul Kolom</label>
                                    <select name="column-title-dropdown" id="column-title-dropdown" class="form-control">
                                        <option value="">Pilih Judul Kolom</option>
                                        @foreach($columnTitle as $column)
                                            <option value="{{ $column->id }}" {{ $questionDetail->column_title_id == $column->id ? 'selected' : '' }}>{{ $column->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <label for="froala-editor" class="form-label">Rationale</label>
                                    <div id="editor">
                                        {!! $questionDetail->rationale !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3" style="margin-top: 5rem;">
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
                                                    <span id="minus_two_title">Sangat tidak mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="{{ $questionDetailPanelis[-2] }}" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="border-bottom: rgba(128, 128, 128, 0.567) 1px solid;padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">-1</span>
                                                    <span id="minus_one_title">Tidak mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="{{ $questionDetailPanelis[-1] }}" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="border-bottom: rgba(128, 128, 128, 0.567) 1px solid;padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">0</span>
                                                    <span id="zero_title">Tidak mendukung ataupun menyikirkan</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="{{ $questionDetailPanelis[0] }}" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="border-bottom: rgba(128, 128, 128, 0.567) 1px solid;padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">1</span>
                                                    <span id="one_title">Mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="{{ $questionDetailPanelis[1] }}" min="0" max="10" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center" style="padding-bottom:15px;padding-top:10px;">
                                                <div>
                                                    <span class="badge bg-secondary text-white" style="width: 30px;padding:8px;border-radius: 50%">2</span>
                                                    <span id="two_title">Sangat mungkin</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm decrement">−</button>
                                                    <input type="text" style="max-width: 3rem" class="form-control mx-2 text-center value" value="{{ $questionDetailPanelis[2] }}" min="0" max="10" readonly>
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
                    <div class="flex justify-content-end">
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

        let quill = new Quill('#editor', {
            modules: {
                toolbar: [
                    [{ font: [] }, { size: [] }],
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Please write something',
            theme: 'snow'
        });

        let rationale = @json($questionDetail->rationale);

        quill.on('text-change', function() {
            rationale = quill.root.innerHTML;
        });

        function initSelect2(question, medicalField, subTopic) {
            console.log('Initializing Select2 for', question, medicalField);

            $(question).select2({
                placeholder: 'Pilih Paket',
                theme: 'bootstrap-5',
            });

            $(medicalField).select2({
                placeholder: 'Pilih Bidang',
                theme: 'bootstrap-5',
            });

            $(subTopic).select2({
                placeholder: 'Pilih Sub Topik',
                theme: 'bootstrap-5',
            });
        }

        $.ajax({
            url: "{{ route('question-detail-type.index') }}",
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    const data = response.data.map(item => ({
                        id: item.id,
                        text: item.name
                    }));

                    // Initialize select2
                    $('#questionType').select2({
                        placeholder: 'Pilih tipe soal',
                        theme: 'bootstrap-5',
                        data: data,
                    });

                    // Check if there is a predefined value to select
                    const predefinedValue = "{{ $questionDetail->id_question_type }}";
                    if (predefinedValue) {
                        $('#questionType').val(predefinedValue).trigger('change');
                        $.ajax({
                            url: "{{ route('question-detail-type.show', ':id') }}".replace(':id', predefinedValue),
                            method: "GET",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#minus_two_title').text(response.data.minus_two);
                                $('#minus_one_title').text(response.data.minus_one);
                                $('#zero_title').text(response.data.zero);
                                $('#one_title').text(response.data.one);
                                $('#two_title').text(response.data.two);
                            },
                            error: function() {
                                toastError('Failed to load data.');
                            }
                        });

                    }

                    // Handle change event
                    $('#questionType').on('change', function () {
                        const selectedValue = $(this).val();
                        const selectedText = $(this).find('option:selected').text();

                        $.ajax({
                            url: "{{ route('question-detail-type.show', ':id') }}".replace(':id', selectedValue),
                            method: "GET",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#minus_two_title').text(response.data.minus_two);
                                $('#minus_one_title').text(response.data.minus_one);
                                $('#zero_title').text(response.data.zero);
                                $('#one_title').text(response.data.one);
                                $('#two_title').text(response.data.two);
                            },
                            error: function() {
                                toastError('Failed to load data.');
                            }
                        });
                    });
                } else {
                    toastError('Failed to load data.');
                }
            },
            error: function () {
                toastError('Failed to fetch data from the server.');
            }
        });


        initSelect2('#id-question', '#medical-field', '#sub-topic-dropdown');

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
            const idQuestionBank = $('#id-question-bank').val();
            const medicalField = $('#medical-field').val();
            const questionType = $('#questionType').val();
            const subTopic = $('#sub-topic-dropdown').val();
            const columnTitle = $('#column-title-dropdown').val();
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
                question_bank_id: idQuestionBank,
                id_medical_field: medicalField,
                id_question_type: questionType,
                id_sub_topic: subTopic,
                column_title_id: columnTitle,
                clinical_case: clinicalCase,
                new_information: newInformation,
                initial_hypothesis: initialHypothesis,
                discussion_image: discussionImageBase64,
                rationale: rationale,
                panelist_answers_distribution: JSON.stringify(panelistJSON)
            };

            const currentUrl = window.location.href;
            const id = currentUrl.split('/').pop();

            $.ajax({
                url: '{{ route('question-detail.update', ':id') }}'.replace(':id', id),
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    toastSuccess(response.message);
                    setTimeout(function() {
                        window.history.back();
                    }, 1500);

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

