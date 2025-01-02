@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card">
                <div class="card-header flex justify-content-between">
                    <div class="card-title">
                        <h3>{{ $question->question }}</h3>
                        <small>{{$question->thumbnail}}</small>
                    </div>
                    <div>
                        <button class="btn btn-success">Mulai Mengerjakan</button>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="font-bold">Deskripsi Materi</h5>
                    <div>
                        {!! $question->description !!}
                    </div>
                    <h5 class="font-bold">Detail Informasi</h5>
                    <table>
                        <tbody>
                            <tr>
                                <td>Waktu Pengerjaan</td>
                                <td>:</td>
                                <td>{{ $question->time }}</td>
                            </tr>
                            <tr>
                                <td>Batas Pengerjaan</td>
                                <td>:</td>
                                <td>{{ \Carbon\Carbon::parse($question->end_time)->translatedFormat('d F Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
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
