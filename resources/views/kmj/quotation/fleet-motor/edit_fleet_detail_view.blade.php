@extends('kmj.layouts.app')
@section('title', 'Edit Fleet Detail')
@section('content')
    <style>
        body {
            background-image: none !important;
        }
    </style>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body pt-6">
                        <div class="card mb-5 mb-xl-10">
                            <div class="card-header border-0 cursor-pointer">
                                <div class="card-title m-0">
                                    <h3 class="fw-bold m-0">Edit Fleet Detail</h3>
                                </div>
                            </div>
                            <div id="kt_fleet_details" class="collapse show">
                                <form id="fleetEditForm"
                                    action="{{ route('quotation.fleet.update', $quotationFleetDetail->id) }}" method="POST"
                                    class="form">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body border-top p-9">

                                        {{-- 1. FLEET & COVER NOTE INFO --}}
                                        <h5 class="mb-4 text-primary">Fleet & Cover Note Information</h5>
                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Fleet Entry</label>
                                                <input type="text" name="fleet_entry"
                                                    class="form-control form-control-lg form-control-solid"
                                                    value="{{ old('fleet_entry', $quotationFleetDetail->fleet_entry) }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note Reference
                                                    Number</label>
                                                <input type="text" name="cover_note_reference_number"
                                                    class="form-control form-control-lg form-control-solid"
                                                    value="{{ old('cover_note_reference_number', $quotationFleetDetail->cover_note_reference_number) }}">
                                            </div>
                                        {{-- </div>
                                        <div class="row mb-6"> --}}
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Previous Cover Note
                                                    Ref</label>
                                                <input type="text" name="prev_cover_note_reference_number"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('prev_cover_note_reference_number', $quotationFleetDetail->prev_cover_note_reference_number) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note
                                                    Description</label>
                                                <input type="text" name="cover_note_desc"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('cover_note_desc', $quotationFleetDetail->cover_note_desc) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Operative Clause</label>
                                                <input type="text" name="operative_clause"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('operative_clause', $quotationFleetDetail->operative_clause) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note Number</label>

                                                <div class="input-group">
                                                    <input type="text" name="cover_note_number" id="cover_note_number"
                                                        class="form-control form-control-solid"
                                                        value="{{ old('cover_note_number', $quotationFleetDetail->cover_note_number) }}">

                                                    <button type="button" class="btn"
                                                        style="background-color: #003153;color:white;"
                                                        id="generateCoverNoteBtn">
                                                        Generate
                                                    </button>
                                                </div>

                                            </div>
                                        </div>


                                        <hr class="my-8">

                                        {{-- 2. RISK & PREMIUM DETAILS --}}
                                        <h5 class="mb-4 text-primary">Risk & Premium Details</h5>
                                        <div class="row mb-6">
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Risk Code</label>
                                                <input type="text" name="risk_code"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('risk_code', $quotationFleetDetail->risk_code) }}">
                                            </div> --}}
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Sum Insured</label>
                                                <input type="number" step="0.01" name="sum_insured"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('sum_insured', $quotationFleetDetail->sum_insured) }}">
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Sum Insured
                                                    (Equivalent)</label>
                                                <input type="number" step="0.01" name="sum_insured_equivalent"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('sum_insured_equivalent', $quotationFleetDetail->sum_insured_equivalent) }}">
                                            </div> --}}
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Premium Rate (%)</label>
                                                <input type="number" step="0.01" name="premium_rate"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('premium_rate', $quotationFleetDetail->premium_rate) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Premium Before
                                                    Discount</label>
                                                <input type="number" step="0.01" name="premium_before_discount"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('premium_before_discount', $quotationFleetDetail->premium_before_discount) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Premium After
                                                    Discount</label>
                                                <input type="number" step="0.01" name="premium_after_discount"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('premium_after_discount', $quotationFleetDetail->premium_after_discount) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Premium Excl. Tax
                                                    (Equiv)</label>
                                                <input type="number" step="0.01"
                                                    name="premium_excluding_tax_equivalent"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('premium_excluding_tax_equivalent', $quotationFleetDetail->premium_excluding_tax_equivalent) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Premium Incl. Tax</label>
                                                <input type="number" step="0.01" name="premium_including_tax"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('premium_including_tax', $quotationFleetDetail->premium_including_tax) }}">
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Discount Type</label>
                                                <select name="discount_type" class="form-select form-control-solid">
                                                    <option value="">Select</option>
                                                    <option value="percentage"
                                                        {{ old('discount_type', $quotationFleetDetail->discount_type) == 'percentage' ? 'selected' : '' }}>
                                                        Percentage</option>
                                                    <option value="amount"
                                                        {{ old('discount_type', $quotationFleetDetail->discount_type) == 'amount' ? 'selected' : '' }}>
                                                        Amount</option>
                                                </select>
                                            </div> --}}
                                            {{-- </div>
                                        <div class="row mb-6"> --}}
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Discount Rate (%)</label>
                                                <input type="number" step="0.01" name="discount_rate"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('discount_rate', $quotationFleetDetail->discount_rate) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Discount Amount</label>
                                                <input type="number" step="0.01" name="discount_amount"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('discount_amount', $quotationFleetDetail->discount_amount) }}">
                                            </div> --}}
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Tax Code</label>
                                                <input type="text" name="tax_code"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('tax_code', $quotationFleetDetail->tax_code) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Tax Exempted?</label>
                                                <select name="is_tax_exempted" class="form-select form-control-solid">
                                                    <option value="N"
                                                        {{ old('is_tax_exempted', $quotationFleetDetail->is_tax_exempted) == 0 ? 'selected' : '' }}>
                                                        No</option>
                                                    <option value="Y"
                                                        {{ old('is_tax_exempted', $quotationFleetDetail->is_tax_exempted) == 1 ? 'selected' : '' }}>
                                                        Yes</option>
                                                </select>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Tax Exemption Type</label>
                                                <input type="text" name="tax_exemption_type"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('tax_exemption_type', $quotationFleetDetail->tax_exemption_type) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Tax Exemption Ref</label>
                                                <input type="text" name="tax_exemption_reference"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('tax_exemption_reference', $quotationFleetDetail->tax_exemption_reference) }}">
                                            </div> --}}
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Tax Rate (%)</label>
                                                <input type="number" step="0.01" name="tax_rate"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('tax_rate', $quotationFleetDetail->tax_rate) }}">
                                            </div>
                                            {{-- </div>
                                        <div class="row mb-6"> --}}
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Tax Amount</label>
                                                <input type="number" step="0.01" name="tax_amount"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('tax_amount', $quotationFleetDetail->tax_amount) }}">
                                            </div>
                                        </div>

                                        <hr class="my-8">

                                        {{-- 3. SUBJECT MATTER --}}
                                        <h5 class="mb-4 text-primary">Subject Matter</h5>
                                        <div class="row mb-6">
                                            <div class="col-md-6">
                                                <label class="col-form-label fw-semibold fs-6">Subject Matter
                                                    Reference</label>
                                                <input type="text" name="subject_matter_reference"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('subject_matter_reference', $quotationFleetDetail->subject_matter_reference) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-form-label fw-semibold fs-6">Subject Matter
                                                    Description</label>
                                                <input type="text" name="subject_matter_desc"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('subject_matter_desc', $quotationFleetDetail->subject_matter_desc) }}">
                                            </div>
                                        </div>

                                        <hr class="my-8">

                                        {{-- 4. VEHICLE DETAILS --}}
                                        <h5 class="mb-4 text-primary">Vehicle Details</h5>
                                        <div class="row mb-6">
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Motor Category</label>
                                                <select class="form-select form-select-solid" name="motor_category"
                                                    required>
                                                    <option value="">-- Select Motor Category --</option>
                                                    <option value="1"
                                                        {{ old('motor_category', $quotationFleetDetail->motor_category) == '1' ? 'selected' : '' }}>
                                                        Motor vehicle
                                                    </option>
                                                    <option value="2"
                                                        {{ old('motor_category', $quotationFleetDetail->motor_category) == '2' ? 'selected' : '' }}>
                                                        Motor cycle
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Motor Type</label>
                                                <select class="form-select form-select-solid" name="motor_type" required>
                                                    <option value="">-- Select Motor Type --</option>
                                                    <option value="1"
                                                        {{ old('motor_type', $quotationFleetDetail->motor_type) == '1' ? 'selected' : '' }}>
                                                        Registered</option>
                                                    <option value="2"
                                                        {{ old('motor_type', $quotationFleetDetail->motor_type) == '2' ? 'selected' : '' }}>
                                                        In transit</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Registration Number</label>
                                                <input type="text" name="registration_number"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('registration_number', $quotationFleetDetail->registration_number) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Chassis Number</label>
                                                <input type="text" name="chassis_number"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('chassis_number', $quotationFleetDetail->chassis_number) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Make</label>
                                                <input type="text" name="make"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('make', $quotationFleetDetail->make) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Model</label>
                                                <input type="text" name="model"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('model', $quotationFleetDetail->model) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Model Number</label>
                                                <input type="text" name="model_number"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('model_number', $quotationFleetDetail->model_number) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Body Type</label>
                                                <input type="text" name="body_type"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('body_type', $quotationFleetDetail->body_type) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Color</label>
                                                <input type="text" name="color"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('color', $quotationFleetDetail->color) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Engine Number</label>
                                                <input type="text" name="engine_number"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('engine_number', $quotationFleetDetail->engine_number) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Engine Capacity (cc)</label>
                                                <input type="number" name="engine_capacity"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('engine_capacity', $quotationFleetDetail->engine_capacity) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Fuel Used</label>
                                                <input type="text" name="fuel_used"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('fuel_used', $quotationFleetDetail->fuel_used) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Number of Axles</label>
                                                <input type="number" name="number_of_axles"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('number_of_axles', $quotationFleetDetail->number_of_axles) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Axle Distance</label>
                                                <input type="number" step="0.01" name="axle_distance"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('axle_distance', $quotationFleetDetail->axle_distance) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Sitting Capacity</label>
                                                <input type="number" name="sitting_capacity"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('sitting_capacity', $quotationFleetDetail->sitting_capacity) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Year of Manufacture</label>
                                                <input type="number" name="year_of_manufacture"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('year_of_manufacture', $quotationFleetDetail->year_of_manufacture) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Tare Weight (kg)</label>
                                                <input type="number" step="0.01" name="tare_weight"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('tare_weight', $quotationFleetDetail->tare_weight) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Gross Weight (kg)</label>
                                                <input type="number" step="0.01" name="gross_weight"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('gross_weight', $quotationFleetDetail->gross_weight) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Motor Usage</label>
                                                <select class="form-select form-select-solid" name="motor_usage" required>
                                                    <option value="">-- Select Motor Usage --</option>
                                                    <option value="1"
                                                        {{ old('motor_usage', $quotationFleetDetail->motor_usage) == '1' ? 'selected' : '' }}>
                                                        Private</option>
                                                    <option value="2"
                                                        {{ old('motor_usage', $quotationFleetDetail->motor_usage) == '2' ? 'selected' : '' }}>
                                                        Commercial</option>
                                                </select>
                                            </div>

                                        </div>

                                        <hr class="my-8">

                                        {{-- 5. OWNER DETAILS --}}
                                        <h5 class="mb-4 text-primary">Owner Details</h5>
                                        <div class="row mb-6">
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Owner Name</label>
                                                <input type="text" name="owner_name"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('owner_name', $quotationFleetDetail->owner_name) }}">
                                            </div> --}}
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Owner Category</label>
                                                <select class="form-select form-select-solid" name="owner_category"
                                                    required>
                                                    <option value="">-- Select Owner Category --</option>
                                                    <option value="1"
                                                        {{ old('owner_category', $quotationFleetDetail->owner_category) == '1' ? 'selected' : '' }}>
                                                        Sole proprietor</option>
                                                    <option value="2"
                                                        {{ old('owner_category', $quotationFleetDetail->owner_category) == '2' ? 'selected' : '' }}>
                                                        Corporate</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Owner Address</label>
                                                <input type="text" name="owner_address"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('owner_address', $quotationFleetDetail->owner_address) }}">
                                            </div>
                                        </div>

                                    </div>

                                    {{-- SUBMIT BUTTONS --}}
                                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                                        {{-- <a href="{{ url()->previous() }}" class="btn btn-light me-2">Cancel</a> --}}
                                        <button type="submit" class="btn text-white d-flex align-items-center"
                                            style="background-color: #003153" id="submitBtn">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <span id="submitText">Save Changes</span>
                                            <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2"
                                                style="display: none; width: 1rem; height: 1rem;"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('fleetEditForm');
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
                    submitText.textContent = 'Save Customer';
                    submitSpinner.style.display = 'none';
                    console.log('Form submitted (loader demo only)');
                }, 2000);
            });
        });
    </script>

    <script>
        document.getElementById('generateCoverNoteBtn').addEventListener('click', function() {

            function pad(n) {
                return n < 10 ? '0' + n : n;
            }

            const now = new Date();

            // YYMMDDHHMMSS
            const yy = now.getFullYear().toString().slice(-2);
            const mm = pad(now.getMonth() + 1);
            const dd = pad(now.getDate());
            const hh = pad(now.getHours());
            const min = pad(now.getMinutes());
            const ss = pad(now.getSeconds());

            // Random 7 digits
            const random7 = Math.floor(1000000 + Math.random() * 9000000);

            // Final format
            const coverNoteNumber = `KMJ${yy}${mm}${dd}${hh}${min}${ss}${random7}`;

            document.getElementById('cover_note_number').value = coverNoteNumber;
        });
    </script>

@endsection
