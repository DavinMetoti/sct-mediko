@extends('layouts.app')

@section('title', config('app.name') . ' | Tipe Soal')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Manajemen Tipe Soal</h3>
                    <p class="text-subtitle text-muted">Atur dan kelola berbagai tipe soal untuk pengalaman belajar yang lebih terstruktur.</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="openModal()">
                        <i class="fas fa-plus me-2"></i> <span>Tambah Tipe Soal</span>
                    </button>
                </div>
            </div>
            <div id="detailQuestionType">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="QuestionTypeModal" tabindex="-1" aria-labelledby="QuestionTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="QuestionTypeModalLabel">Tambah Tipe Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="typeSoalForm">
                    <div class="form-group mb-3">
                        <label for="questionType" class="form-label text-black fw-medium">Tipe Soal</label>
                        <input required type="text" class="form-control rounded px-3 py-2" id="questionType" name="questionType"
                               placeholder="Masukan tipe soal">
                    </div>
                    <div class="form-group mb-3">
                        <label for="questionTypeMinusTwo" class="form-label text-black fw-medium">-2</label>
                        <input required type="text" class="form-control rounded px-3 py-2" id="questionTypeMinusTwo" name="questionTypeMinusTwo"
                               placeholder="Masukan tipe soal -2">
                    </div>
                    <div class="form-group mb-3">
                        <label for="questionTypeMinusOne" class="form-label text-black fw-medium">-1</label>
                        <input required type="text" class="form-control rounded px-3 py-2" id="questionTypeMinusOne" name="questionTypeMinusOne"
                               placeholder="Masukan tipe soal -1">
                    </div>
                    <div class="form-group mb-3">
                        <label for="questionTypeZero" class="form-label text-black fw-medium">0</label>
                        <input required type="text" class="form-control rounded px-3 py-2" id="questionTypeZero" name="questionTypeZero"
                               placeholder="Masukan tipe soal 0">
                    </div>
                    <div class="form-group mb-3">
                        <label for="questionTypeOne" class="form-label text-black fw-medium">1</label>
                        <input required type="text" class="form-control rounded px-3 py-2" id="questionTypeOne" name="questionTypeOne"
                               placeholder="Masukan tipe soal 1">
                    </div>
                    <div class="form-group mb-3">
                        <label for="questionTypeTwo" class="form-label text-black fw-medium">2</label>
                        <input required type="text" class="form-control rounded px-3 py-2" id="questionTypeTwo" name="questionTypeTwo"
                               placeholder="Masukan tipe soal 2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="saveBroadcast">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        let formData;

        generateContentHtml();

        $('#typeSoalForm').on('submit', function (e) {
            e.preventDefault();
            let formData = {
                name: $('#questionType').val(),
                'minus_two': $('#questionTypeMinusTwo').val(),
                'minus_one': $('#questionTypeMinusOne').val(),
                'zero': $('#questionTypeZero').val(),
                'one': $('#questionTypeOne').val(),
                'two': $('#questionTypeTwo').val(),
                _token: '{{ csrf_token() }}'
            };

            // Cek apakah kita sedang melakukan update atau store
            let url = $('#questionType').data('id') ? "{{ route('question-detail-type.update', ':id') }}".replace(':id', $('#questionType').data('id')) : "{{ route('question-detail-type.store') }}";
            let method = $('#questionType').data('id') ? "PUT" : "POST";

            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function () {
                    toastSuccess('Data successfully ' + (method === 'PUT' ? 'updated.' : 'added.'));
                    generateContentHtml();
                    $('#QuestionTypeModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('#typeSoalForm')[0].reset();
                    $('#questionType').removeData('id'); // Reset ID setelah submit
                },
                error: function () {
                    toastError('Failed to ' + (method === 'PUT' ? 'update' : 'add') + ' data.');
                },
            });
        });
    });

    function openModal(){
        $('#QuestionTypeModal').modal('show');
    }

    function generateContentHtml(){
        $.ajax({
            url: "{{ route('question-detail-type.index') }}",
            method: "GET",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    let htmlContent = '';

                    // Looping through the data array
                    response.data.forEach(function(item) {
                        htmlContent += `
                            <div class="card mb-3">
                                <div class="card-header flex justify-content-between align-items-center" style="background-color:#D0E2FF;">
                                    <h5 class="fw-bold">${item.name}</h5>
                                    <div class="flex">
                                        <!-- Tombol Edit -->
                                        <button class="btn" onclick="editItem(${item.id})">
                                            <i class="bi bi-pencil text-green-600"></i> <!-- Gunakan icon pencil -->
                                        </button>
                                        <!-- Tombol Delete -->
                                        <button class="btn" onclick="deleteItem(${item.id})">
                                            <i class="bi bi-trash text-red-600"></i> <!-- Gunakan icon trash -->
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="label"><strong>-2</strong></span>: ${item.minus_two}
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label"><strong>-1</strong></span>: ${item.minus_one}
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label"><strong>0</strong></span>: ${item.zero}
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label"><strong>1</strong></span>: ${item.one}
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label"><strong>2</strong></span>: ${item.two}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        `;
                    });

                    $('#detailQuestionType').html(htmlContent);
                }
            },
            error: function() {
                toastError('Load data failed');
            },
        });
    };

    function deleteItem(id) {
        confirmationModal.open({
            message: 'Apakah anda yakin ingin menghapus? Semua data soal akan ikut terhapus',
            severity: 'error',
            onAccept: () => {
                $.ajax({
                    url: "{{ route('question-detail-type.destroy', ':id') }}".replace(':id', id),
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastSuccess('Data berhasil dihapus');
                        generateContentHtml();
                    },
                    error: function() {
                        toastError('Failed to load data.');
                    }
                });
            },
            onReject: () => {
                console.log('Rejected!');
            },
        });
    }

    function editItem(id) {
        $.ajax({
            url: "{{ route('question-detail-type.show', ':id') }}".replace(':id', id),
            method: "GET",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#questionType').val(response.data.name);
                $('#questionTypeMinusTwo').val(response.data.minus_two);
                $('#questionTypeMinusOne').val(response.data.minus_one);
                $('#questionTypeZero').val(response.data.zero);
                $('#questionTypeOne').val(response.data.one);
                $('#questionTypeTwo').val(response.data.two);
                $('#questionType').data('id', id); // Menyimpan ID untuk keperluan update
                $('#QuestionTypeModal').modal('show');
            },
            error: function() {
                toastError('Failed to load data.');
            }
        });
    }
</script>

