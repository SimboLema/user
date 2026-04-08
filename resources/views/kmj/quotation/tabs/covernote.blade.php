@extends('kmj.layouts.app')

@section('title', 'Cover Note')

@section('content')

    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
        }
    </style>

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">



                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body pt-6">

                        @include('kmj.quotation.components.tabs-nav')

                        @include('kmj.quotation.model.reinsurance_modal')


                        {{-- <div class="mt-5">
                            <h3>{{ $quotation->status === 'pending' ? 'Quotation' : 'Cover Note' }} Section </h3>
                            <p>{{ $quotation->status === 'pending' ? 'Quotation' : 'Cover Note' }} information.</p>
                        </div> --}}

                        <!--begin::details View-->

                        <!--begin::Cover Note Details Card-->
                        <div class="card mb-5 mb-xl-10" id="kt_covernote_details_view">
                            <div class="card-header cursor-pointer">
                                <div class="card-title m-0">
                                    <h3 class="fw-bold m-0">
                                        {{ $quotation->status === 'pending' ? 'Quotation' : 'Cover Note' }} Information
                                        <span>
                                            <a href="{{ route('quoatation.view.edit', $quotation->id) }}">
                                                <i class="bi bi-pencil" style="color: #003153" title="Edit Quotation"></i>
                                            </a>

                                            @if ($quotation->status === 'pending')
                                                <span class="badge border border-warning d-inline-block text-center"
                                                    style="width: auto; color: orange !important;">
                                                    Awaiting Insurer Approval
                                                </span>

                                            @elseif ($quotation->status === 'approved')
                                                <span class="badge border border-info d-inline-block text-center"
                                                    style="width: auto; color: #0dcaf0 !important;">
                                                    Approved — Pending TIRA
                                                </span>

                                            @elseif ($quotation->status === 'success')
                                                <span class="badge border border-success d-inline-block text-center"
                                                    style="width: auto; color: green !important;">
                                                    Risknote Issued
                                                </span>

                                            @elseif ($quotation->status === 'cancelled')
                                                <span class="badge border border-danger d-inline-block text-center"
                                                    style="width: auto; color: red !important;">
                                                    Cancelled
                                                </span>
                                            @endif
                                        </span>
                                    </h3>
                                    </div>

                                    {{-- Buttons: hidden when pending, shown once insurer approves --}}
                                    @if (in_array($quotation->status, ['approved', 'success']))
                                        <div class="d-flex align-items-center gap-2">

                                            {{-- TIRA button: show for fleet on approved/success, show for non-fleet on approved only --}}
                                            @if (!empty($quotation->fleet_id))
                                                <a href="{{ route('quotation.sendTira', $quotation->id) }}"
                                                    class="btn p-0 border-0 bg-transparent shadow-none align-self-center tiraButton"
                                                    title="Send TIRA" onclick="return showLoaderWithClose(this);">
                                                    <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                        alt="TIRA Logo" style="width: 35px; height: auto;">
                                                </a>
                                            @else
                                                @if ($quotation->status === 'approved')
                                                    <a href="{{ route('quotation.sendTira', $quotation->id) }}"
                                                        class="btn p-0 border-0 bg-transparent shadow-none align-self-center tiraButton"
                                                        title="Send TIRA" onclick="return showLoaderWithClose(this);">
                                                        <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                            alt="TIRA Logo" style="width: 35px; height: auto;">
                                                    </a>
                                                @endif
                                            @endif

                                            {{-- Download CoverNote: only after TIRA confirms (success) --}}
                                            @if ($quotation->status === 'success')
                                                <a href="{{ route('kmj.quotation.download.covernote', $quotation->id) }}"
                                                    class="btn btn-sm align-self-center"
                                                    style="background-color: #9aa89b; color: white;" target="_blank">
                                                    Download CoverNote
                                                </a>
                                            @endif

                                            {{-- Download Quotation: always visible once approved --}}
                                            <a href="{{ route('kmj.quotation.download.quotation', $quotation->id) }}"
                                                class="btn btn-sm align-self-center"
                                                style="background-color: #9aa89b; color: white;" target="_blank">
                                                Download Quotation
                                            </a>

                                            {{-- Renew: only makes sense after risknote is issued --}}
                                            @if ($quotation->status === 'success')
                                                <button type="button" class="btn btn-sm align-self-center"
                                                    style="background-color: #003153; color: white;"
                                                    data-bs-toggle="modal" data-bs-target="#renewQuotationModal">
                                                    <i class="fas fa-sync-alt text-white"></i> Renew Covernote
                                                </button>
                                            @endif

                                            <button type="button" class="btn btn-sm align-self-center"
                                                style="background-color: #003153; color: white;"
                                                data-bs-toggle="modal" data-bs-target="#reinsuranceModal"
                                                data-quotation-id="{{ $quotation->id }}">
                                                Reinsurance
                                            </button>

                                </div>
                                @else
                                {{-- Pending message shown instead of buttons --}}
                                <span class="badge border border-warning text-warning px-3 py-2" style="color: orange !important;">
                                    <i class="bi bi-hourglass-split me-1"></i> Awaiting Insurer Approval
                                </span>
                                @endif
                            </div>

                            <!-- Loader HTML -->
                            @include('kmj.include.loader')

                            <div class="card-body p-9">
                                @php
                                    $quotationDetails = [
                                        // 'Sale Point Code' => $quotation->sale_point_code,
                                        'Customer Name' => ucwords(strtolower($quotation->customer->name)),
                                        'Product' => ucwords(strtolower($quotation->coverage->product->name)),
                                        'Coverage' => ucwords(strtolower($quotation->coverage->risk_name)),

                                        'Insurer Name' => ucwords(strtolower($quotation->insuarer->name)),
                                        'Intermediary' => 'KMJ Insurance Brokers Ltd',

                                        'Cover Note Start Date' => \Carbon\Carbon::parse(
                                            $quotation->cover_note_start_date,
                                        )->format('d M Y'),
                                        'Cover Note End Date' => \Carbon\Carbon::parse(
                                            $quotation->cover_note_end_date,
                                        )->format('d M Y'),

                                        'Exchange Rate' => floatval($quotation->exchange_rate),
                                        'Total Premium (Excl. Tax)' => number_format(
                                            $quotation->total_premium_excluding_tax,
                                            2,
                                        ),
                                        'Total Premium (Incl. Tax)' => number_format(
                                            $quotation->total_premium_including_tax,
                                            2,
                                        ),
                                    ];

                                    // ➤ Zile fields za Sum Insured na Premium zikibaki pale pale, tuweke placeholder au remove bila kubadilisha order
                                    if (empty($quotation->fleet_id)) {
                                        $quotationDetails['Sum Insured'] = number_format($quotation->sum_insured, 2);
                                        $quotationDetails['Sum Insured Equivalent'] = number_format(
                                            $quotation->sum_insured,
                                            2,
                                        );
                                        $quotationDetails['Premium Rate (%)'] =
                                            floatval($quotation->premium_rate) * 100;
                                        $quotationDetails['Premium Before Discount'] = number_format(
                                            $quotation->premium_before_discount,
                                        );
                                        $quotationDetails['Premium After Discount'] = number_format(
                                            $quotation->premium_after_discount,
                                            2,
                                        );
                                        $quotationDetails['Premium Excl. Tax (Equivalent)'] = number_format(
                                            $quotation->premium_excluding_tax_equivalent,
                                            2,
                                        );
                                        $quotationDetails['Premium Incl. Tax'] = number_format(
                                            $quotation->premium_including_tax,
                                            2,
                                        );
                                        $quotationDetails['Tax Rate (%)'] = floatval($quotation->tax_rate) * 100;
                                        $quotationDetails['Tax Amount'] = number_format($quotation->tax_amount, 2);

                                        // Conditional Sticker Number
                                        if ($quotation->coverage->product->insurance_id == 2) {
                                            $quotationDetails['Sticker Number'] = $quotation->sticker_number;
                                        }
                                        $quotationDetails['Cover Note Reference'] = $quotation->cover_note_reference;
                                    }

                                    // Hizi zinabaki constant, si condition
                                    $quotationDetails['Acknowledgement Status Code'] =
                                        $quotation->acknowledgement_status_code;
                                    $quotationDetails['Acknowledgement Status Description'] =
                                        $quotation->acknowledgement_status_desc;
                                    $quotationDetails['Response Status Code'] = $quotation->response_status_code;
                                    $quotationDetails['Response Status Description'] = $quotation->response_status_desc;
                                    $quotationDetails['Request ID'] = $quotation->request_id;
                                @endphp


                                <div class="row">
                                    @foreach ($quotationDetails as $label => $value)
                                        <div class="col-md-6 col-lg-4 mb-7">
                                            <div class="d-flex flex-column">
                                                <label class="fw-semibold text-muted">{{ $label }}</label>
                                                @if ($label === 'Coverage' || $label === 'Product')
                                                    <div class="d-flex align-items-center">
                                                        <div class="truncate-text fw-bold fs-6 text-gray-800"
                                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                            data-fulltext="{{ $value }}">
                                                            {{ $value }}
                                                        </div>
                                                        <a href="javascript:void(0);"
                                                            class="toggle-truncate btn btn-sm btn-link p-0 ms-2"
                                                            style="font-size: 12px; display:none;color:#9aa89b">More</a>
                                                    </div>
                                                @else
                                                    <span class="fw-bold fs-6 text-gray-800">
                                                        @if (empty($value))
                                                            <!-- Spinner loader -->
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                style="color: #5f9ea0;">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </span>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if (!empty($quotation->fleet_id))
                                    {{-- <h1>Fleet Details</h1> --}}
                                    <!--begin::Endorsements Table-->
                                    <div class="card mt-5">
                                        <div class="card-header card-header-stretch">
                                            <div class="card-title">
                                                <h3 class="m-0 text-gray-800">Fleet Details</h3>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">

                                                <button type="button" class="btn btn-sm align-self-center"
                                                    style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                                    data-bs-target="#createFleetDetailsModal"> <i
                                                        class="fas fa-plus text-white"></i>
                                                    Insert Fleet
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Create Fleet Details Modal -->
                                        <!-- BADILI HII SEHEMU TU -->
                                        <div class="modal fade" id="createFleetDetailsModal" tabindex="-1"
                                            aria-labelledby="createFleetDetailsModalLabel" aria-hidden="true">

                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                <!-- ONDOA modal-dialog-scrollable hapa -->
                                                <form id="createFleetDetailsForm"
                                                    action="{{ route('quotation.create.fleet.detail', $quotation->id) }}"
                                                    method="POST">
                                                    <div class="modal-content"
                                                        style="height: 100vh; display: flex; flex-direction: column;">

                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Create Fleet Details</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <!-- HII NDIO SCROLLABLE BODY -->
                                                        <div class="modal-body flex-grow-1 overflow-auto p-4">
                                                            <input type="hidden" name="quotation_id"
                                                                value="{{ $quotation->id }}">

                                                            {{-- =============================== --}}
                                                            {{-- Fleet / Cover Note Information --}}
                                                            {{-- =============================== --}}
                                                            <h6 class="fw-bold text-primary mt-2">Fleet / Cover Note
                                                                Information</h6>
                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Fleet Entry</label>
                                                                    <input type="number" name="fleet_entry"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Cover Note
                                                                        Description</label>
                                                                    <input type="text" name="cover_note_desc"
                                                                        value="STANDARD FLEET MOTOR COVER NOTE"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Operative Clause</label>
                                                                    <input type="text" name="operative_clause"
                                                                        value="STANDARD OPERATIVE CLAUSE"
                                                                        class="form-control">
                                                                </div>
                                                            </div>

                                                            {{-- =============================== --}}
                                                            {{-- Risk Covered Information --}}
                                                            {{-- =============================== --}}
                                                            <h6 class="fw-bold text-primary mt-3">Risk Covered</h6>
                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-3"><label class="form-label">Sum
                                                                        Insured</label><input type="number"
                                                                        step="0.01" name="sum_insured"
                                                                        class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Premium
                                                                        Rate</label><input type="number" step="0.00001"
                                                                        name="premium_rate" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Premium
                                                                        Incl. Tax</label><input type="number"
                                                                        step="0.01" name="premium_including_tax"
                                                                        class="form-control" readonly>
                                                                </div>
                                                                <div class="col-md-3"><label class="form-label">Is Tax
                                                                        Exempted</label>
                                                                    <select class="form-select" name="is_tax_exempted"
                                                                        required>
                                                                        <option value="N">No</option>
                                                                        <option value="Y">Yes</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3"><label class="form-label">Tax
                                                                        Rate</label><input type="number" step="0.00001"
                                                                        name="tax_rate" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Tax
                                                                        Amount</label><input type="number" step="0.01"
                                                                        name="tax_amount" class="form-control"></div>
                                                            </div>

                                                            {{-- =============================== --}}
                                                            {{-- Subject Matter --}}
                                                            {{-- =============================== --}}
                                                            <h6 class="fw-bold text-primary mt-3">Subject Matter</h6>
                                                            <div class="row g-3 mb-3">
                                                                {{-- <div class="col-md-6"><label class="form-label">Subject
                                                                    Matter Reference</label><input type="text"
                                                                    name="subject_matter_reference" class="form-control">
                                                            </div> --}}
                                                                <div class="col-md-6"><label class="form-label">Subject
                                                                        Matter Description</label>
                                                                    <textarea name="subject_matter_desc" class="form-control" rows="2">STANDARD MOTOR SUBJECT MATTER DESCRIPTION</textarea>
                                                                </div>
                                                            </div>

                                                            {{-- =============================== --}}
                                                            {{-- ================= COVER NOTE ADDONS ================= --}}


                                                            {{-- <div class="card p-4 mt-3">
                                                                <h4><i class="bi bi-plus-circle me-2"></i>Cover Note Addons
                                                                </h4>
                                                                <p class="text-muted mb-3">Select one or more addons for
                                                                    this cover note.</p>

                                                                <div class="row g-3">
                                                                    @foreach ($ddonProducts as $addon)
                                                                        <div class="col-md-3"
                                                                            style="padding-right: 20px;padding-left: 20px">
                                                                            <div
                                                                                class="form-check border rounded p-3 shadow-sm h-100">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox"
                                                                                    id="addon_{{ $addon['id'] }}"
                                                                                    name="addons[]"
                                                                                    value="{{ $addon['id'] }}">

                                                                                <label class="form-check-label fw-bold"
                                                                                    for="addon_{{ $addon['id'] }}">
                                                                                    {{ $addon['name'] }}
                                                                                </label>

                                                                                <div class="text-muted small mt-1">
                                                                                    Amount:
                                                                                    <strong>{{ number_format($addon['amount']) }}
                                                                                        TZS</strong>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div> --}}

                                                            {{-- Motor Details --}}
                                                            {{-- =============================== --}}
                                                            <h6 class="fw-bold text-primary mt-3">Motor Details</h6>
                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-3"><label class="form-label">Motor
                                                                        Category</label>
                                                                    <select class="form-select" name="motor_category"
                                                                        required>
                                                                        <option value="1">Motor vehicle</option>
                                                                        <option value="2">Motor cycle</option>
                                                                    </select>

                                                                </div>
                                                                <div class="col-md-3"><label class="form-label">Motor
                                                                        Type</label>
                                                                    <select class="form-select" name="motor_type"
                                                                        required>
                                                                        <option value="1">Registered</option>
                                                                        <option value="2">In transit</option>
                                                                    </select>

                                                                </div>
                                                                <div class="col-md-3"><label
                                                                        class="form-label">Registration
                                                                        Number</label><input type="text"
                                                                        name="registration_number" class="form-control">
                                                                </div>
                                                                <div class="col-md-3"><label class="form-label">Chassis
                                                                        Number</label><input type="text"
                                                                        name="chassis_number" class="form-control"></div>
                                                                <div class="col-md-3"><label
                                                                        class="form-label">Make</label><input
                                                                        type="text" name="make"
                                                                        class="form-control"></div>
                                                                <div class="col-md-3"><label
                                                                        class="form-label">Model</label><input
                                                                        type="text" name="model"
                                                                        class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Model
                                                                        Number</label><input type="text"
                                                                        name="model_number" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Body
                                                                        Type</label><input type="text" name="body_type"
                                                                        class="form-control"></div>
                                                                <div class="col-md-3"><label
                                                                        class="form-label">Color</label><input
                                                                        type="text" name="color"
                                                                        class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Engine
                                                                        Number</label><input type="text"
                                                                        name="engine_number" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Engine
                                                                        Capacity</label><input type="number"
                                                                        name="engine_capacity" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Fuel
                                                                        Used</label><input type="text" name="fuel_used"
                                                                        class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Number of
                                                                        Axles</label><input type="number"
                                                                        name="number_of_axles" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Axle
                                                                        Distance</label><input type="number"
                                                                        name="axle_distance" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Sitting
                                                                        Capacity</label><input type="number"
                                                                        name="sitting_capacity" class="form-control">
                                                                </div>
                                                                <div class="col-md-3"><label class="form-label">Year of
                                                                        Manufacture</label><input type="number"
                                                                        name="year_of_manufacture" class="form-control">
                                                                </div>
                                                                <div class="col-md-3"><label class="form-label">Tare
                                                                        Weight</label><input type="number"
                                                                        name="tare_weight" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Gross
                                                                        Weight</label><input type="number"
                                                                        name="gross_weight" class="form-control"></div>
                                                                <div class="col-md-3"><label class="form-label">Motor
                                                                        Usage</label>
                                                                    <select class="form-select" name="motor_usage"
                                                                        required>
                                                                        <option value="1">Private</option>
                                                                        <option value="2">Commercial</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            {{-- =============================== --}}
                                                            {{-- Owner Information --}}
                                                            {{-- =============================== --}}
                                                            <h6 class="fw-bold text-primary mt-3">Owner Details</h6>
                                                            <div class="row g-3 mb-3">
                                                                {{-- <div class="col-md-4"><label class="form-label">Owner
                                                                        Name</label><input type="text"
                                                                        name="owner_name" class="form-control"></div> --}}
                                                                <div class="col-md-4"><label class="form-label">Owner
                                                                        Category</label>
                                                                    <select class="form-select" name="owner_category"
                                                                        required>
                                                                        <option value="1">Sole propriator</option>
                                                                        <option value="2">Corporate</option>
                                                                    </select>

                                                                </div>
                                                                {{-- <div class="col-md-4"><label class="form-label">Owner
                                                                        Address</label><input type="text"
                                                                        name="owner_address" class="form-control"></div> --}}
                                                            </div>


                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" id="submitBtn"
                                                                class="btn btn-primary">
                                                                <i class="bi bi-check-circle me-2"></i> Save Fleet Details
                                                                <span id="submitSpinner"
                                                                    class="spinner-border spinner-border-sm ms-2"
                                                                    style="display: none;"></span>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>




                                        <div id="kt_endorsements_tab_content" class="tab-content">

                                            <div id="kt_endorsements_tab_pane_1"
                                                class="card-body p-0 tab-pane fade show active" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                                        <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                            <tr>
                                                                <th class="min-w-100px text-center">S/N</th>
                                                                <th class="min-w-100px text-center">Fleet Entry</th>
                                                                <th class="min-w-100px text-center">Sum Insured</th>

                                                                <th class="min-w-100px text-center">CoverNote Ref
                                                                </th>
                                                                <th class="min-w-100px text-center">Sticker Number</th>
                                                                <th class="min-w-100px text-center">res Status Desc</th>
                                                                <th class="min-w-100px text-center">Status</th>
                                                                <th class="min-w-100px text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($quotationFleetDetails as $index => $quotationFleetDetail)
                                                                <tr
                                                                    class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>
                                                                        {{ $quotationFleetDetail->fleet_entry ?? '-' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($quotationFleetDetail->sum_insured) }}
                                                                    </td>

                                                                    <td>
                                                                        {{ $quotationFleetDetail->cover_note_reference_number ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $quotationFleetDetail->sticker_number ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $quotationFleetDetail->response_status_desc }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($quotationFleetDetail->status === 'success')
                                                                            <span
                                                                                class="badge border border-success text-success d-inline-block text-center"
                                                                                style="width: auto; color: green !important;">
                                                                                Success
                                                                            </span>
                                                                        @elseif($quotationFleetDetail->status === 'failure')
                                                                            <span
                                                                                class="badge border border-danger text-danger d-inline-block text-center"
                                                                                style="width: auto; color: red !important;">
                                                                                Failure
                                                                            </span>
                                                                        @elseif($quotationFleetDetail->status === 'pending')
                                                                            <span
                                                                                class="badge border border-warning text-warning d-inline-block text-center"
                                                                                style="width: auto; color: orange !important;">
                                                                                Pending
                                                                            </span>
                                                                        @endif
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-sm dropdown-toggle"
                                                                                type="button"
                                                                                style="background-color: #003153; color: white; border-radius: 5px; padding: 5px 12px;"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="false">
                                                                                Actions
                                                                            </button>

                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{ route('quoatation.fleet.edit', $quotationFleetDetail->id) }}">
                                                                                        Edit
                                                                                    </a>
                                                                                </li>

                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{ route('quotation.fleet.endorsement.view', $quotationFleetDetail->id) }}">
                                                                                        Endorsement
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>


                                                                    {{-- <td class="text-center">
                                                                        <a href=" {{ route('quoatation.fleet.edit', $quotationFleetDetail->id) }}"
                                                                            class="btn btn-sm"
                                                                            style="background-color: #003153; color: white; border-radius: 5px; padding: 5px 12px;"
                                                                            title="Edit Fleet">
                                                                            Edit
                                                                        </a>

                                                                    </td> --}}
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Endorsements Table-->
                                @endif
                            </div>

                        </div>
                        <!--end::Cover Note Details Card-->
                        <!--end::details View-->

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


    <!-- Create Endorsement Modal -->
    <div class="modal fade" id="renewQuotationModal" tabindex="-1" aria-labelledby="createCoverNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="createEndorsementForm" action="{{ route('quotation.RenewCoverNote', $quotation->id) }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCoverNoteModalLabel">Renew
                            CoverNote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">

                        <div class="row g-3">
                            <!-- Duration -->
                            <div class="col-md-6">
                                <label class="form-label">Cover Note Duration (months)</label>
                                <select class="form-select" name="cover_note_duration_id" id="cover_note_duration_id"
                                    required>
                                    <option value="">Select Cover Note Type</option>
                                    @foreach ($coverNoteDurations as $coverNoteDuration)
                                        <option value="{{ $coverNoteDuration->id }}"
                                            data-months="{{ $coverNoteDuration->months }}">
                                            {{ $coverNoteDuration->label }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6">
                                <label class="form-label">Cover Note Start Date</label>
                                <input class="form-control" type="date" name="cover_note_start_date"
                                    id="cover_note_start_date">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cover Note End Date</label>
                                <input class="form-control" type="date" name="cover_note_end_date"
                                    id="cover_note_end_date" disabled>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="submitBtn" class="btn"
                            style="background-color: #003153; color: white;">
                            <i class="bi bi-check-circle me-2 text-white"></i>
                            <span id="submitText">Renew</span>
                            <span id="submitSpinner" class="spinner-border spinner-border-sm text-light ms-2"
                                style="display: none;"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const startDateInput = document.getElementById('cover_note_start_date');
            const durationSelect = document.getElementById('cover_note_duration_id');
            const endDateInput = document.getElementById('cover_note_end_date');

            // Set start date minimum to tomorrow
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);

            const yyyy = tomorrow.getFullYear();
            const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const dd = String(tomorrow.getDate()).padStart(2, '0');

            const tomorrowStr = `${yyyy}-${mm}-${dd}`;
            startDateInput.min = tomorrowStr;
            startDateInput.value = tomorrowStr; // default = tomorrow

            // Function to calculate end date
            function calculateEndDate() {
                const startDate = new Date(startDateInput.value);
                const selectedOption = durationSelect.options[durationSelect.selectedIndex];
                const months = parseInt(selectedOption.getAttribute('data-months'));

                if (startDate && months) {
                    const endDate = new Date(startDate);
                    endDate.setMonth(endDate.getMonth() + months);
                    endDate.setDate(endDate.getDate() - 1); // minus 1 day

                    const dd = String(endDate.getDate()).padStart(2, '0');
                    const mm = String(endDate.getMonth() + 1).padStart(2, '0');
                    const yyyy = endDate.getFullYear();

                    endDateInput.value = `${yyyy}-${mm}-${dd}`;
                } else {
                    endDateInput.value = '';
                }
            }

            // Event listeners
            startDateInput.addEventListener('change', calculateEndDate);
            durationSelect.addEventListener('change', calculateEndDate);

            // Initial calculation
            calculateEndDate();

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
                    submitText.textContent = 'Save CoverNote';
                    submitSpinner.style.display = 'none';
                    console.log('Form submitted (loader demo only)');
                }, 2000);
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sumInsuredInput = document.querySelector('input[name="sum_insured"]');
            const premiumRateInput = document.querySelector('input[name="premium_rate"]');
            const premiumInclTaxInput = document.querySelector('input[name="premium_including_tax"]');
            const totalExTaxInput = document.querySelector('input[name="total_premium_excluding_tax"]');
            const taxRateInput = document.querySelector('input[name="tax_rate"]');
            const taxAmountInput = document.querySelector('input[name="tax_amount"]');
            const isTaxExemptedSelect = document.querySelector('select[name="is_tax_exempted"]');

            const coverageRate = {{ $coverage->rate }};
            const minimumAmount = {{ $coverage->minimum_amount }};
            const defaultTaxRate = 0.18;

            function calculatePremium() {
                const sumInsured = parseFloat(sumInsuredInput.value) || 0;
                const isTaxExempted = isTaxExemptedSelect.value === 'Y';
                const premiumRate = coverageRate / 100;

                const premium = sumInsured * premiumRate;
                const totalExTax = premium > minimumAmount ? premium : minimumAmount;
                const taxRate = isTaxExempted ? 0 : defaultTaxRate;
                const taxAmount = totalExTax * taxRate;
                const totalInclTax = totalExTax + taxAmount;

                premiumRateInput.value = premiumRate.toFixed(4);
                totalExTaxInput.value = totalExTax.toFixed(2);
                taxRateInput.value = taxRate.toFixed(2);
                taxAmountInput.value = taxAmount.toFixed(2);
                premiumInclTaxInput.value = totalInclTax.toFixed(2);
            }

            sumInsuredInput.addEventListener('input', calculatePremium);
            isTaxExemptedSelect.addEventListener('change', calculatePremium);
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createFleetDetailsForm');
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
                    submitText.textContent = 'Insert Fleet';
                    submitSpinner.style.display = 'none';
                    console.log('Form submitted (loader demo only)');
                }, 2000);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maxChars = 40; // characters limit
            const truncates = document.querySelectorAll('.truncate-text');

            truncates.forEach(div => {
                const btn = div.nextElementSibling;
                const fullText = div.getAttribute('data-fulltext').trim();

                if (fullText.length > maxChars) {
                    // initial truncated text
                    div.textContent = fullText.substring(0, maxChars) + '...';
                    btn.style.display = 'inline';

                    btn.addEventListener('click', function() {
                        if (this.textContent === 'More') {
                            // expand fully
                            div.textContent = fullText;
                            div.style.whiteSpace = 'normal';
                            div.style.overflow = 'visible';
                            div.style.textOverflow = 'clip';
                            this.textContent = 'Less';
                        } else {
                            // collapse
                            div.textContent = fullText.substring(0, maxChars) + '...';
                            div.style.whiteSpace = 'nowrap';
                            div.style.overflow = 'hidden';
                            div.style.textOverflow = 'ellipsis';
                            this.textContent = 'More';
                        }
                    });
                } else {
                    // text fupi, show full text, hide button
                    div.textContent = fullText;
                    btn.style.display = 'none';
                }
            });
        });
    </script>


@endsection
