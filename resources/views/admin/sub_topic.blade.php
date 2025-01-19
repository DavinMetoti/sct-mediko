@extends('layouts.app')

@section('title', 'Sub Topic')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('partials.sidebar')
    @include('partials.navbar')
    <div class="content" id="content">
        <div class="px-3">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex justify-content-between align-items-center">
                                <h3>Topik</h3>
                                <button class="btn btn-success" id="add-topic-btn">
                                    <i class="fas fa-plus me-2"></i> <span>Tambah</span>
                                </button>
                            </div>
                            <div class="table-responsive mt-3">
                                <table class="table table-stripped" id="table-topic">
                                    <thead>
                                        <tr>
                                            <th class="text-center w-5">No</th>
                                            <th class="text-center w-80">Topik</th>
                                            <th class="text-center w-15"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="card-sub-topic" class="col-6 hidden">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex justify-content-between align-items-center">
                                <h3>Sub Topik</h3>
                                <button class="btn btn-success" id="add-subtopic-btn">
                                    <i class="fas fa-plus me-2"></i> <span>Tambah</span>
                                </button>
                            </div>
                            <div class="table-responsive mt-3">
                                <table class="table striped" id="table-sub-topic">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Sub Topik</th>
                                            <th class="text-center">Document</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="topicModal" tabindex="-1" aria-labelledby="topicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="topicModalLabel">Tambah Topik</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
            <label for="topic">Topik</label>
            <input type="text" id="topic" name="topic" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-type="store" id="btn-topic">Simpan</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subTopicModal" tabindex="-1" aria-labelledby="subTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subTopicModalLabel">Tambah Sub Topik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3 hidden">
                    <label for="sub-topic">Sub Topik</label>
                    <input type="text" id="id-topic" name="id-topic" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="sub-topic">Sub Topik</label>
                    <input type="text" id="sub-topic" name="sub-topic" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="sub-topic-description">Deskripsi</label>
                    <textarea id="sub-topic-description" name="sub-topic-description" class="form-control" rows="3" placeholder="Masukkan deskripsi sub topik..."></textarea>
                </div>
                <div class="form-group">
                    <label for="file-sub-topic" class="form-label">Upload File</label>
                    <div id="dropzone" class="dropzone">
                        <p id="dropzone-text">Drag & drop a file here or click to upload</p>
                        <input type="file" id="file-sub-topic" name="file-sub-topic" class="form-control" hidden>
                        <!-- Progress Bar -->
                        <div id="progress-bar-container" class="progress hidden">
                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-type="store" id="btn-sub-topic">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.script')

