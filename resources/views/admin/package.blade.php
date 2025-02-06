@extends('layouts.app')

@section('title', config('app.name') . ' | Daftar Paket')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Manajemen Paket</h3>
                    <p class="text-subtitle text-muted">Atur dan kelola paket pengguna dengan mudah dan efisien.</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPackageModal">
                        <i class="fas fa-plus"></i> Tambah Paket
                    </button>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="packageTable" class="table table-striped ">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Paket</th>
                                    <th class="text-left">Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Kadaluarsa</th>
                                    <th>Aksi</th>
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
</div>

<!-- Modal for Creating New Package -->
<div class="modal fade" id="createPackageModal" tabindex="-1" aria-labelledby="createPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPackageModalLabel">Tambah Paket Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createPackageForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="expired_date" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" class="form-control" id="expired_date" name="expired_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="savePackageBtn">Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Editing Package -->
<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPackageModalLabel">Edit Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPackageForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit_price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_expired_date" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" class="form-control" id="edit_expired_date" name="expires_at">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mappingPackageModal" tabindex="-1" aria-labelledby="mappingPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mappingPackageModalLabel">Tambah Tryout Ke Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="mappingPackageForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="packageId">
                    <div class="mb-3 p-2">
                        <label for="question" class="form-label">Tryout</label>
                        <div class="input-group">
                            <select class="form-control" id="question" name="question[]" multiple="multiple">
                            </select>
                            <button class="btn btn-primary btn-sm" type="button" id="addQuestion">
                                <i class="fas fa-refresh"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="questionsTable" class="table table-striped">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Tryout</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="userPackageModal" tabindex="-1" aria-labelledby="userPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userPackageModalLabel">Tambah User Ke Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="packageUserId">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-5"><input type="checkbox" class="form-check-input" id="select-all" /></th> <!-- Checkbox for "select all" -->
                                <th class="w-50">Nama</th>
                                <th class="w-25">Universitas</th>
                                <th class="w-20">Email</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary"  id="update_user_package"><i class="fas fa-refresh me-2"></i><span>Simpan Perubahan</span></button>
            </div>
        </div>
    </div>
</div>

@endsection

@include('partials.script')

