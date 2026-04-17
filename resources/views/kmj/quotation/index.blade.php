@extends('kmj.layouts.app')

@section('title', ' Quotation Page')

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

        @include('kmj.quotation.model.create_insurance_modal')


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
                                            Quotation</a>

                                    </div>
                                    <!--end::Name-->

                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('kmj.index') }}" class="btn btn-sm btn-light me-3">Dashboard</a>
                                    <a href="{{ route('kmj.quotation.report') }}" class="btn btn-sm btn-light me-3">Report</a>

                                    <a href="{{ route('fleet.motor') }}" class="btn btn-sm me-3"
                                        style="background-color: #003153; color: white;">Fleet Motors</a>


                                    <button class="btn btn-sm me-3" id="kt_user_follow_button" data-bs-toggle="modal"
                                        data-bs-target="#InsuranceType" style="background-color: #003153; color: white;">
                                        {{-- <i class="bi bi-plus fs-2 section-icon me-1 text-white"></i> --}}
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">
                                            Create Quotation</span>
                                        <!--end::Indicator label-->

                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                        <!--end::Indicator progress--> </button>
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
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table id="myTable"
                                    class="table align-middle table-row-bordered table-row-solid gy-4 gs-9"
                                    style="padding-top:10px;">
                                    <!--begin::Table head-->
                                    <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                        <tr>
                                            <th>#</th>
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
                                                <td>{{ $quotation->insuarer->name ?? 'No Insurer' }}</td>
                                                {{-- <td>KMJ Brokers</td> --}}

                                                <td>
                                                    @if ($quotation->status === 'pending')
                                                        <span
                                                            class="badge border border-warning text-success d-inline-block text-center"
                                                            style="width: auto; color: orange !important;">
                                                            Awaiting approval
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


@endsection
