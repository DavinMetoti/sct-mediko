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
                        <h5 class="mb-0">Daftar Pembayaran</h5>
                    </div>

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="verification-tab" data-bs-toggle="tab" href="#verification" role="tab" aria-controls="verification" aria-selected="true">Verification</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="paid-tab" data-bs-toggle="tab" href="#paid" role="tab" aria-controls="paid" aria-selected="false">Paid</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cancel-tab" data-bs-toggle="tab" href="#cancel" role="tab" aria-controls="cancel" aria-selected="false">Cancel</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="paymentTabsContent">
                        <!-- Verification Tab -->
                        <div class="tab-pane fade show active" id="verification" role="tabpanel" aria-labelledby="verification-tab">
                            @if($invoices_verification->isEmpty())
                                <p class="text-center text-muted">No invoices to verify.</p>
                            @else
                                <table id="verificationTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No Invoice</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>User</th>
                                            <th>Bukti Pembayaran</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices_verification as $invoice)
                                        <tr>
                                            <td>{{  $invoice->formatted_id }}</td>
                                            <td>{{ $invoice->package->name }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $invoice->status }}</span>
                                            </td>
                                            <td>{{ $invoice->created_at }}</td>
                                            <td>{{ $invoice->user->name }}</td>
                                            <td>
                                                @if($invoice->payment_proof)
                                                    <button class="btn btn-sm btn-info w-full" data-bs-toggle="modal" data-bs-target="#paymentProofModal" data-image="{{ asset($invoice->payment_proof) }}">View</button>
                                                @else
                                                    <span class="text-muted">No Proof</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-success" data-id="{{ $invoice->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $invoice->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- Paid Tab -->
                        <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                            @if($invoices_paid->isEmpty())
                                <p class="text-center text-muted">No paid invoices.</p>
                            @else
                                <table id="paidTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No Invoice</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>User</th>
                                            <th>Bukti Pembayaran</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices_paid as $invoice)
                                        <tr>
                                            <td>{{  $invoice->formatted_id }}</td>
                                            <td>{{ $invoice->package->name }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $invoice->status }}</span>
                                            </td>
                                            <td>{{ $invoice->created_at }}</td>
                                            <td>{{ $invoice->user->name }}</td>
                                            <td class="text-center">
                                                @if($invoice->payment_proof)
                                                    <button class="btn btn-sm btn-info w-full" data-bs-toggle="modal" data-bs-target="#paymentProofModal" data-image="{{ asset($invoice->payment_proof) }}">View</button>
                                                @else
                                                    <span class="text-muted">No Proof</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $invoice->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- Cancel Tab -->
                        <div class="tab-pane fade" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">
                            @if($invoices_cancel->isEmpty())
                                <p class="text-center text-muted">No canceled invoices.</p>
                            @else
                                <table id="cancelTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No Invoice</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>User</th>
                                            <th>Bukti Pembayaran</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices_cancel as $invoice)
                                        <tr>
                                            <td>{{  $invoice->formatted_id }}</td>
                                            <td>{{ $invoice->package->name }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $invoice->status }}</span>
                                            </td>
                                            <td>{{ $invoice->created_at }}</td>
                                            <td>{{ $invoice->user->name }}</td>
                                            <td class="text-center">
                                                @if($invoice->payment_proof)
                                                <button class="btn btn-sm btn-info w-full" id="show-proof" data-bs-toggle="modal" data-bs-target="#paymentProofModal" data-image="{{ asset($invoice->payment_proof) }}">View</button>
                                                @else
                                                    <span class="text-muted">No Proof</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-success" data-id="{{ $invoice->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Payment Proof -->
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentProofModalLabel">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="paymentProofImage" src="" alt="Payment Proof" class="img-fluid" />
            </div>
        </div>
    </div>
</div>

@endsection

@include('partials.script')

<script>
    $(document).ready(function() {
        $('#verificationTable').DataTable();
        $('#paidTable').DataTable();
        $('#cancelTable').DataTable();

        $('#paymentProofModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var imageUrl = button.data('image');
            $('#paymentProofImage').attr('src', imageUrl);
        });

        $('.btn-success').on('click', function() {
            var invoiceId = $(this).data('id');
            var status = 'paid';

            updateStatus(invoiceId, status);
        });

        $('.btn-danger').on('click', function() {
            var invoiceId = $(this).data('id');
            var status = 'cancel';

            updateStatus(invoiceId, status);
        });

        function updateStatus(invoiceId, status) {
            confirmationModal.open({
                message: 'Apakah anda yakin ingin merubah status menjadi '+status+'?',
                severity: 'warn',
                onAccept: () => {
                    $.ajax({
                        url: '{{ route("list-invoice.update", ":id") }}'.replace(':id', invoiceId),
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status,
                        },
                        success: function(response) {
                            toastSuccess('Invoice status updated to ' + status);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            alert('Error updating invoice status');
                        }
                    });
                },
                onReject: () => {
                    console.log('Rejected!');
                },
            });
        }
    });
</script>


