@extends('layouts.app')

<style>
    .ql-toolbar .ql-picker-label,
    .ql-toolbar .ql-stroke,
    .ql-toolbar .ql-fill {
        color: white !important;
        stroke: white !important;
        border-radius: 12px !important;
    }

    .card .card-body {
        position: relative;
        overflow: hidden; /* Mencegah efek keluar dari card */
    }


    .card .card-body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0); /* Awalnya transparan */
        transition: background 0.3s ease;
        z-index: 0; /* Pastikan berada di belakang input */
    }

    .card.active .card-body::before {
        background: rgba(0, 0, 0, 0.2); /* Efek redup hanya pada card */
    }

    .custom-input {
        position: relative;
        z-index: 1; /* Pastikan input tetap di atas */
        color: white; /* Pastikan teks tetap putih */
        background-color: transparent;
        border: none;
        outline: none;
        text-align: left;
        width: 100%;
        height: 100%;
    }

    .custom-input:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .custom-input::placeholder {
        color: rgb(255, 255, 255) !important;
    }

</style>

@section('title', config('app.name') . ' | Quiz')

@section('content')
    <div class="min-h-screen">
        @include('quiz.partials.sidebar')  {{-- Sidebar Quiz --}}
        @include('quiz.partials.navbar')   {{-- Navbar Quiz --}}

        <div class="content content-mediko-quiz text-white min-h-screen">
            <div class="container-fluid">
                <div id="toastiin-container"></div>
                @yield('quiz-content')  {{-- Bagian ini akan diisi oleh halaman spesifik --}}
            </div>
        </div>
    </div>
@endsection

@include('partials.script')

