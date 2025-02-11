@extends('layouts.app')

@section('title', config('app.name') . ' | Verifikasi OTP')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="bg-overlay"></div>
    <div class="card shadow-lg rounded" style="width: 28rem;">
        <div class="card-body p-4">
            <h5 class="text-center">Verifikasi OTP</h5>
            <p class="text-center text-muted mb-4">Masukkan OTP yang telah dikirim ke email Anda.</p>

            <form id="verifyOtpForm">
                @csrf
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control rounded px-3 py-2" id="email" name="email"
                        placeholder="Masukkan email Anda" value="{{ session('otp_email') }}" required readonly>
                </div>
                <div class="form-group mb-3">
                    <label for="otp" class="form-label">Kode OTP</label>
                    <input type="text" class="form-control" id="otp" name="otp" required>
                </div>
                <button type="button" id="button-verify-otp" class="btn btn-primary w-100">Verifikasi OTP</button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Belum menerima OTP?
                    <a href="{{ route('forgot-password.index') }}" class="text-primary">Kirim ulang OTP</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        function verifyOtp() {
            const email = $('#email').val().trim();
            const otp = $('#otp').val().trim();

            if (!email || !otp) {
                toastError('Email dan OTP wajib diisi.');
                return;
            }

            $('#button-verify-otp').attr('disabled', true).text('Memverifikasi...');

            $.ajax({
                url: "{{ route('verify.otp') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    email,
                    otp
                },
                success: function (response) {
                    toastSuccess(response.message || 'OTP berhasil diverifikasi!');

                    let countdown = 5; // Hitungan mundur (detik)
                    $('#button-verify-otp').text(`Mengalihkan (${countdown}s)`);

                    const interval = setInterval(() => {
                        countdown--;
                        $('#button-verify-otp').text(`Mengalihkan (${countdown}s)`);
                        if (countdown === 0) {
                            clearInterval(interval);
                            window.location.href = "{{ route('change.password.view') }}?email=" + encodeURIComponent(email);
                        }
                    }, 1000);
                },
                error: function (xhr) {
                    toastError(xhr.responseJSON.error || 'Gagal verifikasi OTP. Silakan coba lagi.');
                    $('#button-verify-otp').attr('disabled', false).text('Verifikasi OTP');
                }
            });
        }

        $('#button-verify-otp').click(verifyOtp);

        $('#otp').keypress(function (event) {
            if (event.which === 13) {
                event.preventDefault();
                verifyOtp();
            }
        });
    });
</script>

