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
                        <h5 class="mb-0">Riwayat Pembelian</h5>
                    </div>

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
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
                { targets: [2, 4], className: 'text-center w-30' }
            ]
        });

        $('#historyTable').on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            console.log('button delete clicked, ID: ' + id);

            $.ajax({
                url: "{{ route('payment.destroy',':id') }}".replace(':id', id),
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: (response) => {
                    toastSuccess('Berhasil membatalkan pembayaran');
                    // Optionally, remove the row or refresh the table
                    $('#historyTable').DataTable().ajax.reload();
                },
                error: () => {
                    toastError('Gagal membatalkan pembayaran');
                }
            });
        });
    });
</script>
