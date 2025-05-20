@extends('layouts.app')

@section('title', '403 Forbidden')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="text-center flex justify-content-center flex-column align-items-center">
        <div class="mb-4">
            <svg width="120" height="120" fill="none" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="56" stroke="#f59e42" stroke-width="8" fill="#fff"/>
                <text x="50%" y="54%" text-anchor="middle" fill="#f59e42" font-size="48" font-weight="bold" dy=".3em">403</text>
            </svg>
        </div>
        <h1 class="display-4 fw-bold mb-2 text-warning">Akses Ditolak</h1>
        <p class="lead mb-4 text-secondary">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url('/') }}" class="btn btn-warning px-4 py-2 shadow text-white">Kembali ke Beranda</a>
    </div>
</div>
@endsection
