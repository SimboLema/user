@extends('kmj.layouts.app')

@section('title', 'Claim Payments')

@section('content')

    <style>
        body {
            background-image: none !important;
        }

        .btn-light {
            color: #ffffff !important;
            background-color: #003153 !important;
            border-color: #003153 !important;
        }

        .btn-light:hover {
            color: #001f33 !important;
            background-color: #001f33 !important;
            border-color: #001f33 !important;
        }
    </style>

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body pt-6">

                        @include('kmj.claim.components.tabs-nav')

                        <div class="card mt-5">
                            <div class="card-header card-header-stretch">
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Claim Payments</h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createClaimPaymentModal">
                                        <i class="fas fa-plus text-white"></i> Create Claim Payment
                                    </button>
                                </div>
                            </div>

                            <!-- Create Claim Payment Modal -->
                            <div class="modal fade" id="createClaimPaymentModal" tabindex="-1"
                                aria-labelledby="createClaimPaymentModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createClaimPaymentForm" action="{{ route('claim.payment.save') }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createClaimPaymentModalLabel">
                                                    Create New Claim Payment
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="claim_notification_id"
                                                    value="{{ $claim->id }}">

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Payment Number</label>
                                                        <input type="text" name="claim_payment_number"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Intimation Number</label>
                                                        <input type="text" name="claim_intimation_number"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Payment Date</label>
                                                        <input type="date" name="payment_date" class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Paid Amount</label>
                                                        <input type="number" step="0.01" name="paid_amount"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Payment Mode</label>
                                                        <select name="payment_mode" class="form-select">
                                                            <option value="">Select</option>
                                                            <option value="1">Cash</option>
                                                            <option value="2">Cheque</option>
                                                            <option value="3">EFT</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Parties Notified</label>
                                                        <select name="parties_notified" class="form-select">
                                                            <option value="Y">Yes</option>
                                                            <option value="N">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Net Premium Earned</label>
                                                        <input type="number" step="0.01" name="net_premium_earned"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Resulted Litigation</label>
                                                        <select name="claim_resulted_litigation" class="form-select">
                                                            <option value="N">No</option>
                                                            <option value="Y">Yes</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Litigation Reason</label>
                                                        <input type="text" name="litigation_reason" class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Currency</label>
                                                        <select name="currency_id" class="form-select">
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency->id }}">{{ $currency->code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Exchange Rate</label>
                                                        <input type="number" step="0.01" name="exchange_rate"
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                <hr class="my-4">
                                                <h5>Claimants</h5>
                                                <div id="claimants-container"></div>
                                                <button type="button" class="btn btn-outline-primary mt-2"
                                                    id="add-claimant-btn">
                                                    + Add Claimant
                                                </button>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" id="submitBtn" class="btn btn-light">
                                                    <span id="submitText">Save Claim Payment</span>
                                                    <span id="submitSpinner"
                                                        class="spinner-border spinner-border-sm text-light ms-2"
                                                        style="display: none;"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div id="kt_payments_tab_content" class="tab-content">
                                <div class="card-body p-0 tab-pane fade show active">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten text-center">
                                                <tr>
                                                    <th>Payment Date</th>
                                                    <th>Paid Amount</th>
                                                    <th>Status</th>
                                                    {{-- <th>Ack ID</th> --}}
                                                    <th>Ack Code</th>
                                                    <th>Ack Desc</th>
                                                    <th>Resp Code</th>
                                                    <th>Resp Desc</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($claimPayments as $payment)
                                                    <tr class="text-center text-gray-600 fw-semibold">
                                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ number_format($payment->paid_amount, 2) }}</td>
                                                        <td>
                                                            <span class="badge border border-info text-info">
                                                                {{ ucfirst($payment->status ?? 'pending') }}
                                                            </span>
                                                        </td>
                                                        {{-- <td>{{ $payment->acknowledgement_id }}</td> --}}
                                                        <td>{{ $payment->acknowledgement_status_code }}</td>
                                                        <td>{{ $payment->acknowledgement_status_desc }}</td>
                                                        <td>{{ $payment->response_status_code }}</td>
                                                        <td>{{ $payment->response_status_desc }}</td>
                                                        <td>
                                                            @if ($payment->status === 'pending')
                                                                <a href="{{ route('claim.payment.sendTira', $payment->id) }}"
                                                                    class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                                                    title="Send TIRA">
                                                                    <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                                        alt="TIRA Logo"
                                                                        style="width: 35px; height: auto;">
                                                                </a>
                                                            @elseif($payment->status === 'success')
                                                                <span class="badge border border-success text-success">
                                                                    Risk Issued
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="9">No Claim Payments found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(function() {
            let claimantIndex = 0;
            $('#createClaimPaymentModal').on('shown.bs.modal', function() {
                $('#add-claimant-btn').off('click.claimant').on('click.claimant', function() {
                    claimantIndex++;
                    const idx = claimantIndex;
                    const card = $(`
            <div class="card p-3 mt-3 claimant-card" data-idx="${idx}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="m-0">Claimant #${idx}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-claimant">Remove</button>
                </div>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="claimants[${idx}][claimant_category]" class="form-select">
                            <option value="">Select</option>
                            <option value="1">Policyholder</option>
                            <option value="2">Third Party</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="claimants[${idx}][claimant_type]" class="form-select">
                            <option value="">Select</option>
                            <option value="1">Individual</option>
                            <option value="2">Corporate</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ID Type</label>
                        <select name="claimants[${idx}][claimant_id_type]" class="form-select">
                            <option value="">Select</option>
                            <option value="1">National ID</option>
                            <option value="2">Voter ID</option>
                            <option value="3">Passport</option>
                            <option value="4">Driving License</option>
                            <option value="5">ZAN ID</option>
                            <option value="6">TIN</option>
                            <option value="7">Company Reg No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ID Number</label>
                        <input type="text" name="claimants[${idx}][claimant_id_number]" class="form-control">
                    </div>
                </div>
            </div>`);
                    $('#claimants-container').append(card);
                });

                $('#claimants-container').off('click.remove').on('click.remove', '.remove-claimant',
                    function() {
                        $(this).closest('.claimant-card').remove();
                    });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createClaimPaymentForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            form.addEventListener('submit', function(e) {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                submitSpinner.style.display = 'inline-block';

                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Save Claim Payment';
                    submitSpinner.style.display = 'none';
                }, 2000);
            });
        });
    </script>

@endsection
