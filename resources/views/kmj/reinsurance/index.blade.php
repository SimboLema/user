@extends('kmj.layouts.app')

@section('title', ' Reinsurance Page')

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
                                            Reinsurance</a>

                                    </div>
                                    <!--end::Name-->

                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('kmj.index') }}" class="btn btn-sm btn-light me-3">Dashboard</a>
                                    <a href="#" class="btn btn-sm btn-light me-3">Report</a>


                                    {{-- <button class="btn btn-l" id="kt_user_follow_button" data-bs-toggle="modal"
                                        data-bs-target="#InsuranceType" style="background-color: #003153; color: white;">
                                        <i class="bi bi-plus fs-2 section-icon me-1 text-white"></i>
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">
                                            Create Quotation</span>
                                        <!--end::Indicator label-->

                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                        <!--end::Indicator progress-->
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


                <div class="card card-flush">
                    <!--begin::Header-->
                    <div class="card-header pt-7">

                    </div>
                    <div class="card-body pt-6">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <!--begin::Table-->
                            <table id="myTable" class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                    <tr>
                                        <th>SNo</th>
                                        <th>Reference</th>
                                        <th>Premium (Incl)</th>
                                        {{-- <th>Currency</th> --}}
                                        {{-- <th>Exchange Rate</th> --}}
                                        <th>Officer</th>
                                        <th>Status</th>
                                        {{-- <th>Ack Code</th> --}}
                                        <th>Ack Desc</th>
                                        {{-- <th>Resp Code</th> --}}
                                        <th>Resp Desc</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($reinsurances as $index => $reinsurance)
                                        <tr class="text-gray-700 fw-semibold">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $reinsurance->quotation->cover_note_reference ?? '-' }}</td>
                                            <td>{{ number_format($reinsurance->quotation->total_premium_including_tax) }}
                                            </td>
                                            {{-- <td>{{ $reinsurance->currency->code ?? '' }}</td> --}}
                                            {{-- <td>{{ $reinsurance->exchange_rate }}</td> --}}
                                            <td>{{ $reinsurance->authorizing_officer_name }}</td>
                                            <td>
                                                @php
                                                    $status = strtolower($reinsurance->status ?? 'pending');
                                                @endphp

                                                @if ($status === 'success')
                                                    <span
                                                        class="badge border border-success text-success d-inline-block text-center"
                                                        style="width: auto;">{{ ucfirst($status) }}</span>
                                                @elseif($status === 'fail')
                                                    <span
                                                        class="badge border border-danger text-danger d-inline-block text-center"
                                                        style="width: auto;">{{ ucfirst($status) }}</span>
                                                @else
                                                    <span
                                                        class="badge border border-warning text-warning d-inline-block text-center"
                                                        style="width: auto;">{{ ucfirst($status) }}</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $reinsurance->acknowledgement_status_code }}</td> --}}
                                            <td>{{ $reinsurance->acknowledgement_status_desc }}</td>
                                            {{-- <td>{{ $reinsurance->response_status_code }}</td> --}}
                                            <td>{{ $reinsurance->response_status_desc }}</td>
                                            <td class="text-center">
                                                @if ($reinsurance->status === 'pending')
                                                    <a href="{{ route('kmj.reinsurance.sendTira', $reinsurance->id) }}"
                                                        class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                                        title="Send TIRA" onclick="return confirmAndDisable(this);">
                                                        <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                            alt="TIRA Logo" style="height: 22px;">
                                                    </a>
                                                @elseif($reinsurance->status === 'success')
                                                    <a href="{{ route('kmj.quotation.show', $reinsurance->quotation->id) }}"
                                                        class="btn btn-sm"
                                                        style="background-color: #9aa89b; color: white; padding: 4px 8px; font-size: 10px;"
                                                        title="View More">
                                                        <small>View More</small>
                                                    </a>
                                                    {{-- <span
                                                        class="badge border border-success text-success d-inline-block text-center"
                                                        style="width: auto; color: green !important;">
                                                        Risknote Issued
                                                    </span> --}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!--end::Table body-->
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
