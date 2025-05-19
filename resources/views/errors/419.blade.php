@extends('layouts.app')

@section('title', '419 Page Expired')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="text-center">
        <div class="mb-4">
            <svg width="120" height="120" fill="none" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="56" stroke="#38bdf8" stroke-width="8" fill="#fff"/>
                <text x="50%" y="54%" text-anchor="middle" fill="#38bdf8" font-size="48" font-weight="bold" dy=".3em">419</text>
            </svg>
        </div>
        <h1 class="display-4 fw-bold mb-2 text-info">Sesi Kadaluarsa</h1>
        <p class="lead mb-4 text-secondary">Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.</p>
        <a href="{{ url()->previous() }}" class="btn btn-info px-4 py-2 shadow text-white">Kembali</a>
    </div>
</div>
@endsection
