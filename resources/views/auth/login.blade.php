@extends('layouts.app')

@section('title', config('app.name') . ' | Login')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 ">
    <div class="bg-overlay"></div>
    <div class="card shadow-lg rounded" style="width: 28rem;">
        <div class="card-body p-4">
            <div class="flex justify-content-center mb-3">
                <a href="/">
                    <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="100%">
                </a>
            </div>
            <p class="text-center text-muted mb-4">Masuk ke akun Anda untuk melanjutkan</p>
            <form id="loginForm">
                @csrf
                <div class="form-group mb-3">
                    <label for="username" class="form-label text-black fw-medium">Username</label>
                    <input type="text" class="form-control rounded px-3 py-2" id="username" name="username"
                        placeholder="Enter your username" required>
                </div>
                <div class="form-group mb-3 position-relative">
                    <label for="password" class="form-label text-black fw-medium">Password</label>
                    <div class="position-relative">
                        <input type="password" class="form-control rounded px-3 py-2 pe-5" id="password" name="password"
                            placeholder="Enter your password" required>
                        <i class="fa fa-eye position-absolute end-0 top-50 translate-middle-y me-3 text-secondary cursor-pointer"
                            id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                </div>
                <div class="flex justify-content-between">
                    <div class="form-group form-check d-flex align-items-center mb-4">
                        <input type="checkbox" class="form-check-input me-2" id="remember" name="remember">
                        <label class="form-check-label text-secondary" for="remember">Remember me</label>
                    </div>
                    <div class="text-muted">
                        <a href="{{ route('forgot-password.index') }}" class="text-decoration-none text-primary">Forgot Password</a>
                    </div>
                </div>
                <button type="button" id="button-login"
                    class="btn btn-primary btn-block rounded py-2 w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">
                    Don't have account?
                    <a href="{{ route('register') }}" class="text-decoration-none text-primary">Register Now</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        $('#togglePassword').click(function () {
            const passwordField = $('#password');
            const icon = $(this);

            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });


        function login() {
            const username = $('#username').val().trim();
            const password = $('#password').val().trim();
            const remember = $('#remember').is(':checked');

            if (!username || !password) {
                toastError('Username dan Password wajib diisi.');
                return;
            }

            $('#button-login').attr('disabled', true);

            $.ajax({
                url: "{{ route('login.check') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    username,
                    password,
                    remember
                },
                success: function (response) {
                    if (response.status === 'success') {
                        toastSuccess(response.message || 'Login berhasil! Anda akan diarahkan.');
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1000);
                    } else {
                        toastError(response.message || 'Login gagal. Periksa kembali username dan password Anda.');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || {};
                        let errorMessage = 'Login gagal. Harap perbaiki kesalahan berikut:\n';
                        for (const field in errors) {
                            errorMessage += `${errors[field][0]}\n`;
                        }
                        toastError(errorMessage);
                    } else {
                        toastError(xhr.responseJSON.message || 'Login gagal. Silakan coba lagi.');
                    }
                },
                complete: function () {
                    $('#button-login').attr('disabled', false);
                }
            });
        }

        $('#button-login').click(login);

        $('#password').keypress(function (event) {
            if (event.which === 13) {
                event.preventDefault();
                login();
            }
        });
    });
</script>
