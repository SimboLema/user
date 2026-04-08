@extends('kmj.layouts.app')

@section('title', ' Agents Report Page')

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
                                            Agents Report</a>

                                    </div>
                                    <!--end::Name-->

                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('kmj.index') }}" class="btn btn-sm btn-light me-3">Dashboard</a>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                    </div>
                </div>
                <!--end::Navbar-->

                <!--begin::Summary Cards-->
                <div class="row g-6 mb-6">
                    <!-- Card 1: Total Quotations -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100" style="background-color:#9aa89b; color:white;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="bi bi-collection fs-1 mb-2 text-white"></i>
                                <div class="fs-3 fw-bold">10</div>
                                <div class="fw-bold fs-7 text-white-75">Total Agent</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Pending -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100" style="background-color:#c0c7c4; color:white;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="bi bi-people-fill fs-1 mb-2 text-white"></i>
                                <div class="fs-3 fw-bold">10</div>
                                <div class="fw-bold fs-7 text-white-75">Active Agents</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Success / Risknote Issued -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100" style="background-color:#9aa89b; color:white;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="bi bi-person-x-fill fs-1 mb-2 text-white"></i>
                                <div class="fs-3 fw-bold">0</div>
                                <div class="fw-bold fs-7 text-white-75">Inactive Agents</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Cancelled -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100" style="background-color:#c0c7c4; color:white;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="bi bi-building fs-1 mb-2 text-white"></i>
                                <div class="fs-3 fw-bold">0</div>
                                <div class="fw-bold fs-7 text-white-75">Total Branches</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Summary Cards-->


                <div class="card card-flush">
                    <!--begin::Header-->
                    {{-- <div class="card-header pt-7">

                    </div> --}}
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


                            <form method="GET" action="#" class="row g-3 mb-4" id="renewalForm">
                                <div class="col-md-3">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        value="{{ request('from_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        value="{{ request('to_date') }}">
                                </div>
                                <div class="col-md-3 align-self-end">
                                    <button type="submit" class="btn" id="filterBtn"
                                        style="background-color: #9aa89b;color:white">
                                        <span id="filterText">Filter</span>
                                        <span id="filterSpinner" class="spinner-border spinner-border-sm d-none"
                                            role="status" aria-hidden="true"></span>
                                    </button>
                                    <a href="{{ route('kmj.getAgents.report') }}" class="btn" id="resetBtn"
                                        style="background-color: #003153;color:white">
                                        <span id="resetText">Reset</span>
                                        <span id="resetSpinner" class="spinner-border spinner-border-sm d-none"
                                            role="status" aria-hidden="true"></span>
                                    </a>
                                </div>
                            </form>

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



    <!--end::Content wrapper-->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = $('#myTable').DataTable({
                pageLength: 10,
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        className: 'custom-dt-btn'
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'custom-dt-btn'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'custom-dt-btn'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'custom-dt-btn'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'custom-dt-btn'
                    }
                ]
            });


            $('#resetBtn').on('click', function() {
                $('#from_date').val('');
                $('#to_date').val('');
                table.search('').columns().search('').draw();
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.getElementById('filterBtn');
            const filterText = document.getElementById('filterText');
            const filterSpinner = document.getElementById('filterSpinner');

            const resetBtn = document.getElementById('resetBtn');
            const resetText = document.getElementById('resetText');
            const resetSpinner = document.getElementById('resetSpinner');

            const form = document.getElementById('renewalForm');

            // Handle filter submit
            form.addEventListener('submit', function(e) {
                filterBtn.disabled = true; // prevent double click
                filterText.classList.add('d-none'); // hide text
                filterSpinner.classList.remove('d-none'); // show spinner
            });

            // Handle reset click
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault(); // prevent immediate navigation
                resetBtn.disabled = true; // prevent double click
                resetText.classList.add('d-none');
                resetSpinner.classList.remove('d-none');

                // redirect after short delay for loader effect
                setTimeout(() => {
                    window.location.href = "{{ route('kmj.getAgents.report') }}";
                }, 200); // 200ms for spinner effect
            });
        });
    </script>

@endsection
