@extends('layouts.app')

@section('title', 'Daftar Paket')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <h4 class="mb-4 font-weight-bold text-primary">📦 Daftar Paket Tryout</h4>

            @if($packages->isEmpty())
                <div class="alert alert-warning text-center">🚫 Tidak ada paket tersedia.</div>
            @else
                <div class="row">
                    @foreach($packages as $package)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-lg border-0 rounded-lg h-100 d-flex flex-column p-3">
                                <div class="card-header text-white text-left py-3 fw-bold"
                                     style="background: linear-gradient(135deg, #007bff, #28a745); border-radius: 10px;">
                                     <div class="d-flex justify-content-between mb-2">
                                        <h3 class="fw-bold">{{ $package->name }}</h3>
                                        <div style="background-color: white; padding: 10px; border-radius: 50%; min-width: 60px; height: 60px; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                                            <img src="{{ secure_asset('assets/images/logo-icon-mediko.webp') }}" alt="logo-mediko" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                     </div>
                                    <div class="d-block font-weight-bold text-uppercase py-2 text-center mt-4"
                                          style="font-size: 1.5rem; background-color: rgba(227, 242, 253, 0.6); border-radius: 8px; font-weight: bold;">
                                        @if($package->price == 0)
                                            GRATIS
                                        @else
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-left flex-grow-1">
                                    <div class="flex justify-content-between">
                                        <p>
                                            <span class="badge
                                                @if($package->final_status == 'paid')
                                                    bg-success
                                                @elseif($package->final_status == 'pending')
                                                    bg-warning
                                                @elseif($package->final_status == 'Unpurchased')
                                                    bg-danger
                                                @elseif($package->final_status == 'verification')
                                                    bg-primary
                                                @else
                                                    bg-secondary
                                                @endif
                                            ">
                                                @if($package->final_status == 'paid')
                                                    Berhasil
                                                @elseif($package->final_status == 'pending')
                                                    Menunggu Pembayaran
                                                @elseif($package->final_status == 'Unpurchased')
                                                    Belum Dibeli
                                                @elseif($package->final_status == 'verification')
                                                    Dalam Verifikasi
                                                @else
                                                    Status Tidak Diketahui
                                                @endif
                                            </span>
                                        </p>
                                        <p>Exp : {{ \Carbon\Carbon::parse($package->expires_at)->translatedFormat('d F Y') }}</p>
                                        </div>
                                    <p>{{$package->description}}</p>
                                </div>
                                <div class="card-footer text-center bg-white border-0 d-flex justify-content-between">
                                    <button class="btn btn-warning btn-lg font-weight-bold px-4 shadow-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal"
                                        onclick="showDetails({{ json_encode($package) }})">
                                        📖 Detail
                                    </button>
                                    <a href="{{ route('payment.index', ['id' => $package->id]) }}"
                                        class="btn btn-primary btn-lg font-weight-bold px-4 shadow-sm @if($package->final_status == 'paid')
                                            disabled
                                        @endif"
                                        >
                                            🚀 Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail Paket -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailModalLabel">Detail Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="modalPackageName" class="fw-bold"></h4>
                <p id="modalPackageDescription"></p>
                <h6 class="text-muted font-weight-bold mt-3">📋 Daftar Tryout:</h6>
                <ul id="modalTryoutList" class="list-group list-group-flush"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@include('partials.script')

<script>
    function showDetails(package) {
        document.getElementById('modalPackageName').textContent = package.name;
        document.getElementById('modalPackageDescription').textContent = package.description;

        let tryoutList = document.getElementById('modalTryoutList');
        tryoutList.innerHTML = '';

        if (package.questions && package.questions.length > 0) {
            package.questions.forEach(question => {
                let li = document.createElement('li');
                li.classList.add('list-group-item');
                li.textContent = `✅ ${question.question}`;
                tryoutList.appendChild(li);
            });
        } else {
            let li = document.createElement('li');
            li.classList.add('list-group-item', 'text-muted');
            li.textContent = 'Tidak ada tryout tersedia';
            tryoutList.appendChild(li);
        }
    }
</script>
