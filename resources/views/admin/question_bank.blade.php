@extends('layouts.app')

@section('title', config('app.name') . ' | Bank Soal')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Manajemen Bank Soal</h3>
                    <p class="text-subtitle text-muted">Kelola kumpulan soal dengan mudah untuk pengalaman belajar yang lebih baik.</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="openCreateModal()">
                       <i class="fas fa-plus me-2"></i>Tambah Bank Soal
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="questionBankTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bank Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Question Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="createBankName">Bank Name</label>
                        <input type="text" class="form-control" id="createBankName" name="bank_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Question Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editBankName">Bank Name</label>
                        <input type="text" class="form-control" id="editBankName" name="bank_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        var table = $('#questionBankTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('question-bank.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'bank_name', name: 'bank_name' },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [
                { targets: 0, width: '5%', className: 'text-center' },
                { targets: 1, width: '80%' },
                { targets: 2, width: '15%', className: 'text-center' }
            ]
        });

        $(document).on('click', '.btn-edit', function () {
            var id = $(this).data('id');
            var bankName = $(this).data('bank-name');

            $('#editId').val(id);
            $('#editBankName').val(bankName);

            $('#editModal').modal('show');
        });

        $('#editForm').on('submit', function (e) {
            e.preventDefault();

            var id = $('#editId').val();
            var bankName = $('#editBankName').val();

            $.ajax({
                url: '{{ route('question-bank.update', '') }}/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    bank_name: bankName,
                },
                success: function (response) {
                    $('#editModal').modal('hide');

                    table.ajax.reload(null, false);

                    toastSuccess(response.message);
                },
                error: function (xhr) {
                    toastError('Gagal memperbarui data. Silakan coba lagi.');
                }
            });
        });

        $('#createForm').on('submit', function (e) {
            e.preventDefault();

            var bankName = $('#createBankName').val();

            $.ajax({
                url: '{{ route('question-bank.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    bank_name: bankName,
                },
                success: function (response) {
                    $('#createModal').modal('hide');

                    table.ajax.reload(null, false);

                    toastSuccess(response.message);
                },
                error: function (xhr) {
                    toastError('Gagal menambah data. Silakan coba lagi.');
                }
            });
        });
    });

    function openCreateModal() {
        $('#createModal').modal('show');
    }

    function deleteQuestionBank(id) {
        confirmationModal.open({
            message: 'Apakah anda yakin ingin menghapus bank soal ini?, semua data soal di dalamnya akan dihapus',
            severity: 'warn',
            onAccept: () => {
                $.ajax({
                    url: '{{ route('question-bank.destroy', '') }}/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        toastSuccess(response.message);
                        $('#questionBankTable').DataTable().ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        toastError('Gagal menghapus bank soal. Silakan coba lagi.');
                    }
                });
            },
            onReject: () => {
                console.log('Rejected!');
            },
        });
    }
</script>
