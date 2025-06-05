@extends('quiz.content.index')

@section('quiz-content')
<div class="quiz-container">
    <div class="row">
        <div class="col-md-6">
            <h4 class="fw-semibold" style="color: #5E5E5E;">Classroom</h4>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center gap-3 flex-wrap">
            <button id="save-question" onclick="openCreateModal()" class="btn btn-green d-flex align-items-center">
                <i class="fas fa-plus me-2"></i>Tambah
            </button>
        </div>
    </div>

    <div class="card bg-light mt-3 p-3 rounded-4">
        <div class="table-responsive">
            <table class="table table-striped" id="classroom-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
    <div class="modal-dialog" role="document">
        <form id="createForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title color-dark">Tambah Classroom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="text-dark">Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-dark">Tanggal Mulai</label>
                        <input type="text" id="create_start_time" class="form-control" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-dark">Tanggal Selesai</label>
                        <input type="text" id="create_end_time" class="form-control" name="end_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-dark">Status</label>
                        <select class="form-control" name="is_active" required>
                            <option value=1>Aktif</option>
                            <option value=0>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <form id="editForm">
            @csrf
            <input type="hidden" id="editId" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title color-dark">Edit Classroom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="text-dark">Nama</label>
                        <input type="text" class="form-control" name="name" id="editName" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-dark">Tanggal Mulai</label>
                        <input type="text" id="edit_start_time" class="form-control" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-dark">Tanggal Selesai</label>
                        <input type="text" id="edit_end_time" class="form-control" name="end_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-dark">Status</label>
                        <select class="form-control" name="is_active" id="editStatus" required>
                            <option value=1>Aktif</option>
                            <option value=0>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Session Modal -->
<div class="modal fade" id="sessionModal" tabindex="-1" role="dialog" aria-labelledby="sessionModalLabel">
    <div class="modal-dialog" role="document">
        <form id="sessionForm">
            @csrf
            <input type="hidden" id="idsession" name="id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title color-dark" style="color: black;" id="sessionModalLabel">Kelola Sesi Classroom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="sessionSelect" class="text-dark">Tambah Sesi ke Classroom</label>
                        <select id="sessionSelect" name="sessions[]" multiple="multiple" class="form-control" style="width: 100%">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="text-dark">Daftar Sesi yang Terhubung</label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="attachedSessionTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Sesi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let classroomTable;
