@extends('kmj.layouts.app')

@section('title', 'Endorsements')

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

    <!-- Loader HTML -->
    @include('kmj.include.loader')

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body pt-6">


                        {{-- <div class="mt-5">
                            <h3>Endorsements Section</h3>
                            <p>This is the Endorsements section of the quotation.</p>
                        </div> --}}

                        <!--begin::Endorsements Table-->
                        <div class="card mt-5">
                            <div class="card-header card-header-stretch">
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Endorsements <span style="color: #003153;">(Sum Insured:
                                            TZS {{ number_format($quotationFleetDetail->sum_insured) }})</span> </h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">

                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createEndorsementModal"> <i class="fas fa-plus text-white"></i>
                                        Create Endorsement
                                    </button>
                                </div>
                            </div>

                            <!-- Create Endorsement Modal -->
                            <div class="modal fade" id="createEndorsementModal" tabindex="-1"
                                aria-labelledby="createEndorsementModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createEndorsementForm"
                                            action="{{ route('quotation.makeEndorsement', $quotationFleetDetail->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createEndorsementModalLabel">Create New
                                                    Endorsement</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <input type="hidden" name="quotation_fleet_detail_id"
                                                    value="{{ $quotationFleetDetail->id }}">

                                                <div class="row g-3">
                                                    <!-- Endorsement Type -->
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Endorsement Type</label>
                                                        <select class="form-select" name="endorsement_type_id"
                                                            id="endorsement_type_id" required>
                                                            <option value="">-- Select Type --</option>
                                                            @foreach ($endorsementTypes as $type)
                                                                <option value="{{ $type->id }}">{{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Payment Mode -->
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Payment Mode</label>
                                                        <select class="form-select" name="payment_mode_id"
                                                            id="payment_mode_id" required>
                                                            <option value="">-- Select Mode --</option>
                                                            @foreach ($paymentModes as $mode)
                                                                <option value="{{ $mode->id }}">{{ $mode->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Reason -->
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Reason / Description</label>
                                                        <textarea class="form-control" name="description" rows="3" placeholder="Enter endorsement reason..." required></textarea>
                                                    </div>

                                                    <!-- Cancellation Date (only for type 4) -->
                                                    <div class="col-md-6" id="cancelDateSection" style="display: none;">
                                                        <label class="form-label fw-semibold">Cancellation Date</label>
                                                        <input type="date" class="form-control" name="cancellation_date"
                                                            id="cancellation_date">
                                                    </div>

                                                    <!-- Cheque / EFT Fields (optional) -->
                                                    <div class="col-md-6" id="chequeDetails" style="display: none;">
                                                        <label class="form-label fw-semibold">Cheque Number</label>
                                                        <input type="text" class="form-control" name="cheque_number"
                                                            placeholder="Enter cheque number">
                                                    </div>

                                                    <div class="col-md-6" id="eftDetails" style="display: none;">
                                                        <label class="form-label fw-semibold">EFT Phone Number</label>
                                                        <input type="text" class="form-control"
                                                            name="eft_payment_phone_no" placeholder="Enter phone number">
                                                    </div>

                                                    <!-- Sum Insured Field -->
                                                    <div class="col-md-6" id="sumInsuredSection" style="display: none;">
                                                        <label class="form-label fw-semibold">Sum Insured Amount</label>
                                                        <input type="number" class="form-control" name="sum_insured"
                                                            id="sum_insured" placeholder="Enter Amount">
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" id="submitBtn" class="btn btn-light">
                                                    <i class="bi bi-check-circle me-2 text-white"></i>
                                                    <span id="submitText">Save Endorsement</span>
                                                    <span id="submitSpinner"
                                                        class="spinner-border spinner-border-sm text-light ms-2"
                                                        style="display: none;"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            <div id="kt_endorsements_tab_content" class="tab-content">

                                <div id="kt_endorsements_tab_pane_1" class="card-body p-0 tab-pane fade show active"
                                    role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                <tr>
                                                    <th class="min-w-100px text-center">Date</th>
                                                    <th class="min-w-100px text-center">Premium Earned</th>
                                                    <th class="min-w-100px text-center">Request Id</th>
                                                    <th class="min-w-100px text-center">AckReqDes</th>

                                                    <th class="min-w-100px text-center">Details</th>
                                                    <th class="min-w-100px text-center">Type</th>
                                                    <th class="min-w-100px text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($quotationFleetDetailEndorsements as $quotationFleetDetailEndorsement)
                                                    <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                        <td>
                                                            {{ Carbon\Carbon::parse($quotationFleetDetailEndorsement->created_at)->format('d M Y, h:i:A') }}
                                                        </td>
                                                        <td>
                                                            {{ $quotationFleetDetailEndorsement->endorsement_premium_earned != null ? number_format($quotationFleetDetailEndorsement->endorsement_premium_earned) : '-' }}
                                                        </td>
                                                        <td>{{ $quotationFleetDetailEndorsement->request_id ?? '' }}
                                                        </td>
                                                        <td>{{ $quotationFleetDetailEndorsement->response_status_desc ?? '' }}
                                                        </td>
                                                        <td>{{ \Illuminate\Support\Str::limit($quotationFleetDetailEndorsement->description, 20, '...') }}
                                                        </td>
                                                        <td>{{ $quotationFleetDetailEndorsement->endorsementType->name }}
                                                        </td>

                                                        <td class="text-center">
                                                            @if ($quotationFleetDetailEndorsement->status === 'pending')
                                                                <a href="{{ route('quotation.sendTira.Endorsement', $quotationFleetDetailEndorsement->id) }}"
                                                                    class="btn p-0 border-0 bg-transparent shadow-none align-self-center tiraButton"
                                                                    title="Send TIRA"
                                                                    onclick="return showLoaderWithClose(this);">
                                                                    <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                                        alt="TIRA Logo"
                                                                        style="width: 35px; height: auto;">
                                                                </a>
                                                            @elseif($quotationFleetDetailEndorsement->status === 'success')
                                                                <span
                                                                    class="badge border border-success text-success d-inline-block text-center"
                                                                    style="width: auto; color: green !important;">
                                                                    Success
                                                                </span>
                                                            @elseif($quotationFleetDetailEndorsement->status === 'cancelled')
                                                                <span
                                                                    class="badge border border-danger text-danger d-inline-block text-center"
                                                                    style="width: auto; color: red !important;">
                                                                    Cancelled
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Endorsements Table-->

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const endorsementTypeSelect = document.getElementById('endorsement_type_id');
            const cancellationDateInput = document.getElementById('cancellation_date');

            // Set minimum selectable date to tomorrow
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const minDate = `${yyyy}-${mm}-${dd}`;
            cancellationDateInput.setAttribute('min', minDate);
            cancellationDateInput.value = minDate; // default to tomorrow

            // Div ya kuonyesha premium earned
            const premiumDisplay = document.createElement('div');
            premiumDisplay.style.marginTop = '10px';
            premiumDisplay.style.fontWeight = '600';
            premiumDisplay.style.color = '#003153';
            premiumDisplay.style.display = 'none'; // hide initially
            const modalBody = cancellationDateInput.closest('.modal-body');
            modalBody.appendChild(premiumDisplay);

            function calculatePremium() {
                const typeId = parseInt(endorsementTypeSelect.value);
                if (typeId !== 4) {
                    premiumDisplay.style.display = 'none';
                    return;
                }

                if (!cancellationDateInput.value) {
                    premiumDisplay.textContent = '';
                    return;
                }

                // Use selected date directly without manipulating
                const cancellationDate = new Date(cancellationDateInput.value);
                cancellationDate.setHours(0, 0, 0, 0); // ensure midnight local for accurate diff

                const startDate = new Date('{{ $quotationFleetDetail->cover_note_start_date }}');
                const endDate = new Date('{{ $quotationFleetDetail->cover_note_end_date }}');
                startDate.setHours(0, 0, 0, 0);
                endDate.setHours(0, 0, 0, 0);

                // Total days between start and end
                const dayCovered = Math.max((endDate - startDate) / (1000 * 60 * 60 * 24), 1);

                // Days from start to cancellation
                const cancellationDays = Math.max((cancellationDate - startDate) / (1000 * 60 * 60 * 24), 0);

                let premiumEarned = 0;
                if (cancellationDays > 0) {
                    premiumEarned = (cancellationDays / dayCovered) * parseFloat(
                        '{{ $quotationFleetDetail->total_premium_including_tax }}');
                }

                premiumDisplay.style.display = 'block';
                premiumDisplay.textContent = 'Premium Earned: ' + premiumEarned.toFixed(2);
            }

            endorsementTypeSelect.addEventListener('change', function() {
                const cancelSection = document.getElementById('cancelDateSection');
                if (this.value == '4') {
                    cancelSection.style.display = 'block';
                } else {
                    cancelSection.style.display = 'none';
                    premiumDisplay.style.display = 'none';
                }
                calculatePremium();
            });

            cancellationDateInput.addEventListener('change', calculatePremium);

            // Calculate on page load if type 4 already selected
            if (endorsementTypeSelect.value == '4') {
                calculatePremium();
            }

        });
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createEndorsementForm');
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const typeSelect = document.getElementById('endorsement_type_id');
            const sumInsuredSection = document.getElementById('sumInsuredSection');

            typeSelect.addEventListener('change', function() {
                const selectedType = parseInt(this.value);

                // Type 1 OR 2 = show Sum Insured field
                if (selectedType === 1 || selectedType === 2) {
                    sumInsuredSection.style.display = 'block';
                    document.getElementById('sum_insured').setAttribute('required', true);
                } else {
                    sumInsuredSection.style.display = 'none';
                    document.getElementById('sum_insured').removeAttribute('required');
                }
            });

        });
    </script>





@endsection
