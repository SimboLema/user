@extends('kmj.layouts.app')

@section('title', 'Payments')

@section('content')

    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
        }

        .btn-light {
            color: #ffffff !important;
            background-color: #003153 !important;
            border-color: #003153 !important;
        }

        .btn-light:hover {
            color: #001f33 !important;
            background-color: #001f33 !important;
            border-color: #001f33 !important;
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




                        <!--begin::Statements-->
                        <div class="card  ">
                            <!--begin::Header-->
                            <div class="card-header card-header-stretch">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Customer Payments</h3>

                                </div>
                                <!--end::Title-->


                            </div>
                            <!--end::Header-->

                            <!--begin::Tab Content-->
                            <div id="kt_referred_users_tab_content" class="tab-content">
                                <!--begin::Tab panel-->
                                <div id="kt_referrals_1" class="card-body p-0 tab-pane fade show active"
                                     role="tabpanel">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                            <!--begin::Thead-->
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                            <tr>
                                                <th class="min-w-100px text-center">Date</th>
                                                <th class="min-w-100px text-center">Insurance</th>
                                                <th class="min-w-100px text-center">CoverNote Type</th>
                                                <th class="min-w-100px text-center">Reference No</th>
                                                <th class="min-w-100px text-center">Channel</th>
                                                <th class="min-w-100px text-center">Amount</th>
                                                <th class="min-w-100px text-center">CoverNote</th>
                                            </tr>
                                            </thead>
                                            <!--end::Thead-->

                                            <!--begin::Tbody-->
                                            <tbody>
                                            @foreach($payments as $payment)
                                                <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, h:i A') }}</td>
                                                    <td>{{ $payment->quotation->coverage->product->insurance->name }}</td>
                                                    <td>{{ $payment->quotation->coverNoteType->name }}</td>
                                                    <td>{{ $payment->reference_no }}</td>
                                                    <td>{{ $payment->paymentMode->name }}</td>
                                                    <td>{{ number_format($payment->amount) }}</td>
                                                    <td>
                                                        <a href="{{ route('kmj.quotation.download.payment', $payment->id) }}"
                                                            class="btn btn-light btn-sm btn-active-light-primary">
                                                            Download
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach



                                            </tbody>
                                            <!--end::Tbody-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Tab panel-->


                            </div>
                            <!--end::Tab Content-->
                        </div>
                        <!--end::Statements-->

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

@endsection
