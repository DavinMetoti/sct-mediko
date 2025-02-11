@extends('layouts.app')

@section('title', config('app.name') . ' | Daftar Pembayaran')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Riwayat Pembelian</h3>
                    <p class="text-subtitle text-muted">Temukan daftar pembelian Anda dan pastikan semua transaksi berjalan lancar!</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="historyTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No Transaksi</th>
                                <th>Paket</th>
                                <th>Status</th>
                                <th>Tanggal Pembelian</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        $('#historyTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payment.show',':id') }}".replace(':id', 1),
            columns: [
                { data: 'formatted_id', name: 'formatted_id'},
                { data: 'package.name', name: 'package.name' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                // { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
                { targets: [2], className: 'text-center w-30' }
            ]
        });

        $('#historyTable').on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('payment.destroy',':id') }}".replace(':id', id),
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: (response) => {
                    toastSuccess('Berhasil membatalkan pembayaran');
                    // Optionally, remove the row or refresh the table
                    $('#historyTable').DataTable().ajax.reload(null, false);
                },
                error: () => {
                    toastError('Gagal membatalkan pembayaran');
                }
            });
        });
    });
</script>
