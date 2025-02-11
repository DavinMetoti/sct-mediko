@extends('layouts.app')

@section('title', config('app.name') . ' | Ubah Password')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="bg-overlay"></div>
    <div class="card shadow-lg rounded" style="width: 28rem;">
        <div class="card-body p-4">
            <h5 class="text-center">Ubah Password</h5>
            <p class="text-center text-muted mb-4">Masukkan password baru Anda.</p>

            <form id="changePasswordForm">
                @csrf
                <input type="hidden" id="email" name="email" value="{{ session('otp_email') }}">

                <div class="form-group mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="button" id="button-change-password" class="btn btn-primary w-100">Ubah Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        function changePassword() {
            const email = $('#email').val().trim();
            const newPassword = $('#new_password').val().trim();
            const confirmPassword = $('#confirm_password').val().trim();

            if (!newPassword || !confirmPassword) {
                toastError('Password baru dan konfirmasi password wajib diisi.');
                return;
            }

            if (newPassword !== confirmPassword) {
                toastError('Konfirmasi password tidak cocok.');
                return;
            }

            // Menonaktifkan tombol untuk mencegah spam klik
            $('#button-change-password').attr('disabled', true);

            $.ajax({
                url: "{{ route('change.password') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    email,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                },
                success: function (response) {
                    toastSuccess(response.message || 'Password berhasil diubah! Silakan login.');

                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 2000);
                },
                error: function (xhr) {
                    toastError(xhr.responseJSON.error || 'Gagal mengubah password. Silakan coba lagi.');

                    $('#button-change-password').attr('disabled', false);
                }
            });
        }

        $('#button-change-password').click(changePassword);

        $('#confirm_password').keypress(function (event) {
            if (event.which === 13) {
                event.preventDefault();
                changePassword();
            }
        });
    });
</script>
