@extends('layouts.app')

@section('title', config('app.name') . ' | Riwayat Tryout')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card shadow-lg border-0 rounded-xl">
                <div class="card-body bg-white p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="text-dark font-weight-bold">Riwayat Tryout</h5>
                    </div>

                    <div class="row">
                        @foreach ($tryoutHistory as $item)
                        <div class="col-md-4">
                            <div class="card mb-5 shadow-lg border-0 rounded-xl">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-column">
                                            <h5 class="fw-bold">{{$item->question->question}}</h5>

                                            <p class="text-muted text-sm">
                                                Completed on: {{ $item->completed_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <span class="badge
                                                @if($item->status == 'completed')
                                                    bg-success
                                                @elseif($item->status == 'in progress')
                                                    bg-warning
                                                @else
                                                    bg-secondary
                                                @endif
                                                text-white rounded-pill p-2 font-weight-semibold">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @if ($item->total_score < 70)
                                    <div class="text-center">
                                        <span class="badge text-danger rounded-pill p-2 font-weight-semibold" style="font-size: 2rem;">
                                            Tidak Lulus
                                        </span>
                                    </div>
                                    @else
                                    <div class="text-center">
                                        <span class="badge text-success rounded-pill p-2 font-weight-semibold" style="font-size: 2rem;">
                                            Lulus
                                        </span>
                                    </div>
                                    @endif
                                    <div class="flex justify-content-between">
                                        <p class="mt-3 mb-1 text-lg fw-bold">
                                            Score:
                                            <span class="text-dark">{{ $item->total_score }}</span>
                                        </p>

                                        <a href="{{ route('task-history.show', $item->id) }}" class="btn btn-info btn-sm rounded-pill mt-3 d-flex align-items-center px-3 py-2">
                                            <i class="fas fa-book mr-2"></i> Pembahasan
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')
