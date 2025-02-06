@extends('layouts.app')

@section('title', config('app.name') . ' | Setting')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Setting</h3>
                    <p class="text-subtitle text-muted">Kelola pengaturan lebih mudah.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @foreach($settings as $setting)
                    <form action="{{ route('setting.update', $setting->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="setting_{{ $setting->id }}">{{ $setting->label }}</label>
                            <input
                                type="text"
                                name="settings[{{ $setting->key }}]"
                                id="setting_{{ $setting->id }}"
                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                class="form-control"
                                required
                            />
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')
