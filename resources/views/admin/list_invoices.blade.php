@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="mb-0">Daftar Pembayaran</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')
