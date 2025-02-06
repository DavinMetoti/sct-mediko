@extends('layouts.app')

@section('title', config('app.name') . ' | Detail Tryout')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-4 py-3">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-3 rounded-top">
                    <div>
                        <h3 class="mb-1 font-bold">{{ $question->question }}</h3>
                        <small class="text-light font-italic">{{ $question->thumbnail }}</small>
                    </div>
                    @if ($checkTryout == null)
                        <button
                            id="run-tryout"
                            class="btn btn-success font-weight-bold px-4 py-2 {{ $questionDetail == 0 ? 'disabled' : '' }}">
                            <span class="default-text"><i class="fas fa-play-circle me-2"></i> Mulai Mengerjakan</span>
                            <span class="loading-spinner d-none"><i class="fas fa-spinner fa-spin me-2"></i> Memproses...</span>
                        </button>
                    @elseif ($checkTryout->status == 'in_progress')
                        <button
                            id="run-tryout"
                            class="btn btn-success font-weight-bold px-4 py-2 {{ $questionDetail == 0 ? 'disabled' : '' }}">
                            <span class="default-text"><i class="fas fa-play-circle me-2"></i> Lanjut Mengerjakan</span>
                            <span class="loading-spinner d-none"><i class="fas fa-spinner fa-spin me-2"></i> Memproses...</span>
                        </button>
                    @elseif ($checkTryout->status == 'completed')
                        <div>
                            <button id="btn-refresh"
                                class="btn btn-warning font-weight-bold {{ $questionDetail == 0 ? 'disabled' : '' }} mr-2">
                                <span class="default-text"><i class="fas fa-refresh me-2"></i> Mengerjakan Ulang</span>
                                <span class="loading-spinner d-none"><i class="fas fa-spinner fa-spin me-2"></i> Memproses...</span>
                            </button>
                            <button
                                id="btn-pembahasan"
                                class="btn btn-info font-weight-bold py-2 {{ $questionDetail == 0 ? 'disabled' : '' }}">
                                <span class="default-text"><i class="fas fa-info-circle me-2"></i> Pembahasan</span>
                                <span class="loading-spinner d-none"><i class="fas fa-spinner fa-spin me-2"></i> Memproses...</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body bg-white">
                    <h5 class="font-weight-bold text-primary mb-3">Deskripsi Materi</h5>
                    <div class="mb-4 text-muted">
                        {!! $question->description !!}
                    </div>
                    <h5 class="font-weight-bold text-primary mb-3">Detail Informasi</h5>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-secondary w-25">Jumlah Soal</td>
                                <td>{{ $questionDetail }} Soal</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-secondary w-25">Waktu Pengerjaan</td>
                                <td>{{ $question->time }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-secondary">Batas Pengerjaan</td>
                                <td>{{ \Carbon\Carbon::parse($question->end_time)->translatedFormat('d F Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <span class="text-muted">Pastikan Anda membaca semua informasi dengan cermat sebelum memulai!</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function () {
        const question = @json($question);

        function timeToMinutes(time) {
            const parts = time.split(':');
            const hours = parseInt(parts[0], 10);
            const minutes = parseInt(parts[1], 10);
            return hours * 60 + minutes;
        }

        $('#run-tryout').on('click', function (e) {
            e.preventDefault();
            const button = $(this);


            button.find('.default-text').addClass('d-none');
            button.find('.loading-spinner').removeClass('d-none');
            button.prop('disabled', true);

            const sisa_waktu = timeToMinutes(question.time);

            const data = {
                user_id: "{{ auth()->id() }}",
                question_id: question.id,
                sisa_waktu: sisa_waktu,
                score: 0,
                status: 'in_progress'
            };

            $.ajax({
                url: "{{ route('task-history.store') }}",
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {

                    const redirectUrl = "{{ route('tryout.question.detail', ['idQuestion' => ':idQuestion', 'token' => auth()->user()->session_token]) }}";
                    window.location.href = redirectUrl.replace(':idQuestion', response.data.id);
                },
                error: function (xhr, status, error) {
                    console.error('Error creating task history:', error);
                    alert('Failed to start the task. Please try again.');


                    button.find('.default-text').removeClass('d-none');
                    button.find('.loading-spinner').addClass('d-none');
                    button.prop('disabled', false);
                }
            });
        });

        $('#btn-refresh').on('click', function (e) {
            e.preventDefault();
            const button = $(this);


            button.find('.default-text').addClass('d-none');
            button.find('.loading-spinner').removeClass('d-none');
            button.prop('disabled', true);

            const sisa_waktu = timeToMinutes(question.time);

            const data = {
                user_id: "{{ auth()->id() }}",
                question_id: question.id,
                sisa_waktu: sisa_waktu,
                score: 0,
                status: 'in_progress'
            };

            $.ajax({
                url: "{{ route('task-history.store') }}",
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {

                    const redirectUrl = "{{ route('tryout.question.detail', ['idQuestion' => ':idQuestion', 'token' => auth()->user()->session_token]) }}";
                    window.location.href = redirectUrl.replace(':idQuestion', response.data.id);
                },
                error: function (xhr, status, error) {
                    console.error('Error creating task history:', error);
                    alert('Failed to start the task. Please try again.');


                    button.find('.default-text').removeClass('d-none');
                    button.find('.loading-spinner').addClass('d-none');
                    button.prop('disabled', false);
                }
            });
        });

        $('#btn-pembahasan').on('click', function() {
            @if ($checkTryout && $checkTryout->id)
                window.location.href = "{{ route('task-history.show', ':id') }}".replace(':id', {{ $checkTryout->id }});
            @else
                alert('ID tidak ditemukan. Tidak dapat melanjutkan.');
            @endif
        });
    });
</script>
