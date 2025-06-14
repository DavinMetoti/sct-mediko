@extends('layouts.app')

@section('title', config('app.name') . ' | Daftar Pertanyaan')

@section('content')
<div class="min-h-screen bg-light">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Daftar Tryout</h3>
                    <p class="text-subtitle text-muted">Temukan tryout yang dapat menunjang belajar anda</p>
                </div>
            </div>
            <form method="GET" action="{{ route('question-list.index') }}" class="mb-4 w-100">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Tryout..."
                            value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
            @if($questions->isNotEmpty())
            <h4 class="fw-bold">Tryout Gratis</h4>
            @endif

            <div class="row">
                @foreach ($questions as $question)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header text-white"
                                style="background: linear-gradient(135deg, #2D8BC9 0%, #83BC30 100%); border-radius: 10px 10px 0 0;">
                                <h5 class="fw-bold mb-0">{{ $question->question }}</h5>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <p class="text-muted">
                                    {{ $question->thumbnail ?? 'Thumbnail belum ditentukan' }}
                                </p>
                                <div class="mt-auto">
                                    <div class="flex justify-content-between">
                                        <div class="badge {{ $question->is_public == 1 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $question->is_public == 1 ? 'Gratis' : 'Berbayar' }}
                                        </div>
                                        <div class="flex gap-2">
                                            <div class="flex align-item-center text-muted text-sm">
                                                <i class="material-icons text-sm me-1">
                                                    <span class="material-symbols-outlined">
                                                        menu_book
                                                    </span>
                                                </i>
                                                <span>{{ $question->questionDetail?->count() ?? 0 }} Soal</span>
                                            </div>
                                            <div class="flex align-item-center text-muted text-sm">
                                                <i class="material-icons text-sm me-1">
                                                    <span class="material-symbols-outlined">
                                                        schedule
                                                    </span>
                                                </i>
                                                @php
                                                    $time = $question->time;
                                                    $timeParts = explode(":", $time);
                                                    $totalMinutes = ($timeParts[0] * 60) + $timeParts[1];
                                                @endphp
                                                <span>{{ $totalMinutes }} Menit</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('question.preview', $question->id) }}"
                                        class="btn btn-primary mt-3 w-100 fw-bold">
                                        Mulai Mengerjakan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            @if($packages->isNotEmpty())
                @php
                    $displayedPackages = [];
                @endphp

                @foreach($packages as $package)
                    @if(!in_array($package->name, $displayedPackages))
                        @php
                            $displayedPackages[] = $package->name;
                        @endphp
                        <h4 class="fw-bold">{{ $package->name }}</h4>
                    @endif

                    <div class="row">
                        @if($package->questions->isEmpty())
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <strong>Info:</strong> Tidak ada soal dalam paket ini.
                                </div>
                            </div>
                        @else
                            @foreach ($package->questions as $question)
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-header text-white"
                                            style="background: linear-gradient(135deg, #FF7F50 0%, #FFD700 100%); border-radius: 10px 10px 0 0;">
                                            <h5 class="fw-bold mb-0">{{ $question->question }}</h5>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <p class="text-muted">
                                                {{ $question->thumbnail ?? 'Thumbnail belum ditentukan' }}
                                            </p>
                                            <div class="mt-auto">
                                                <div class="flex justify-content-between">
                                                    <div class="badge {{ $question->is_public == 1 ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $question->is_public == 1 ? 'Gratis' : 'Berbayar' }}
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <div class="flex align-item-center text-muted text-sm">
                                                            <i class="material-icons text-sm me-1">
                                                                <span class="material-symbols-outlined">
                                                                    menu_book
                                                                </span>
                                                            </i>
                                                            <span>{{ $question->questionDetail?->count() ?? 0 }} Soal</span>
                                                        </div>
                                                        <div class="flex align-item-center text-muted text-sm">
                                                            <i class="material-icons text-sm me-1">
                                                                <span class="material-symbols-outlined">
                                                                    schedule
                                                                </span>
                                                            </i>
                                                            @php
                                                                $time = $question->time;
                                                                $timeParts = explode(":", $time);
                                                                $totalMinutes = ($timeParts[0] * 60) + $timeParts[1];
                                                            @endphp
                                                            <span>{{ $totalMinutes }} Menit</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('question.preview', $question->id) }}"
                                                    class="btn btn-warning mt-3 w-100 fw-bold">
                                                    Mulai Mengerjakan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <hr>
                @endforeach
            @else
                <p>Tidak ada paket tersedia.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@include('partials.script')
