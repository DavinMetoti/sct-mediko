<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mediko Try Out & Bimbel Kedokteran Terbaik | Mediko.id</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ secure_asset('assets/css/landing-page.css') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @endif
    <link href="{{ secure_asset('assets/bootstrap-5.0.2-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header class="flex align-items-center">
        <div class="container flex align-items-center justify-content-between">
            <div class="flex">
                <a href="#home">
                    <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="60%">
                </a>
                <ul class="d-md-flex d-none gap-4 align-items-center mt-2">
                    <li>
                        <a href="#about" class="btn-nav text-lg nav-text">Tentang Kami</a>
                    </li>
                    <li>
                        <a href="#price" class="btn-nav text-lg nav-text">Harga</a>
                    </li>
                    <li>
                        <a href="#testimoni" class="btn-nav text-lg nav-text">Testimoni</a>
                    </li>
                    <li>
                        <a href="#faq" class="btn-nav text-lg nav-text">FAQ</a>
                    </li>
                    <li>
                        <a href="#kebijakan-privasi" class="btn-nav text-lg nav-text">Privasi</a>
                    </li>
                </ul>
            </div>
            <div class="d-md-block d-none">
                <a href="login" class="btn btn-outline-blue mr-2 px-4">Masuk</a>
                <a href="register" class="btn-blue px-4">Daftar</a>
            </div>
            <div class="d-block d-md-none">
                <button id="toggleSidebar" class="btn toggle-btn"><i class="fas fa-bars text-xl"></i></button>
            </div>
        </div>
    </header>
    <div class="sidebar text-center">
        <a href="#home" class="flex justify-content-center">
            <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="60%">
        </a>
        <div class="flex gap-2 mt-5 w-full">
            <a href="login" class="btn btn-sm btn-outline-primary w-100">Masuk</a>
            <a href="register" class="btn btn-sm btn-primary w-100">Daftar</a>
        </div>
        <div class="mt-3 w-full">
            <ul class="gap-4 align-items-center mt-2">
                <li class="mb-3 text-right">
                    <a href="#about" class="btn-nav text-lg nav-text">Tentang Kami</a>
                </li>
                <li class="mb-3 text-right">
                    <a href="#price" class="btn-nav text-lg nav-text">Harga</a>
                </li>
                <li class="mb-3 text-right">
                    <a href="#testimoni" class="btn-nav text-lg nav-text">Testimoni</a>
                </li>
                <li class="mb-3 text-right">
                    <a href="#faq" class="btn-nav text-lg nav-text">FAQ</a>
                </li>
                <li class="mb-3 text-right">
                    <a href="#kebijakan-privasi" class="btn-nav text-lg nav-text">Privasi</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="overlay"></div>
    <main>
        <div id="main">
            <section class="jumbotron mb-5" id="home">
                <div class="container min-h-screen">
                    <div class="flex justify-content-between align-items-center">
                        <div class="jumbotron-title">
                            <div class="mb-3 badge-custom">
                                Hadir di seluruh Indonesia
                            </div>
                            <h2 class="jumbotron-title_main">Try Out & Bimbel SCT UKMPPD</h2>
                            <h1 class="jumbotron-title_sub">Pertama di Indonesia</h1>
                            <p class="sub-title mt-4 text-muted mb-5">
                                MEDIKO.ID adalah platform bimbingan kedokteran TERBAIK yang <br> mendukung setiap langkah perjalanan belajarmu, mulai dari masa<br> preklinik, koas, persiapan UKMPPD hingga Internship.                    </p>
                            <a href="register" class="btn-blue px-5 py-2">Gabung Sekarang</a>
                        </div>
                        <div class="d-none d-md-block" style="position: relative; width: 800px; display: flex; justify-content: flex-end; align-items: center;">
                            <img src="{{ secure_asset('/assets/images/bg-grid.png') }}" alt="bg mediko"
                                style="position: absolute; top: -50px; right: 0; width: 100vw; transform: scale(1.5); z-index: -1;">
                            <img src="{{ secure_asset('/assets/images/medics.svg') }}" alt="logo mediko"
                                style="position: relative; width: 600px; z-index: 1;">
                        </div>
                    </div>
                    <div class="jumbotron-list md-mt-0 mt-5">
                        <div class="d-md-flex justify-content-around w-100">
                            <div class="flex gap-3 mb-md-0 mb-3">
                                <i class="fas fa-check me-1" style="display: flex;justify-content: center;align-items: center;text-align: center;width: 24px;height: 24px;background-color: #3273F6;color: white;border-radius: 50%;font-size: 12px;"></i>Bimbingan Komprehensif
                            </div>
                            <div class="flex gap-3 mb-md-0 mb-3">
                                <i class="fas fa-check me-1" style="display: flex;justify-content: center;align-items: center;text-align: center;width: 24px;height: 24px;background-color: #3273F6;color: white;border-radius: 50%;font-size: 12px;"></i>Materi Berkualitas & Terstruktur
                            </div>
                            <div class="flex gap-3 mb-md-0 mb-3">
                                <i class="fas fa-check me-1" style="display: flex;justify-content: center;align-items: center;text-align: center;width: 24px;height: 24px;background-color: #3273F6;color: white;border-radius: 50%;font-size: 12px;"></i>Simulasi Tryout dengan Sistem SCT
                            </div>
                            <div class="flex gap-3 mb-md-0 mb-3">
                                <i class="fas fa-check me-1" style="display: flex;justify-content: center;align-items: center;text-align: center;width: 24px;height: 24px;background-color: #3273F6;color: white;border-radius: 50%;font-size: 12px;"></i>Komunitas & Dukungan Belajar
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="fw-bold">Tentang Mediko.ID</h1>
                        </div>
                        <div class="col-md-6">
                            <p class="sub-title">
                                MEDIKO.ID adalah bimbingan kedokteran terbaik di Indonesia, berbasis di Semarang dengan pengajar dari seluruh negeri.
                            </p>
                            <br>
                            <p class="sub-title mb-5">
                                Tim kami terdiri dari mantan asisten dosen dan mentor UKMPPD yang berpengalaman serta berprestasi di tingkat nasional dan internasional. Kami menawarkan program untuk S1, Koas, hingga UKMPPD, baik online maupun offline.
                            </p>
                            <a href="register" class="btn-blue">Gabung Sekarang</a>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="container">
                    <div class="text-center">
                        <h2 class="fw-medium">Raih Skor Terbaik UKMPPD dengan Mediko!</h2>
                        <p style="font-size: 14px;">
                            Platform pembelajaran kedokteran terlengkap dengan metode pembelajaran yang efektif
                        </p>
                    </div>
                    <div class="d-md-flex justify-content-between gap-4 mt-5">
                        <div class="card-1 mb-md-0 mb-3">
                            <div>
                                <img src="{{ secure_asset('/assets/images/image-2.jpg') }}" alt="logo mediko"
                                    style="width: 100%; height: 80%; object-fit: cover; border-radius: 8px;">
                            </div>
                            <div class="mt-4">
                                <h4>Tutor Berkualitas Terpercaya</h4>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-md-5 flex flex-column justify-content-end h-100">
                                    <h4 class="fw-bold mb-0">50+</h4>
                                    <p class="mb-0" style="font-size: 12px;">Tutor dari ahli</p>
                                </div>
                                <div class="col-md-7 flex flex-column justify-content-end md-mt-0 mt-3">
                                    <a href="register" class="btn-blue" style="font-size: 14px;">Gabung Sekarang</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-1 mb-md-0 mb-3">
                            <div>
                                <img src="{{ secure_asset('/assets/images/image-1.jpg') }}" alt="logo mediko"
                                    style="width: 100%; height: 80%; object-fit: cover; border-radius: 8px;">
                            </div>
                            <div class="mt-4">
                                <h4>Bank Soal Terstruktur</h4>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-md-5 flex flex-column justify-content-end h-100">
                                    <h4 class="fw-bold mb-0">10,000+</h4>
                                    <p class="mb-0" style="font-size: 12px;">Soal Terbaru</p>
                                </div>
                                <div class="col-md-7 flex flex-column justify-content-end md-mt-0 mt-3">
                                    <a href="register" class="btn-blue" style="font-size: 14px;">Gabung Sekarang</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-1 mb-md-0 mb-3">
                            <div>
                                <img src="{{ secure_asset('/assets/images/image-3.jpg') }}" alt="logo mediko"
                                    style="width: 100%; height: 80%; object-fit: cover; border-radius: 8px;">
                            </div>
                            <div class="mt-4">
                                <h4>Komunitas Belajar Interaktif</h4>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-md-5 flex flex-column justify-content-end h-100">
                                    <h4 class="fw-bold mb-0">44,000+</h4>
                                    <p class="mb-0" style="font-size: 12px;">Jumlah Pengguna</p>
                                </div>
                                <div class="col-md-7 flex flex-column justify-content-end md-mt-0 mt-3">
                                    <a href="register" class="btn-blue" style="font-size: 14px;">Gabung Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="price" class="mt-5">
                <div class="container">
                    <div class="text-center">
                        <h2 class="mb-0">Pilihan Paket Belajar Mediko.id</h2>
                        <h2>Batch Februari 2025</h2>
                        <p>
                            Dapatkan akses ke program bimbingan terbaik dengan metode belajar yang efektif & terstruktur.<br> Pilih paket yang sesuai dengan kebutuhanmu & raih skor terbaik!
                        </p>
                    </div>
                    <div class="row d-flex pb-5">
                    @foreach($packages as $package)
                        <div class="col-md-4 d-flex">
                            <div class="card-price flex-fill">
                                <div class="flex justify-content-between flex-column h-100">
                                    <div>
                                        <div style="color: #3273F6; padding: 2px 20px; border-radius: 100px; border: #3273F6 solid 2px; display: inline-block; font-size: 14px !important;" class="mb-3">
                                            {{ $package->category }}
                                        </div>
                                        <h5 class="fw-bold">{{ $package->name }}</h5>
                                        <p class="sub-title-price my-3">{{ $package->description }}</p>
                                        <h3 style="color: #3273F6;" class="fw-bold my-3">
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                            <span class="text-muted text-lg fw-normal">/Periode</span>
                                        </h3>
                                        <hr>
                                        <div class="w-100 flex my-3">
                                            <i class="fas fa-check me-3" style="display: flex;justify-content: center;align-items: center;text-align: center;width: 24px;height: 24px;background-color: #3273F6;color: white;border-radius: 50%;font-size: 12px;"></i>Akses Pembahasan Live
                                        </div>
                                        <div class="w-100 flex my-3">
                                            <i class="fas fa-check me-3" style="display: flex;justify-content: center;align-items: center;text-align: center;width: 24px;height: 24px;background-color: #3273F6;color: white;border-radius: 50%;font-size: 12px;"></i>{{$package->questions->count()}} Paket Tryout
                                        </div>
                                        <div class="w-100 flex my-3">
                                            <i class="fas fa-check me-3" style="display: flex; justify-content: center; align-items: center; text-align: center; width: 24px; height: 24px; background-color: #3273F6; color: white; border-radius: 50%; font-size: 12px;"></i>{{ $package->questions->sum(fn($q) => $q->questionDetail->count()) }} Soal Latihan Terbaru
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex flex-column justify-content-end h-full">
                                            <a href="register" class="btn-blue my-3 flex-grow-1 text-center">Gabung Sekarang</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </section>
            <section id="faq" class="mt-5">
                <div class="container">
                    <div class="text-center">
                        <h2 class="mb-0">Yang Sering Ditanyakan</h2>
                        <p class="sub-title mb-5 text-muted mt-3">Punya pertanyaan tentang layanan kami? Temukan jawabannya di sini! Kami telah merangkum<br> pertanyaan yang paling sering diajukan untuk membantumu memahami MEDIKO.ID</p>
                    </div>
                    <div id="faqAccordion" class="accordion">
                        <!-- Item 1 -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center fw-bold text-xl" data-toggle="collapse" data-target="#collapseOne">
                            Kenapa memilih Mediko.id?
                                <span class="text-right toggle-icon ms-auto">-</span>
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
                            <div class="card-header d-flex justify-content-between align-items-center fw-bold text-xl" data-toggle="collapse" data-target="#collapseTwo">
                            Apa kelebihan tryout di Mediko.id?
                            <span class="text-right toggle-icon ms-auto">+</span>
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
                            <div class="card-header d-flex justify-content-between align-items-center fw-bold text-xl" data-toggle="collapse" data-target="#collapseThree">
                                Apakah bisa mencoba tryout secara gratis?
                                <span class="text-right toggle-icon ms-auto">+</span>
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
                            <div class="card-header d-flex justify-content-between align-items-center fw-bold text-xl" style="background-image: url('assets/images/bg-accordion.jpg');background-size: cover;background-position: center;background-repeat: no-repeat;" data-toggle="collapse" data-target="#collapseFour">
                                Hubungi untuk pertanyaan lebih lanjut
                                <span class="text-right ms-auto">
                                    <i class="fas fa-phone text-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="contact" class="mt-5 mb-5">
                <div class="container">
                    <div class="row p-3">
                        <div class="col-md-6 mb-mb-0 mb-3 flex justify-content-center" style="font-size: 1.5rem;background-image: url('assets/images/bg-contact.png');background-size: 120%;background-position: center;background-repeat: no-repeat;border-radius: 20px;">
                            <div class="my-auto text-center py-3">
                                <div class="mb-3 badge-custom">
                                    Contact Us
                                </div>
                                <h2 class="mb-0 fw-medium">Siap untuk yang terbaik?</h2>
                                <h2 class="fw-medium">Hubungi Kami</h2>
                                <p class="sub-title fw-normal">Mari rencanakan masa depan impian Anda dengan<br> penawaran eksklusif dan pengalaman yang tak terlupakan.</p>
                            </div>
                        </div>
                        <div class="col-md-6 px-md-2 px-0">
                            <div class="flex gap-3 flex-column justify-content-between">
                                <a href="https://www.instagram.com/medikoind" class="flex gap-4 p-3 align-items-center" style="text-decoration: none;border: gainsboro 1px solid;border-radius:12px;">
                                    <img src="{{ secure_asset('/assets/images/instagram.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                    <h6 class="text-black mt-2">@medikoind (Mediko Tryout)</h6>
                                </a>
                                <a href="https://www.instagram.com/mediko.id" class="flex gap-4 p-3 align-items-center" style="text-decoration: none;border: gainsboro 1px solid;border-radius:12px;">
                                    <img src="{{ secure_asset('/assets/images/instagram.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                    <h6 class="text-black mt-2">@mediko.id (Mediko Bimbel)</h6>
                                </a>
                                <a href="https://wa.me/6281215371635" class="flex gap-4 p-3 align-items-center" style="text-decoration: none;border: gainsboro 1px solid;border-radius:12px;">
                                    <img src="{{ secure_asset('/assets/images/whatsapp.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                    <h6 class="text-black mt-2">+6281215371635 (Mediko Tryout)</h6>
                                </a>
                                <a href="https://wa.me/6282134038758" class="flex gap-4 p-3 align-items-center" style="text-decoration: none;border: gainsboro 1px solid;border-radius:12px;">
                                    <img src="{{ secure_asset('/assets/images/whatsapp.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                    <h6 class="text-black mt-2">+6282134038758 (Mediko Bimbel)</h6>
                                </a>
                                <a href="https://t.me/medikoindonesia" class="flex gap-4 p-3 align-items-center" style="text-decoration: none;border: gainsboro 1px solid;border-radius:12px;">
                                    <img src="{{ secure_asset('/assets/images/telegram.png') }}" alt="logo mediko" style="width: 6%; height: auto;">
                                    <h6 class="text-black mt-2">Mediko Indonesia</h6>
                                </a>
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
    <footer class="mt-20 h-64" data-sentry-component="Footer" data-sentry-source-file="Footer.tsx">
        <hr>
        <div class="container">
            <div class="row py-5 align-items-center">
                <div class="col-md-6">
                    <p>Copyright © 2024 Healix. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 d-md-flex justify-content-end">
                    <div>
                        <ul class="flex gap-4 align-items-center p-0 text-center">
                            <li>
                                <a href="#about" class="btn-nav text-lg nav-text">Tentang Kami</a>
                            </li>
                            <li>
                                <a href="#price" class="btn-nav text-lg nav-text">Harga</a>
                            </li>
                            <li>
                                <a href="#testimoni" class="btn-nav text-lg nav-text">Testimoni</a>
                            </li>
                        </ul>
                        <ul class="flex gap-4 align-items-center p-0 text-center">
                            <li>
                                <a href="#faq" class="btn-nav text-lg nav-text">FAQ</a>
                            </li>
                            <li>
                                <a href="#kebijakan-privasi" class="btn-nav text-lg nav-text">Privasi</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

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

            $('.card-header').click(function () {
                var icon = $(this).find('.toggle-icon');

                if ($(this).next('.collapse').hasClass('show')) {
                    icon.text('+');
                } else {
                    $('.toggle-icon').text('+');
                    icon.text('-');
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

            $("#toggleSidebar").click(function() {
                $(".sidebar").toggleClass("show");
                $(".overlay").toggleClass("active"); // Tambahkan class active
                $("body").toggleClass("no-scroll");
            });

            $(".overlay").click(function() {
                $(".sidebar").removeClass("show");
                $(this).removeClass("active"); // Hapus class active
                $("body").removeClass("no-scroll");
            });

            $(document).click(function(event) {
                if (!$(event.target).closest("#toggleSidebar").length) {
                    $(".sidebar").removeClass("show");
                    $(".overlay").removeClass("active");
                    $("body").removeClass("no-scroll");
                }
            });


        });
    </script>
</body>

</html>