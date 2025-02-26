<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mediko Try Out & Bimbel Kedokteran Terbaik | Mediko.id</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ secure_asset('assets/css/landing-page.css') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @endif
    <link href="{{ secure_asset('assets/bootstrap-5.0.2-dist/css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body>
    <header class="flex align-items-center">
        <div class="container flex align-items-center justify-content-between">
            <div class="">
                <a href="#home">
                    <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="60%">
                </a>
            </div>
            <div class="flex align-middle mt-3">
                <ul class="flex gap-4 align-items-center">
                    <li>
                        <a href="#about" class="btn-nav text-lg fw-bold nav-text">Tentang Kami</a>
                    </li>
                    <li>
                        <a href="#price" class="btn-nav text-lg fw-bold nav-text">Harga</a>
                    </li>
                    <li>
                        <a href="#testimoni" class="btn-nav text-lg fw-bold nav-text">Testimoni</a>
                    </li>
                    <li>
                        <a href="#faq" class="btn-nav text-lg fw-bold nav-text">FAQ</a>
                    </li>
                    <li>
                        <a href="#contact" class="btn-nav text-lg fw-bold nav-text">Kontak</a>
                    </li>
                    <li>
                        <a href="#kebijakan-privasi" class="btn-nav text-lg fw-bold nav-text">Kebijakan Privasi</a>
                    </li>
                </ul>
            </div>
            <div>
                <a href="login" class="btn btn-outline-primary mr-2 px-4">Masuk</a>
                <a href="register" class="btn btn-primary px-4">Daftar</a>
            </div>
        </div>
    </header>
    <main>
        <div class="container" id="main">
            <section class="jumbotron flex justify-content-between align-items-center" id="home">
                <div class="jumbotron-title">
                    <h1 class="jumbotron-title_main">KINI HADIR DI</h1>
                    <h1 class="jumbotron-title_sub">SELURUH INDONESIA</h1>
                    <p class="sub-title mt-4 text-muted">
                        MEDIKO.ID adalah platform bimbingan kedokteran TERBAIK yang mendukung<br>setiap langkah perjalanan belajarmu, mulai dari masa preklinik, koas,<br> persiapan UKMPPD hingga Internship.
                    </p>
                    <a href="#" class="btn btn-primary px-5 py-2 mt-4">Gabung Sekarang</a>
                </div>
                <div>
                    <img src="{{ secure_asset('/assets/images/medics.svg') }}" alt="logo mediko" width="550rem">
                </div>
            </section>
            <section id="about">
                <div class="card" style="border-radius: 12px;">
                    <div class="card-body" style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;border-radius: 12px;">
                        <div class="row justify-content-between">
                            <div class="col-md-8">
                                <div class="flex gap-2 align-items-center mb-5">
                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" width="8%">
                                    <h4 class="fw-bold" style="color: #17ADA8;">Tentang Kami</h4>
                                </div>
                                <p class="sub-title text-muted">
                                    MEDIKO.ID adalah bimbingan kedokteran TERBAIK yang ada di Indonesia. Kami dibangun di Kota Semarang. MEDIKO.ID terdiri dari pengajar dari seluruh Indonesia! Pengajar-pengajar kami sudah terlibat dalam berbagai jenis tutorial seperti mantan asisten dosen dan mentoring UKMMPD. Pengajar-pengajar kami merupakan pengajar terbaik di bidangnya masing-masing, berpengalaman dan MENJUARAI berbagai kompetisi tingkat nasional dan internasional. Kami menyediakan program dari S1, Koas hingga UKMPPD baik secara online maupun offline! Yuk Kepoin MEDIKO.ID, MEDIKO Made the med-easy!
                                </p>
                            </div>
                            <div class="col-md-4 flex justify-content-end">
                                <img src="{{ secure_asset('/assets/images/about.svg') }}" alt="logo mediko" width="80%">
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <section id="price" class="mt-5">
                <div class="row d-flex">
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Full Premium Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 100.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        10 Paket Try Out UKMPPD @‌1500 soal Akses pembahasan langsung di aplikasi Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Intensif Pendalaman Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 50.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        26 Paket Try Out Pendalaman UKMPPD @‌3420 soal. Terdapat 13 bidang UKMPPD dan setiap bidangnya terdapat 350 butir soal. Bahan soal disusun berdasarkan soal UKMPPD yang muncul selama 3 tahun terakhir. Cocok buat kamu yang ingin intensif mempelajari bidang tertentu. Akses pembahasan langsung di aplikasi. Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Full Premium Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 100.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        10 Paket Try Out UKMPPD @‌1500 soal Akses pembahasan langsung di aplikasi Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Intensif Pendalaman Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 50.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        26 Paket Try Out Pendalaman UKMPPD @‌3420 soal. Terdapat 13 bidang UKMPPD dan setiap bidangnya terdapat 350 butir soal. Bahan soal disusun berdasarkan soal UKMPPD yang muncul selama 3 tahun terakhir. Cocok buat kamu yang ingin intensif mempelajari bidang tertentu. Akses pembahasan langsung di aplikasi. Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Full Premium Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 100.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        10 Paket Try Out UKMPPD @‌1500 soal Akses pembahasan langsung di aplikasi Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Intensif Pendalaman Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 50.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        26 Paket Try Out Pendalaman UKMPPD @‌3420 soal. Terdapat 13 bidang UKMPPD dan setiap bidangnya terdapat 350 butir soal. Bahan soal disusun berdasarkan soal UKMPPD yang muncul selama 3 tahun terakhir. Cocok buat kamu yang ingin intensif mempelajari bidang tertentu. Akses pembahasan langsung di aplikasi. Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Full Premium Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 100.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        10 Paket Try Out UKMPPD @‌1500 soal Akses pembahasan langsung di aplikasi Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card-price flex-fill">
                            <div class="flex flex-column justify-content-between h-100">
                                <div class="content">
                                    <div class="card-price_header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="fw-bold text-white">Intensif Pendalaman Bundling Batch Februari 2025</h5>
                                            </div>
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden p-2"
                                                    style="width: 60px; height: 60px;">
                                                    <img src="{{ secure_asset('/assets/images/logo-icon-mediko.webp') }}" alt="logo mediko" style="width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <div style="padding-right: 30%;">
                                                <div class="price p-2">
                                                    <h2 class="mb-0 fw-bold">Rp. 50.000</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-muted">
                                        26 Paket Try Out Pendalaman UKMPPD @‌3420 soal. Terdapat 13 bidang UKMPPD dan setiap bidangnya terdapat 350 butir soal. Bahan soal disusun berdasarkan soal UKMPPD yang muncul selama 3 tahun terakhir. Cocok buat kamu yang ingin intensif mempelajari bidang tertentu. Akses pembahasan langsung di aplikasi. Waktu pengerjaan fleksibel
                                    </p>
                                </div>
                                <div class="card-price_footer">
                                    <a href="register" class="btn btn-secondary w-full py-2 mt-3">DAFTAR SEKARANG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="testimoni" class="mt-5 text-center">
                <h1 class="testimoni-title">Apa Kata Pengguna Kami?</h1>
                <p class="sub-title mb-5 text-muted">Berikut adalah beberapa testimoni dari pengguna kami yang telah merasakan manfaat dari layanan kami</p>
                <div class="row d-flex justify-content-center gap-5">
                    <div class="col-md-3">
                        <div class="flex w-full flex-col justify-center gap-4 rounded-lg p-6 text-tertiary shadow-lg" data-sentry-component="CardAchievement" data-sentry-source-file="CardAchievement.tsx">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="rounded-full p-4" style="background-color: rgba(36,174,170,0.1);">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="h-12 w-12" data-sentry-element="Icon" data-sentry-source-file="CardAchievement.tsx" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M192 256c61.9 0 112-50.1 112-112S253.9 32 192 32 80 82.1 80 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C51.6 288 0 339.6 0 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zM480 256c53 0 96-43 96-96s-43-96-96-96-96 43-96 96 43 96 96 96zm48 32h-3.8c-13.9 4.8-28.6 8-44.2 8s-30.3-3.2-44.2-8H432c-20.4 0-39.2 5.9-55.7 15.4 24.4 26.3 39.7 61.2 39.7 99.8v38.4c0 2.2-.5 4.3-.6 6.4H592c26.5 0 48-21.5 48-48 0-61.9-50.1-112-112-112z"></path>
                                    </svg>
                                </div>
                                <p class="text-4xl font-semibold">44.238</p>
                            </div>
                            <div>
                                <p class="text-center font-medium text-muted-foreground">Jumlah Pengguna</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="flex w-full flex-col justify-center gap-4 rounded-lg p-6 text-tertiary shadow-lg" data-sentry-component="CardAchievement" data-sentry-source-file="CardAchievement.tsx">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="rounded-full p-4" style="background-color: rgba(36,174,170,0.1);">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="h-12 w-12" data-sentry-element="Icon" data-sentry-source-file="CardAchievement.tsx" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M192 256c61.9 0 112-50.1 112-112S253.9 32 192 32 80 82.1 80 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C51.6 288 0 339.6 0 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zM480 256c53 0 96-43 96-96s-43-96-96-96-96 43-96 96 43 96 96 96zm48 32h-3.8c-13.9 4.8-28.6 8-44.2 8s-30.3-3.2-44.2-8H432c-20.4 0-39.2 5.9-55.7 15.4 24.4 26.3 39.7 61.2 39.7 99.8v38.4c0 2.2-.5 4.3-.6 6.4H592c26.5 0 48-21.5 48-48 0-61.9-50.1-112-112-112z"></path>
                                    </svg>
                                </div>
                                <p class="text-4xl font-semibold">178.860</p>
                            </div>
                            <div>
                                <p class="text-center font-medium text-muted-foreground">Try Out Terlaksana</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="flex w-full flex-col justify-center gap-4 rounded-lg p-6 text-tertiary shadow-lg" data-sentry-component="CardAchievement" data-sentry-source-file="CardAchievement.tsx">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="rounded-full p-4" style="background-color: rgba(36,174,170,0.1);">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="h-12 w-12" data-sentry-element="Icon" data-sentry-source-file="CardAchievement.tsx" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M192 256c61.9 0 112-50.1 112-112S253.9 32 192 32 80 82.1 80 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C51.6 288 0 339.6 0 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zM480 256c53 0 96-43 96-96s-43-96-96-96-96 43-96 96 43 96 96 96zm48 32h-3.8c-13.9 4.8-28.6 8-44.2 8s-30.3-3.2-44.2-8H432c-20.4 0-39.2 5.9-55.7 15.4 24.4 26.3 39.7 61.2 39.7 99.8v38.4c0 2.2-.5 4.3-.6 6.4H592c26.5 0 48-21.5 48-48 0-61.9-50.1-112-112-112z"></path>
                                    </svg>
                                </div>
                                <p class="text-4xl font-semibold">43.353</p>
                            </div>
                            <div>
                                <p class="text-center font-medium text-muted-foreground">Jumlah Alumni</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="faq" class="mt-5">
                <div id="faqAccordion" class="accordion">
                    <!-- Item 1 -->
                    <div class="card">
                        <div class="card-header" style="font-size: 1.5rem;" data-toggle="collapse" data-target="#collapseOne">
                            <span class="toggle-icon me-2">▾</span> Kenapa memilih Mediko.id?
                        </div>
                        <div id="collapseOne" class="collapse show" data-parent="#faqAccordion">
                            <div class="card-body">
                                <div class="content">
                                    MEDIKO.ID adalah try-out kedokteran terbaik, yang menyediakan soal yang berkualitas, pembahasan dan video pembahasan yang berkualitas, menyesuaikan dengan silabus UKMPPD dan tingkat kesusahan yang bertingkat sesuai dengan perubahan paket soal dari waktu. Harga yang murah dengan kualitas nomor SATU! Menyesuaikan dengan kantong mahasiswa ya gengs!
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="card">
                        <div class="card-header" style="font-size: 1.5rem;" data-toggle="collapse" data-target="#collapseTwo">
                            <span class="toggle-icon me-2">▸</span> Apa kelebihan tryout di Mediko.id?
                        </div>
                        <div id="collapseTwo" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                <div class="content">
                                    Try-out MEDIKO.ID merupakan try-out yang sangat berkualitas karena:
                                    <ul>
                                        <li>
                                            1. Try-out terdiri dari 10x try-out masing-masing terdiri dari 100 soal dengan topik yang paling sering keluar di UKMPPD.
                                        </li>
                                        <li>
                                            2. Pembahasan lengkap dan menarik, beberapa soal disertai dengan video pembahasan yang super interaktif!
                                        </li>
                                        <li>
                                            3. Interface yang menarik dan terdapat applikasi di playstore yang membantu kamu bisa mengerjakan dengan gadget!
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="card">
                        <div class="card-header" style="font-size: 1.5rem;" data-toggle="collapse" data-target="#collapseThree">
                            <span class="toggle-icon me-2">▸</span> Apakah bisa mencoba tryout secara gratis?
                        </div>
                        <div id="collapseThree" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                <div class="content">
                                    Ada try-out sampel yang bisa kamu kerjain!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" style="font-size: 1.5rem;" data-toggle="collapse" data-target="#collapseThree">
                            <span class="toggle-icon me-2">▸</span> Pertanyaan lain?
                        </div>
                        <div id="collapseThree" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                <div class="content">
                                    Kamu bisa menghubungi kontak yang ada di bawah. Kami senang membantumu!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="contact" class="mt-5 mb-5">
                <div>
                    <div class="card" style="border: none;">
                        <div class="card-body border-none" style="border: none;box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;border-radius: 12px;">
                            <div class="row px-5 p-3">
                                <div class="col-md-6 flex justify-content-center">
                                    <img src="{{ secure_asset('/assets/images/doctor.svg') }}" alt="logo mediko" style="width: 80%; height: auto;">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="fw-bold" style="color: #17ADA8;">Hubungi Kami</h3>
                                    <h5>Kami Menjawab Pertanyaan Anda!</h5>
                                    <div class="mt-3">
                                        <a href="https://www.instagram.com/medikoind" class="flex gap-4 p-2 align-items-center mb-3" style="text-decoration: none;border: black 2px solid;border-radius:12px;">
                                            <img src="{{ secure_asset('/assets/images/instagram.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                            <h6 class="text-black mt-2">@medikoind (Mediko Tryout)</h6>
                                        </a>
                                        <a href="https://www.instagram.com/mediko.id" class="flex gap-4 p-2 align-items-center mb-3" style="text-decoration: none;border: black 2px solid;border-radius:12px;">
                                            <img src="{{ secure_asset('/assets/images/instagram.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                            <h6 class="text-black mt-2">@mediko.id (Mediko Bimble)</h6>
                                        </a>
                                        <a href="https://wa.me/6281215371635" class="flex gap-4 p-2 align-items-center mb-3" style="text-decoration: none;border: black 2px solid;border-radius:12px;">
                                            <img src="{{ secure_asset('/assets/images/whatsapp.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                            <h6 class="text-black mt-2">+6281215371635 (Mediko Tryout)</h6>
                                        </a>
                                        <a href="https://wa.me/6282134038758" class="flex gap-4 p-2 align-items-center mb-3" style="text-decoration: none;border: black 2px solid;border-radius:12px;">
                                            <img src="{{ secure_asset('/assets/images/whatsapp.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                            <h6 class="text-black mt-2">+6282134038758 (Mediko Bimbel)</h6>
                                        </a>
                                        <a href="https://t.me/medikoindonesia" class="flex gap-4 p-2 align-items-center mb-3" style="text-decoration: none;border: black 2px solid;border-radius:12px;">
                                            <img src="{{ secure_asset('/assets/images/telegram.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                            <h6 class="text-black mt-2">Mediko Indonesia</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="container hidden" id="policy" style="padding-top: 80px;">
            <div class="prose max-w-none">
                <h1 class="fw-bold mt-3 mb-3">Kebijakan Privasi</h1>
                <p>Terakhir diperbarui: 09 Januari 2025</p>
                <p>Kebijakan Privasi ini menjelaskan kebijakan dan prosedur Kami terkait pengumpulan, penggunaan, dan pengungkapan informasi Anda saat Anda menggunakan Layanan dan memberi tahu Anda tentang hak privasi Anda dan bagaimana hukum melindungi Anda.</p>
                <p>Kami menggunakan Data Pribadi Anda untuk menyediakan dan meningkatkan Layanan. Dengan menggunakan Layanan, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan Kebijakan Privasi ini. Kebijakan Privasi ini dibuat dengan bantuan <a href="https://www.termsfeed.com/privacy-policy-generator/" target="_blank">Generator Kebijakan Privasi</a>.</p>
                <h2 class="fw-bold mt-3 mb-3">Interpretasi dan Definisi</h2>
                <h3 class="fw-bold mt-3 mb-3">Interpretasi</h3>
                <p>Kata-kata yang huruf awalnya diawali dengan huruf kapital memiliki makna yang didefinisikan dalam kondisi berikut. Definisi berikut ini memiliki makna yang sama, terlepas dari apakah definisi tersebut muncul dalam bentuk tunggal atau jamak.</p>
                <h3 class="fw-bold mt-3 mb-3">Definisi</h3>
                <p>Untuk tujuan Kebijakan Privasi ini:</p>
                <ul>
                    <li>
                        <p><strong>Akun</strong> berarti akun unik yang dibuat untuk Anda guna mengakses Layanan kami atau bagian dari Layanan kami.</p>
                    </li>
                    <li>
                        <p><strong>Afiliasi</strong> berarti entitas yang mengendalikan, dikendalikan oleh atau berada di bawah kendali bersama dengan suatu pihak, di mana "kendali" berarti kepemilikan 50% atau lebih dari saham, kepentingan ekuitas, atau sekuritas lain yang berhak memberikan suara untuk pemilihan direktur atau otoritas pengelola lainnya.</p>
                    </li>
                    <li>
                        <p><strong>Aplikasi</strong> mengacu pada Mediko.id, program perangkat lunak yang disediakan oleh Perusahaan.</p>
                    </li>
                    <li>
                        <p><strong>Perusahaan</strong> (disebut sebagai "Perusahaan", "Kami", "Kita" atau "Milik Kami" dalam Perjanjian ini) mengacu pada Mediko.id.</p>
                    </li>
                    <li>
                        <p><strong>Negara</strong> mengacu pada: Indonesia</p>
                    </li>
                    <li>
                        <p><strong>Perangkat</strong> berarti perangkat apa pun yang dapat mengakses Layanan seperti komputer, ponsel, atau tablet digital.</p>
                    </li>
                    <li>
                        <p><strong>Data Pribadi</strong> adalah informasi apa pun yang berkaitan dengan individu yang teridentifikasi atau dapat diidentifikasi.</p>
                    </li>
                    <li>
                        <p><strong>Layanan</strong> mengacu pada Aplikasi.</p>
                    </li>
                    <li>
                        <p><strong>Penyedia Layanan</strong> berarti setiap orang atau badan hukum yang memroses data atas nama Perusahaan. Ini mengacu pada perusahaan pihak ketiga atau individu yang dipekerjakan oleh Perusahaan untuk memfasilitasi Layanan, untuk menyediakan Layanan atas nama Perusahaan, untuk melakukan layanan yang terkait dengan Layanan atau untuk membantu Perusahaan dalam menganalisis bagaimana Layanan digunakan.</p>
                    </li>
                    <li>
                        <p><strong>Data Penggunaan</strong> mengacu pada data yang dikumpulkan secara otomatis, baik yang dihasilkan oleh penggunaan Layanan atau dari infrastruktur Layanan itu sendiri (misalnya, durasi kunjungan halaman).</p>
                    </li>
                    <li>
                        <p><strong>Anda</strong> berarti individu yang mengakses atau menggunakan Layanan, atau perusahaan, atau badan hukum lain yang atas nama individu tersebut mengakses atau menggunakan Layanan, sebagaimana berlaku.</p>
                    </li>
                </ul>
                <h2 class="fw-bold mt-3 mb-3">Pengumpulan dan Penggunaan Data Pribadi Anda</h2>
                <h3 class="fw-bold mt-3 mb-3">Jenis Data yang Dikumpulkan</h3>
                <h4 class="fw-bold mt-3 mb-3">Data Pribadi</h4>
                <p>Saat menggunakan Layanan Kami, Kami mungkin meminta Anda untuk memberikan Kami informasi pengenal pribadi tertentu yang dapat digunakan untuk menghubungi atau mengidentifikasi Anda. Informasi yang dapat mengidentifikasi pribadi dapat mencakup, tetapi tidak terbatas pada:</p>
                <ul>
                    <li>
                        <p>Alamat email</p>
                    </li>
                    <li>
                        <p>Nama depan dan nama belakang</p>
                    </li>
                    <li>
                        <p>Nomor telepon</p>
                    </li>
                    <li>
                        <p>Data Penggunaan</p>
                    </li>
                </ul>
                <h4 class="fw-bold mt-3 mb-3">Data Penggunaan</h4>
                <p>Data Penggunaan dikumpulkan secara otomatis saat menggunakan Layanan.</p>
                <p>Data Penggunaan dapat mencakup informasi seperti alamat Protokol Internet Perangkat Anda (misalnya alamat IP), jenis browser, versi browser, halaman Layanan kami yang Anda kunjungi, waktu dan tanggal kunjungan Anda, waktu yang dihabiskan di halaman tersebut, pengenal perangkat unik, dan data diagnostik lainnya.</p>
                <p>Saat Anda mengakses Layanan melalui perangkat seluler, Kami dapat mengumpulkan informasi tertentu secara otomatis, termasuk, namun tidak terbatas pada, jenis perangkat seluler yang Anda gunakan, ID unik perangkat seluler Anda, alamat IP perangkat seluler Anda, sistem operasi seluler Anda, jenis peramban Internet seluler yang Anda gunakan, pengenal perangkat unik, dan data diagnostik lainnya.</p>
                <p>Kami juga dapat mengumpulkan informasi yang dikirim peramban Anda setiap kali Anda mengunjungi Layanan kami atau saat Anda mengakses Layanan melalui perangkat seluler.</p>
                <h4>Informasi yang Dikumpulkan saat Menggunakan Aplikasi</h4>
                <p>Saat menggunakan Aplikasi Kami, untuk menyediakan fitur Aplikasi Kami, Kami dapat mengumpulkan, dengan izin Anda sebelumnya:</p>
                <ul>
                    <li>Gambar dan informasi lain dari kamera dan pustaka foto Perangkat Anda</li>
                </ul>
                <p>Kami menggunakan informasi ini untuk menyediakan fitur Layanan Kami, untuk meningkatkan dan menyesuaikan Layanan Kami. Informasi tersebut dapat diunggah ke server Perusahaan dan/atau server Penyedia Layanan atau dapat disimpan begitu saja di perangkat Anda.</p>
                <p>Anda dapat mengaktifkan atau menonaktifkan akses ke informasi ini kapan saja, melalui pengaturan Perangkat Anda.</p>
                <h3 class="fw-bold mt-3 mb-3">Penggunaan Data Pribadi Anda</h3>
                <p>Perusahaan dapat menggunakan Data Pribadi untuk tujuan berikut:</p>
                <ul>
                    <li>
                        <p><strong>Untuk menyediakan dan memelihara Layanan kami</strong>, termasuk untuk memantau penggunaan Layanan kami.</p>
                    </li>
                    <li>
                        <p><strong>Untuk mengelola Akun Anda:</strong> untuk mengelola pendaftaran Anda sebagai pengguna Layanan. Data Pribadi yang Anda berikan dapat memberi Anda akses ke berbagai fungsi Layanan yang tersedia bagi Anda sebagai pengguna terdaftar.</p>
                    </li>
                    <li>
                        <p><strong>Untuk pelaksanaan kontrak:</strong> pengembangan, pemenuhan, dan pelaksanaan kontrak pembelian untuk produk, barang, atau layanan yang telah Anda beli atau kontrak lain dengan Kami melalui Layanan.</p>
                    </li>
                    <li>
                        <p><strong>Untuk menghubungi Anda:</strong> Untuk menghubungi Anda melalui email, panggilan telepon, SMS, atau bentuk komunikasi elektronik lain yang setara, seperti pemberitahuan push aplikasi seluler mengenai pembaruan atau komunikasi informatif yang terkait dengan fungsi, produk, atau layanan yang dikontrak, termasuk pembaruan keamanan, bila perlu atau wajar untuk penerapannya.</p>
                    </li>
                    <li>
                        <p><strong>Untuk memberi Anda</strong> berita, penawaran khusus, dan informasi umum tentang barang, layanan, dan acara lain yang kami tawarkan yang serupa dengan yang telah Anda beli atau tanyakan kecuali Anda telah memilih untuk tidak menerima informasi tersebut.</p>
                    </li>
                    <li>
                        <p><strong>Untuk mengelola permintaan Anda:</strong> Untuk menghadiri dan mengelola permintaan Anda kepada Kami.</p>
                    </li>
                    <li>
                        <p><strong>Untuk transfer bisnis:</strong> Kami dapat menggunakan informasi Anda untuk mengevaluasi atau melakukan penggabungan, divestasi, restrukturisasi, reorganisasi, pembubaran, atau penjualan atau transfer lain atas sebagian atau seluruh aset Kami, baik sebagai usaha yang masih berjalan atau sebagai bagian dari kebangkrutan, likuidasi, atau proses serupa, di mana Data Pribadi yang Kami miliki tentang pengguna Layanan kami termasuk di antara aset yang ditransfer.</p>
                    </li>
                    <li>
                        <p><strong>Untuk tujuan lain</strong>: Kami dapat menggunakan informasi Anda untuk tujuan lain, seperti analisis data, mengidentifikasi tren penggunaan, menentukan efektivitas kampanye promosi kami, dan untuk mengevaluasi dan meningkatkan Layanan, produk, layanan, pemasaran, dan pengalaman Anda.</p>
                    </li>
                </ul>
                <p>Kami dapat membagikan informasi pribadi Anda dalam situasi berikut:</p>
                <ul>
                    <li><strong>Dengan Penyedia Layanan:</strong> Kami dapat membagikan informasi pribadi Anda dengan Penyedia Layanan untuk memantau dan menganalisis penggunaan Layanan kami, untuk menghubungi Anda.</li>
                    <li><strong>Untuk transfer bisnis:</strong> Kami dapat membagikan atau mentransfer informasi pribadi Anda sehubungan dengan, atau selama negosiasi, setiap penggabungan, penjualan aset Perusahaan, pembiayaan, atau akuisisi semua atau sebagian dari bisnis Kami ke perusahaan lain.</li>
                    <li><strong>Dengan Afiliasi:</strong> Kami dapat membagikan informasi Anda dengan afiliasi Kami, dalam hal ini kami akan meminta afiliasi tersebut untuk menghormati Kebijakan Privasi ini. Afiliasi termasuk perusahaan induk Kami dan anak perusahaan lainnya, mitra usaha patungan, atau perusahaan lain yang Kami kendalikan atau yang berada di bawah kendali bersama dengan Kami.</li>
                    <li><strong>Dengan mitra bisnis:</strong> Kami dapat membagikan informasi Anda dengan mitra bisnis kami untuk menawarkan produk, layanan, atau promosi tertentu kepada Anda.</li>
                    <li><strong>Dengan pengguna lain:</strong> saat Anda membagikan informasi pribadi atau berinteraksi di area publik dengan pengguna lain, informasi tersebut dapat dilihat oleh semua pengguna dan dapat didistribusikan ke publik di luar.</li>
                    <li><strong>Dengan persetujuan Anda</strong>: Kami dapat mengungkapkan informasi pribadi Anda untuk tujuan lain dengan persetujuan Anda.</li>
                </ul>
                <h3 class="fw-bold mt-3 mb-3">Penyimpanan Data Pribadi Anda</h3>
                <p>Perusahaan akan menyimpan Data Pribadi Anda hanya selama diperlukan untuk tujuan yang ditetapkan dalam Kebijakan Privasi ini. Kami akan menyimpan dan menggunakan Data Pribadi Anda sejauh yang diperlukan untuk mematuhi kewajiban hukum kami (misalnya, jika kami diharuskan menyimpan data Anda untuk mematuhi hukum yang berlaku), menyelesaikan perselisihan, dan menegakkan perjanjian dan kebijakan hukum kami.</p>
                <p>Perusahaan juga akan menyimpan Data Penggunaan untuk keperluan analisis internal. Data Penggunaan umumnya disimpan untuk jangka waktu yang lebih pendek, kecuali jika data ini digunakan untuk memperkuat keamanan atau meningkatkan fungsionalitas Layanan Kami, atau Kami secara hukum diwajibkan untuk menyimpan data ini untuk jangka waktu yang lebih lama.</p>
                <h3 class="fw-bold mt-3 mb-3">Pengalihan Data Pribadi Anda</h3>
                <p>Informasi Anda, termasuk Data Pribadi, diproses di kantor operasional Perusahaan dan di tempat lain tempat pihak yang terlibat dalam pemrosesan berada. Artinya, informasi ini dapat dialihkan ke — dan disimpan di — komputer yang berlokasi di luar negara bagian, provinsi, negara, atau yurisdiksi pemerintahan Anda yang undang-undang perlindungan datanya mungkin berbeda dari yurisdiksi Anda.</p>
                <p>Persetujuan Anda terhadap Kebijakan Privasi ini diikuti dengan penyerahan informasi tersebut oleh Anda merupakan persetujuan Anda terhadap pengalihan tersebut.</p>
                <p>Perusahaan akan mengambil semua langkah yang diperlukan secara wajar untuk memastikan bahwa data Anda diperlakukan dengan aman dan sesuai dengan Kebijakan Privasi ini dan tidak ada pengalihan Data Pribadi Anda yang akan dilakukan ke organisasi atau negara mana pun kecuali ada kontrol yang memadai termasuk keamanan data Anda dan informasi pribadi lainnya.</p>
                <h3 class="fw-bold mt-3 mb-3">Hapus Data Pribadi Anda</h3>
                <p>Anda berhak menghapus atau meminta Kami membantu menghapus Data Pribadi yang telah Kami kumpulkan tentang Anda.</p>
                <p>Layanan Kami dapat memberi Anda kemampuan untuk menghapus informasi tertentu tentang Anda dari dalam Layanan.</p>
                <p>Anda dapat memperbarui, mengubah, atau menghapus informasi Anda kapan saja dengan masuk ke Akun Anda, jika Anda memilikinya, dan mengunjungi bagian pengaturan akun yang memungkinkan Anda mengelola informasi pribadi Anda. Anda juga dapat menghubungi Kami untuk meminta akses, memperbaiki, atau menghapus informasi pribadi apa pun yang telah Anda berikan kepada Kami.</p>
                <p>Namun, perlu diperhatikan bahwa Kami mungkin perlu menyimpan informasi tertentu ketika kami memiliki kewajiban hukum atau dasar hukum untuk melakukannya.</p>
                <h3 class="fw-bold mt-3 mb-3">Pengungkapan Data Pribadi Anda</h3>
                <h4 class="fw-bold mt-3 mb-3">Transaksi Bisnis</h4>
                <p>Jika Perusahaan terlibat dalam penggabungan, akuisisi, atau penjualan aset, Data Pribadi Anda dapat ditransfer. Kami akan memberikan pemberitahuan sebelum Data Pribadi Anda ditransfer dan menjadi subjek Kebijakan Privasi yang berbeda.</p>
                <h4 class="fw-bold mt-3 mb-3">Penegakan hukum</h4>
                <p>Dalam keadaan tertentu, Perusahaan mungkin diharuskan untuk mengungkapkan Data Pribadi Anda jika diharuskan oleh hukum atau sebagai tanggapan atas permintaan yang sah oleh otoritas publik (misalnya pengadilan atau lembaga pemerintah).</p>
                <h4 class="fw-bold mt-3 mb-3">Persyaratan hukum lainnya</h4>
                <p>Perusahaan dapat mengungkapkan Data Pribadi Anda dengan keyakinan yang baik bahwa tindakan tersebut diperlukan untuk:</p>
                <ul>
                    <li>Mematuhi kewajiban hukum</li>
                    <li>Melindungi dan mempertahankan hak atau properti Perusahaan</li>
                    <li>Mencegah atau menyelidiki kemungkinan pelanggaran sehubungan dengan Layanan</li>
                    <li>Melindungi keselamatan pribadi Pengguna Layanan atau publik</li>
                    <li>Melindungi dari tanggung jawab hukum</li>
                </ul>
                <h3 class="fw-bold mt-3 mb-3">Keamanan Data Pribadi Anda</h3>
                <p>Keamanan Data Pribadi Anda penting bagi Kami, tetapi ingatlah bahwa tidak ada metode transmisi melalui Internet, atau metode penyimpanan elektronik yang 100% aman. Meskipun Kami berupaya menggunakan cara yang dapat diterima secara komersial untuk melindungi Data Pribadi Anda, Kami tidak dapat menjamin keamanannya secara mutlak.</p>
                <h2 class="fw-bold mt-3 mb-3">Privasi Anak</h2>
                <p>Layanan Kami tidak ditujukan kepada siapa pun yang berusia di bawah 13 tahun. Kami tidak secara sengaja mengumpulkan informasi identitas pribadi dari siapa pun yang berusia di bawah 13 tahun. Jika Anda adalah orang tua atau wali dan Anda mengetahui bahwa Anak Anda telah memberikan Data Pribadi kepada Kami, silakan hubungi Kami. Jika Kami mengetahui bahwa Kami telah mengumpulkan Data Pribadi dari siapa pun yang berusia di bawah 13 tahun tanpa verifikasi persetujuan orang tua, Kami mengambil langkah-langkah untuk menghapus informasi tersebut dari server Kami.</p>
                <p>Jika Kami perlu mengandalkan persetujuan sebagai dasar hukum untuk memproses informasi Anda dan negara Anda mengharuskan persetujuan dari orang tua, Kami mungkin memerlukan persetujuan orang tua Anda sebelum Kami mengumpulkan dan menggunakan informasi tersebut.</p>
                <h2 class="fw-bold mt-3 mb-3">Tautan ke Situs Web Lain</h2>
                <p>Layanan Kami mungkin berisi tautan ke situs web lain yang tidak dioperasikan oleh Kami. Jika Anda mengeklik tautan pihak ketiga, Anda akan diarahkan ke situs pihak ketiga tersebut. Kami sangat menyarankan Anda untuk meninjau Kebijakan Privasi setiap situs yang Anda kunjungi.</p>
                <p>Kami tidak memiliki kendali atas dan tidak bertanggung jawab atas konten, kebijakan privasi, atau praktik situs atau layanan pihak ketiga mana pun.</p>
                <h2 class="fw-bold mt-3 mb-3">Perubahan pada Kebijakan Privasi ini</h2>
                <p>Kami dapat memperbarui Kebijakan Privasi Kami dari waktu ke waktu. Kami akan memberi tahu Anda tentang perubahan apa pun dengan mengeposkan Kebijakan Privasi baru di halaman ini.</p>
                <p>Kami akan memberi tahu Anda melalui email dan/atau pemberitahuan yang mencolok di Layanan Kami, sebelum perubahan tersebut berlaku dan memperbarui tanggal "Terakhir diperbarui" di bagian atas Kebijakan Privasi ini.</p>
                <p>Anda disarankan untuk meninjau Kebijakan Privasi ini secara berkala untuk mengetahui perubahan apa pun. Perubahan pada Kebijakan Privasi ini berlaku saat diposkan di halaman ini.</p>
                <h2 class="fw-bold mt-3 mb-3">Hubungi Kami</h2>
                <p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, Anda dapat menghubungi kami:</p>
                Dengan mengunjungi halaman ini di situs web kami: <a href="https://mediko.id/contact" rel="external nofollow noopener" target="_blank">https://mediko.id/contact</a></li>
            </div>
        </div>
    </main>
    <footer class="mt-20 h-64 bg-footer-pattern" data-sentry-component="Footer" data-sentry-source-file="Footer.tsx"></footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {
            const main = $("#main");
            const policy = $("#policy");
            const hash = window.location.hash;

            if (hash == '#kebijakan-privasi') {
                policy.removeClass('hidden');
                main.addClass('hidden');
            } else {
                policy.addClass('hidden');
                main.removeClass('hidden');
            }

            $(".card-header").click(function() {
                let icon = $(this).find(".toggle-icon");
                $(".toggle-icon").text("▸");
                if (!$(this).next(".collapse").hasClass("show")) {
                    icon.text("▾");
                }
            });

            $(".btn-nav").click(function() {
                const href = $(this).attr("href");

                if (href == '#kebijakan-privasi') {
                    policy.removeClass('hidden');
                    main.addClass('hidden');
                } else {
                    policy.addClass('hidden');
                    main.removeClass('hidden');
                }
            });


        });
    </script>
</body>

</html>