<script>
    $(document).ready(function() {

        let packagetable = $('#packageTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('package.index') }}',
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                    className: 'text-center'
                },
                { data: 'name', name: 'name', className: 'text-left' },
                { data: 'price', name: 'price', className: 'text-center',render: function(data, type, row) {
                    return 'Rp ' + data.toLocaleString('id-ID');
                } },
                { data: 'description', name: 'description', className: 'text-left' },
                { data: 'expires_at', name: 'expires_at', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            },
            columnDefs: [
                { targets: 0, width: '5%' },
                { targets: 1, width: '20%' },
                { targets: 2, width: '15%' },
                { targets: 3, width: '30%' },
                { targets: 4, width: '10%' },
                { targets: 5, width: '20%' }
            ]
        });

        $(document).on('click', '.question-package', function (event) {
            var button = $(this);
            var id = button.data('id');

            $('#packageId').val(id);

            $('#mappingPackageModal').modal('show');

            if ($.fn.DataTable.isDataTable('#questionsTable')) {
                $('#questionsTable').DataTable().destroy();
            }

            let questionTable = $('#questionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("package.edit", ":id") }}'.replace(':id', id),
                    type: 'GET',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'question', name: 'question' },
                    { data: 'thumbnail', name: 'thumbnail' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                columnDefs: [
                    { targets: 0, width: '10%' },
                    { targets: 1, width: '40%' },
                    { targets: 2, width: '40%' },
                    { targets: 3, width: '10%' },
                ],
                language: {
                    emptyTable: "Belum ada tryout untuk paket ini.",
                },
                responsive: true,
                language: {
                    lengthMenu: '_MENU_',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'No entries to show',
                    search: 'Search:'
                },
            });

            $.ajax({
                url: '{{ route("package.getSelectedQuestions", ":id") }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {


                    if (!$('#question').data('select2')) {
                        const selectedQuestions = response.selectedQuestions.map(question => {
                            const questionOption = new Option(question.text, question.id, true, true);
                            $('#question').append(questionOption);
                            return question.id;
                        });


                        $('#question').val(selectedQuestions).trigger('change');
                        $('#question').select2({
                            placeholder: 'Search and select a tryout',
                            theme: 'bootstrap-5',
                            dropdownParent: $('#mappingPackageModal'),
                            ajax: {
                                url: '{{ route("package.search.question") }}',
                                type: 'GET',
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return {
                                        search: params.term,
                                    };
                                },
                                processResults: function (data) {
                                    return {
                                        results: data.map(function (item) {
                                            return { id: item.id, text: item.question };
                                        }),
                                    };
                                },
                                cache: true,
                            },
                            minimumInputLength: 3,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch selected questions:', error);
                }
            });



            $('#addQuestion').off('click').on('click', function () {
                const selectedQuestions = $('#question').val();
                const packageId = $('#packageId').val();

                const data = {
                    _token: '{{csrf_token()}}',
                    questions: selectedQuestions
                };

                $.ajax({
                    url: '{{ route("package.update", ":id") }}'.replace(':id', packageId),
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function (response) {
                        if (response.success) {
                            toastSuccess(response.message);
                            questionTable.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr, status, error) {
                        toastError('Something went wrong!');
                    }
                });
            });
        });

        $(document).on('click', '.user-package', function (event) {
            var button = $(this);
            var id = button.data('id');

            $('#packageUserId').val(id);

            const packageUserId = $('#packageUserId').val();

            $('#userPackageModal').modal('show');


            let registeredUsers = @json($registeredUsers);
            let package = registeredUsers.find(pkg => pkg.id === parseInt(packageUserId));
            let totalChecked = 0;
            let totalUser = 0;

            for (let i = 0; i < package.users.length; i++) {
                totalChecked++
            }

            if ($.fn.DataTable.isDataTable('#userTable')) {
                $('#userTable').DataTable().destroy();
            }

            let user_table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.list-student.public') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                },
                drawCallback: function(settings) {
                    totalUser = settings.aoData.length;
                    if (totalChecked == totalUser) {
                        $('#select-all').prop('checked', true);
                    }

                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            const isChecked = package && package.users.some(user => user.id === data) ? 'checked' : '';
                            return `<input type="checkbox" class="user-checkbox form-check-input" value="${data}" ${isChecked} />`;
                        }
                    },
                    { data: 'name', name: 'name' },
                    {
                        data: 'user_detail',
                        name: 'user_detail.univ',
                        render: function(data, type, row) {
                            return data && data.univ ? data.univ : 'Belum ditentukan';
                        }
                    },
                    { data: 'email', name: 'name' },
                ],
                columnDefs: [
                    { targets: 0, width: '5%' },
                ],
                language: {
                    emptyTable: "Belum ada user",
                    lengthMenu: '_MENU_',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'No entries to show',
                    search: 'Search:'
                },
                responsive: true
            });

            $('.user-checkbox').each(function() {
                if (registeredUsers.includes($(this).val())) {
                    $(this).prop('checked', true);
                }
            });

            $('#select-all').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('.user-checkbox').prop('checked', isChecked);
            });

            $(document).on('change', '.user-checkbox', function () {
                const allChecked = $('.user-checkbox').length === $('.user-checkbox:checked').length;
                $('#select-all').prop('checked', allChecked);
            });

            $(document).on('click', '#update_user_package', function () {
                const selectedUsers = [];

                $('.user-checkbox:checked').each(function () {
                    selectedUsers.push($(this).val());
                });

                const data = {
                    _token: '{{ csrf_token() }}',
                    users: selectedUsers.length === 0 ? [] : selectedUsers
                };

                $.ajax({
                    url: '{{ route("package.update", ":id") }}'.replace(':id', packageUserId),
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function (response) {
                        if (response.success) {
                            toastSuccess(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function (xhr, status, error) {
                        toastError('Something went wrong!');
                    }
                });
            });

        });

        $(document).on('click', '.delete-package', function (event) {
            var button = $(this);
            var id = button.data('id');
            confirmationModal.open({
                message: 'Apakah anda yakin ingin menghapus data ini? Semua data didalamnya akan ikut terhapus',
                severity: 'error',
                onAccept: () => {
                    $.ajax({
                        url: '{{ route('package.destroy', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        data:{
                            _token:'{{ csrf_token() }}'
                        },
                        success: function(response) {
                            packagetable.ajax.reload(null, false);
                            toastSuccess(response.message)
                        },
                        error: function(xhr, status, error) {
                            console.log("Error:", error);
                            alert("Terjadi kesalahan saat delete data.");
                        }
                    });
                },
                onReject: () => {
                    console.log('Rejected!');
                },
            });
        });

        $(document).on('click', '.edit-package', function (event) {
            var button = $(this);
            var id = button.data('id');

            $.ajax({
                url: '{{ route('package.show', ':id') }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    $('#edit_name').val(response.package.name);
                    $('#edit_price').val(response.package.price);
                    $('#edit_description').val(response.package.description);
                    $('#edit_expired_date').val(response.package.expires_at ? response.package.expires_at : '');
                    $('#editPackageModal').modal('show');
                    $('#editPackageForm').attr('action', '{{ route('package.update', ':id') }}'.replace(':id', response.package.id));
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                    alert("Terjadi kesalahan saat memuat data.");
                }
            });
        });

        $(document).on('submit', '#editPackageForm', function (event) {
            event.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: actionUrl,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editPackageModal').modal('hide');
                    packagetable.ajax.reload(null, false);
                    toastSuccess(response.message);
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                    toastError("Terjadi kesalahan saat menyimpan data.");
                }
            });
        });

        $(document).on('click', '.delete-question', function() {
            let questionId = $(this).data('id');
            let packageId = $(this).data('package-id');

            confirmationModal.open({
                message: 'Apakah anda yakin ingin menghapus tryout ini?',
                severity: 'warn',
                onAccept: () => {
                    $.ajax({
                        url: `/package/${packageId}/question/${questionId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                table.ajax.reload(null, false);
                            } else {
                                alert('Failed to delete question.');
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred while trying to delete the question.');
                        }
                    });
                },
                onReject: () => {
                    console.log('Rejected!');
                },
            });
        });

        $('#savePackageBtn').click(function() {
            var formData = new FormData($('#createPackageForm')[0]);
            $('#savePackageBtn').prop('disabled', true);
            $.ajax({
                url: "{{ route('package.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {

                        $('#createPackageModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');

                        $('#createPackageForm')[0].reset();

                        $('#savePackageBtn').prop('disabled', false);

                        toastSuccess(response.message);

                        packagetable.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {

                    $('#savePackageBtn').prop('disabled', false);


                    var errors = xhr.responseJSON.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '\n';
                    });

                    toastError('Error: \n' + errorMessages);
                }
            });
        });
    });
</script>

