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
                            <h5 class="mb-0">Pengaturan</h5>
                        </div>
                    </div>
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