let startFlatpickrCreate, endFlatpickrCreate;
let startFlatpickrEdit, endFlatpickrEdit;
let sessions = [];

    $(document).ready(function () {
        classroomTable = $('#classroom-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('quiz-classroom.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                {
                    data: 'start_time', name: 'start_time',
                    render: function(data, type, row) {
                        if (!data) return '';
                        // Format: 25 Maret 2025 12:00
                        return moment(data).locale('id').format('D MMMM YYYY HH:mm');
                    }
                },
                {
                    data: 'end_time', name: 'end_time',
                    render: function(data, type, row) {
                        if (!data) return '';
                        return moment(data).locale('id').format('D MMMM YYYY HH:mm');
                    }
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                },
                { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%' },
            ],
            language: {
                emptyTable: "Tidak ada data kelas tersedia"
            }
        });

        startFlatpickrCreate = flatpickr("#create_start_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });

        endFlatpickrCreate = flatpickr("#create_end_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });

        startFlatpickrEdit = flatpickr("#edit_start_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });

        endFlatpickrEdit = flatpickr("#edit_end_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });

        $('#createForm').on('submit', function (e) {
            e.preventDefault();
            storeClassroom();
        });

        $('#editForm').on('submit', function (e) {
            e.preventDefault();
            updateClassroom();
        });

        $.ajax({
            url: "{{ route('quiz-session.index') }}",
            method: "GET",
            success: function (data) {
                // Filter session yang aktif berdasarkan waktu
                const now = moment();
                sessions = (data.data || data).filter(function(session) {
                    return moment(session.start_time, "YYYY-MM-DD HH:mm").isSameOrBefore(now) &&
                           moment(session.end_time, "YYYY-MM-DD HH:mm").isSameOrAfter(now);
                });
            }
        });

        $('#sessionForm').on('submit', function (e) {
            e.preventDefault();
            attachSession();
        });
    });

    function openSessionModal(classroomId) {
        $('#idsession').val(classroomId);
        const $select = $('#sessionSelect');
        $select.empty();

        // Get attached sessions for this classroom
        $.get("{{ url('quiz-classroom') }}/" + classroomId, function(data) {
            const attachedSessions = data.classroom.sessions || [];
            const attachedIds = attachedSessions.map(s => s.id);

            // Populate select2 with sessions that are NOT attached
            sessions.forEach(session => {
                if (!attachedIds.includes(session.id)) {
                    const option = new Option(session.title, session.id, false, false);
                    $select.append(option);
                }
            });

            $select.select2({
                placeholder: "Pilih satu atau lebih sesi",
                allowClear: true,
                theme: 'bootstrap-5',
                dropdownParent: $('#sessionModal')
            });

            renderAttachedSessions(attachedSessions);
            $('#sessionModal').modal('show');
        });
    }

    function renderAttachedSessions(attachedSessions) {
        const $tbody = $('#attachedSessionTable tbody');
        $tbody.empty();
        if (attachedSessions.length === 0) {
            $tbody.append('<tr><td colspan="3" class="text-center">Belum ada sesi terhubung</td></tr>');
            return;
        }
        attachedSessions.forEach((session, idx) => {
            $tbody.append(`
                <tr>
                    <td>${idx + 1}</td>
                    <td>${session.title}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="detachSession(${session.pivot.classroom_id}, ${session.id})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function openCreateModal() {
        $('#createForm')[0].reset();

        startFlatpickrCreate.clear();
        endFlatpickrCreate.clear();

        $('#createModal').modal('show');
    }

    function openEditModal(id) {
        $.get("{{ url('quiz-classroom') }}/" + id, function(data) {
            $('#editId').val(data.classroom.id);
            $('#editName').val(data.classroom.name);

            startFlatpickrEdit.setDate(data.classroom.start_time, true, "Y-m-d H:i");
            endFlatpickrEdit.setDate(data.classroom.end_time, true, "Y-m-d H:i");

            $('#editStatus').val(data.classroom.is_active);
            $('#editModal').modal('show');
        }).fail(function() {
            toastError('Gagal mengambil data kelas.');
        });
    }

    function deleteClassroom(id) {
        if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
            $.ajax({
                url: '{{ route("quiz-classroom.destroy", ":id") }}'.replace(':id', id),
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    classroomTable.ajax.reload(null, false);
                    toastSuccess(response.message);
                },
                error: function() {
                    toastError('Gagal menghapus kelas. Silakan coba lagi.');
                }
            });
        }
    }

    function storeClassroom() {
        $.ajax({
            url: "{{ route('quiz-classroom.store') }}",
            method: 'POST',
            data: $('#createForm').serialize(),
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(response) {
                $('#createModal').modal('hide');
                classroomTable.ajax.reload(null, false);
                toastSuccess(response.message);
            },
            error: function(xhr) {
                toastError(xhr.responseJSON?.message || 'Gagal membuat kelas.');
            }
        });
    }

    function updateClassroom() {
        const id = $('#editId').val();
        $.ajax({
            url: '{{ route("quiz-classroom.update", ":id") }}'.replace(':id', id),
            method: 'POST',
            data: $('#editForm').serialize() + '&_method=PUT',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(response) {
                $('#editModal').modal('hide');
                classroomTable.ajax.reload(null, false);
                toastSuccess(response.message);
            },
            error: function(xhr) {
                toastError(xhr.responseJSON?.message || 'Gagal memperbarui kelas.');
            }
        });
    }

    function attachSession() {
        const classroomId = $('#idsession').val();
        const selectedSessionIds = $('#sessionSelect').val() || [];

        // Ambil session yang sudah terhubung sebelumnya dari tabel
        let previousSessionIds = [];
        $('#attachedSessionTable tbody tr').each(function() {
            const sessionId = $(this).find('button[onclick^="detachSession"]').attr('onclick');
            if (sessionId) {
                // Mendapatkan parameter kedua dari detachSession(classroomId, sessionId)
                const match = sessionId.match(/detachSession\(\s*\d+\s*,\s*(\d+)\s*\)/);
                if (match) {
                    previousSessionIds.push(match[1]);
                }
            }
        });

        // Gabungkan session sebelumnya dan yang baru dipilih, lalu hilangkan duplikat
        const allSessionIds = Array.from(new Set([...previousSessionIds, ...selectedSessionIds]));

        $.ajax({
            url: '{{ route("classroom.attach") }}',
            method: 'POST',
            data: {
                classroom_id: classroomId,
                session_ids: allSessionIds,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#sessionModal').modal('hide');
                classroomTable.ajax.reload(null, false);
                toastSuccess(response.message);
            },
            error: function(xhr) {
                toastError(xhr.responseJSON?.message || 'Gagal memperbarui kelas.');
            }
        });
    }

    function detachSession(classroomId, sessionId) {
        if (!confirm('Yakin ingin menghapus sesi dari classroom ini?')) return;
        $.ajax({
            url: '{{ route("classroom.detach") }}',
            method: 'POST',
            data: {
                classroom_id: classroomId,
                session_id: sessionId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Refresh attached sessions table
                $.get("{{ url('quiz-classroom') }}/" + classroomId, function(data) {
                    const attachedSessions = data.classroom.sessions || [];
                    renderAttachedSessions(attachedSessions);
                });
                classroomTable.ajax.reload(null, false);
                toastSuccess(response.message);
            },
            error: function(xhr) {
                toastError(xhr.responseJSON?.message || 'Gagal menghapus sesi.');
            }
        });
    }

</script>
@endpush
