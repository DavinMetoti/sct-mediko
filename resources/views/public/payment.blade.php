@php
    \Carbon\Carbon::setLocale('id'); // Set locale to Indonesian
@endphp

@extends('layouts.app')

@section('title', config('app.name') . ' | Pembayaran')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2 fw-bold">Pembayaran</h4>
                    <h6 class="mb-4 fw-medium">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY, HH:mm:ss') }}</h6>
                    <div class="w-100 mb-2 text-center text-lg text-muted">
                        Terimakasih telah mendaftar {{ $package->name }}
                    </div>
                    <div class="w-100 mb-2 text-center text-4xl fw-bold">
                        Rp. {{ number_format($package->price, 0, ',', '.') }}
                    </div>
                    <div class="text-center mb-2">
                        <span class="badge
                            @if($invoice->status === 'pending') bg-warning
                            @elseif($invoice->status === 'paid') bg-success
                            @elseif($invoice->status === 'cancel') bg-danger
                            @elseif($invoice->status === 'verification') bg-primary
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>

                    <div class="w-100 mb-4 text-center text-4xl fw-bold" id="refresh-page">
                        <button class="btn btn-info btn-lg font-weight-bold px-4 shadow-sm">
                            Cek Status Pembayaran
                        </button>
                    </div>
                    <hr>
                    @if ($invoice->status == 'verification')
                        <p class="text-muted" style="font-style: italic;">Pembayaran dalam proses verifikasi</p>
                    @elseif ($invoice->status == 'paid')
                        <p class="text-muted" style="font-style: italic;">Pembayaran Berhasil</p>
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border rounded-lg p-4 shadow-sm bg-light">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-university me-2"></i> Transfer Bank</h5>
                                    <h6 class="mb-3 border-2 p-2 rounded-lg">
                                        <i class="fas fa-user me-2"></i> Atas Nama: <span class="fw-semibold">Puja Chrisdianto Manapa</span>
                                    </h6>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded shadow-sm">
                                            <h6 class="text-dark mb-0"><i class="fas fa-credit-card text-warning"></i> BNI: <span class="fw-bold">134 171 695 0</span></h6>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('1341716950')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded shadow-sm">
                                            <h6 class="text-dark mb-0"><i class="fas fa-credit-card text-info"></i> BRI: <span class="fw-bold">111 001 000 251 566</span></h6>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('111001000251566')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="flex items-center flex-column justify-center w-full">
                                            <div id="dropzone"
                                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-7">
                                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                    </svg>
                                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                    <p class="text-xs text-gray-500">JPG, PNG, GIF, PDF (MAX. 2MB)</p>
                                                </div>
                                                <input id="fileInput" type="file" class="hidden" accept=".jpg, .jpeg, .png, .gif, .pdf">
                                            </div>
                                            <div id="file-preview" class="mt-3 text-center text-gray-700"></div>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary w-100" id="uploadBtn">
                                        <i class="fas fa-cloud-upload-alt"></i> Upload
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">Pilih Metode Pembayaran Lain</h5>
                                <a href="#" class="block mb-2">
                                    <div class="flex items-center gap-4 border p-3 rounded-lg transition duration-300 hover:bg-gray-100 hover:shadow-md" style="background-color: #D0E2FF;">
                                        <img src="{{ secure_asset('assets/images/payments/tranfer-bank.png') }}" alt="logo bank transfer" class="w-12 h-12 object-contain">
                                        <div class="flex flex-col">
                                            <h5 class="mb-1 font-semibold">Transfer Bank</h5>
                                            <small class="text-gray-500">Gunakan metode transfer bank untuk pembayaran manual</small>
                                        </div>
                                    </div>
                                </a>

                                <a href="#" class="block mb-2 pointer-events-none opacity-50 cursor-not-allowed">
                                    <div class="flex items-center gap-4 border p-3 rounded-lg bg-gray-200 shadow-sm">
                                        <img src="{{ secure_asset('assets/images/payments/ovo.png') }}" alt="logo ovo" class="w-12 h-12 object-contain" style="filter: grayscale(100%);">
                                        <div class="flex flex-col">
                                            <h5 class="mb-1 font-semibold text-gray-500">OVO</h5>
                                            <small class="text-gray-400">Fitur ini sedang tidak tersedia</small>
                                        </div>
                                    </div>
                                </a>

                                <a href="#" class="block mb-2 pointer-events-none opacity-50 cursor-not-allowed">
                                    <div class="flex items-center gap-4 border p-3 rounded-lg bg-gray-200 shadow-sm">
                                        <img src="{{ secure_asset('assets/images/payments/dana.png') }}" alt="logo dana" class="w-12 h-12 object-contain" style="filter: grayscale(100%);">
                                        <div class="flex flex-col">
                                            <h5 class="mb-1 font-semibold text-gray-500">DANA</h5>
                                            <small class="text-gray-400">Fitur ini sedang tidak tersedia</small>
                                        </div>
                                    </div>
                                </a>

                                <a href="#" class="block mb-2 pointer-events-none opacity-50 cursor-not-allowed">
                                    <div class="flex items-center gap-4 border p-3 rounded-lg bg-gray-200 shadow-sm">
                                        <img src="{{ secure_asset('assets/images/payments/linkaja.png') }}" alt="logo linkaja" class="w-12 h-12 object-contain" style="filter: grayscale(100%);">
                                        <div class="flex flex-col">
                                            <h5 class="mb-1 font-semibold text-gray-500">LinkAja</h5>
                                            <small class="text-gray-400">Fitur ini sedang tidak tersedia</small>
                                        </div>
                                    </div>
                                </a>

                                <a href="#" class="block mb-2 pointer-events-none opacity-50 cursor-not-allowed">
                                    <div class="flex items-center gap-4 border p-3 rounded-lg bg-gray-200 shadow-sm">
                                        <img src="{{ secure_asset('assets/images/payments/shopeepay.png') }}" alt="logo shopeepay" class="w-12 h-12 object-contain" style="filter: grayscale(100%);">
                                        <div class="flex flex-col">
                                            <h5 class="mb-1 font-semibold text-gray-500">ShopeePay</h5>
                                            <small class="text-gray-400">Fitur ini sedang tidak tersedia</small>
                                        </div>
                                    </div>
                                </a>

                            </div>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    function copyToClipboard(text) {
        var tempInput = document.createElement("input");
        document.body.appendChild(tempInput);

        tempInput.value = text;

        tempInput.select();
        document.execCommand("copy");

        document.body.removeChild(tempInput);

        toastSuccess("Rekening berhasil dicopy");
    }

    document.addEventListener("DOMContentLoaded", function () {
        const dropzone = document.getElementById("dropzone");
        const fileInput = document.getElementById("fileInput");
        const filePreview = document.getElementById("file-preview");
        const uploadBtn = document.getElementById("uploadBtn");
        const refreshBtn = document.getElementById("refresh-page");

        let base64File = null;
        let fileType = null;
        let fileName = null;
        let invoiceId = @json($invoiceId);

        dropzone.addEventListener("click", function () {
            fileInput.click();
        });

        ["dragover", "dragleave", "drop"].forEach(event => {
            dropzone.addEventListener(event, function (e) {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropzone.addEventListener("dragover", function () {
            dropzone.classList.add("border-blue-500", "bg-blue-50");
        });

        dropzone.addEventListener("dragleave", function () {
            dropzone.classList.remove("border-blue-500", "bg-blue-50");
        });

        dropzone.addEventListener("drop", function (e) {
            dropzone.classList.remove("border-blue-500", "bg-blue-50");
            let files = e.dataTransfer.files;
            handleFiles(files);
        });

        fileInput.addEventListener("change", function (e) {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                let file = files[0];

                if (file.size > 2 * 1024 * 1024) {
                    filePreview.innerHTML = "<p class='text-red-500'>File terlalu besar! Maksimal 2MB.</p>";
                    return;
                }

                let validTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"];
                if (!validTypes.includes(file.type)) {
                    filePreview.innerHTML = "<p class='text-red-500'>Format file tidak didukung!</p>";
                    return;
                }

                fileName = file.name;
                fileType = file.type;
                filePreview.innerHTML = `<p class="text-green-500 font-semibold">${fileName}</p>`;

                let reader = new FileReader();
                reader.onload = function (e) {
                    base64File = e.target.result;

                    if (fileType.startsWith("image/")) {
                        filePreview.innerHTML += `<img src="${base64File}" alt="Preview" class="mt-2 rounded-lg shadow-md" width="150">`;
                    }
                    else if (fileType === "application/pdf") {
                        filePreview.innerHTML += `<iframe src="${base64File}" width="200" height="150" class="mt-2 rounded-lg shadow-md"></iframe>`;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        refreshBtn.addEventListener("click", function () {
            location.reload();
        });
        uploadBtn.addEventListener("click", function () {
            if (!base64File) {
                alert("Harap pilih file terlebih dahulu!");
                return;
            }

            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');

            $.ajax({
                url: "{{ route('payment.update',':id') }}".replace(':id',invoiceId),
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    payment_proof:base64File
                },
                beforeSend: function () {
                    uploadBtn.innerHTML = "Uploading...";
                    uploadBtn.disabled = true;
                },
                success: function (response) {
                    toastSuccess('Pembelian berhasil, silakan cek status pembayaran anda');
                    uploadBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Upload';
                    uploadBtn.disabled = false;
                    filePreview.innerHTML = "";

                },
                error: function () {
                    toastError("Gagal mengunggah file.");
                    uploadBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Upload';
                    uploadBtn.disabled = false;
                }
            });
        });

    });
</script>