<script>
    let pathPdf = '';
    let subTopicTable;

    $(document).ready(function() {
        const tableTopic = $('#table-topic').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.topic.table') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            columnDefs: [
                { targets: 0, width: '5%' },
                { targets: 1, width: '70%' },
                { targets: 2, width: '25%' },
            ],
            responsive: true,
            autoWidth: false,
            language: {
                lengthMenu: '_MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                search: 'Search:'
            }
        });

        $('#add-subtopic-btn').on('click', function() {
            const btnSubTopic = $('#btn-sub-topic');
            $('#subTopicModal').modal('show');

            btnSubTopic
                .removeClass('btn-warning')
                .addClass('btn-primary')
                .text('Simpan')
                .attr('data-type', 'store')
                .removeAttr('data-id');
        });

        $('#add-topic-btn').on('click', function() {
            const btnTopic = $('#btn-topic');
            $('#topic').val('');

            btnTopic
                .removeClass('btn-warning')
                .addClass('btn-primary')
                .text('Simpan')
                .attr('data-type', 'store')
                .removeAttr('data-id');

            $('#topicModal').modal('show');
        });

        $('#btn-topic').on('click', function() {
            let type = $(this).attr('data-type');

            const id = $(this).data('id');
            const data = {
                name : $('#topic').val(),
                _token : '{{ csrf_token() }}'
            }

            if (type == 'store') {
                $.ajax({
                    url: '{{ route('topic.store') }}',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        toastSuccess('Topik berhasil ditambahkan!');
                        $('#topicModal').modal('hide');
                        tableTopic.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        toastError('Gagal menambahkan topik. Silakan coba lagi.');
                    }
                });
            } else {
                $.ajax({
                    url: '{{ route('topic.update',':id') }}'.replace(':id', id),
                    type: 'PUT',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        toastSuccess('Topic berhasil diperbarui!');
                        $('#topicModal').modal('hide');
                        tableTopic.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        toastError('Gagal memperbarui topik. Silakan coba lagi.');
                    }
                });
            }
        });

        $('#btn-sub-topic').on('click', function() {
            let type = $(this).attr('data-type');
            const id = $(this).attr('data-id');
            const idHeaderSubTopic = $('#id-topic').val();
            const subTopic = $('#sub-topic').val();
            const description = $('#sub-topic-description').val();
            const path = pathPdf;
            const dropzoneText = document.getElementById('dropzone-text');

            if (type == 'store'){
                $.ajax({
                    url: "{{ route('sub-topic.store') }}",
                    method: "POST",
                    data: {
                        'name': subTopic,
                        'description': description,
                        'path': path,
                        'id_header_sub_topic': idHeaderSubTopic,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastSuccess('Sub topic berhasil disimpan!');
                        $('#subTopicModal').modal('hide');
                        $('#sub-topic').val('');
                        $('#sub-topic-description').val('');
                        pathPdf = "";
                        dropzoneText.textContent = 'Drag & drop a file here or click to upload'
                        subTopicTable.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        const errorMessage = xhr.responseJSON?.message || 'Gagal menyimpan sub topic. Silakan coba lagi.';
                        toastError(errorMessage);
                    }
                });
            } else {
                $.ajax({
                    url: "{{ route('sub-topic.update',':id') }}".replace(':id', id),
                    method: "PUT",
                    data: {
                        'name': subTopic,
                        'description': description,
                        'path': path,
                        'id_header_sub_topic': idHeaderSubTopic,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastSuccess('Sub topic berhasil disimpan!');
                        $('#subTopicModal').modal('hide');
                        $('#sub-topic').val('');
                        $('#sub-topic-description').val('');
                        pathPdf = "";
                        dropzoneText.textContent = 'Drag & drop a file here or click to upload'
                        subTopicTable.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        const errorMessage = xhr.responseJSON?.message || 'Gagal menyimpan sub topic. Silakan coba lagi.';
                        toastError(errorMessage);
                    }
                });
            }
        });

        $(document).on('click', '#btn-edit-topic', function() {
            const id = $(this).data('id');
            const topicModal = $('#topicModal');
            const topicTitleModal = $('#topicModalLabel');
            const btnTopic = $('#btn-topic');

            $.ajax({
                url: '{{ route("topic.show", ":id") }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    topicTitleModal.text('Edit Topik');

                    topicModal.modal('show');

                    $('#topic').val(response.data.name);

                    btnTopic
                        .removeClass('btn-primary')
                        .addClass('btn-warning')
                        .text('Update')
                        .attr('data-type', 'update')
                        .attr('data-id', id);
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseJSON?.message || 'Something went wrong');
                }
            });
        });

        $(document).on('click', '#btn-show-topic', function() {

            const id = $(this).data('id');
            const subTopicCard = $('#card-sub-topic');
            const tableRow = $(this).closest('tr');
            $('#id-topic').val(id);

            subTopicCard.addClass('hidden');

            if ($.fn.DataTable.isDataTable('#table-sub-topic')) {
                $('#table-sub-topic').DataTable().destroy();
            }

            $('#table-topic tbody tr').removeClass('bg-blue text-white');

            tableRow.addClass('bg-blue text-white');

            setTimeout(function () {
                subTopicCard.removeClass('hidden');
            }, 500);

            subTopicTable = $('#table-sub-topic').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.sub-topic.table') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'preview', name: 'preview', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                columnDefs: [
                    {
                        targets: 3,
                        width: '15%',
                        className: 'text-center'
                    },
                    {
                        targets: 2,
                        width: '25%',
                        className: 'text-center'
                    },
                    {
                        targets: 0,
                        width: '10%',
                        className: 'text-center'
                    },
                ],
                responsive: true,
                autoWidth: false,
                language: {
                    lengthMenu: '_MENU_',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'No entries to show',
                    search: 'Search:'
                }
            });

        });

        $(document).on('click', '#btn-delete-topic', function() {
            const id = $(this).data('id');
            const btnTopic = $('#btn-topic');
            const subTopicCard = $('#card-sub-topic');

            confirmationModal.open({
                message: 'Apakah anda yakin akan menghapus topik ini? semua data sub topik akan terhapus juga',
                severity: 'warn',
                onAccept: () => {
                    $.ajax({
                        url: '{{ route("topic.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            tableTopic.ajax.reload();
                            toastSuccess(response.message || 'Topik berhasil dihapus!');

                            subTopicCard.addClass('hidden');
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseJSON?.message || 'Something went wrong');
                            toastError(xhr.responseJSON?.message || 'Gagal menghapus topik. Silakan coba lagi.');
                        }
                    });
                },
                onReject: () => {
                    console.log('Rejected!');
                },
            });

        });

        $(document).on('click', '.btn-delete-subtopic', function() {
            const id = $(this).data('id');

            confirmationModal.open({
                message: 'Apakah anda yakin akan menghapus sub topik ini?',
                severity: 'warn',
                onAccept: () => {
                    $.ajax({
                        url: '{{ route("sub-topic.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            subTopicTable.ajax.reload();
                            toastSuccess(response.message || 'Sub topik berhasil dihapus!');
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseJSON?.message || 'Something went wrong');
                            toastError(xhr.responseJSON?.message || 'Gagal menghapus sub topik. Silakan coba lagi.');
                        }
                    });
                },
                onReject: () => {
                    console.log('Rejected!');
                },
            });
        });

        $(document).on('click', '.btn-edit-subtopic', function() {
            const id = $(this).data('id');
            const subtopicModal = $('#subTopicModal');
            const subtopicTitleModal = $('#subTopicModalLabel');
            const btnSubTopic = $('#btn-sub-topic');

            $.ajax({
                url: '{{ route("sub-topic.show", ":id") }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    subtopicTitleModal.text('Edit Topik');

                    subtopicModal.modal('show');

                    $('#sub-topic').val(response.sub_topic.name);
                    $('#sub-topic-description').val(response.sub_topic.description);

                    btnSubTopic
                        .removeClass('btn-primary')
                        .addClass('btn-warning')
                        .text('Update')
                        .attr('data-type', 'update')
                        .attr('data-id', id);
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseJSON?.message || 'Something went wrong');
                }
            });
        });

    });

    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-sub-topic');
        const dropzoneText = document.getElementById('dropzone-text');
        const progressBarContainer = document.getElementById('progress-bar-container');
        const progressBar = document.getElementById('progress-bar');

        dropzone.addEventListener('click', function () {
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                uploadFile(fileInput.files[0]);
            }
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');

            const files = e.dataTransfer.files;

            if (files.length > 0) {
                fileInput.files = files;
                uploadFile(files[0]);
            } else {
                toastError('Tidak ada file yang dipilih.');
            }
        });

        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function () {
            dropzone.classList.remove('dragover');
        });

        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');


            progressBarContainer.classList.remove('hidden');
            progressBar.style.width = '0%';
            dropzoneText.textContent = 'Uploading'

            $.ajax({
                url: "{{ route('admin.question.upload_file') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    const xhr = new window.XMLHttpRequest();


                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            progressBar.style.width = percentComplete + '%';
                        }
                        });

                        return xhr;
                    },
                    success: function (response) {
                        pathPdf = response.link;
                        progressBar.style.width = '100%';
                        toastSuccess('File berhasil diupload');
                        dropzoneText.textContent = `File uploaded: ${file.name}`;
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        toastError('Gagal upload file: ' + (xhr.responseJSON?.error || 'Unknown error'));
                    },
                    complete: function () {

                        setTimeout(() => {
                            progressBarContainer.classList.add('hidden');
                            progressBar.style.width = '0%';
                        }, 500);
                    }
                });
            }
    });



</script>
