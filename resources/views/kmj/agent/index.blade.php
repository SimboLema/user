@extends('kmj.layouts.app')

@section('title', ' Agents Page')

@section('content')

    {{-- For Datatable --}}
    @include('kmj.layouts.partials.datatable')
    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
        }

        /* #myTable.dataTable tbody tr:nth-child(odd) {
                                                                                                                                                                            background-color: #e6e9e7;
                                                                                                                                                                        } */

        #myTable.dataTable tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        #myTable.dataTable tbody tr:hover {
            background-color: #d9dedb;
        }

        .symbol .symbol-label1 .bi {
            color: #003153 !important;
        }

        /* Focus / hover effect for DataTables search input */
        #myTable_filter input[type="search"] {
            transition: background-color 0.3s, color 0.3s;
        }

        /* On focus */
        #myTable_filter input[type="search"]:focus {
            /* background-color: #9aa89b; */
            /* optional: text color */
            border-color: #7f8c6f;
            /* optional: border color */
            outline: none;
            /* remove default outline */
        }
    </style>

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

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">





        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">


                <!--begin::Navbar-->
                <div class="card card-flush mb-9" id="kt_user_profile_panel" style="background-color:#9aa89b">
                    <!--begin::Hero nav-->
                    <div class="card-header rounded-top bgi-size-cover h-50px" style="background-position: 80% 30%;">
                    </div>
                    <!--end::Hero nav-->

                    <!--begin::Body-->
                    <div class="card-body mt-n19">
                        <!--begin::Details-->
                        <div class="m-0">


                            <!--begin::Info-->
                            <div class="d-flex flex-stack flex-wrap align-items-end">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-2 text-white">
                                        <a href="{{ route('kmj.quotation') }}" class="text-gray-800 fs-2 fw-bolder me-1"
                                            style="color: white !important;"><i
                                                class="bi bi-archive fs-2 section-icon me-3 text-white"></i>
                                            Agents</a>

                                    </div>
                                    <!--end::Name-->

                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('kmj.index') }}" class="btn btn-sm btn-light me-3">Dashboard</a>
                                    <a href="{{ route('kmj.getAgents.report') }}"
                                        class="btn btn-sm btn-light me-3">Report</a>
                                    <button class="btn btn-sm me-3" id="kt_user_follow_button" data-bs-toggle="modal"
                                        data-bs-target="#CustomerInfo" style="background-color: #003153; color: white;">
                                        {{-- <i class="bi bi-plus fs-2 section-icon me-1 text-white"></i> --}}
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">
                                            Create Agent</span>
                                        <!--end::Indicator label-->

                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                        <!--end::Indicator progress-->
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                    </div>
                </div>
                <!--end::Navbar-->


                <div class="card card-flush">
                    <!--begin::Header-->
                    <div class="card-header pt-7">

                    </div>
                    <div class="card-body pt-6">
                        <!--begin::Table container-->
                        <div class="table-responsive">

                            @php
                                $agents = [
                                    (object) [
                                        'id' => 1,
                                        'full_name' => 'Jackson Mwakipesile',
                                        'phone' => '0754123456',
                                        'region' => 'Dar es Salaam',
                                        'status' => 'Active',
                                        'agent_code' => 'AGT-101',
                                        'email' => 'jackson@example.com',
                                    ],
                                    (object) [
                                        'id' => 2,
                                        'full_name' => 'Maria Petro',
                                        'phone' => '0789456321',
                                        'region' => 'Dodoma',
                                        'status' => 'Inactive',
                                        'agent_code' => 'AGT-102',
                                        'email' => 'maria@example.com',
                                    ],
                                    (object) [
                                        'id' => 3,
                                        'full_name' => 'Joseph Ally',
                                        'phone' => '0712345678',
                                        'region' => 'Arusha',
                                        'status' => 'Active',
                                        'agent_code' => 'AGT-103',
                                        'email' => 'joseph@example.com',
                                    ],
                                ];
                            @endphp


                            <!--begin::Table-->
                            <table id="myTable" class="table align-middle table-row-bordered table-row-solid gy-4 gs-9"
                                style="padding-top:10px;">
                                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                    <tr>
                                        <th>S/No</th>
                                        <th>Full Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Region</th>
                                        <th>Status</th>
                                        <th>Agent Code</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $index => $agent)
                                        <tr class="text-gray-700 fw-semibold">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ucwords(strtolower($agent->full_name)) }}</td>
                                            <td>{{ $agent->phone }}</td>
                                            <td>{{ $agent->email }}</td>
                                            <td>{{ $agent->region }}</td>
                                            <td>
                                                @if ($agent->status == 'Active')
                                                    <span class="badge badge-light-success">Active</span>
                                                @else
                                                    <span class="badge badge-light-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $agent->agent_code }}</td>

                                            <td>
                                                <a href="#" class="btn btn-sm"
                                                    style="background-color: #9aa89b; color: white; padding: 4px 8px; font-size: 10px; width: auto;"
                                                    title="View More">
                                                    <small>View More</small>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                            <!--end::Table-->
                        </div>
                        <!--end::Table container-->
                    </div>
                </div>



            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->


    @include('kmj.customer.modal.save_customer')
    <!--end::Content wrapper-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable', {
                pageLength: 10,
                searching: true,
                ordering: true,
                info: true,
                responsive: true
            });
        });
    </script>

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
