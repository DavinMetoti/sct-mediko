@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-4 py-3">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-3 rounded-top">
                    <div>
                        <h3 class="mb-1 font-bold">{{ $question->question }}</h3>
                        <small class="text-light font-italic">{{ $question->thumbnail }}</small>
                    </div>
                    <button class="btn btn-success font-weight-bold px-4 py-2">
                        <i class="fas fa-play-circle me-2"></i> Mulai Mengerjakan
                    </button>
                </div>
                <div class="card-body bg-white">
                    <h5 class="font-weight-bold text-primary mb-3">Deskripsi Materi</h5>
                    <div class="mb-4 text-muted">
                        {!! $question->description !!}
                    </div>
                    <h5 class="font-weight-bold text-primary mb-3">Detail Informasi</h5>
                    <table class="table ">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-secondary w-25">Jumlah Soal</td>
                                <td>{{ $questionDetail }} Soal</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-secondary w-25">Waktu Pengerjaan</td>
                                <td>{{ $question->time }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-secondary">Batas Pengerjaan</td>
                                <td>{{ \Carbon\Carbon::parse($question->end_time)->translatedFormat('d F Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <span class="text-muted">Pastikan Anda membaca semua informasi dengan cermat sebelum memulai!</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')
