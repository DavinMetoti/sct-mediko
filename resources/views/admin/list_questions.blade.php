@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="row">
                <h3 class="fw-bold">Gratis</h3>
                @foreach ($questions as $question)
                <div class="col-md-3 mb-3">
                    <div class="bg-white p-3 rounded-lg shadow-md mb-3 align-items-stretch" style="height: 100%;">
                        <div class="flex justify-start flex-column" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                            <div
                                class="w-full flex justify-content-between py-2 px-3"
                                style="min-height: 8rem; border-radius: 12px; background: linear-gradient(90deg, rgba(45,139,201,1) 0%, rgba(44,140,200,1) 54%, rgba(131,188,48,1) 100%);">
                                <div class="font-bold text-white text-2xl" style="flex: 1;">{{ $question->question }}</div>
                                <div class="text-lg font-semibold">
                                    <span class="badge bg-success">{{ $question->is_public == 1 ? 'Rilis' : 'Belum Rilis' }}</span>
                                </div>
                            </div>
                            <p class="mt-3">{{ $question->thumbnail == null ? 'Thumbnail belum ditentukan' : $question->thumbnail }}</p>
                            <a href="{{ route('question.preview', $question->id) }}" class="btn btn-primary text-white fw-bold" style="margin-top: auto;">Mulai Mengerjakan</a>
                        </div>
                    </div>
                </div>
                @endforeach
                <h3 class="fw-bold"></h3>

            </div>
        </div>
    </div>

</div>

@endsection

@include('partials.script')