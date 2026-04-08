@extends('kmj.layouts.app')
@section('title', 'Edit Quotation')
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
                                    <h3 class="fw-bold m-0">Edit Quotation</h3>
                                </div>
                            </div>

                            <div id="kt_quotation_details" class="collapse show">
                                <form id="quotationEditForm" action="{{ route('quotation.update', $quotation->id) }}"
                                    method="POST" class="form">
                                    @csrf
                                    @method('PUT')

                                    <div class="card-body border-top p-9">

                                        {{-- 1. Quotation & Cover Note Info --}}
                                        <h5 class="mb-4 text-primary">Quotation & Cover Note Information</h5>
                                        <div class="row mb-6">
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Fleet ID</label>
                                                <input type="text" name="fleet_id"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('fleet_id', $quotation->fleet_id) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Fleet Type</label>
                                                <select class="form-select form-control-solid" name="fleet_type"
                                                    id="fleet_type" required>
                                                    <option value="1"
                                                        {{ old('fleet_type', $quotation->fleet_type) == 1 ? 'selected' : '' }}>
                                                        New</option>
                                                    <option value="2"
                                                        {{ old('fleet_type', $quotation->fleet_type) == 2 ? 'selected' : '' }}>
                                                        Addition</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Fleet Size</label>
                                                <input type="text" name="fleet_size"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('fleet_size', $quotation->fleet_size) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="col-form-label fw-semibold fs-6">Comprehensive Insured</label>
                                                <input type="text" name="comprehensive_insured"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('comprehensive_insured', $quotation->comprehensive_insured) }}">
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Comprehensive Insured</label>
                                                <input type="text" name="comprehensive_insured"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('comprehensive_insured', $quotation->comprehensive_insured) }}">
                                            </div> --}}
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Addons</label>
                                                <input type="text" name="addons" class="form-control form-control-solid"
                                                    value="{{ old('addons', $quotation->addons) }}">
                                            </div> --}}
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Coverage ID</label>
                                                <input type="text" name="coverage_id"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('coverage_id', $quotation->coverage_id) }}">
                                            </div> --}}
                                            {{-- </div>

                                        <div class="row mb-6"> --}}
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Customer ID</label>
                                                <input type="text" name="customer_id"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('customer_id', $quotation->customer_id) }}">
                                            </div> --}}
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note Type ID</label>
                                                <input type="text" name="cover_note_type_id"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('cover_note_type_id', $quotation->cover_note_type_id) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note Duration
                                                    ID</label>
                                                <input type="text" name="cover_note_duration_id"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('cover_note_duration_id', $quotation->cover_note_duration_id) }}">
                                            </div> --}}
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">CoverNote Start Date</label>
                                                <input type="date" name="cover_note_start_date"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('cover_note_start_date', \Carbon\Carbon::parse($quotation->cover_note_start_date)->format('Y-m-d')) }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note End Date</label>
                                                <input type="date" name="cover_note_end_date"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('cover_note_end_date', \Carbon\Carbon::parse($quotation->cover_note_end_date)->format('Y-m-d')) }}">
                                            </div>

                                        </div>

                                        {{-- Cover Note Description & Clause --}}
                                        <div class="row mb-6">
                                            <div class="col-md-6">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note
                                                    Description</label>
                                                <input type="text" name="cover_note_desc"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('cover_note_desc', $quotation->cover_note_desc) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-form-label fw-semibold fs-6">Operative Clause</label>
                                                <input type="text" name="operative_clause"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('operative_clause', $quotation->operative_clause) }}">
                                            </div>
                                        </div>

                                        {{-- 2. Premium & Tax --}}
                                        <hr class="my-8">
                                        <h5 class="mb-4 text-primary">Premium & Tax Details</h5>
                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Total Premium Excl.
                                                    Tax</label>
                                                <input type="number" step="0.01" name="total_premium_excluding_tax"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('total_premium_excluding_tax', $quotation->total_premium_excluding_tax) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Total Premium Incl.
                                                    Tax</label>
                                                <input type="number" step="0.01" name="total_premium_including_tax"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('total_premium_including_tax', $quotation->total_premium_including_tax) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Commission Paid</label>
                                                <input type="number" step="0.01" name="commission_paid"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('commission_paid', $quotation->commission_paid) }}">
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Commission Rate (%)</label>
                                                <input type="number" step="0.01" name="commission_rate"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('commission_rate', $quotation->commission_rate) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Cover Note Reference</label>
                                                <input type="text" name="cover_note_reference"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('cover_note_reference', $quotation->cover_note_reference) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">prev CoverNote Reference
                                                    Number</label>
                                                <input type="text" name="prev_cover_note_reference_number"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('prev_cover_note_reference_number', $quotation->prev_cover_note_reference_number) }}">
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <label class="col-form-label fw-semibold fs-6">Sum Insured</label>
                                                <input type="number" step="0.01" name="sum_insured"
                                                    class="form-control form-control-solid"
                                                    value="{{ old('sum_insured', $quotation->sum_insured) }}">
                                            </div> --}}
                                        </div>

                                        {{-- Premium & Tax remaining fields... continue similarly for all fillable fields --}}

                                    </div>

                                    {{-- SUBMIT BUTTON --}}
                                    <div class="card-footer d-flex justify-content-end py-6 px-9">
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
            const form = document.getElementById('quotationEditForm');
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

@endsection
<
