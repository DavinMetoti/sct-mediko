@extends('layouts.app')

@section('title', 'Judul Kolom')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Judul Kolom</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Kolom</button>
                    </div>
                    <div class="table-responsive">
                        <table id="columnTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kolom 1</th>
                                    <th>Kolom 2</th>
                                    <th>Kolom 3</th>
                                    <th>Aksi</th>
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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Kolom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="column_1" class="form-label">Kolom 1</label>
                        <input type="text" class="form-control" id="column_1" name="column_1" required>
                    </div>
                    <div class="mb-3">
                        <label for="column_2" class="form-label">Kolom 2</label>
                        <input type="text" class="form-control" id="column_2" name="column_2" required>
                    </div>
                    <div class="mb-3">
                        <label for="column_3" class="form-label">Kolom 3</label>
                        <input type="text" class="form-control" id="column_3" name="column_3" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Kolom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_column_1" class="form-label">Kolom 1</label>
                        <input type="text" class="form-control" id="edit_column_1" name="column_1" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_column_2" class="form-label">Kolom 2</label>
                        <input type="text" class="form-control" id="edit_column_2" name="column_2" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_column_3" class="form-label">Kolom 3</label>
                        <input type="text" class="form-control" id="edit_column_3" name="column_3" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('partials.script')
<script>
    $(document).ready(function () {
        const table = $('#columnTable').DataTable({
            processing: true, // Show processing indicator
            serverSide: true, // Enable server-side processing
            ajax: {
                url: "{{ route('column-title.index') }}", // Backend route for fetching data
                type: "GET", // HTTP method for the request
            },
            columns: [
                { data: 'name', name: 'name', className: 'text-left' },
                { data: 'column_1', name: 'column_1', className: 'text-center' },
                { data: 'column_2', name: 'column_2', className: 'text-right' },
                { data: 'column_3', name: 'column_3' },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                },
            ],

            order: [[0, 'asc']], // Default order by the first column (ID)
        });


        // Add data
        $('#addForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('column-title.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $('#addModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    table.ajax.reload();
                    toastSuccess('Data berhasil ditambahkan!');
                },
                error: function () {
                    toastError('Gagal menambah data!');
                }
            });
        });

        // Edit button handler
        $('#columnTable').on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const column_1 = $(this).data('column_1');
            const column_2 = $(this).data('column_2');
            const column_3 = $(this).data('column_3');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_column_1').val(column_1);
            $('#edit_column_2').val(column_2);
            $('#edit_column_3').val(column_3);
            $('#editModal').modal('show');
        });

        // Save edited data
        $('#editForm').submit(function (e) {
            e.preventDefault();
            const id = $('#edit_id').val();
            $.ajax({
                url: `{{ route('column-title.update',':id') }}`.replace(':id',id),
                method: 'PUT',
                data: $(this).serialize(),
                success: function (response) {
                    $('#editModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    table.ajax.reload();
                    toastSuccess('Data berhasil diperbarui!');
                },
                error: function () {
                    toastError('Gagal memperbarui data!');
                }
            });
        });

        // Delete data
        $('#columnTable').on('click', '.delete-btn', function () {
            const id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: `{{ route('column-title.destroy',':id') }}`.replace(':id',id),
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        table.ajax.reload();
                        toastSuccess('Data berhasil dihapus!');
                    },
                    error: function () {
                        toastError('Gagal menghapus data!');
                    }
                });
            }
        });
    });
</script>
