@extends('kmj.layouts.app')

@section('title', 'Claim Details')

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

                        @include('kmj.claim.components.tabs-nav')

                        <div class="card mb-5 mb-xl-10" id="kt_claim_details_view">
                            <div class="card-header cursor-pointer">
                                <div class="card-title m-0">
                                    <h3 class="fw-bold m-0">Claim Notification Information
                                        <span>
                                            @if ($claim->claim_notification_status === 'pending')
                                                <span
                                                    class="badge border border-warning text-warning d-inline-block text-center">Pending</span>
                                            @elseif($claim->claim_notification_status === 'success')
                                                <span
                                                    class="badge border border-success text-success d-inline-block text-center"
                                                    style="width: auto; color: green !important;">
                                                    Risknote Issued
                                                </span>
                                            @elseif($claim->claim_notification_status === 'rejected')
                                                <span
                                                    class="badge border border-danger text-danger d-inline-block text-center">Rejected</span>
                                            @endif
                                        </span>
                                    </h3>
                                </div>

                                <div class="d-flex align-items-center gap-2">

                                    @if ($claim->claim_notification_status === 'pending')
                                        <a href="{{ route('claim.notification.sendTira', $claim->id) }}"
                                            class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                            title="Send TIRA">
                                            <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}" alt="TIRA Logo"
                                                style="width: 35px; height: auto;">
                                        </a>
                                    @endif

                                </div>
                            </div>

                            <div class="card-body p-9">
                                @php
                                    $claimDetails = [
                                        'Claim Notification Number' => $claim->claim_notification_number,
                                        'Claim Report Date' => \Carbon\Carbon::parse($claim->claim_report_date)->format(
                                            'd M Y, h:i A',
                                        ),
                                        'Claim Form Dully Filled' => $claim->claim_form_dully_filled ? 'Yes' : 'No',
                                        'Loss Date' => \Carbon\Carbon::parse($claim->loss_date)->format('d M Y, h:i A'),
                                        'Nature of Loss' => $claim->loss_nature,
                                        'Type of Loss' => $claim->loss_type,
                                        'Loss Location' => $claim->loss_location,
                                        'Officer Name' => $claim->officer_name,
                                        'Officer Title' => $claim->officer_title,
                                        'Acknowledgement ID' => $claim->acknowledgement_id,
                                        'Request ID' => $claim->request_id,
                                        'Acknowledgement Status Code' => $claim->acknowledgement_status_code,
                                        'Acknowledgement Status Description' => $claim->acknowledgement_status_desc,
                                        'Response ID' => $claim->response_id,
                                        'Claim Reference Number' => $claim->claim_reference_number,
                                        'Response Status Code' => $claim->response_status_code,
                                        'Response Status Description' => $claim->response_status_desc,
                                        'Created At' => $claim->created_at
                                            ? $claim->created_at->format('d M Y, h:i A')
                                            : '',
                                        // 'Updated At' => $claim->updated_at
                                        //     ? $claim->updated_at->format('d M Y, h:i A')
                                        //     : '',
                                    ];
                                @endphp

                                <div class="row">
                                    @foreach ($claimDetails as $label => $value)
                                        <div class="col-md-6 col-lg-4 mb-7">
                                            <div class="d-flex flex-column">
                                                <label class="fw-semibold text-muted">{{ $label }}</label>
                                                <span class="fw-bold fs-6 text-gray-800">
                                                    @if (empty($value))
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            style="color: #5f9ea0;">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </span>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
