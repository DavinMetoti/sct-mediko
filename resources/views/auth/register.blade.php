@extends('layouts.app')

@section('title', config('app.name') . ' | Register')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg rounded" style="width: 30rem;">
        <div class="card-body p-4">
            <div class="flex justify-content-center mb-3">
                <a class="flex items-center" href="/">
                    <div class="flex items-center gap-2">
                        <svg viewBox="0 0 100 100" class="h-8" fill="none" xmlns="http:
                            <path d="M30 70C30 45 45 40 45 40H55C55 40 70 45 70 70" stroke="#7AB929" stroke-width="6"
                                stroke-linecap="round"></path>
                            <circle cx="50" cy="35" r="15" stroke="#00A0DC" stroke-width="6"></circle>
                            <path d="M20 50L35 50L40 35L50 65L60 50L80 50" stroke="#00A0DC" stroke-width="4"
                                stroke-linecap="round"></path>
                        </svg>
                        <span class="text-2xl font-bold"><span class="text-[#7AB929]">MEDIKO</span><span
                                class="text-[#00A0DC]">.ID</span></span>
                    </div>
                </a>
            </div>
            <p class="text-center text-muted mb-4">Buat akun baru untuk melanjutkan</p>
            <form id="registerForm">
                @csrf
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
                <div class="form-group mb-3">
                    <label for="password" class="form-label text-black fw-medium">Password</label>
                    <input required type="password" class="form-control rounded px-3 py-2" id="password" name="password"
                        placeholder="Enter your password" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label text-black fw-medium">Konfirmasi Password</label>
                    <input required type="password" class="form-control rounded px-3 py-2" id="password_confirmation"
                        name="password_confirmation" placeholder="Konfirmasi password Anda" required>
                </div>
                <button type="button" id="button-register"
                    class="btn btn-success btn-block rounded py-2 w-full">Register</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}"
                        class="text-decoration-none text-primary">Masuk Sekarang</a></small>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        $('#button-register').click(function () {
            const name = $('#name').val().trim();
            const username = $('#username').val().trim();
            const email = $('#email').val().trim();
            const password = $('#password').val().trim();
            const confirmPassword = $('#password_confirmation').val().trim();

            if (!username || !email || !password || !confirmPassword) {
                toastError('Semua kolom wajib diisi.');
                return;
            }

            if (password !== confirmPassword) {
                toastError('Password dan Konfirmasi Password tidak cocok.');
                return;
            }


            $('#button-register').attr('disabled', true);


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
                    id_access_role: 2
                },
                success: function (response) {

                    toastSuccess('Pendaftaran berhasil! Silakan masuk akun Anda.');
                    $('#registerForm')[0].reset();
                },
                error: function (xhr) {

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Pendaftaran gagal. Harap perbaiki kesalahan:';
                        for (const field in errors) {
                            errorMessage += `${errors[field][0]}`;
                        }
                        toastError(errorMessage);
                    } else {
                        toastError('Terjadi kesalahan yang tidak terduga. Silakan coba lagi.');
                    }
                },
                complete: function () {

                    $('#button-register').attr('disabled', false);
                }
            });

        });
    });

</script>