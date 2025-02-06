@extends('layouts.app')

@section('title', config('app.name') . ' | Hak Akses')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Hak Akses</h3>
                    <p class="text-subtitle text-muted">Atur hak akses untuk membatasi aktivitas admin.</p>
                </div>
                <div>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAccessRoleModal">
                        <i class="fas fa-plus me-2"></i><span>Tambah</span>
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped " id="accessRoleTable">
                            <thead class="bg-secondary">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">Hak Akses</th>
                                    <th class="text-white">Deskripsi</th>
                                    <th class="text-white">Akses</th>
                                    <th class="text-white"></th>
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

<!-- Add Access Role Modal -->
<div class="modal fade" id="addAccessRoleModal" tabindex="-1" aria-labelledby="addAccessRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccessRoleModalLabel">Tambah Hak Akses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Hak Akses</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="save-button">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="listMenuModal" tabindex="-1" aria-labelledby="listMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listMenuModalLabel">List Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <input type="text" id="id-access-roles" class="hidden">
                </div>
                <div class="row" id="menu-list-container">
                    <!-- Checkboxes will be dynamically appended here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="save-button-permission">Simpan</button>
            </div>
        </div>
    </div>
</div>


@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        const table = $('#accessRoleTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.access-role.data') }}',
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
                { data: 'description', name: 'description' },
                {
                    data: 'access',
                    name: 'access',
                    render: function(data) {
                        if (data === 'public') {
                            return '<span class="badge bg-success">Public</span>';
                        } else if (data === 'private') {
                            return '<span class="badge bg-warning">Private</span>';
                        }
                        return data;
                    },
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            columnDefs: [
                { width: "5%", targets: 0 },
                { width: "30%", targets: 1 },
                { width: "44%", targets: 2 },
                { width: "15%", targets: 4 }
            ],
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }
        });

        $('#save-button').on('click', function() {
            const name = $('#name').val();
            const description = $('#description').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!name) {
                toastWarning('Nama wajib diisi!');
                return;
            }

            $.ajax({
                url: '{{ route('access-role.store') }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    name: name,
                    description: description
                },
                success: function(response) {
                    $('#addAccessRoleModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('#name').val('');
                    $('#description').val('');
                    table.ajax.reload(null, false);
                    toastSuccess(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    toastError('Terjadi kesalahan saat menyimpan data!');
                }
            });
        });


        $(document).on('click', '.edit-button', function() {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';

            $.ajax({
                url: `{{ url('admin/access-role') }}/${id}`,
                method: 'GET',
                data: {
                    _token: csrfToken
                },
                success: function(data) {
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                    $('#addAccessRoleModal').modal('show');

                    $('#save-button').off('click').on('click', function() {
                        const updatedName = $('#name').val();
                        const updatedDescription = $('#description').val();

                        if (!updatedName) {
                            toastWarning('Nama wajib diisi!');
                            return;
                        }

                        $.ajax({
                            url: `{{ url('admin/access-role') }}/${id}`,
                            method: 'PUT',
                            data: {
                                _token: csrfToken,
                                name: updatedName,
                                description: updatedDescription
                            },
                            success: function(response) {
                                $('#addAccessRoleModal').modal('hide');
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('#name').val('');
                                $('#description').val('');
                                table.ajax.reload(null, false);
                                toastSuccess(response.message);
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                toastError('Terjadi kesalahan saat menyimpan data!');
                            }
                        });
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    toastError('Gagal mengambil data untuk edit!');
                }
            });
        });


        $(document).on('click', '.delete-button', function() {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';

            confirmationModal.open({
                message: 'Apakah Anda yakin ingin menghapus data ini?',
                severity: 'warn',
                onAccept: () => {
                    $.ajax({
                        url: `{{ url('admin/access-role') }}/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            table.ajax.reload(null, false);
                            toastSuccess(response.message);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            toastError('Hak akses sedang digunakan, tidak dapat dihapus!');
                        }
                    });
                },
                onReject: () => {
                }
            });
        });

        $(document).on('click', '.shield-button', function () {
            $('#id-access-roles').val($(this).data('id'));
            const csrfToken = '{{ csrf_token() }}';
            const accessRoleId = $(this).data('id');

            $.ajax({
                url: `{{ route('admin.access-role.menus', ':id') }}`.replace(':id', accessRoleId),
                method: 'GET',
                data: {
                    _token: csrfToken
                },
                success: function (response) {
                    const menus = response.data;

                    $.ajax({
                        url: `{{ route('admin.access-role.get.permission', ':id') }}`.replace(':id', accessRoleId),
                        method: 'GET',
                        data: {
                            _token: csrfToken,
                        },
                        success: function (permissionResponse) {
                            const existingPermissions = permissionResponse.data || [];
                            const menuListContainer = $('#menu-list-container');
                            menuListContainer.empty();

                            menus.forEach(function (menu) {
                                const isChecked = existingPermissions.some(permission => permission.route === menu.route);
                                const checkboxHtml = `
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="menu-${menu.id}"
                                                value="${menu.route}"
                                                ${isChecked ? 'checked' : ''}>
                                            <label class="form-check-label" for="menu-${menu.id}">
                                                ${menu.label}
                                            </label>
                                        </div>
                                    </div>
                                `;
                                menuListContainer.append(checkboxHtml);
                            });

                            // Show the modal after dynamically populating the checkboxes
                            $('#listMenuModal').modal('show');
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            toastError('Gagal mengambil data permission.');
                        }
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastError('Gagal mengambil data menu.');
                }
            });
        });


        $(document).on('click', '#save-button-permission', function () {
            const selectedMenus = [];


            $('#menu-list-container input[type="checkbox"]:checked').each(function () {
                const menuRoute = $(this).val();
                const id_access_role = $('#id-access-roles').val();

                selectedMenus.push({
                    id_access_role: id_access_role,
                    route: menuRoute,
                });
            });


            $.ajax({
                url: '{{ route('admin.access-role.permission') }}',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                    permissions: selectedMenus,
                }),
                success: function (response) {
                    $('#listMenuModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    toastSuccess(response.message);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastError('Terjadi kesalahan saat menyimpan data!');
                }
            });
        });


    });
</script>

