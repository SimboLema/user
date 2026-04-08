@extends('kmj.layouts.app')

@section('title', ' Quotation Report Page')

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
            border-color: #9aa89b;
            /* optional: border color */
            outline: none;
            /* remove default outline */
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
                                            Quotation Report</a>

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
                                <i class="bi bi-file-text fs-1 mb-2 text-white"></i>
                                <div class="fs-3 fw-bold">{{ $quotations->count() }}</div>
                                <div class="fw-bold fs-7 text-white-75">Total Quotations</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Pending -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100" style="background-color:#c0c7c4; color:white;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="bi bi-file-check fs-1 mb-2" style="color: white"></i>
                                <div class="fs-3 fw-bold" style="color: white">
                                    {{ $quotations->where('status', 'success')->count() }}</div>
                                <div class="fw-bold fs-7" style="color: white">Risknote Issued</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Success / Risknote Issued -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100" style="background-color:#9aa89b; color:white;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="bi bi-hourglass-split fs-1 mb-2 text-white"></i>
                                <div class="fs-3 fw-bold">{{ $quotations->where('status', 'pending')->count() }}</div>
                                <div class="fw-bold fs-7 text-white-75">Pending Quotation</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Cancelled -->
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100" style="background-color:#c0c7c4; color:white;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="bi bi-x-octagon fs-1 mb-2" style="color: white"></i>
                                <div class="fs-3 fw-bold" style="color: white">
                                    {{ $quotations->where('status', 'cancelled')->count() }}</div>
                                <div class="fw-bold fs-7" style="color: white">Failed/Cancelled Quotation</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Summary Cards-->



                <div class="col-xl-12">

                    <div class="card card-flush h-md-100" style="background-color: #f5f6f5">


                        <div class="card-body pt-6">
                            <!--begin::Table container-->
                            <div class="table-responsive">

                                <form method="GET" action="{{ route('kmj.quotation.report') }}" class="row g-3 mb-4"
                                    id="renewalForm">
                                    <div class="col-md-2">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control"
                                            value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="to_date" class="form-label">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control"
                                            value="{{ request('to_date') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Insurance Type</label>
                                        <select class="form-select select2" id="insuranceSelect" name="insurance_id">
                                            <option value="">-- Select Insurance --</option>
                                            @foreach ($insurance as $ins)
                                                {{-- <option value="{{ $ins->id }}">{{ $ins->name }}</option> --}}
                                                <option value="{{ $ins->id }}"
                                                    {{ request('insurance_id') == $ins->id ? 'selected' : '' }}>
                                                    {{ $ins->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Product</label>
                                        <select class="form-select select2" id="productSelect" name="product_id">
                                            <option value="">-- Select Product --</option>
                                            @if (request('insurance_id') &&
                                                    ($products = \App\Models\Models\KMJ\Product::where('insurance_id', request('insurance_id'))->get()))
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Coverage</label>
                                        <select class="form-select select2" id="coverageSelect" name="coverage_id">
                                            <option value="">-- Select Coverage --</option>
                                            @if (request('product_id') &&
                                                    ($coverages = \App\Models\Models\KMJ\Coverage::where('product_id', request('product_id'))->get()))
                                                @foreach ($coverages as $coverage)
                                                    <option value="{{ $coverage->id }}"
                                                        {{ request('coverage_id') == $coverage->id ? 'selected' : '' }}>
                                                        {{ $coverage->risk_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                    <div class="col-md-2 align-self-end">
                                        <button type="submit" class="btn" id="filterBtn"
                                            style="background-color: #9aa89b;color:white">
                                            <span id="filterText">Filter</span>
                                            <span id="filterSpinner" class="spinner-border spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                        </button>
                                        <a href="{{ route('kmj.quotation.report') }}" class="btn" id="resetBtn"
                                            style="background-color: #003153;color:white">
                                            <span id="resetText">Reset</span>
                                            <span id="resetSpinner" class="spinner-border spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                        </a>
                                    </div>
                                </form>

                                <!--begin::Table-->
                                <table id="myTable"
                                    class="table align-middle table-row-bordered table-row-solid gy-4 gs-9"
                                    style="padding-top:10px;">
                                    <!--begin::Table head-->
                                    <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                        <tr>
                                            <th>sno</th>
                                            <th>Client</th>
                                            <th>Type</th>
                                            {{-- <th>CoverNoteRef</th> --}}
                                            {{-- <th class="min-w-100px text-center">Type</th> --}}
                                            {{-- <th class="min-w-100px text-center">Payment</th> --}}
                                            <th>Premium</th>
                                            <th>Created At</th>
                                            <th>Insurer Name</th>
                                            {{-- <th>Intermediary</th> --}}

                                            <th>Status</th>

                                            <th>Actions</th>

                                        </tr>
                                    </thead>
                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach ($quotations as $quotation)
                                            <tr class="text-gray fs-6 fw-semibold  border-bottom-2">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucwords(strtolower($quotation->customer->name)) }}</td>
                                                <td>{{ !empty($quotation->fleet_id) ? 'Fleet Motors' : ucwords(strtolower($quotation->coverage->product->insurance->name)) ?? '-' }}
                                                </td>
                                                {{-- <td>{{ $quotation->cover_note_reference ?? '-' }}</td> --}}
                                                {{-- <td>{{ ucwords(strtolower($quotation->coverage->product->insurance->name)) }}
                                                </td>
                                                <td>{{ ucwords(strtolower($quotation->paymentMode->name)) }}</td> --}}
                                                <td>{{ number_format($quotation->total_premium_including_tax) }}

                                                </td>

                                                <td>{{ \Carbon\Carbon::parse($quotation->created_at)->format('Y-m-d, h:i') }}
                                                </td>
                                                <td>{{ $quotation->insuarer->name ?? '-' }}</td>

                                                {{-- <td>KMJ Brokers</td> --}}

                                                <td>
                                                    @if ($quotation->status === 'pending')
                                                        <span
                                                            class="badge border border-warning text-success d-inline-block text-center"
                                                            style="width: auto; color: orange !important;">
                                                            Awaiting receipt
                                                        </span>
                                                    @elseif($quotation->status === 'success')
                                                        <span
                                                            class="badge border border-success text-success d-inline-block text-center"
                                                            style="width: auto; color: green !important;">
                                                            Risknote Issued
                                                        </span>
                                                    @elseif($quotation->status === 'cancelled')
                                                        <span
                                                            class="badge border border-danger text-danger d-inline-block text-center"
                                                            style="width: auto; color: red !important;">
                                                            Cancelled
                                                        </span>
                                                    @endif
                                                </td>


                                                <td class="text-center">
                                                    <div
                                                        style="display: flex; gap: 5px; align-items: center; justify-content: center;">

                                                        <a href="{{ route('kmj.quotation.show', $quotation->id) }}"
                                                            class="btn btn-sm"
                                                            style="background-color: #9aa89b; color: white; padding: 4px 8px; font-size: 10px;"
                                                            title="View More">
                                                            <small>View More</small>
                                                        </a>

                                                        {{-- <button class="btn btn-sm"
                                                            style="background-color: #003153; color: white; padding: 4px 8px; font-size: 10px;"
                                                            data-bs-toggle="modal" data-bs-target="#reinsuranceModal"
                                                            data-quotation-id="{{ $quotation->id }}">
                                                            <small>Reinsurance</small>
                                                        </button> --}}

                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                    </div>
                    <!--end::Table widget 14-->


                </div>

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

        /* Andika text vizuri katikati */
        .select2-selection__rendered {
            line-height: 36px !important;
        }

        /* Rekebisha arrow (dropdown icon) */
        .select2-selection__arrow {
            height: 36px !important;
        }

        /* Hakikisha modal layout haivunjiki */
        .modal .select2-container {
            width: 100% !important;
        }

        /* Rekebisha space kati ya selects */
        .select2-container {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /* Background ya dropdown item (option) */
        .select2-results__option--highlighted {
            background-color: #001f33 !important;
            color: white !important;
        }

        /* Search input ndani ya dropdown ya Select2 */
        .select2-container .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            padding: 6px 10px !important;
            outline: none !important;
            background-color: #fff !important;
            /* badilisha kama unataka dark mode */
            color: #000 !important;
        }

        /* Ikiwa unataka dark mode kwa dropdown search (optional) */
        .modal .select2-dropdown {
            background-color: #f8f9fa !important;
            /* light gray background */
            border: 1px solid #ced4da !important;
        }

        /* Ondoa blue border wakati wa focus */
        .select2-container .select2-search__field:focus {
            border-color: #001f33 !important;
            /* theme color yako */
            box-shadow: none !important;
            outline: none !important;
        }

        /* Rekebisha font-size na spacing za options */
        .select2-results__option {
            font-size: 12px;
            padding: 6px 10px;
        }

        /* Dropdown items ziwe na hover style safi */
        .select2-results__option--highlighted {
            background-color: #001f33 !important;
            color: #fff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 12px !important;
        }
    </style>


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

            // Toast notification function
            function showNotification(message, type) {
                const toastContainer = document.getElementById('toast-container');

                const toast = document.createElement('div');
                toast.className = `toast show align-items-center custom-toast toast-${type} bg-light border-0`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');

                // Set icon based on type
                let icon = 'bi-info-circle';
                if (type === 'success') icon = 'bi-check-circle';
                if (type === 'danger') icon = 'bi-trash';
                if (type === 'warning') icon = 'bi-exclamation-triangle';

                toast.innerHTML = `
                    <div class="d-flex align-items-center px-3 py-2">
                        <i class="bi ${icon} toast-icon text-${type}"></i>
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;

                toastContainer.appendChild(toast);

                // Initialize Bootstrap Toast
                const bsToast = new bootstrap.Toast(toast, {
                    autohide: true,
                    delay: 3000
                });
                bsToast.show();

                // Remove toast after it's hidden
                toast.addEventListener('hidden.bs.toast', () => {
                    toast.remove();
                });

                // Allow clicking to dismiss
                toast.addEventListener('click', () => {
                    bsToast.hide();
                });
            }
        });
    </script>
    @if (session('success'))
        <script>
            function showNotification(message, type) {
                const toastContainer = document.getElementById('toast-container');

                const toast = document.createElement('div');
                toast.className = `toast show align-items-center custom-toast toast-${type} bg-light border-0`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');

                // Set icon based on type
                let icon = 'bi-info-circle';
                if (type === 'success') icon = 'bi-check-circle';
                if (type === 'danger') icon = 'bi-trash';
                if (type === 'warning') icon = 'bi-exclamation-triangle';

                toast.innerHTML = `
                                    <div class="d-flex align-items-center px-3 py-2">
                                        <i class="bi ${icon} toast-icon text-${type}"></i>
                                        <div class="toast-body">${message}</div>
                                        <button type="button" class="btn-close ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                `;

                toastContainer.appendChild(toast);

                // Initialize Bootstrap Toast
                const bsToast = new bootstrap.Toast(toast, {
                    autohide: true,
                    delay: 3000
                });
                bsToast.show();

                // Remove toast after it's hidden
                toast.addEventListener('hidden.bs.toast', () => {
                    toast.remove();
                });

                // Allow clicking to dismiss
                toast.addEventListener('click', () => {
                    bsToast.hide();
                });
            }
        </script>
    @endif



    <script>
        document.getElementById('downloadBtn').addEventListener('click', function() {
            // Store the download flag
            sessionStorage.setItem('downloadCoverNote', 'true');
            // Redirect to the page that will generate the PDF
            window.location.href = 'textDownload.html';
        });
    </script>
    <script>
        function openNewTransactionModal() {
            const modal = new bootstrap.Modal(document.getElementById('newTransactionModal'));
            modal.show();
        }
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
                    window.location.href = "{{ route('kmj.quotation.report') }}";
                }, 200); // 200ms for spinner effect
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Fetch products based on selected insurance type
            $('#insuranceSelect').on('change', function() {
                var insuranceId = $(this).val();
                if (insuranceId) {
                    $.ajax({
                        url: `/dash/insurance/${insuranceId}/products`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#productSelect').empty().append(
                                '<option value="">-- Select Product --</option>');
                            $('#coverageSelect').empty().append(
                                '<option value="">-- Select Coverage --</option>'
                            ); // Clear coverage dropdown
                            $.each(data, function(key, value) {
                                $('#productSelect').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#productSelect').empty().append('<option value="">-- Select Product --</option>');
                    $('#coverageSelect').empty().append(
                        '<option value="">-- Select Coverage --</option>'); // Clear coverage dropdown
                }
            });

            // Fetch coverages based on selected product
            $('#productSelect').on('change', function() {
                var productId = $(this).val();
                if (productId) {
                    $.ajax({
                        url: `/dash/product/${productId}/coverages`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#coverageSelect').empty().append(
                                '<option value="">-- Select Coverage --</option>');
                            $.each(data, function(key, value) {
                                $('#coverageSelect').append('<option value="' + value
                                    .id + '">' + value.risk_name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#coverageSelect').empty().append('<option value="">-- Select Coverage --</option>');
                }
            });
        });
    </script>



@endsection
