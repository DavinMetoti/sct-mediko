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
                        <h5 class="mb-0">Import Soal</h5>
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-select" id="bank-soal-select" aria-label="Select Bank Soal"></select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="check-bank" type="button">
                                <i class="fas fa-cloud-arrow-up me-2"></i>
                                <span>Check Bank Soal</span>
                            </button>
                        </div>
                    </div>
                    <div id="alert-question-match">

                    </div>
                    <div class="row" id="chart-container" hidden>
                        <div class="col-md-6">
                            <h5 class="mb-0">Bidang</h5>
                            <canvas id="medicalFieldChart" class="p-0"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-0">Bank Soal</h5>
                            <canvas id="questionBankChart" class="p-0"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-check-import">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Bank Soal</th>
                                    <th scope="col">Pertanyaan</th>
                                    <th scope="col">Bidang</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="questionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="questionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="questionDetailsModalLabel">Daftar Pertanyaan</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="questions-list-table">
                        <thead>
                            <tr>
                                <th>Pertanyaan</th>
                                <th>Bidang</th>
                                <th>Type</th>
                                <th>Digunakan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="button-close-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="button-save-import">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
$(document).ready(function () {
    const questionId = @json($id_question);
    const chatContainer = $('#chart-container')

    let questionTable;

    const ctx = $('#medicalFieldChart')[0].getContext('2d');
    const qtx = $('#questionBankChart')[0].getContext('2d');

    const table = $('#table-check-import').DataTable({
        responsive: true,
        pageLength: 10,
        ajax: {
            url: `{{ route('question.create') }}?question=${questionId}`,
            type: 'GET',
            dataSrc: 'data',
        },
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1,
                className: 'text-center',
                width: '5%'
            },
            { data: 'question_bank.bank_name', className: 'text-left', width: '20%' },
            { data: 'clinical_case', className: 'text-left', width: '50%' },
            { data: 'medical_field.name', className: 'text-left', width: '15%' },
            {
                data: 'id',
                render: (data) => `
                    <button type="button" class="btn text-secondary edit-btn" data-id="${data}">
                        <img src="{{ asset('assets/images/up-down.png') }}" alt="Up-Down" width="15">
                    </button>
                    <button type="button" class="btn text-danger delete-btn" id="btn-detach" data-id="${data}">
                        <i class="fa fa-trash"></i>
                    </button>`,
                className: 'text-center',
                orderable: false,
                width: '10%'
            }
        ],
        drawCallback: function(settings) {
            var api = this.api();
            var tableData = api.rows().data();
            const similarityThreshold = 80;

            const labels = [];
            const labels_bank = [];
            const data = [];
            const question_bank_data = [];
            const similarQuestions = [];

            tableData.each(function (soal1, index1) {
                for (let index2 = index1 + 1; index2 < tableData.length; index2++) {
                    const soal2 = tableData[index2];
                    const similarity = calculateSimilarity(soal1.clinical_case, soal2.clinical_case);
                    if (similarity > 90) {
                        similarQuestions.push({
                            case1: soal1.clinical_case,
                            case2: soal2.clinical_case,
                            similarity: similarity
                        });
                    }
                }
            });


            tableData.each(function(row) {
                var label = row.medical_field.name;
                if (!labels.includes(label)) {
                    labels.push(label);
                    data.push(1);
                } else {
                    const index = labels.indexOf(label);
                    data[index] += 1;
                }
            });

            tableData.each(function(row) {
                var label = row.question_bank.bank_name;
                if (!labels_bank.includes(label)) {
                    labels_bank.push(label);
                    question_bank_data.push(1);
                } else {
                    const index = labels_bank.indexOf(label);
                    question_bank_data[index] += 1;
                }
            });

            if (tableData.length > 0) {
                var firstRowData = tableData[0];
                chatContainer.attr('hidden', false);
                if (similarQuestions.length > 0) {
                    let alertHTML = `
                        <div class="alert alert-warning" role="alert">
                            <strong>Peringatan:</strong> Ditemukan soal yang memiliki kemiripan!
                            <ul>
                                ${similarQuestions.map(question => `
                                    <li class="mb-2">
                                        <strong>Soal 1:</strong> ${question.case1}<br>
                                        <strong>Soal 2:</strong> ${question.case2}<br>
                                        <strong>Persentase Kemiripan:</strong> ${question.similarity.toFixed(2)}%
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    `;
                    $('#alert-question-match').html(alertHTML);
                } else {
                    let alertSuccessHTML = `
                        <div class="alert alert-success" role="alert">
                            <strong>Berhasil:</strong> Tidak ditemukan soal yang memiliki kemiripan.
                        </div>
                    `;
                    $('#alert-question-match').html(alertSuccessHTML);
                }
            } else {
                chatContainer.attr('hidden', true);
            }

            if (window.myChart) {
                window.myChart.destroy();
            }

            window.myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '# of Votes',
                        data: data,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            font: {
                                size: 10,
                            },
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 15,
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });

            if (window.myChart2) {
                window.myChart2.destroy();
            }

            window.myChart2 = new Chart(qtx, {
                type: 'pie',
                data: {
                    labels: labels_bank,
                    datasets: [{
                        label: '# of Votes',
                        data: question_bank_data,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            font: {
                                size: 10,
                            },
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 15,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });

            window.myChart.update();
            window.myChart2.update();
        }

    });

    $('#bank-soal-select').select2({
        placeholder: 'Choose a Bank Soal...',
        theme: 'bootstrap-5',
        ajax: {
            url: '{{ route("admin.searchQuestionBank") }}',
            dataType: 'json',
            delay: 250,
            data: (params) => ({ query: params.term }),
            processResults: (data) => ({
                results: data.map(item => ({ id: item.id, text: item.name }))
            }),
            cache: true
        },
        allowClear: true
    });

    $('#check-bank').on('click', function () {
        const idBank = $('#bank-soal-select').val();

        if (!idBank) {
            toastWarning('Please select a Bank Soal first.');
            return;
        }

        $.ajax({
            url: `{{ route('question-bank.show', ':id') }}`.replace(':id', idBank),
            method: 'GET',
            success: (response) => {
                    $('#questionDetailsModal').modal('show');
                    if ($.fn.DataTable.isDataTable('#questions-list-table')) {
                        $('#questions-list-table').DataTable().clear().destroy();
                    }

                    questionTable = $('#questions-list-table').DataTable({
                    responsive: true,
                    pageLength: 10,
                    destroy: true,
                    data: response.data.question_details,
                    columns: [
                        { data: 'clinical_case' },
                        { data: 'medical_field.name' },
                        { data: 'question_type.name' },
                        {
                            data: 'question',
                            render: (data) => {
                                if (Array.isArray(data)) {
                                    return data.map(item => `<span class="badge bg-success mr-1 mb-1">${$('<div>').text(item.question).html()}</span>`).join(' ');
                                }
                                return data;
                            }
                        }
                    ]
                });
            },
            error: (xhr, status, error) => {
                toastError('Failed to load questions. Please try again later.');
                console.error('Error:', error);
            }
        });
    });

    $('#button-save-import').on('click', function () {
        const selectedData = $('#questions-list-table').DataTable().rows('.selected').data();

        if (selectedData.length === 0) {
            toastWarning('Please select at least one question detail to import.');
            return;
        }

        const questionData = Array.from(selectedData).map(row => ({
            question_detail_id: row.id,
            question_id: questionId
        }));

        $.ajax({
            url: '{{ route('question.attach-question-detail') }}',
            type: 'POST',
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: JSON.stringify({
                question_id: questionId,
                question_detail_ids: questionData.map(item => item.question_detail_id)
            }),
            success: (response) => {
                toastSuccess('Successfully attached question details.');
                table.ajax.reload();
                $('#questionDetailsModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            },
            error: (xhr, status, error) => {
                toastError('Failed to attach question details. Please try again.');
                console.error('Error:', error);
            }
        });
    });

    $('#questionDetailsModal').on('hidden.bs.modal', function () {
        if ($.fn.dataTable.isDataTable('#questions-list-table')) {
            $('#questions-list-table').DataTable().clear().destroy();
        }
    });

    $('#table-check-import').on('click', '#btn-detach', function(){
        const id = $(this).data('id');

        $.ajax({
            url: '{{ route('question.detach-question-detail') }}',
            type: 'POST',
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: JSON.stringify({
                question_id: questionId,
                question_detail_ids: [id]
            }),
            success: (response) => {
                toastSuccess('Successfully detached question details.');
                table.ajax.reload();
                $('#questionDetailsModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            },
            error: (xhr, status, error) => {
                toastError('Failed to detach question details. Please try again.');
                console.error('Error:', error);
            }
        });
    });

    $('#button-close-modal').on('click', function() {
        $('#questionDetailsModal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    })

    $('#questions-list-table tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });

});

function calculateSimilarity(string1, string2) {
    const length1 = string1.length;
    const length2 = string2.length;

    const distance = levenshteinDistance(string1, string2);
    const maxLength = Math.max(length1, length2);

    // Persentase kemiripan
    return ((maxLength - distance) / maxLength) * 100;
}

// Fungsi untuk menghitung jarak Levenshtein
function levenshteinDistance(a, b) {
    const matrix = [];

    // Inisialisasi matriks
    for (let i = 0; i <= b.length; i++) {
        matrix[i] = [i];
    }
    for (let j = 0; j <= a.length; j++) {
        matrix[0][j] = j;
    }

    // Hitung jarak
    for (let i = 1; i <= b.length; i++) {
        for (let j = 1; j <= a.length; j++) {
            if (b.charAt(i - 1) === a.charAt(j - 1)) {
                matrix[i][j] = matrix[i - 1][j - 1];
            } else {
                matrix[i][j] = Math.min(
                    matrix[i - 1][j - 1] + 1, // Substitusi
                    Math.min(matrix[i][j - 1] + 1, // Insert
                        matrix[i - 1][j] + 1) // Delete
                );
            }
        }
    }

    return matrix[b.length][a.length];
}
</script>
