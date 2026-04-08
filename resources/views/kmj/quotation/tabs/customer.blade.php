@extends('kmj.layouts.app')

@section('title', 'Customer Details')

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

                        {{-- <div class="mt-5">
                            <h3>Customer Details Section</h3>
                            <p>This is the Customer Details section of the quotation.</p>
                        </div> --}}

                        <!--begin::Profile info-->
                        <div class="card mb-5 mb-xl-10">
                            <div class="card-header border-0 cursor-pointer">
                                <div class="card-title m-0">
                                    <h3 class="fw-bold m-0">Customer Details</h3>
                                </div>
                            </div>

                            <div id="CustomerInfo" class="collapse show">
                                <form id="createCustomerForm" action="{{ route('customer.update', $customer->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3">

                                        {{-- Client Full Name --}}
                                        <div class="col-md-6 form-group autocomplete-wrapper">
                                            <label for="name" class="form-label required-field">Client Full Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter Full Name" autocomplete="off" required
                                                value="{{ old('name', $customer->name) }}">
                                        </div>

                                        {{-- Region --}}
                                        <div class="col-md-6">
                                            <label for="region" class="form-label required-field">Region</label>
                                            <select class="form-select select2" name="region_id" id="region" required>
                                                <option value="">Select Region</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->id }}"
                                                        {{ old('region_id', $customer->district->region_id ?? '') == $region->id ? 'selected' : '' }}>
                                                        {{ $region->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- District --}}
                                        <div class="col-md-6">
                                            <label for="district" class="form-label required-field">District</label>
                                            <select class="form-select select2" name="district_id" id="district" required>
                                                <option value="">Select District</option>
                                                @if ($customer->district)
                                                    <option value="{{ $customer->district->id }}" selected>
                                                        {{ $customer->district->name }}</option>
                                                @endif
                                            </select>
                                        </div>

                                        {{-- Street --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">Street</label>
                                            <input class="form-control" type="text" name="street" placeholder="Street"
                                                required value="{{ old('street', $customer->street) }}">
                                        </div>

                                        {{-- Birthday --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">Date of Birth</label>
                                            <input class="form-control" type="date" name="dob"
                                                placeholder="Date of Birth"
                                                value="{{ old('dob', $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('Y-m-d') : '') }}"
                                                required>
                                        </div>

                                        {{-- Policy Holder Type --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">Policy Holder Type</label>
                                            <select class="form-select" name="policy_holder_type_id" required>
                                                <option value="">Select Type</option>
                                                @foreach ($policyHolderType as $type)
                                                    <option value="{{ $type->id }}"
                                                        {{ old('policy_holder_type_id', $customer->policy_holder_type_id) == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Policy Holder ID Type --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">Policy Holder ID Type</label>
                                            <select class="form-select" name="policy_holder_id_type_id" required>
                                                <option value="">Select ID Type</option>
                                                @foreach ($policyHolderIdType as $idType)
                                                    <option value="{{ $idType->id }}"
                                                        {{ old('policy_holder_id_type_id', $customer->policy_holder_id_type_id) == $idType->id ? 'selected' : '' }}>
                                                        {{ $idType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- ID Number --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">ID Number</label>
                                            <input class="form-control" type="text" name="policy_holder_id_number"
                                                placeholder="ID Number" required
                                                value="{{ old('policy_holder_id_number', $customer->policy_holder_id_number) }}">
                                        </div>

                                        {{-- Gender --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">Gender</label>
                                            <select class="form-select" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="M"
                                                    {{ old('gender', $customer->gender) == 'M' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="F"
                                                    {{ old('gender', $customer->gender) == 'F' ? 'selected' : '' }}>Female
                                                </option>
                                            </select>
                                        </div>

                                        {{-- Phone --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">Phone</label>
                                            <input class="form-control" type="text" name="phone" placeholder="Phone"
                                                required value="{{ old('phone', $customer->phone) }}">
                                        </div>

                                        {{-- Fax --}}
                                        <div class="col-md-6">
                                            <label class="form-label">Fax</label>
                                            <input class="form-control" type="text" name="fax" placeholder="Fax"
                                                value="{{ old('fax', $customer->fax) }}">
                                        </div>

                                        {{-- Postal Address --}}
                                        <div class="col-md-6">
                                            <label class="form-label required-field">Postal Address</label>
                                            <input class="form-control" type="text" name="postal_address"
                                                placeholder="Postal Address" required
                                                value="{{ old('postal_address', $customer->postal_address) }}">
                                        </div>

                                        {{-- Email Address --}}
                                        <div class="col-md-6">
                                            <label class="form-label">Email Address</label>
                                            <input class="form-control" type="email" name="email_address"
                                                placeholder="Email Address"
                                                value="{{ old('email_address', $customer->email_address) }}">
                                        </div>

                                    </div>

                                    {{-- SUBMIT BUTTON --}}
                                    <div class="mt-4 text-end">
                                        <button type="submit" id="submitBtn" class="btn"
                                            style="background-color: #003153; color: white;">
                                            <i class="bi bi-check-circle me-2 text-white"></i>
                                            <span id="submitText">Save Customer</span>
                                            <span id="submitSpinner"
                                                class="spinner-border spinner-border-sm text-light ms-2"
                                                style="display: none;"></span>
                                        </button>
                                    </div>
                                </form>


                            </div>
                        </div>
                        <!--end::Profile info-->

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

    <style>
        /* Rekebisha urefu wa select2 ili ifanane na Bootstrap form-select */
        .select2-container .select2-selection--single {
            height: 38px !important;
            display: flex;
            align-items: center;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            padding-left: 8px;
        }

        .select2-selection__rendered {
            line-height: 36px !important;
            font-size: 14px !important;
        }

        .select2-selection__arrow {
            height: 36px !important;
        }

        .select2-container {
            width: 100% !important;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /* Dropdown items hover style (lightened navy) */
        .select2-results__option--highlighted {
            background-color: #1A4A73 !important;
            color: #fff !important;
        }

        /* Search input inside dropdown */
        .select2-container .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            padding: 6px 10px !important;
            outline: none !important;
            background-color: #fff !important;
            color: #000 !important;
        }

        .select2-container .select2-search__field:focus {
            border-color: #1A4A73 !important;
            box-shadow: none !important;
            outline: none !important;
        }

        .select2-dropdown {
            background-color: #f8f9fa !important;
            border: 1px solid #ced4da !important;
        }

        .select2-results__option {
            font-size: 14px;
            padding: 8px 12px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize all select2 elements
            $('.select2').select2({
                dropdownParent: $('#CustomerInfo'),
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });


            $('#region').on('change', function() {
                var regionId = $(this).val();
                if (regionId) {
                    $.ajax({
                        url: '/dash/regions/' + regionId + '/districts',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#district').empty();
                            $('#district').append('<option value="">Select District</option>');
                            $.each(data, function(key, district) {
                                $('#district').append('<option value="' + district.id +
                                    '">' + district.name + '</option>');
                            });
                            $('#district').trigger('change.select2'); // refresh select2
                        }
                    });
                } else {
                    $('#district').empty();
                    $('#district').append('<option value="">Select District</option>');
                    $('#district').trigger('change.select2');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createCustomerForm');
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
