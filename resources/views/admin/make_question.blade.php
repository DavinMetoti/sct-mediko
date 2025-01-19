@extends('layouts.app')

@section('title', 'Admin Hak Akses')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="mb-0">Tambah Tryout</h5>
                            <small>Buat paket sebelum menambahkan soal</small>
                        </div>
                        <div class="d-flex gap-2">
                            <div>
                                <button class="btn btn-primary" id="save-button"><i class="fas fa-save me-2"></i><span>Tambah Tryout</span></button>
                            </div>
                            <div>
                                <button class="btn btn-warning" hidden id="update-button"><i class="fas fa-refresh me-2"></i><span>Update</span></button>
                            </div>
                            <div>
                                <button class="btn btn-danger" hidden id="cancel-button"><i class="fas fa-times me-2"></i><span>Cancel</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="question_name">Nama Paket</label>
                                <input type="text" class="form-control" id="question_name">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="time">Waktu Pengerjaan (Menit)</label>
                                <input type="number" class="form-control" id="time">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="release_date">Tanggal Rilis</label>
                                <input type="text" class="form-control" id="release_date">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="expired_date">Tanggal Berakhir</label>
                                <input type="text" class="form-control" id="expired_date">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="froala-editor">Deskripsi</label>
                            <div id="froala-editor"> </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="thumbnail">Thumbnail</label>
                                <input type="text" class="form-control" id="thumbnail">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    id="is-public"
                                    aria-label="Rilis untuk publik">
                                <label class="form-check-label" for="is-public">
                                    Rilis untuk publik
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-stripped" id="questions-table">
                            <thead class="bg-secondary text-center">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">Nama Paket</th>
                                    <th class="text-white">Waktu</th>
                                    <th class="text-white">Status</th>
                                    <th class="text-white">Rilis</th>
                                    <th class="text-white">Tanggal Rilis</th>
                                    <th class="text-white">Expired</th>
                                    <th class="text-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

        const today = new Date();
        const sevenDaysFromNow = new Date();
        sevenDaysFromNow.setDate(today.getDate() + 7);

        $("#release_date").flatpickr({
            enableTime: true,
            altInput: true,
            altFormat: "H:i F j, Y",
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            defaultDate: today
        });

        $("#expired_date").flatpickr({
            enableTime: true,
            altInput: true,
            altFormat: "H:i F j, Y",
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            defaultDate: sevenDaysFromNow
        });

        let description = new FroalaEditor('div#froala-editor', {
            fileUploadURL: '{{ route('admin.question.upload_file') }}',
            imageUploadURL: '{{ route('admin.question.upload_image') }}',
            videoUploadURL: '/upload_video',
            requestHeaders: {
                'X-CSRF-TOKEN':  '{{ csrf_token() }}',
            },
            events: {
                'file.uploaded': function (response) {
                    console.log('File uploaded successfully:', response);
                },
                'file.error': function (error) {
                    console.error('File upload error:', error);
                },
                'image.uploaded': function (response) {
                    console.log('Image uploaded successfully:', response);
                },
                'image.error': function (error) {
                    console.error('Image upload error:', error);
                },
                'video.uploaded': function (response) {
                    console.log('Video uploaded successfully:', response);
                },
                'video.error': function (error) {
                    console.error('Video upload error:', error);
                },
            },
        });

        const questionsTable = $('#questions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("question.table") }}',
                type: 'POST',
                data: function (d) {
                    d._token = '{{ csrf_token() }}';
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'question', name: 'question' },
                { data: 'time', name: 'time' },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data) {
                        switch (data) {
                            case 'active':
                                return '<span class="badge bg-success">Aktif</span>';
                            case 'inactive':
                                return '<span class="badge bg-secondary">Nonaktif</span>';
                            case 'archived':
                                return '<span class="badge bg-warning">Arsip</span>';
                            default:
                                return '<span class="badge bg-dark">Tidak Diketahui</span>';
                        }
                    }
                },
                {
                    data: 'is_public',
                    name: 'is_public',
                    render: function(data) {
                        return data
                            ? '<span class="badge bg-primary">Public</span>'
                            : '<span class="badge bg-danger">Private</span>';
                    }
                },
                {
                    data: 'start_time',
                    name: 'start_time',
                    render: function(data) {
                        return moment(data).format('D MMMM YYYY');
                    }
                },
                {
                    data: 'end_time',
                    name: 'end_time',
                    render: function(data) {
                        return moment(data).format('D MMMM YYYY');
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                }
            ],
            columnDefs: [
                { className: 'text-center', targets: '_all' },
                { width: '5%', targets: 0 },
                { width: '30%', targets: 1 },
                { width: '10%', targets: 2 },
                { width: '5%', targets: 3 },
                { width: '5%', targets: 4 },
                { width: '15%', targets: 5 },
                { width: '10%', targets: 6 },
                { width: '20%', targets: 7 }
            ],
            order: [[0, 'asc']],
            pageLength: 5,
            lengthMenu: [ [5, 10, 25], [5, 10, 25] ],
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }

        });

        $('[data-bs-toggle="tooltip"]').tooltip();


        $('#questions-table').on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';

            $.ajax({
                url: `{{ route('question.show', ':id') }}`.replace(':id', id),
                method: 'GET',
                data: {
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.data) {
                        const questionData = response.data;
                        const timeString = questionData.time;

                        const [hours, minutes, seconds] = timeString.split(':').map(Number);

                        const totalMinutes = (hours * 60) + minutes;


                        $('#time').val(totalMinutes);

                        $('#question_name').val(questionData.question);
                        $('#thumbnail').val(questionData.thumbnail);
                        $('#time').val(totalMinutes);
                        $('#release_date').val(questionData.start_time.split(' ')[0]);
                        $('#expired_date').val(questionData.end_time.split(' ')[0]);
                        description.html.set(questionData.description);

                        $('#is-public').prop('checked', questionData.is_public === 1);

                        $('#cancel-button').attr('hidden', false);
                        $('#update-button').attr('hidden', false).attr('data-id', questionData.id);
                        $('#save-button').attr('hidden', true);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    toastError('Gagal mengambil data untuk edit!');
                }
            });
        });

        $('#questions-table').on('click', '.delete-btn', function () {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';

            $.ajax({
                url: `{{ route('question.destroy', ':id') }}`.replace(':id', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                success: function (response) {
                    toastSuccess(response.message);
                    $('#questions-table').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Failed to delete the question.');
                },
            });
        });


        $('#questions-table').on('click', '.archive-btn', function () {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';
            const payload = {
                status: 'archived',
            };

            $.ajax({
                url: `{{ route('question.update', ':id') }}`.replace(':id', id),
                method: 'PUT',
                data: {
                    id: id,
                    _token: csrfToken,
                    ...payload },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastSuccess(response.message);
                    questionsTable.ajax.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Failed to update question.');
                },
            });
        });

        $('#questions-table').on('click', '.nonactive-btn', function () {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';
            const payload = {
                status: 'inactive',
            };

            $.ajax({
                url: `{{ route('question.update', ':id') }}`.replace(':id', id),
                method: 'PUT',
                data: {
                    id: id,
                    _token: csrfToken,
                    ...payload },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastSuccess(response.message);
                    questionsTable.ajax.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Failed to update question.');
                },
            });
        });

        $('#questions-table').on('click', '.active-btn', function () {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';
            const payload = {
                status: 'active',
            };

            $.ajax({
                url: `{{ route('question.update', ':id') }}`.replace(':id', id),
                method: 'PUT',
                data: {
                    id: id,
                    _token: csrfToken,
                    ...payload },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastSuccess(response.message);
                    questionsTable.ajax.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Failed to update question.');
                },
            });
        });

        $('#save-button').on('click', function (e) {
            e.preventDefault();

            const questionName = $('#question_name').val();
            const thumbnail = $('#thumbnail').val();
            const releaseDate = $('#release_date').val();
            const expiredDate = $('#expired_date').val();
            const time = $('#time').val();
            const minutes = parseInt(time, 10);
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;

            const formattedTime = `${String(hours).padStart(2, '0')}:${String(remainingMinutes).padStart(2, '0')}`;

            const isPublic = $('#is-public').is(':checked') ? 1 : 0;

            if (!questionName || !releaseDate || !expiredDate) {
                toastWarning('Nama Paket, Tanggal Rilis, dan Tanggal Berakhir harus diisi.');
                return;
            }

            const formData = {
                question: questionName,
                thumbnail: thumbnail,
                start_time: releaseDate,
                end_time: expiredDate,
                description: description.html.get(),
                time: formattedTime,
                is_public: isPublic,
                status: 'active',
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('question.store') }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    toastSuccess('Paket berhasil disimpan!');

                    questionsTable.ajax.reload();
                    console.log(response);

                    $('#question_name').val('');
                    $('#thumbnail').val('');
                    $('#release_date').flatpickr().clear();
                    $('#expired_date').flatpickr().clear();
                    $('#time').val('');
                    $('#is-public').prop('checked', false);
                    description.html.set('');
                },
                error: function (xhr) {
                    toastError('Terjadi kesalahan saat menyimpan data.');
                    console.error(xhr.responseText);
                }
            });
        });

        $('#cancel-button').on('click', function (e) {
            $('#question_name').val('');
            $('#release_date').flatpickr().clear();
            $('#expired_date').flatpickr().clear();
            $('#time').val('');
            $('#is-public').prop('checked', false);
            description.html.set('');

            $('#cancel-button').attr('hidden', true);
            $('#update-button').attr('hidden', true);
            $('#save-button').attr('hidden', false);
        });

        $('#update-button').on('click', function (e) {
            const id = $(this).data('id');
            const csrfToken = '{{ csrf_token() }}';
            const questionName = $('#question_name').val();
            const thumbnail = $('#thumbnail').val();
            const releaseDate = $('#release_date').val();
            const expiredDate = $('#expired_date').val();
            const time = $('#time').val();
            const minutes = parseInt(time, 10);
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;

            const formattedTime = `${String(hours).padStart(2, '0')}:${String(remainingMinutes).padStart(2, '0')}`;
            const isPublic = $('#is-public').is(':checked') ? 1 : 0;


            $.ajax({
                url: `{{ route('question.update', ':id') }}`.replace(':id', id),
                method: 'PUT',
                data: {
                    question: questionName,
                    thumbnail: thumbnail,
                    start_time: releaseDate,
                    end_time: expiredDate,
                    description: description.html.get(),
                    time: formattedTime,
                    is_public: isPublic,
                    status: 'active',
                    _token: '{{ csrf_token() }}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastSuccess(response.message);
                    questionsTable.ajax.reload();
                    $('#question_name').val('');
                    $('#thumbnail').val('');
                    $('#release_date').flatpickr().clear();
                    $('#expired_date').flatpickr().clear();
                    $('#time').val('');
                    $('#is-public').prop('checked', false);
                    description.html.set('');

                    $('#cancel-button').attr('hidden', true);
                    $('#update-button').attr('hidden', true);
                    $('#save-button').attr('hidden', false);
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Failed to update question.');
                },
            });
        });
    });
</script>
