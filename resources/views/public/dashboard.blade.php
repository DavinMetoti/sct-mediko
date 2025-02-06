@extends('layouts.app')

@section('title', config('app.name') . ' | Dashboard')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md p-3 bg-blue-500 text-white">
                                    <i class="fas fa-list" style="font-size: 30px;"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Tryout</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $questionActive_total }}</dd>
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
                                <div class="flex-shrink-0 rounded-md p-3 bg-green-500 text-white">
                                    <i class="fas fa-tasks" style="font-size: 30px;"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Tryout Selesai</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $taskHistory }}</dd>
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
                                <div class="flex-shrink-0 rounded-md p-3 bg-yellow-500 text-white">
                                    <i class="fas fa-bar-chart" style="font-size: 30px;"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Rata-rata Nilai</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{$average_total_akhir}} %</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- First Card (Chart) -->
                <div class="col-md-12 mb-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <h6 class="text-muted fw-bold mb-2">Grafik Pendalaman</h6>
                            <canvas id="studentChart" class="w-100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Second Card (Estimasi Perbidang) -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow h-100 w-full">
                        <div class="card-body">
                            <h6 class="text-muted fw-bold mb-4">Estimasi Perbidang</h6>
                            @php
                                $sortedMedicalFields = $medicalField->sortByDesc('average');
                            @endphp

                            @foreach ($sortedMedicalFields as $item)
                                <div class="flex justify-content-between">
                                    <div>{{$item->name}}</div>
                                    <div class="
                                        @if ($item->average >= 90)
                                            text-success
                                        @elseif ($item->average >= 70)
                                            text-warning
                                        @else
                                            text-danger
                                        @endif fw-bold">
                                        {{$item->average}} %
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow h-100 w-full">
                        <div class="card-body">
                            <h6 class="text-muted fw-bold mb-4">Riwayat Tryout</h6>
                            @foreach ($taskHistoryQuestionDetail as $item)
                                <div class="flex justify-content-between">
                                    <div>{{$item->question->question}}</div>
                                    <div class="
                                        @if (($item->score / $item->question->questionDetail->count())*100 >= 90)
                                            text-success
                                        @elseif (($item->score / $item->question->questionDetail->count())*100 >= 70)
                                            text-warning
                                        @else
                                            text-danger
                                        @endif fw-bold">
                                        {{($item->score / $item->question->questionDetail->count())*100}} %
                                    </div>
                                </div>
                            @endforeach
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
        const medicalFieldData = @json($medicalField);
        const taskHistoryQuestionDetail = @json($taskHistoryQuestionDetail);

        const labels = medicalFieldData.map(field => field.name);
        const averages = medicalFieldData.map(field => field.average);


        function getBarColor(average) {
            if (average >= 90) {
                return 'rgba(40, 167, 69, 0.2)';
            } else if (average >= 70) {
                return 'rgba(255, 193, 7, 0.2)';
            } else {
                return 'rgba(220, 53, 69, 0.2)';
            }
        }


        const ctx = document.getElementById('studentChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Skor perbidang (%)',
                    data: averages,
                    backgroundColor: averages.map(average => getBarColor(average)),
                    borderColor: averages.map(average => getBarColor(average).replace('0.2', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100
                    },
                    y: {
                        ticks: {
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    });


</script>
