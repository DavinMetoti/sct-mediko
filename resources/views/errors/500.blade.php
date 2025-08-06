@extends('layouts.app')

@section('title', '500 Internal Server Error')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="text-center flex justify-content-center flex-column align-items-center">
        <div class="mb-4">
            <svg width="120" height="120" fill="none" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="56" stroke="#6366f1" stroke-width="8" fill="#fff"/>
                <text x="50%" y="54%" text-anchor="middle" fill="#6366f1" font-size="48" font-weight="bold" dy=".3em">500</text>
            </svg>
        </div>
        <h1 class="display-4 fw-bold mb-2 text-primary">Kesalahan Server</h1>
        <p class="lead mb-4 text-secondary">Maaf, terjadi kesalahan pada server kami. Silakan coba beberapa saat lagi.</p>
        <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2 shadow">Kembali ke Beranda</a>
    </div>
</div>
@endsection
