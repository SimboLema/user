@extends('kmj.layouts.app')

@section('title', ' Renewals Page')

@section('content')

    @php
        use Carbon\Carbon;
    @endphp


    {{-- For Datatable --}}
    @include('kmj.layouts.partials.datatable')
    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
        }


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
                                            Renewals</a>

                                    </div>
                                    <!--end::Name-->

                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('kmj.index') }}" class="btn btn-sm btn-light me-3">Dashboard</a>
                                    <a href="{{ route('kmj.renewals.report') }}"
                                        class="btn btn-sm btn-light me-3">Report</a>

                                    {{-- <a href="{{ route('fleet.motor') }}" class="btn btn-sm me-3"
                                        style="background-color: #003153; color: white;">Fleet Motors</a>


                                    <button class="btn btn-sm me-3" id="kt_user_follow_button" data-bs-toggle="modal"
                                        data-bs-target="#InsuranceType" style="background-color: #003153; color: white;">
                                        <span class="indicator-label">
                                            Create Quotation</span>
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button> --}}
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                    </div>
                </div>
                <!--end::Navbar-->


                <div class="col-xl-12">

                    <div class="card card-flush h-md-100">


                        <div class="card-body pt-6">
                            <!--begin::Table container-->
                            {{-- Filter Area --}}
                            <div class="table-responsive">

                                <form method="GET" action="{{ route('kmj.renewals') }}" class="row g-3 mb-4"
                                    id="renewalForm">
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
                                        <a href="{{ route('kmj.renewals') }}" class="btn" id="resetBtn"
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
                                            {{-- <th>Reference</th> --}}
                                            {{-- <th>Request ID</th> --}}
                                            {{-- <th>CoverNoteRef</th> --}}
                                            {{-- <th class="min-w-100px text-center">Type</th> --}}
                                            {{-- <th class="min-w-100px text-center">Payment</th> --}}
                                            <th>Premium</th>
                                            <th>Created At</th>
                                            <th>Expire At</th>
                                            <th>N.o.Days</th>
                                            <th>R. Days</th>
                                            <th>Status</th>

                                            <th>Actions</th>

                                        </tr>
                                    </thead>
                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach ($quotations as $quotation)
                                            @php
                                                $start = Carbon::parse($quotation->cover_note_start_date);
                                                $end = Carbon::parse($quotation->cover_note_end_date);
                                                $today = Carbon::today();

                                                // Jumla ya siku kutoka start → end
                                                $number_of_days = $start->diffInDays($end);

                                                // Siku zilizobaki kutoka leo → end
                                                $remain_days = $today->lte($end) ? $today->diffInDays($end) : 0;
                                            @endphp

                                            <tr class="text-gray fs-6 fw-semibold  border-bottom-2">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucwords(strtolower($quotation->customer->name)) }}</td>
                                                {{-- <td>{{ $quotation->cover_note_reference ?? '-' }}</td> --}}
                                                {{-- <td>{{ $quotation->request_id ?? '-' }}</td> --}}
                                                {{-- <td>{{ $quotation->cover_note_reference ?? '-' }}</td> --}}
                                                {{-- <td>{{ ucwords(strtolower($quotation->coverage->product->insurance->name)) }}
                                                </td>
                                                <td>{{ ucwords(strtolower($quotation->paymentMode->name)) }}</td> --}}
                                                <td>{{ number_format($quotation->total_premium_including_tax) }}
                                                </td>
                                                <td>{{ Carbon::parse($quotation->created_at)->format('d M Y, h:i A') }}
                                                </td>
                                                <td>{{ Carbon::parse($quotation->cover_note_end_date)->format('d M Y') }}
                                                </td>



                                                <td>{{ number_format($number_of_days) }}</td>
                                                <td>{{ number_format($remain_days) }}</td>

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


    <script>
        $(document).ready(function() {
            let table = $('#myTable').DataTable({
                pageLength: 10,
                responsive: true,
                // dom: 'Bfrtip',
                //     buttons: [{
                //             extend: 'copy',
                //             text: 'Copy',
                //             className: 'custom-dt-btn'
                //         },
                //         {
                //             extend: 'csv',
                //             text: 'CSV',
                //             className: 'custom-dt-btn'
                //         },
                //         {
                //             extend: 'excel',
                //             text: 'Excel',
                //             className: 'custom-dt-btn'
                //         },
                //         {
                //             extend: 'pdf',
                //             text: 'PDF',
                //             className: 'custom-dt-btn'
                //         },
                //         {
                //             extend: 'print',
                //             text: 'Print',
                //             className: 'custom-dt-btn'
                //         }
                //     ]
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
                    window.location.href = "{{ route('kmj.renewals') }}";
                }, 200); // 200ms for spinner effect
            });
        });
    </script>


@endsection
