@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="bg-cover-profile"></div>
                    <div class="absolute image-profile">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($person->name) }}&size=128" alt="{{ $person->name }}" class="rounded-full w-32 border-4 border-white shadow-md">
                    </div>
                    <div class="w-full flex justify-content-end pt-3 px-4">
                        <button class="btn"><i class="fas fa-pencil text-primary"></i></button>
                        <button class="btn"><i class="fas fa-trash text-danger"></i></button>
                    </div>
                    <div class="px-4 pb-5" style="padding-top: 2rem">
                        <h3 class="fw-bold">{{ ucwords($person->name) }} <span class="badge bg-success"></span></h3>
                        <div class="text-sm" style="font-weight: 200"><i class="fas fa-user me-2" style="font-weight: 200"></i><span>Username : {{ $person->username }} | Email : {{ $person->email }}</span></div>
                        <div class="mt-4">
                            <h4 class="text-lg font-semibold text-gray-700">Account Details:</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600">
                                <li>Email Verified At: {{ $person->email_verified_at ?? 'Not Verified' }}</li>
                                <li>Phone: {{ $person->phone ?? 'Not Provided' }}</li>
                                <li>Role: {{ $person->accessRole->name }}</li>
                                <li>Account Created At: {{ $person->created_at }}</li>
                                <li>Last Updated At: {{ $person->updated_at }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@include('partials.script')

