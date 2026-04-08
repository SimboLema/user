@extends('kmj.layouts.app')

@section('title', 'Discharge Voucher')

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
                                    <h3 class="m-0 text-gray-800">Discharge Vouchers</h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createDischargeVoucherModal">
                                        <i class="fas fa-plus text-white"></i> Create Discharge Voucher
                                    </button>
                                </div>
                            </div>

                            <!-- Create Discharge Voucher Modal -->
                            <div class="modal fade" id="createDischargeVoucherModal" tabindex="-1"
                                aria-labelledby="createDischargeVoucherModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createDischargeVoucherForm"
                                            action="{{ route('claim.discharge.voucher.save') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createDischargeVoucherModalLabel">
                                                    Create New Discharge Voucher
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="claim_notification_id"
                                                    value="{{ $claim->id }}">

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Discharge Voucher Number</label>
                                                        <input type="text" name="discharge_voucher_number"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Assessment Number</label>
                                                        <input type="text" name="claim_assessment_number"
                                                            class="form-control">
                                                    </div>
                                                    {{-- <div class="col-md-4">
                                                        <label class="form-label">Cover Note Reference</label>
                                                        <input type="text" name="cover_note_reference_number"
                                                            class="form-control">
                                                    </div> --}}

                                                    <div class="col-md-4">
                                                        <label class="form-label">Discharge Voucher Date</label>
                                                        <input type="date" name="discharge_voucher_date"
                                                            class="form-control">
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

                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Offer Communication Date</label>
                                                        <input type="date" name="claim_offer_communication_date"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Offer Amount</label>
                                                        <input type="number" step="0.01" name="claim_offer_amount"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Claimant Response Date</label>
                                                        <input type="date" name="claimant_response_date"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Adjustment Date</label>
                                                        <input type="date" name="adjustment_date" class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Adjustment Reason</label>
                                                        <input type="text" name="adjustment_reason" class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Adjustment Amount</label>
                                                        <input type="number" step="0.01" name="adjustment_amount"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Reconciliation Date</label>
                                                        <input type="date" name="reconciliation_date"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Reconciliation Summary</label>
                                                        <input type="text" name="reconciliation_summary"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Reconciled Amount</label>
                                                        <input type="number" step="0.01" name="reconciled_amount"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Offer Accepted</label>
                                                        <select name="offer_accepted" class="form-select">
                                                            <option value="N">No</option>
                                                            <option value="Y">Yes</option>
                                                        </select>
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
                                                    <span id="submitText">Save Discharge Voucher</span>
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
                            <div id="kt_vouchers_tab_content" class="tab-content">
                                <div class="card-body p-0 tab-pane fade show active">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten text-center">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Offer Amount</th>
                                                    <th>Reconciled Amount</th>
                                                    <th>Status</th>
                                                    <th>Ack ID</th>
                                                    <th>Ack Status Code</th>
                                                    <th>Ack Status Desc</th>
                                                    <th>Resp Code</th>
                                                    <th>Resp Desc</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($claimDischargeVouchers as $voucher)
                                                    <tr class="text-center text-gray-600 fw-semibold">
                                                        <td>{{ \Carbon\Carbon::parse($voucher->discharge_voucher_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ number_format($voucher->claim_offer_amount, 2) }}</td>
                                                        <td>{{ number_format($voucher->reconciled_amount, 2) }}</td>
                                                        <td><span class="badge border border-info text-info">
                                                                {{ ucfirst($voucher->status ?? 'pending') }}
                                                            </span></td>
                                                        <td>{{ $voucher->acknowledgement_id }}</td>
                                                        <td>{{ $voucher->acknowledgement_status_code }}</td>
                                                        <td>{{ $voucher->acknowledgement_status_desc }}</td>
                                                        <td>{{ $voucher->response_status_code }}</td>
                                                        <td>{{ $voucher->response_status_desc }}</td>
                                                        <td>
                                                            @if ($voucher->status === 'pending')
                                                                <a href="{{ route('claim.discharge.voucher.sendTira', $voucher->id) }}"
                                                                    class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                                                    title="Send TIRA">
                                                                    <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                                        alt="TIRA Logo"
                                                                        style="width: 35px; height: auto;">
                                                                </a>
                                                            @elseif($voucher->status === 'success')
                                                                <span
                                                                    class="badge border border-success text-success d-inline-block text-center"
                                                                    style="width: auto; color: green !important;">
                                                                    Risknote Issued
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="9">No Discharge Vouchers found.</td>
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

            $('#createDischargeVoucherModal').on('shown.bs.modal', function() {
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
            const form = document.getElementById('createDischargeVoucherForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            form.addEventListener('submit', function(e) {

                // Prevent double submission
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                // Disable button and show loader
                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                submitSpinner.style.display = 'inline-block';

                // Just simulate loading (you can remove this timeout if you want)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Save Endorsement';
                    submitSpinner.style.display = 'none';
                    console.log('Form submitted (loader demo only)');
                }, 2000);
            });
        });
    </script>

@endsection
