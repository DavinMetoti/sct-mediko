@extends('layouts.app')

@section('title', config('app.name') . ' | Login')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 ">
    <div class="bg-overlay"></div>
    <div class="card shadow-lg rounded" style="width: 28rem;">
        <div class="card-body p-4">
            <div class="flex justify-content-center mb-3">
                <a class="flex items-center" href="/">
                    <div class="flex items-center gap-2">
                        <svg viewBox="0 0 100 100" class="h-8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30 70C30 45 45 40 45 40H55C55 40 70 45 70 70" stroke="#7AB929" stroke-width="6"
                                stroke-linecap="round"></path>
                            <circle cx="50" cy="35" r="15" stroke="#00A0DC" stroke-width="6"></circle>
                            <path d="M20 50L35 50L40 35L50 65L60 50L80 50" stroke="#00A0DC" stroke-width="4"
                                stroke-linecap="round"></path>
                        </svg>
                        <span class="text-2xl font-bold">
                            <span class="text-[#7AB929]">MEDIKO</span>
                            <span class="text-[#00A0DC]">.ID</span>
                        </span>
                    </div>
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
                <div class="form-group mb-3">
                    <label for="password" class="form-label text-black fw-medium">Password</label>
                    <input type="password" class="form-control rounded px-3 py-2" id="password" name="password"
                        placeholder="Enter your password" required>
                </div>
                <div class="form-group form-check d-flex align-items-center mb-4">
                    <input type="checkbox" class="form-check-input me-2" id="remember" name="remember">
                    <label class="form-check-label text-secondary" for="remember">Remember me</label>
                </div>
                <button type="button" id="button-login"
                    class="btn btn-primary btn-block rounded py-2 w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-decoration-none text-primary">Daftar Sekarang</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
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

        // Klik tombol login
        $('#button-login').click(login);

        // Tekan Enter di input password
        $('#password').keypress(function (event) {
            if (event.which === 13) {
                event.preventDefault();
                login();
            }
        });
    });
</script>
