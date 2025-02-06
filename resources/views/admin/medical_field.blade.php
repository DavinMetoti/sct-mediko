@extends('layouts.app')

@section('title', config('app.name') . ' | Bidang')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Manajemen Bidang</h3>
                    <p class="text-subtitle text-muted">Kelola dan sesuaikan bidang keilmuan dengan mudah dan terstruktur.</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#medicalFieldModal" onclick="clearModal()">
                        <i class="fas fa-plus me-2"></i><span>Tambah</span>
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped " id="medicalFieldTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="medicalFieldModal" tabindex="-1" aria-labelledby="medicalFieldModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="medicalFieldForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="medicalFieldModalLabel">Tambah Bidang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="medicalFieldId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Bidang</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        const table = $('#medicalFieldTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.medical-fields.table') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                error: function(xhr, error, thrown) {
                    console.error('Error fetching data:', xhr.responseText);
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'name', name: 'name' },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            columnDefs: [
                { width: '5%', targets: 0 },
                { width: '85%', targets: 1 },
                { width: '10%', targets: 2 }
            ],
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }

        });

        // Handle form submission
        $('#medicalFieldForm').submit(function(e) {
            e.preventDefault();
            const id = $('#medicalFieldId').val();
            const url = id ? '{{ route('medical-field.update', ':id') }}'.replace(':id', id) : '{{ route('medical-field.store') }}';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                },
                success: function(response) {
                    $('#medicalFieldModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Edit button click
        $(document).on('click', '.edit-medical-field', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#medicalFieldId').val(id);
            $('#name').val(name);
            $('#medicalFieldModalLabel').text('Edit Bidang');
            $('#medicalFieldModal').modal('show');
        });

        // Delete button click
        $(document).on('click', '.delete-medical-field', function() {
            const id = $(this).data('id');
            const url = '{{ route('medical-field.destroy', ':id') }}'.replace(':id', id);

            confirmationModal.open({
            message: 'Apakah anda yakin ingin menghapus ini?',
            severity: 'error',
                onAccept: () => {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                },
                onReject: () => {

                },
            });
        });
    });

    function clearModal() {
        $('#medicalFieldId').val('');
        $('#name').val('');
        $('#medicalFieldModalLabel').text('Tambah Bidang');
    }
</script>
