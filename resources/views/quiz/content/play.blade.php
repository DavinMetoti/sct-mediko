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

    .card-purple {
        background-color: #2D0A56;
        color: white; /* Agar teks terlihat jelas */
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-control-purple {
        background-color: #3B1171;
        color: white;
        padding: 0.5rem;
        border-radius: 5px;
        outline: none;
        transition: all 0.3s ease-in-out;
    }

    .form-control-purple::placeholder {
        color: #C2A1E3; /* Warna placeholder agar lebih terlihat */
        opacity: 0.7;
    }

    .form-control-purple:focus {
        background-color: #4A148C; /* Warna lebih terang saat fokus */
        border-color: #8A2BE2; /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(138, 43, 226, 0.5);
    }

    .quiz-session {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Desktop */
    @media (min-width: 768px) {
        .quiz-session {
            flex-direction: row;
            align-items: center;
        }
        .quiz-session .text-end {
            text-align: right;
        }
    }

    /* Mobile */
    @media (max-width: 767px) {
        .quiz-session {
            text-align: center;
        }
        .quiz-session .text-end {
            text-align: center;
        }
    }

    .glosy-card {
        position: relative;
        width: 100%;
        padding: 1rem;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        text-align: center;
        overflow: hidden;
    }
    /* Efek cahaya mengalir */
    .glosy-card::after {
        content: "";
        position: absolute;
        top: -100%;
        left: -100%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 50%);
        transform: rotate(50deg);
        animation: glossy-move 3s infinite linear;
    }

    @keyframes glossy-move {
        0% {
            top: -100%;
            left: -100%;
        }
        100% {
            top: 100%;
            left: 100%;
        }
    }

    .progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: white;
        transition: width 1s linear, background-color 1s linear;
    }

    .quiz-container-rank {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
            background-color: #2E0052;
            border-radius: 10px;
            color: white;
            max-height: 500px;
            overflow-y: auto;
        }
        .rank-item {
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.5s ease-in-out;
        }
        .rank-item:nth-child(1) {
            background-color: #FFD700;
            color: #000;
            font-weight: bold;
        }
        .rank-item:nth-child(2) {
            background-color: #C0C0C0;
            color: #000;
            font-weight: bold;
        }
        .rank-item:nth-child(3) {
            background-color: #CD7F32;
            color: #000;
            font-weight: bold;
        }
        .rank-card h5 {
            margin: 0;
            font-size: 18px;
        }
        .rank-card p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        /* Animasi */
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes flash {
            0%, 100% {
                background-color: #FFD700;
            }
            50% {
                background-color: #fff275;
            }
        }

        .animate-rank {
            animation: fadeInScale 0.5s ease-in-out;
        }

        .rank-up {
            animation: flash 1s ease-in-out 2;
        }

</style>
@livewireStyles

@section('title', config('app.name') . ' | Quiz')

@section('content')
    <div class="min-h-screen">
        <div class="content-mediko-quiz text-white min-h-screen" style="width: 100% !important;">
            <div class="container-fluid">
                <div id="toastiin-container"></div>
                @yield('quiz-content')
            </div>
        </div>
    </div>
    @livewireScripts
@endsection

@include('partials.script')

