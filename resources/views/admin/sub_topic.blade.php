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
                                <table class="table table-bordered table-stripped" id="table-topic">
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
@endsection

@include('partials.script')

<script>
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
                {
                    targets: 2,
                    width: '20%',
                    className: 'text-center'
                }
            ],
            responsive: true,
            autoWidth: false
        });


        $('#add-topic-btn').on('click', function() {
            $('#topicModal').modal('show');
        });


        $('#btn-topic').on('click', function() {
            const data = {
                name : $('#topic').val(),
                _token : '{{ csrf_token() }}'
            }
            $.ajax({
                url: '{{ route('topic.store') }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    alert('Topic berhasil ditambahkan!');
                    console.log(response);

                    $('#topicModal').modal('hide');

                },
                error: function(xhr, status, error) {
                    // Tampilkan pesan error
                    console.error(xhr.responseText);
                    alert('Gagal menambahkan SubTopic. Silakan coba lagi.');
                }
            });
        });


    });


</script>
