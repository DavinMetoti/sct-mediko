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
                    <h3 class="fw-bold">Manajemen User</h3>
                    <p class="text-subtitle text-muted">Pantau pengguna baru dan lama melalui halaman ini.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped " id="userPublicTable">
                            <thead class="bg-secondary">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">Nama</th>
                                    <th class="text-white">Email</th>
                                    <th class="text-white">Akses</th>
                                    <th class="text-white">Status</th>
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
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailModalLabel">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-md fw-bold mb-2">User</div>
                <table class="w-100">
                    <tbody>
                        <tr>
                            <td class="text-sm" style="width: 30%;">Nama</td>
                            <td class="text-sm" style="width: 5%;">:</td>
                            <td class="text-sm" style="width: 65%;" id="fullname"></td>
                        </tr>
                        <tr>
                            <td class="text-sm" style="width: 30%;">Username</td>
                            <td class="text-sm" style="width: 5%;">:</td>
                            <td class="text-sm" style="width: 65%;" id="username"></td>
                        </tr>
                        <tr>
                            <td class="text-sm" style="width: 30%;">Email</td>
                            <td class="text-sm" style="width: 5%;">:</td>
                            <td class="text-sm" style="width: 65%;" id="email"></td>
                        </tr>
                        <tr>
                            <td class="text-sm" style="width: 30%;">No telp</td>
                            <td class="text-sm" style="width: 5%;">:</td>
                            <td class="text-sm" style="width: 65%;" id="phone"></td>
                        </tr>
                        <tr>
                            <td class="text-sm" style="width: 30%;">Status</td>
                            <td class="text-sm" style="width: 5%;">:</td>
                            <td class="text-sm" style="width: 65%;" id="is_actived"></td>
                        </tr>
                        <tr>
                            <td colspan="3" id="allertuser" hidden>
                                <div class="alert alert-warning my-2" role="alert">
                                    User belum mengisi data diri lengkap!
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div id="detail_user" hidden>
                    <div class="text-md fw-bold mb-2 mt-3">Data Diri</div>
                    <table class="w-100">
                        <tbody>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Jenis Kelamin</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="gender"></td>
                            </tr>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Alamat</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="address"></td>
                            </tr>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Tanggal Lahir</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="dob"></td>
                            </tr>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Umur</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="age"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-md fw-bold mb-2 mt-3">Pendidikan</div>
                    <table class="w-100">
                        <tbody>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Universitas</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="univ"></td>
                            </tr>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Jurusan</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="major"></td>
                            </tr>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Prodi</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="study_programs"></td>
                            </tr>
                            <tr>
                                <td class="text-sm" style="width: 30%;">Tingkat</td>
                                <td class="text-sm" style="width: 5%;">:</td>
                                <td class="text-sm" style="width: 65%;" id="grade"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();

        const table = $('#userPublicTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.list-student.public') }}',
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
            columnDefs: [
                { targets: 0, width: '5%', className: 'text-center' },
                { targets: 4, width: '10%' },
                { targets: 5, width: '15%', className: 'text-center' }
            ],
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }
        });

        $(document).on('click', '.show-button', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route('list-students.show', ":id") }}'.replace(':id', id),
                type: 'GET',
                success: function (response) {
                    const date = new Date(response.created_at);
                    const formattedDate = date.toLocaleDateString('id-ID');

                    $('#fullname').text(response.name);
                    $('#username').text(response.username);
                    $('#email').text(response.email);
                    $('#phone').text(response.phone??'-');
                    const isActiveBadge = response.is_actived == 1
                        ? `<span class="badge bg-success">Active | ${formattedDate}</span>`
                        : `<span class="badge bg-danger">Inactive</span>`;
                    $('#is_actived').html(isActiveBadge);

                    if (response.user_detail == null) {
                        $('#allertuser').removeAttr('hidden');
                        $('#detail_user').attr('hidden', 'hidden');
                    } else {
                        $('#allertuser').attr('hidden', 'hidden');
                        $('#detail_user').removeAttr('hidden');
                        $('#gender').text(response.user_detail.gender);
                        $('#address').text(response.user_detail.address);
                        $('#univ').text(response.user_detail.univ);
                        $('#major').text(response.user_detail.major??'-');
                        $('#study_programs').text(response.user_detail.study_programs??'-');
                        $('#grade').text(response.user_detail.grade);
                        $('#dob').text(new Date(response.user_detail.dob).toLocaleDateString('id-ID'));

                        const dob = new Date(response.user_detail.dob);
                        const currentDate = new Date();
                        let age = currentDate.getFullYear() - dob.getFullYear();
                        const month = currentDate.getMonth() - dob.getMonth();
                        if (month < 0 || (month === 0 && currentDate.getDate() < dob.getDate())) {
                            age--;
                        }
                        $('#age').text(age + ' Tahun');
                    }



                    $('#userDetailModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Failed to fetch user details. Please try again.');
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

        $(document).on('click', '.deactivate-button', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route('list-students.update', ":id") }}'.replace(':id', id),
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_actived: 0,
                },
                success: function (response) {

                    toastSuccess('User has been deactivated!');
                    table.ajax.reload(null, false);

                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastError('Failed to deactivate the user. Please try again.');
                }
            });
        });

        $(document).on('click', '.activate-button', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route('list-students.update', ":id") }}'.replace(':id', id),
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_actived: 1,
                },
                success: function (response) {

                    toastSuccess('User has been actived!');
                    table.ajax.reload(null, false);

                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastError('Failed to actived the user. Please try again.');
                }
            });
        });

    });
</script>