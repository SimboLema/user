@extends('kmj.layouts.app')

@section('title', 'Intimation')

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

                        @include('kmj.claim.components.tabs-nav')



                        <!--begin::Intimation Table-->
                        <div class="card mt-5">
                            <div class="card-header card-header-stretch">
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Rejection</h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">

                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createClaimInimationModal"> <i class="fas fa-plus text-white"></i>
                                        Create Intimation
                                    </button>
                                </div>
                            </div>

                            <!-- Create Intimation Modal -->
                            <div class="modal fade" id="createClaimInimationModal" tabindex="-1"
                                aria-labelledby="createClaimInimationModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createClaimInimation" action="{{ route('claim.intimation.save') }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createClaimInimationModalLabel">Create New
                                                    Intimation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="claim_notification_id"
                                                    value="{{ $claim->id }}">

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Intimation Number</label>
                                                        <input type="text" name="claim_intimation_number"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Intimation Date</label>
                                                        <input type="date" name="claim_intimation_date"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Currency</label>
                                                        <select name="currency_id" class="form-select select2">
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
                                                        <label class="form-label">Estimated Amount</label>
                                                        <input type="number" step="0.01" name="claim_estimated_amount"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Reserve Amount</label>
                                                        <input type="number" step="0.01" name="claim_reserve_amount"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Reserve Method</label>
                                                        <input type="text" name="claim_reserve_method"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Loss Assessment Option</label>
                                                        <select name="loss_assessment_option" class="form-select">
                                                            <option value="1">In-house - Insurer employee</option>
                                                            <option value="2">External - Registered insurance adjuster
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Assessor Name</label>
                                                        <input type="text" name="assessor_name" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Assessor ID Number</label>
                                                        <input type="text" name="assessor_id_number"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Assessor ID Type</label>
                                                        <select name="assessor_id_type" class="form-select">
                                                            <option value="1">National ID</option>
                                                            <option value="2">Voter ID</option>
                                                            <option value="3">Passport</option>
                                                            <option value="4">Driving License</option>
                                                            <option value="5">ZAN ID</option>
                                                            <option value="6">TIN</option>
                                                            <option value="7">Company Registration</option>
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
                                                    <span id="submitText">Save Intimation</span>
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
                                                <tr class="text-center">
                                                    <th class="min-w-100px">Date</th>
                                                    <th class="min-w-100px">Estimated Amount</th>
                                                    <th class="min-w-100px">Reserve Amount</th>
                                                    <th class="min-w-100px">Loss Assessment</th>
                                                    <th class="min-w-100px">Assessor</th>
                                                    <th class="min-w-100px">Status</th>
                                                    <th class="min-w-100px">Acknowledgement ID</th>
                                                    <th class="min-w-100px">Acknowledgement Status Code</th>
                                                    <th class="min-w-100px">Acknowledgement Status Desc</th>
                                                    <th class="min-w-100px">Response Status Code</th>
                                                    <th class="min-w-100px">Response Status Desc</th>
                                                    <th class="min-w-100px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($claimIntimations as $intimation)
                                                    <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                        <td>{{ \Carbon\Carbon::parse($intimation->claim_intimation_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ number_format($intimation->claim_estimated_amount, 2) }}</td>
                                                        <td>{{ number_format($intimation->claim_reserve_amount, 2) }}</td>
                                                        <td>{{ $intimation->loss_assessment_option }}</td>
                                                        <td>{{ $intimation->assessor_name }}</td>
                                                        <td>

                                                            @if ($intimation->status === 'pending')
                                                                <span
                                                                    class="badge border border-warning text-success d-inline-block text-center"
                                                                    style="width: auto; color: orange !important;">
                                                                    Awaiting receipt
                                                                </span>
                                                            @elseif($intimation->status === 'success')
                                                                <span
                                                                    class="badge border border-success text-success d-inline-block text-center"
                                                                    style="width: auto; color: green !important;">
                                                                    Risknote Issued
                                                                </span>
                                                            @endif


                                                        </td>
                                                        <td>{{ $intimation->acknowledgement_id }}</td>
                                                        <td>{{ $intimation->acknowledgement_status_code }}</td>
                                                        <td>{{ $intimation->acknowledgement_status_desc }}</td>
                                                        <td>{{ $intimation->response_status_code }}</td>
                                                        <td>{{ $intimation->response_status_desc }}</td>
                                                        <td>
                                                            @if ($intimation->status === 'pending')
                                                                <a href="{{ route('claim.intimation.sendTira', $intimation->id) }}"
                                                                    class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                                                    title="Send TIRA">
                                                                    <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                                        alt="TIRA Logo"
                                                                        style="width: 35px; height: auto;">
                                                                </a>
                                                            @elseif($intimation->status === 'success')
                                                                <span
                                                                    class="badge border border-success text-success d-inline-block text-center"
                                                                    style="width: auto; color: green !important;">
                                                                    Risknote Issued
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    {{-- Optional: show Claimants for this intimation --}}
                                                    @if ($intimation->claimants && $intimation->claimants->count())
                                                        <tr>
                                                            <td colspan="12" class="p-0">
                                                                <table class="table table-sm table-bordered mb-0">
                                                                    <thead>
                                                                        <tr class="text-center">
                                                                            <th>Name</th>
                                                                            <th>Birth Date</th>
                                                                            <th>Gender</th>
                                                                            <th>Category</th>
                                                                            {{-- <th>Type</th>
                                                                            <th>ID Type</th>
                                                                            <th>ID Number</th> --}}
                                                                            <th>Phone</th>
                                                                            <th>Email</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($intimation->claimants as $claimant)
                                                                            <tr class="text-center">
                                                                                <td>{{ $claimant->claimant_name }}</td>
                                                                                <td>{{ $claimant->claimant_birth_date }}
                                                                                </td>
                                                                                <td>{{ $claimant->gender }}</td>
                                                                                {{-- <td>{{ $claimant->claimant_category }}</td>
                                                                                <td>{{ $claimant->claimant_type }}</td>
                                                                                <td>{{ $claimant->claimant_id_type }}</td> --}}
                                                                                {{-- <td>{{ $claimant->claimant_id_number }} --}}
                                                                                {{-- </td> --}}
                                                                                <td>{{ $claimant->claimant_phone_number }}
                                                                                </td>
                                                                                <td>{{ $claimant->email_address }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="12">No Claim Intimations found.</td>
                                                    </tr>
                                                @endforelse
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

    <!-- jQuery (iwepo kabla ya script zako) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (bundle includes popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- select2 ( ikiwa unatumia select2 ) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script>
        $(function() {
            let claimantIndex = 0;

            $('#createClaimInimationModal').on('shown.bs.modal', function() {

                // Add Claimant button
                $('#add-claimant-btn').off('click.claimant').on('click.claimant', function() {
                    claimantIndex++;
                    const idx = claimantIndex;

                    const card = $(`
                <div class="card p-3 mt-3 border rounded-3 shadow-sm claimant-card" data-idx="${idx}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="m-0">Claimant #${idx}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-claimant">Remove</button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Name</label>
                            <input type="text" name="claimants[${idx}][claimant_name]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="claimants[${idx}][claimant_birth_date]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="claimants[${idx}][gender]" class="form-select">
                                <option value="">Select</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Region</label>
                            <select name="claimants[${idx}][region_id]" id="region_${idx}" class="form-select region-select">
                                <option value="">Select Region</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">District</label>
                            <select name="claimants[${idx}][district_id]" id="district_${idx}" class="form-select district-select">
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="claimants[${idx}][claimant_category]" class="form-select">
                                <option value="">Select</option>
                                <option value="1">Policyholder</option>
                                <option value="2">Third Party</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="claimants[${idx}][claimant_type]" class="form-select">
                                <option value="">Select</option>
                                <option value="1">Individual</option>
                                <option value="2">Corporate</option>
                            </select>
                        </div>

                        <div class="col-md-6">
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

                        <div class="col-md-6">
                            <label class="form-label">ID Number</label>
                            <input type="text" name="claimants[${idx}][claimant_id_number]" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Street</label>
                            <input type="text" name="claimants[${idx}][street]" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="claimants[${idx}][claimant_phone_number]" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fax</label>
                            <input type="text" name="claimants[${idx}][claimant_fax]" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Postal Address</label>
                            <input type="text" name="claimants[${idx}][postal_address]" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="claimants[${idx}][email_address]" class="form-control">
                        </div>
                    </div>
                </div>
            `);

                    $('#claimants-container').append(card);

                    // Initialize select2 if needed
                    // if ($.fn.select2) {
                    //     card.find('.form-select').each(function() {
                    //         $(this).select2({
                    //             width: '100%',
                    //             dropdownParent: $('#createClaimInimationModal')
                    //         });
                    //     });
                    // }
                });

                // Remove claimant and re-index
                $('#claimants-container').off('click.remove').on('click.remove', '.remove-claimant',
                    function() {
                        $(this).closest('.claimant-card').remove();

                        // Re-index all claimants
                        $('#claimants-container .claimant-card').each(function(index) {
                            const idx = index;
                            $(this).attr('data-idx', idx);
                            $(this).find('h6').text(`Claimant #${idx + 1}`);

                            $(this).find('input, select').each(function() {
                                const name = $(this).attr('name');
                                if (!name) return;
                                const newName = name.replace(/claimants\[\d+\]/,
                                    `claimants[${idx}]`);
                                $(this).attr('name', newName);
                            });

                            // Update region/district ids
                            $(this).find('.region-select').attr('id', `region_${idx}`);
                            $(this).find('.district-select').attr('id', `district_${idx}`);
                        });
                    });

                // Dynamic Region -> District
                $('#claimants-container').off('change.region').on('change.region', '.region-select',
                    function() {
                        const regionId = $(this).val();
                        const idAttr = $(this).attr('id') || '';
                        const match = idAttr.match(/region_(\d+)/);
                        const idx = match ? match[1] : null;
                        const districtSelect = idx ? $(`#district_${idx}`) : $(this).closest('.row')
                            .find('.district-select');

                        if (!regionId) {
                            districtSelect.empty().append('<option value="">Select District</option>');
                            if ($.fn.select2) districtSelect.trigger('change.select2');
                            return;
                        }

                        $.get(`/dash/regions/${regionId}/districts`, function(data) {
                            districtSelect.empty().append(
                                '<option value="">Select District</option>');
                            $.each(data, function(_, district) {
                                districtSelect.append(
                                    `<option value="${district.id}">${district.name}</option>`
                                );
                            });
                            if ($.fn.select2) districtSelect.trigger('change.select2');
                        }).fail(function() {
                            console.error('Failed to load districts for region', regionId);
                        });
                    });

            });

            // Optional: clear claimants when modal closes
            $('#createClaimInimationModal').on('hidden.bs.modal', function() {
                // $('#claimants-container').empty();
                // claimantIndex = 0;
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createClaimInimation');
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
