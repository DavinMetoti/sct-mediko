@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md p-3 bg-blue-500 text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list w-6 h-6">
                                        <line x1="8" x2="21" y1="6" y2="6"></line>
                                        <line x1="8" x2="21" y1="12" y2="12"></line>
                                        <line x1="8" x2="21" y1="18" y2="18"></line>
                                        <line x1="3" x2="3.01" y1="6" y2="6"></line>
                                        <line x1="3" x2="3.01" y1="12" y2="12"></line>
                                        <line x1="3" x2="3.01" y1="18" y2="18"></line>
                                    </svg></div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Soal</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $question_total }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md p-3 bg-green-500 text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-6 h-6">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg></div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Peserta</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $student_total }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md p-3 bg-yellow-500 text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity w-6 h-6">
                                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                    </svg></div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Ujian Aktif</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $questionActive_total }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="myChart" class="w-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        const ctx = $('#myChart')[0].getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
