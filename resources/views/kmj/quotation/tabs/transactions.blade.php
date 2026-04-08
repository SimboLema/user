@extends('kmj.layouts.app')

@section('title', 'Transactions')

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

                        <div class="mt-5">
                            <h3>Transactions Section</h3>
                            <p>This is the Transactions section of the quotation.</p>
                        </div>

                        <!--begin::Billing Address-->
                        <div class="card  mb-5 mb-xl-10">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <h3>Billing Address</h3>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Addresses-->
                                <div class="row gx-9 gy-6">
                                    <!--begin::Col-->
                                    <div class="col-xl-6" data-kt-billing-element="address">
                                        <!--begin::Address-->
                                        <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                                            <!--begin::Details-->
                                            <div class="d-flex flex-column py-2">
                                                <div class="d-flex align-items-center fs-5 fw-bold mb-5">
                                                    Address 1
                                                    <span class="badge badge-light-success fs-7 ms-2">Primary</span>
                                                </div>

                                                <div class="fs-6 fw-semibold text-gray-600">
                                                    Ap #285-7193 Ullamcorper Avenue<br />
                                                    Amesbury HI 93373<br />US
                                                </div>
                                            </div>
                                            <!--end::Details-->

                                            <!--begin::Actions-->
                                            <div class="d-flex align-items-center py-2">
                                                <button class="btn btn-sm btn-light btn-active-light-primary me-3"
                                                    data-kt-billing-action="address-delete">

                                                    <!--begin::Indicator label-->
                                                    <span class="indicator-label">
                                                        Delete</span>
                                                    <!--end::Indicator label-->

                                                    <!--begin::Indicator progress-->
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                    <!--end::Indicator progress-->
                                                </button>
                                                <button class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_new_address">Edit</button>
                                            </div>
                                            <!--end::Actions-->
                                        </div>
                                        <!--end::Address-->
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-xl-6" data-kt-billing-element="address">
                                        <!--begin::Address-->
                                        <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                                            <!--begin::Details-->
                                            <div class="d-flex flex-column py-2">
                                                <div class="d-flex align-items-center fs-5 fw-bold mb-3">
                                                    Address 2
                                                </div>

                                                <div class="fs-6 fw-semibold text-gray-600">
                                                    Ap #285-7193 Ullamcorper Avenue<br />
                                                    Amesbury HI 93373<br />US
                                                </div>
                                            </div>
                                            <!--end::Details-->

                                            <!--begin::Actions-->
                                            <div class="d-flex align-items-center py-2">
                                                <button class="btn btn-sm btn-light btn-active-light-primary me-3"
                                                    data-kt-billing-action="address-delete">

                                                    <!--begin::Indicator label-->
                                                    <span class="indicator-label">
                                                        Delete</span>
                                                    <!--end::Indicator label-->

                                                    <!--begin::Indicator progress-->
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                    <!--end::Indicator progress-->
                                                </button>
                                                <button class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_new_address">Edit</button>
                                            </div>
                                            <!--end::Actions-->
                                        </div>
                                        <!--end::Address-->
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-xl-6" data-kt-billing-element="address">
                                        <!--begin::Address-->
                                        <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                                            <!--begin::Details-->
                                            <div class="d-flex flex-column py-2">
                                                <div class="d-flex align-items-center fs-5 fw-bold mb-3">
                                                    Address 3
                                                </div>

                                                <div class="fs-6 fw-semibold text-gray-600">
                                                    Ap #285-7193 Ullamcorper Avenue<br />
                                                    Amesbury HI 93373<br />US
                                                </div>
                                            </div>
                                            <!--end::Details-->

                                            <!--begin::Actions-->
                                            <div class="d-flex align-items-center py-2">
                                                <button class="btn btn-sm btn-light btn-active-light-primary me-3"
                                                    data-kt-billing-action="address-delete">

                                                    <!--begin::Indicator label-->
                                                    <span class="indicator-label">
                                                        Delete</span>
                                                    <!--end::Indicator label-->

                                                    <!--begin::Indicator progress-->
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                    <!--end::Indicator progress-->
                                                </button>
                                                <button class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_new_address">Edit</button>
                                            </div>
                                            <!--end::Actions-->
                                        </div>
                                        <!--end::Address-->
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-xl-6">

                                        <!--begin::Notice-->
                                        <div
                                            class="notice d-flex bg-light-primary rounded border-primary border border-dashed flex-stack h-xl-100 mb-10 p-6">

                                            <!--begin::Wrapper-->
                                            <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                                <!--begin::Content-->
                                                <div class="mb-3 mb-md-0 fw-semibold">
                                                    <h4 class="text-gray-900 fw-bold">This is a very important note!</h4>

                                                    <div class="fs-6 text-gray-700 pe-7">Writing headlines for blog posts is
                                                        much science and probably cool audience</div>
                                                </div>
                                                <!--end::Content-->

                                                <!--begin::Action-->
                                                <a href="billing.html#"
                                                    class="btn btn-primary px-6 align-self-center text-nowrap"
                                                    data-bs-toggle="modal" data-bs-target="#kt_modal_new_address">
                                                    New Address </a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Addresses-->

                                <!--begin::Tax info-->
                                <div class="mt-10">
                                    <h3 class="mb-3">Tax Location</h3>

                                    <div class="fw-semibold text-gray-600 fs-6">
                                        United States - 10% VAT<br />
                                        <a class="fw-bold" href="billing.html#">More Info</a>
                                    </div>
                                </div>
                                <!--end::Tax info-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Billing Address-->
                        <!--begin::Billing History-->
                        <div class="card ">
                            <!--begin::Card header-->
                            <div class="card-header card-header-stretch border-bottom border-gray-200">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <h3 class="fw-bold m-0">Billing History</h3>
                                </div>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar m-0">
                                    <!--begin::Tab nav-->
                                    <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
                                        <!--begin::Tab nav item-->
                                        <li class="nav-item" role="presentation">
                                            <a id="kt_billing_6months_tab" class="nav-link fs-5 fw-semibold me-3 active"
                                                data-bs-toggle="tab" role="tab" href="billing.html#kt_billing_months">
                                                Month
                                            </a>
                                        </li>
                                        <!--end::Tab nav item-->

                                        <!--begin::Tab nav item-->
                                        <li class="nav-item" role="presentation">
                                            <a id="kt_billing_1year_tab" class="nav-link fs-5 fw-semibold me-3"
                                                data-bs-toggle="tab" role="tab" href="billing.html#kt_billing_year">
                                                Year
                                            </a>
                                        </li>
                                        <!--end::Tab nav item-->

                                        <!--begin::Tab nav item-->
                                        <li class="nav-item" role="presentation">
                                            <a id="kt_billing_alltime_tab" class="nav-link fs-5 fw-semibold"
                                                data-bs-toggle="tab" role="tab" href="billing.html#kt_billing_all">
                                                All Time
                                            </a>
                                        </li>
                                        <!--end::Tab nav item-->
                                    </ul>
                                    <!--end::Tab nav-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Tab Content-->
                            <div class="tab-content">
                                <!--begin::Tab panel-->
                                <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active"
                                    role="tabpanel" aria-labelledby="kt_billing_months">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                                            <thead
                                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                <tr>
                                                    <td class="min-w-150px">Date</td>
                                                    <td class="min-w-250px">Description</td>
                                                    <td class="min-w-150px">Amount</td>
                                                    <td class="min-w-150px">Invoice</td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Nov 01, 2020</td>
                                                    <td><a href="billing.html#">Invoice for Ocrober 2025</a></td>
                                                    <td>$123.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Oct 08, 2020</td>
                                                    <td><a href="billing.html#">Invoice for September 2025</a></td>
                                                    <td>$98.03</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Aug 24, 2020</td>
                                                    <td>Paypal</td>
                                                    <td>$35.07</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Aug 01, 2020</td>
                                                    <td><a href="billing.html#">Invoice for July 2025</a></td>
                                                    <td>$142.80</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jul 01, 2020</td>
                                                    <td><a href="billing.html#">Invoice for June 2025</a></td>
                                                    <td>$123.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jun 17, 2020</td>
                                                    <td>Paypal</td>
                                                    <td>$523.09</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jun 01, 2020</td>
                                                    <td><a href="billing.html#">Invoice for May 2025</a></td>
                                                    <td>$123.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table container-->
                                </div>
                                <!--end::Tab panel-->
                                <!--begin::Tab panel-->
                                <div id="kt_billing_year" class="card-body p-0 tab-pane fade " role="tabpanel"
                                    aria-labelledby="kt_billing_year">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                                            <thead
                                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                <tr>
                                                    <td class="min-w-150px">Date</td>
                                                    <td class="min-w-250px">Description</td>
                                                    <td class="min-w-150px">Amount</td>
                                                    <td class="min-w-150px">Invoice</td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Dec 01, 2021</td>
                                                    <td><a href="billing.html#">Billing for Ocrober 2025</a></td>
                                                    <td>$250.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Oct 08, 2021</td>
                                                    <td><a href="billing.html#">Statements for September 2025</a></td>
                                                    <td>$98.03</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Aug 24, 2021</td>
                                                    <td>Paypal</td>
                                                    <td>$35.07</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Aug 01, 2021</td>
                                                    <td><a href="billing.html#">Invoice for July 2025</a></td>
                                                    <td>$142.80</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jul 01, 2021</td>
                                                    <td><a href="billing.html#">Statements for June 2025</a></td>
                                                    <td>$123.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jun 17, 2021</td>
                                                    <td>Paypal</td>
                                                    <td>$23.09</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table container-->
                                </div>
                                <!--end::Tab panel-->
                                <!--begin::Tab panel-->
                                <div id="kt_billing_all" class="card-body p-0 tab-pane fade " role="tabpanel"
                                    aria-labelledby="kt_billing_all">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-bordered align-middle gy-4 gs-9">
                                            <thead
                                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                <tr>
                                                    <td class="min-w-150px">Date</td>
                                                    <td class="min-w-250px">Description</td>
                                                    <td class="min-w-150px">Amount</td>
                                                    <td class="min-w-150px">Invoice</td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Nov 01, 2021</td>
                                                    <td><a href="billing.html#">Billing for Ocrober 2025</a></td>
                                                    <td>$123.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Aug 10, 2021</td>
                                                    <td>Paypal</td>
                                                    <td>$35.07</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Aug 01, 2021</td>
                                                    <td><a href="billing.html#">Invoice for July 2025</a></td>
                                                    <td>$142.80</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jul 20, 2021</td>
                                                    <td><a href="billing.html#">Statements for June 2025</a></td>
                                                    <td>$123.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jun 17, 2021</td>
                                                    <td>Paypal</td>
                                                    <td>$23.09</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <td>Jun 01, 2021</td>
                                                    <td><a href="billing.html#">Invoice for May 2025</a></td>
                                                    <td>$123.79</td>
                                                    <td><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                    </td>
                                                    <td class="text-right"><a href="billing.html#"
                                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table container-->
                                </div>
                                <!--end::Tab panel-->
                            </div>
                            <!--end::Tab Content-->
                        </div>
                        <!--end::Billing Address-->

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
