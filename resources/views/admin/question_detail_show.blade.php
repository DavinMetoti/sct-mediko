@extends('layouts.app')

@section('title', config('app.name') . ' | Daftar Soal')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Daftar Soal : {{ strtoupper($question->bank_name) }}</h3>
                    <p class="text-subtitle text-muted">Edit dan hapus soal yang sudah dibuat agar lebih optimal</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="question-detail-table">
                            <thead>
                                <tr>
                                    <th class="text-center align-items-center" rowspan="2">No</th>
                                    <th class="text-center align-items-center" rowspan="2">Bidang</th>
                                    <th class="text-center align-items-center" rowspan="2">Kasus Klinik</th>
                                    <th class="text-center align-items-center" rowspan="2">Hipotesis Baru</th>
                                    <th class="text-center align-items-center" colspan="5">Skor Panelis</th>
                                    <th class="text-center align-items-center" rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center">-2</th>
                                    <th class="text-center">-1</th>
                                    <th class="text-center">0</th>
                                    <th class="text-center">1</th>
                                    <th class="text-center">2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questionDetail as $index => $detail)
                                @php
                                    $panelistScores = json_decode($detail->panelist_answers_distribution, true);
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->medicalField->name ?? 'N/A' }}</td> <!-- Relasi medicalField -->
                                    <td>{{ $detail->clinical_case }}</td>
                                    <td>{{ $detail->initial_hypothesis }}</td>
                                    <td>{{ $panelistScores['-2'] ?? 0 }}</td>
                                    <td>{{ $panelistScores['-1'] ?? 0 }}</td>
                                    <td>{{ $panelistScores['0'] ?? 0 }}</td>
                                    <td>{{ $panelistScores['1'] ?? 0 }}</td>
                                    <td>{{ $panelistScores['2'] ?? 0 }}</td>
                                    <td>
                                        <div class="flex justify-content-center gap-2">
                                            <a class="btn btn-warning" href="{{ route('question-detail.detail.edit', $detail->id) }}" data-id="{{ $detail->id }}" id="btn-edit"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-danger" data-id="{{ $detail->id }}" id="btn-delete" ><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        const table= $('#question-detail-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                {
                    "targets": 9,
                    "orderable": false
                }
            ],
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }
        });

        $('#btn-delete').on('click', function () {
            const id = $(this).data('id');

            $.ajax({
                url: `{{ route('question-detail.destroy', ':id') }}`.replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.status === 'success') {
                        toastSuccess('Data berhasil dihapus!');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastError('Gagal menghapus data: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                    toastError('Gagal menghapus data. Silakan coba lagi.');
                }
            });
        });

    });

</script>
