@extends('layouts.app')

@section('title', config('app.name') . ' | Broadcast')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="container-fluid">
            <div class="flex justify-content-between">
                <div>
                    <h3 class="fw-bold">Broadcast</h3>
                    <p class="text-subtitle text-muted">Tambahkan broadcast untuk memberikan pengumuman lebih cepat.</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                        <i class="fas fa-plus me-2"></i>Tambah Broadcast
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-content-between mb-3">
                        <div>
                            <h5 class="mb-0">Broadcast Notifikasi</h5>
                            <small>Kirim pesan pengumuman kepada setiap user</small>
                        </div>

                    </div>
                    <!-- Tabel untuk menampilkan Data -->
                    <table id="broadcast-table" class="table table-striped">
                        <thead class="bg-secondary">
                            <tr>
                                <th class="text-white">Judul</th>
                                <th class="text-white">Pesan</th>
                                <th class="text-white">Waktu Dibuat</th>
                                <th class="text-white">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Broadcast -->
<div class="modal fade" id="broadcastModal" tabindex="-1" aria-labelledby="broadcastModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="broadcastModalLabel">Tambah Broadcast</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="broadcastForm">
                    <div class="form-group mb-3">
                        <label for="title" class="form-label text-black fw-medium">Judul</label>
                        <input required type="text" class="form-control rounded px-3 py-2" id="title" name="title"
                               placeholder="Masukan judul" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="message" class="form-label text-black fw-medium">Keterangan</label>
                        <textarea required class="form-control rounded px-3 py-2" id="message" name="message" placeholder="Tambahkan pesan" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveBroadcast">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        const table = $('#broadcast-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.broadcast.table") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: 'title', name: 'title' },
                { data: 'message', name: 'message' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
            columnDefs: [
                { targets: -1, width: '5%' }
            ],
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }
        });

        $('#broadcast-table').on('click', '#delete-btn', function() {
            const notificationId = $(this).data('id');

            confirmationModal.open({
                message: 'Are you sure you want to proceed?',
                severity: 'warn', // 'warn', 'success', 'error'
                onAccept: () => {
                    $.ajax({
                        url: '{{ route("broadcast.destroy", ":id") }}'.replace(':id', notificationId),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastSuccess(response.message || 'Notifikasi berhasil dihapus');
                            } else {
                                toastError(response.message || 'Gagal menghapus notifikasi');
                            }

                            table.ajax.reload(null, false);
                        },
                        error: function() {
                            alert('Terjadi kesalahan');
                        }
                    });
                },
                onReject: () => {
                    console.log('Rejected!');
                },
            });
        });

        $('#saveBroadcast').on('click', function() {
            const title = $('#title').val();
            const message = $('#message').val();

            $.ajax({
                url: '{{ route("broadcast.store") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: title,
                    message: message
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#broadcastModal').modal('hide');
                        $('#broadcastForm')[0].reset();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        toastSuccess(response.message || 'Notifikasi berhasil ditambahkan');

                        table.ajax.reload(null, false);
                    } else {
                        toastError(response.message || 'Gagal menambahkan notifikasi');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan');
                }
            });
        });
    });
</script>
