@extends('layouts.app')

@section('title', config('app.name') . ' | Profile')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="bg-cover-profile"></div>
                    <div class="absolute image-profile">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($person->name) }}&size=128" alt="{{ $person->name }}" class="rounded-full w-32 border-4 border-white shadow-md">
                    </div>
                    <div class="w-full flex justify-content-end pt-3 px-4">
                        <button class="btn" id="btn-update"><i class="fas fa-pencil text-primary"></i></button>
                        <button class="btn" id="deleteUserButton" hidden><i class="fas fa-trash text-danger"></i></button>
                    </div>
                    <div class="px-4" style="padding-top: 2rem">
                        <div class="flex justify-content-between">
                            <h3 class="fw-bold">{{ ucwords($person->username) }} <span class="badge bg-success"></span></h3>
                            <div>
                                <div class="badge bg-success">{{ $person->is_actived == 1 ? 'Actived' : 'Non Actived' }}</div>
                            </div>
                        </div>
                        <hr>
                        @if($person->userDetail == null)
                        <div class="alert alert-warning" role="alert">
                            Silakan lengkapi data diri dan universitas anda!
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="text-lg font-semibold text-gray-700">Detail Akun</h4>
                                <div class="form-group mb-2">
                                    <label for="fullname" class="text-sm fw-bold">Nama</label>
                                    <input type="text" class="form-control form-control-sm" id="fullname" value="{{ $person->name }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="email" class="text-sm fw-bold">Alamat Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email" value="{{ $person->email }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="phone-number" class="text-sm fw-bold">Telepon</label>
                                    <input type="text" class="form-control form-control-sm" id="phone-number" value="{{ $person->phone }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="access-role" class="text-sm fw-bold">Hak Akses</label>
                                    <input type="text" class="form-control form-control-sm" id="access-role" readonly value="{{ $person->accessRole->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-lg font-semibold text-gray-700">Data Diri</h4>
                                <div class="form-group mb-2">
                                    <label for="gender" class="text-sm fw-bold">Jenis Kelamin</label>
                                    <select class="form-control form-control-sm" id="gender">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ $person->userDetail && $person->userDetail->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $person->userDetail && $person->userDetail->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="address" class="text-sm fw-bold">Alamat</label>
                                    <textarea class="form-control form-control-sm" id="address" rows="3">{{ $person->userDetail && $person->userDetail->address ? $person->userDetail->address : '-' }}</textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="dob" class="text-sm fw-bold">Tanggal Lahir</label>
                                    <input type="date" class="form-control form-control-sm" id="dob"
                                           value="{{ $person->userDetail && $person->userDetail->dob ? \Carbon\Carbon::parse($person->userDetail->dob)->format('Y-m-d') : '' }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h4 class="text-lg font-semibold text-gray-700">Data Pendidikan</h4>
                                <div class="form-group mb-2">
                                    <label for="univ" class="text-sm fw-bold">Universitas</label>
                                    <input type="text" class="form-control form-control-sm" id="univ"
                                           value="{{ $person->userDetail && $person->userDetail->univ ? $person->userDetail->univ : '-' }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="grade" class="text-sm fw-bold">Tingkat Pendidikan</label>
                                    <input type="text" class="form-control form-control-sm" id="grade"
                                           value="{{ $person->userDetail && $person->userDetail->grade ? $person->userDetail->grade : '-' }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="major" class="text-sm fw-bold">Jurusan</label>
                                    <input type="text" class="form-control form-control-sm" id="major"
                                           value="{{ $person->userDetail && $person->userDetail->major ? $person->userDetail->major : '-' }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="study_programs" class="text-sm fw-bold">Program Studi</label>
                                    <input type="text" class="form-control form-control-sm" id="study_programs"
                                           value="{{ $person->userDetail && $person->userDetail->study_programs ? $person->userDetail->study_programs : '-' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-lg font-semibold text-gray-700">Ganti Password</h4>

                            <!-- Peringatan -->
                            <div class="alert alert-danger text-sm" role="alert">
                                Anda akan mengganti password. Pastikan untuk mengingat password baru Anda.
                            </div>

                            <!-- Form Ganti Password -->
                            <form id="changePasswordForm">
                                <div class="form-group mb-3">
                                    <label for="currentPassword" class="text-sm fw-bold">Password Lama</label>
                                    <input type="password" class="form-control form-control-sm" id="currentPassword" name="currentPassword" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="newPassword" class="text-sm fw-bold">Password Baru</label>
                                    <input type="password" class="form-control form-control-sm" id="newPassword" name="newPassword" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="confirmPassword" class="text-sm fw-bold">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control form-control-sm" id="confirmPassword" name="confirmPassword" required>
                                </div>

                                <!-- Button Ganti Password -->
                                <button type="submit" class="btn btn-outline-danger btn-sm w-full">Ganti Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@include('partials.script')

<script>
    $(document).ready(function () {

        $('#content').on('click', '#btn-update', function () {

            const formData = {
                _token: "{{ csrf_token() }}",
                name: $('#fullname').val(),
                email: $('#email').val(),
                phone: $('#phone-number').val(),
                access_role: $('#access-role').val(),
                gender: $('#gender').val(),
                address: $('#address').val(),
                dob: $('#dob').val(),
                univ: $('#univ').val(),
                grade: $('#grade').val(),
                major: $('#major').val(),
                study_programs: $('#study_programs').val(),
            };


            $.ajax({
                url: "{{ route('profile.update', $person->id) }}",
                method: "PUT",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil diperbarui!'
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat memperbarui data!'
                        });
                    }
                },
                error: function (xhr) {

                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    if (errors) {
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessage += errors[key] + '\n';
                            }
                        }
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage || 'Terjadi kesalahan saat memperbarui data!'
                    });
                }
            });
        });

        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;


            if (newPassword !== confirmPassword) {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Password baru dan konfirmasi password harus sama.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }


            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    cancelButton: "btn btn-success mr-2",
                    confirmButton: "btn btn-danger"
                },
                buttonsStyling: false
            });


            swalWithBootstrapButtons.fire({
                title: 'Apakah Anda yakin?',
                text: "Password Anda akan diganti!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, ganti',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    const formData = new FormData();
                    formData.append('currentPassword', currentPassword);
                    formData.append('newPassword', newPassword);
                    formData.append('confirmPassword', confirmPassword);
                    formData.append('_token', "{{ csrf_token() }}");


                    fetch('{{ route('update.password', ['id' => $person->id]) }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Berhasil!',
                                'Password Anda berhasil diganti.',
                                'success'
                            );
                            setTimeout(function() {
                                $.ajax({
                                    url: '{{ route('login.logout') }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        window.location.href = '{{ route('login') }}';
                                    },
                                    error: function(error) {
                                        console.error('Error logging out:', error);
                                    }
                                });
                            }, 3000);
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Pastikan password lama benar.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Terjadi kesalahan!',
                            'Gagal mengganti password.',
                            'error'
                        );
                    });
                }
            });
        });

        $('#deleteUserButton').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "User dan data terkait akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('profile.destroy', $person->id) }}",
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Dihapus!',
                                    'User dan data terkait berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    $.ajax({
                                        url: '{{ route('login.logout') }}',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            window.location.href = '{{ route('login') }}';
                                        },
                                        error: function(error) {
                                            console.error('Error logging out:', error);
                                        }
                                    });
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus user.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Terjadi kesalahan!',
                                'Tidak dapat menghapus user.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

    });
</script>


