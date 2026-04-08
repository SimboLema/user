@extends('kmj.layouts.app')

@section('title', ' Claims Page')

@section('content')

    {{-- For Datatable --}}
    @include('kmj.layouts.partials.datatable')
    @include('kmj.claim.modal.search-covernote-reference')
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
                                        <a href="{{ route('kmj.quotation') }}" class="text-gray-800  fs-2 fw-bolder me-1"
                                            style="color: white !important;"><i
                                                class="bi bi-archive fs-2 section-icon me-3 text-white"></i>
                                            Claims</a>

                                    </div>
                                    <!--end::Name-->

                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('kmj.index') }}" class="btn btn-sm btn-light me-3">Dashboard</a>
                                    <a href="#" class="btn btn-sm btn-light me-3">Report</a>


                                    <button class="btn btn-sm me-3" id="kt_user_follow_button" data-bs-toggle="modal"
                                        data-bs-target="#coverNoteModal" style="background-color: #003153; color: white;">
                                        {{-- <i class="bi bi-plus fs-2 section-icon me-1 text-white"></i> --}}
                                        Select Cover Note
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
                            <!--begin::Table-->
                            <table id="myTable" class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                    <tr>
                                        <th class="min-w-100px text-center">Date</th>
                                        <th class="min-w-100px text-center">Claim Notification #</th>
                                        <th class="min-w-100px text-center">Loss Date</th>
                                        <th class="min-w-100px text-center">Loss Type</th>
                                        <th class="min-w-100px text-center">Officer</th>
                                        <th class="min-w-100px text-center">Notification Status</th>
                                        <th class="min-w-100px text-center">Status</th>
                                        <th class="min-w-100px text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($claims as $claim)
                                        <tr class="fs-6 fw-semibold text-center border-bottom-2">
                                            <td>{{ \Carbon\Carbon::parse($claim->claim_report_date)->format('d-m-Y') }}</td>
                                            <td>{{ $claim->claim_notification_number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($claim->loss_date)->format('d-m-Y') }}</td>
                                            <td>{{ $claim->loss_type }}</td>
                                            <td>{{ $claim->officer_name }}</td>
                                            <td>
                                                @if ($claim->claim_notification_status)
                                                    <span
                                                        class="badge border border-success text-success d-inline-block text-center"
                                                        style="width: auto; color: green !important;">
                                                        {{ ucwords(strtolower($claim->claim_notification_status)) }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($claim->status == 'pending')
                                                    <span
                                                        class="badge border border-warning text-warning d-inline-block text-center"
                                                        style="width: auto; color: warning !important;">Pending</span>
                                                @elseif($claim->status == 'success')
                                                    <span
                                                        class="badge border border-success text-success d-inline-block text-center"
                                                        style="width: auto; color: green !important;">Success</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('claim.notification', $claim->id) }}" class="btn btn-sm"
                                                    style="background-color: #9aa89b; color: white; padding: 4px 8px; font-size: 10px; width: auto;"
                                                    title="View More">
                                                    View More
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No claims found for this
                                                quotation.</td>
                                        </tr>
                                    @endforelse
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

@endsection
