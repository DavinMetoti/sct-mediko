@extends('layouts.app')

@section('title', config('app.name') . ' | Kirim OTP')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
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
            <p class="text-center text-muted mb-4">Masukkan email Anda untuk menerima OTP</p>
            <form id="otpForm">
                @csrf
                <div class="form-group mb-3">
                    <label for="email" class="form-label text-black fw-medium">Email</label>
                    <input type="email" class="form-control rounded px-3 py-2" id="email" name="email"
                        placeholder="Masukkan email Anda" required>
                </div>
                <button type="button" id="button-send-otp"
                    class="btn btn-primary btn-block rounded py-2 w-100">Kirim OTP</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-decoration-none text-primary">Login</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        function sendOtp() {
            const email = $('#email').val().trim();

            if (!email) {
                toastError('Email wajib diisi.');
                return;
            }

            $('#button-send-otp').attr('disabled', true).text('Mengirim...');

            $.ajax({
                url: "{{ route('send.otp') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    email
                },
                success: function (response) {
                    toastSuccess(response.message || 'OTP berhasil dikirim! Periksa email Anda.');

                    let countdown = 5; // Durasi hitungan mundur (detik)
                    $('#button-send-otp').text(`Menunggu (${countdown}s)`);

                    const interval = setInterval(() => {
                        countdown--;
                        $('#button-send-otp').text(`Menunggu (${countdown}s)`);
                        if (countdown === 0) {
                            clearInterval(interval);
                            window.location.href = "{{ route('verify.otp.view') }}?email=" + encodeURIComponent(email);
                        }
                    }, 1000);
                },
                error: function (xhr) {
                    toastError(xhr.responseJSON.message || 'Gagal mengirim OTP. Silakan coba lagi.');
                    $('#button-send-otp').attr('disabled', false).text('Kirim OTP');
                }
            });
        }

        $('#button-send-otp').click(sendOtp);

        $('#email').keypress(function (event) {
            if (event.which === 13) {
                event.preventDefault();
                sendOtp();
            }
        });
    });
</script>

