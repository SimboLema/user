@extends('kmj.layouts.app')

@section('title', 'Assessment')

@section('content')

    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
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
                                    <h3 class="m-0 text-gray-800">Assessments</h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createAssessmentModal"> <i class="fas fa-plus text-white"></i>
                                        Create Assessment
                                    </button>
                                </div>
                            </div>

                            <!-- Create Assessment Modal -->
                            <div class="modal fade" id="createAssessmentModal" tabindex="-1"
                                aria-labelledby="createAssessmentModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createAssessmentForm" action="{{ route('claim.assessment.save') }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createAssessmentModalLabel">Create New
                                                    Assessment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="claim_notification_id"
                                                    value="{{ $claim->id }}">

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Assessment Number</label>
                                                        <input type="text" name="claim_assessment_number"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Intimation Number</label>
                                                        <input type="text" name="claim_intimation_number"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Assessment Received Date</label>
                                                        <input type="date" name="assessment_received_date"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Assessment Report Summary</label>
                                                        <textarea name="assessment_report_summary" class="form-control" rows="2" required></textarea>
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
                                                        <label class="form-label">Assessment Amount</label>
                                                        <input type="number" step="0.01" name="assessment_amount"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Approved Claim Amount</label>
                                                        <input type="number" step="0.01" name="approved_claim_amount"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Approval Date</label>
                                                        <input type="date" name="claim_approval_date"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Claim Approval Authority</label>
                                                        <input type="text" name="claim_approval_authority"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Is Re-Assessment</label>
                                                        <select name="is_re_assessment" class="form-select">
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
                                                    <span id="submitText">Save Assessment</span>
                                                    <span id="submitSpinner"
                                                        class="spinner-border spinner-border-sm text-light ms-2"
                                                        style="display: none;"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Assessments Table -->
                            <div id="kt_assessments_tab_content" class="tab-content">
                                <div class="card-body p-0 tab-pane fade show active">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten text-center">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Assessment Amount</th>
                                                    <th>Approved Amount</th>
                                                    {{-- <th>Re-Assessment</th> --}}
                                                    {{-- <th>Assessor</th> --}}
                                                    <th>Status</th>

                                                    <th>Acknowledgement ID</th>
                                                    <th>Acknowledgement Status Code</th>
                                                    <th>Acknowledgement Status Desc</th>
                                                    <th>Response Status Code</th>
                                                    <th>Response Status Desc</th>


                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($claimAssessments as $assessment)
                                                    <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                        <td>{{ \Carbon\Carbon::parse($assessment->assessment_received_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ number_format($assessment->assessment_amount, 2) }}</td>
                                                        <td>{{ number_format($assessment->approved_claim_amount, 2) }}</td>
                                                        {{-- <td>{{ $assessment->is_re_assessment }}</td> --}}
                                                        {{-- <td>{{ $assessment->assessor_name ?? '-' }}</td> --}}
                                                        <td>
                                                            <span
                                                                class="badge border border-info text-info">{{ ucfirst($assessment->status) }}</span>
                                                        </td>

                                                        <td>{{ $assessment->acknowledgement_id }}</td>
                                                        <td>{{ $assessment->acknowledgement_status_code }}</td>
                                                        <td>{{ $assessment->acknowledgement_status_desc }}</td>
                                                        <td>{{ $assessment->response_status_code }}</td>
                                                        <td>{{ $assessment->response_status_desc }}</td>
                                                        <td>
                                                            @if ($assessment->status === 'pending')
                                                                <a href="{{ route('claim.assessment.sendTira', $assessment->id) }}"
                                                                    class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                                                    title="Send TIRA">
                                                                    <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                                        alt="TIRA Logo"
                                                                        style="width: 35px; height: auto;">
                                                                </a>
                                                            @elseif($assessment->status === 'success')
                                                                <span
                                                                    class="badge border border-success text-success d-inline-block text-center"
                                                                    style="width: auto; color: green !important;">
                                                                    Risknote Issued
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    {{-- @if ($assessment->claimants && $assessment->claimants->count())
                                                        <tr>
                                                            <td colspan="7" class="p-0">
                                                                <table class="table table-sm table-bordered mb-0">
                                                                    <thead>
                                                                        <tr class="text-center">
                                                                            <th>Category</th>
                                                                            <th>Type</th>
                                                                            <th>ID Type</th>
                                                                            <th>ID Number</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($assessment->claimants as $claimant)
                                                                            <tr class="text-center">
                                                                                <td>{{ $claimant->claimant_category }}</td>
                                                                                <td>{{ $claimant->claimant_type }}</td>
                                                                                <td>{{ $claimant->claimant_id_type }}</td>
                                                                                <td>{{ $claimant->claimant_id_number }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endif --}}
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="7">No Assessments found.</td>
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

            $('#createAssessmentModal').on('shown.bs.modal', function() {

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
                </div>
            `);
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
            const form = document.getElementById('createAssessmentForm');
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
