@extends('layouts.app')

@section('title', config('app.name') . ' | Manajemen User')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Manajemen Admin</h3>
                    <p class="text-subtitle text-muted">Tambahkan dan kelola admin untuk mempermudah pekerjaan.</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccessRoleModal" onclick="clearForm()">
                        <i class="fas fa-plus me-2"></i><span>Tambah</span>
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped " id="userPrivateTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Akses</th>
                                    <th>Status</th>
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

<!-- Add Access Role Modal -->
<div class="modal fade" id="addAccessRoleModal" tabindex="-1" aria-labelledby="addAccessRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccessRoleModalLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="name" class="form-label text-black fw-medium">Nama</label>
                    <input required type="text" class="form-control rounded px-3 py-2" id="name" name="name"
                        placeholder="Enter your name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="username" class="form-label text-black fw-medium">Username</label>
                    <input required type="text" class="form-control rounded px-3 py-2" id="username" name="username"
                        placeholder="Enter your username" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email" class="form-label text-black fw-medium">Email</label>
                    <input required type="email" class="form-control rounded px-3 py-2" id="email" name="email"
                        placeholder="Enter your email" required>
                </div>
                <div class="form-group mb-3" id="password-group">
                    <label for="password" class="form-label text-black fw-medium">Password</label>
                    <input required type="password" class="form-control rounded px-3 py-2" id="password" name="password"
                        placeholder="Enter your password" required>
                </div>
                <div class="form-group mb-3" id="password-confirmation-group">
                    <label for="password_confirmation" class="form-label text-black fw-medium">Konfirmasi Password</label>
                    <input required type="password" class="form-control rounded px-3 py-2" id="password_confirmation"
                        name="password_confirmation" placeholder="Konfirmasi password Anda" required>
                </div>
                <div class="form-group mb-3">
                    <label for="access_role" class="form-label text-black fw-medium">Akses</label>
                    <select id="access_role" name="access_role" class="form-control rounded px-3 py-2" required>
                        <option value="" disabled>Pilih Akses</option>
                        <!-- Options will be loaded dynamically -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="save-button">Simpan</button>
                <button type="button" class="btn btn-primary" id="update-button">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        $('#update-button').hide();

        const table = $('#userPrivateTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.user-management.private') }}',
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
                { data: 'username', name: 'username' },
                { data: 'email', name: 'email' },
                { data: 'access_role', name: 'access_role' },
                {
                    data: 'is_actived',
                    name: 'is_actived',
                    render: function(data) {
                        if (data == 1) {
                            return '<span class="badge bg-success">Active</span>';
                        } else if (data == 0) {
                            return '<span class="badge bg-danger">Non-Active</span>';
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
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }
        });

        $('#access_role').select2({
            placeholder: 'Pilih Akses',
            allowClear: true,
            dropdownParent: $('#addAccessRoleModal'),
            theme:'bootstrap-5'
        });

        $.ajax({
            url: '{{ route("admin.access-role.private") }}',
            method: 'GET',
            success: function(response) {
                let options = '<option value="">Pilih Akses</option>';
                response.data.forEach(function(role) {
                    options += `<option value="${role.id}">${role.name}</option>`;
                });
                $('#access_role').html(options).trigger('change');
            },
            error: function(xhr) {
                console.error('Error fetching access roles:', xhr.responseText);
                alert('Gagal memuat data akses!');
            }
        });

        $('#save-button').on('click', function() {
            const name = $('#name').val().trim();
            const username = $('#username').val().trim();
            const email = $('#email').val().trim();
            const password = $('#password').val().trim();
            const confirmPassword = $('#password_confirmation').val().trim();
            const idAccessRole = $('#access_role').val().trim();

            if (!username || !email || !password || !confirmPassword) {
                toastError('Semua kolom wajib diisi.');
                return;
            }

            if (password !== confirmPassword) {
                toastError('Password dan Konfirmasi Password tidak cocok.');
                return;
            }

            $('#save-button').attr('disabled', true);

            $.ajax({
                url: "{{ route('register.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name,
                    username: username,
                    email: email,
                    password: password,
                    password_confirmation: confirmPassword,
                    id_access_role:idAccessRole
                },
                success: function (response) {

                    table.ajax.reload(null, false);
                    toastSuccess('User berhasil ditambahkan!');
                    $('#addAccessRoleModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('#name').val('');
                    $('#username').val('');
                    $('#email').val('');
                    $('#password').val('');
                    $('#password_confirmation').val('');
                    $('#access_role').val('');
                },
                error: function (xhr) {

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'User gagal ditambahkan. Harap perbaiki kesalahan:';
                        for (const field in errors) {
                            errorMessage += `${errors[field][0]}`;
                        }
                        toastError(errorMessage);
                    } else {
                        toastError('Terjadi kesalahan yang tidak terduga. Silakan coba lagi.');
                    }
                },
                complete: function () {

                    $('#save-button').attr('disabled', false);
                }
            });
        });

        $(document).on('click', '.edit-button', function() {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';

            localStorage.setItem('id',id);

            $('#save-button').hide();
            $('#update-button').show();
            $('#update-button').attr('data-id',id);

            $.ajax({
                url: `{{ url('admin/user-management') }}/${id}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#name').val(response.data.name);
                    $('#username').val(response.data.username);
                    $('#email').val(response.data.email);
                    $('#password-group').attr('hidden', true);;
                    $('#password-confirmation-group').attr('hidden', true);;
                    $('#access_role').val(response.data.id_access_role).trigger('change');
                    $('#addAccessRoleModal').modal('show');
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
                        url: `{{ url('admin/user-management') }}/${id}`,
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

        $('#update-button').on('click', function() {
            const csrfToken = '{{ csrf_token() }}';
            let id = localStorage.getItem('id');
            const name = $('#name').val();
            const username = $('#username').val();
            const email = $('#email').val();
            const idAccessRole = $('#access_role').val().trim();

            if (!username) {
                toastWarning('Username wajib diisi!');
                return;
            }

            $.ajax({
                url: `{{ url('admin/user-management') }}/${id}`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    name: name,
                    username: username,
                    email: email,
                    id_access_role: idAccessRole
                },
                success: function(response) {
                    $('#addAccessRoleModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('#name').val('');
                    $('#username').val('');
                    $('#email').val('');
                    $('#access_role').val('').trigger('change');
                    $('#password-group').attr('hidden', false);;
                    $('#password-confirmation-group').attr('hidden', false);;

                    table.ajax.reload(null, false);
                    $('#save-button').show();
                    localStorage.removeItem('id');

                    toastSuccess(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    toastError('Terjadi kesalahan saat menyimpan data!');
                }
            });
        });

    });
    function clearForm() {
        $('#name').val('');
        $('#username').val('');
        $('#email').val('');
        $('#access_role').val('');
        $('#password-group').val('');
        $('#password-confirmation-group').val('');

        $('#save-button').show();
        $('#update-button').hide();


        $('#password-group').attr('hidden', false);;
        $('#password-confirmation-group').attr('hidden', false);;
    }
</script>

