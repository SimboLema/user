@extends('kmj.layouts.app')

@section('title', 'Insuarer Dashboard')

@section('content')

<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-18">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">

            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-white fw-bold fs-3 flex-column justify-content-center my-0"
                    style="color: #003153 !important;">
                    Insurance Portal
                </h1>
                <!--end::Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('insuarer.dashboard') }}" class="text-muted text-hover-primary">
                            Home | </a>
                    </li>
                    <li class="breadcrumb-item text-muted">Dashboard</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->

            <!--begin::Actions-->
            <div class="d-flex align-items-center">
                <div class="toolbar-select form-floating w-175px w-lg-175px h-55px me-4 bg-white rounded">
                    <div class="h-55px d-flex align-items-center justify-content-center">
                        <span class="fs-5 fw-bold" style="color: #003153 !important;">Quotation</span>
                    </div>
                </div>

                <a href="{{ route('insuarer.support') }}" class="btn btn-sm me-3"
                    style="text-decoration: none; color: inherit; background-color: #9aa89b; color: white;">
                    <span>Support</span>
                </a>
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">

        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!--begin::Row-->
            <div class="row gx-5 gx-xl-10">
                <!--begin::Col-->
                <div class="col-xl-4 mb-10">

                    <!--begin::Lists Widget 19-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Heading-->
                        <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"
                            style="background: linear-gradient(135deg, #9aa89b 0%, #9aa89b 30%, #9aa89b 100%);
           background-size: 300% 300%;
           animation: gradientShift 10s ease infinite;"
                            data-bs-theme="light">

                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column text-white pt-15">
                                <span class="fw-bold fs-1x mb-3">Premium (Ex VAT)</span>

                                <div class="fs-4 text-white">
                                    <span class="position-relative d-inline-block">
                                        <a href="#" class="text-white fs-2x fw-bold d-block mb-1">Tsh. 0.00</a>
                                    </span>
                                </div>
                            </h3>
                            <!--end::Title-->

                            <!--begin::Toolbar-->
                            <div class="card-toolbar pt-5">
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">
                                            Quick Actions</div>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu separator-->
                                    <div class="separator mb-3 opacity-75"></div>
                                    <!--end::Menu separator-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('insuarer.quotations') }}" class="menu-link px-3">
                                            View Quotations
                                        </a>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('insuarer.support') }}" class="menu-link px-3">
                                            Contact Support
                                        </a>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu separator-->
                                    <div class="separator mt-3 opacity-75"></div>
                                    <!--end::Menu separator-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content px-3 py-3">
                                            <a class="btn btn-primary btn-sm px-4" href="{{ route('insuarer.quotations') }}">
                                                Generate Reports
                                            </a>
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--begin::Body-->
                        <div class="card-body mt-n20">
                            <!--begin::Stats-->
                            <div class="mt-n20 position-relative">
                                <!--begin::Row-->
                                <div class="row g-3 mt-10 g-lg-6">

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('insuarer.dashboard') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-house fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Dashboard</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('insuarer.quotations') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-file-text fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Quotations</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('insuarer.support') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-headset fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Support</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('insuarer.quotations') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-clipboard-check fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">My Quotations</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('insuarer.quotations') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-graph-up fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Analytics</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('insuarer.quotations') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-download fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Downloads</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Lists Widget 19-->
                </div>
                <!--end::Col-->

                <style>
                    @keyframes gradientShift {
                        0% { background-position: 0% 50%; }
                        50% { background-position: 100% 50%; }
                        100% { background-position: 0% 50%; }
                    }
                </style>

                <!--begin::Col-->
                <div class="col-xl-8 mb-10">
                    <!--begin::Row-->
                    <div class="row g-5 g-xl-10">
                        <!--begin::Col-->
                        <div class="col-xl-6 mb-xl-10">

                            <!--begin::Slider Widget 1-->
                            <div id="kt_sliders_widget_campaigns"
                                class="card card-flush carousel carousel-custom carousel-stretch slide h-xl-100"
                                data-bs-ride="carousel" data-bs-interval="5500">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <h4 class="card-title d-flex align-items-start flex-column">
                                        <span class="card-label fw-bold" style="color: #003153">Latest Updates</span>
                                    </h4>
                                    <div class="card-toolbar">
                                        <ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-warning">
                                            <li data-bs-target="#kt_sliders_widget_campaigns" data-bs-slide-to="0"
                                                class="active ms-1"></li>
                                            <li data-bs-target="#kt_sliders_widget_campaigns" data-bs-slide-to="1"
                                                class="ms-1"></li>
                                        </ol>
                                    </div>
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div class="card-body py-6">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active show">
                                            <div class="d-flex align-items-center mb-9">
                                                <div class="symbol me-5 position-relative">
                                                    <span class="symbol-label1">
                                                        <i class="bi bi-file-earmark-text fs-3x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                        <span class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                    </span>
                                                </div>

                                                <div class="m-0">
                                                    <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                        style="color:#003153;">
                                                        Total Quotations
                                                    </h4>

                                                    <div class="d-flex d-grid gap-5">
                                                        <div class="d-flex flex-column flex-shrink-0 me-4">
                                                            <div class="d-flex align-items-center fs-10 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                style="animation-delay: 0.1s">
                                                                <i class="fs-6 text-gray-600 me-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                </i>
                                                                <span class="fs-1 ms-1" style="color:#003153;">{{ $quotations }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="m-0 animate__animated animate__fadeInUp"
                                                style="animation-delay: 0.4s;">
                                                <a href="{{ route('insuarer.quotations') }}" class="btn btn-sm mb-2 btn-hover-rise"
                                                    style="background-color: #9aa89b; color:white">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>

                                        <div class="carousel-item">
                                            <div class="d-flex align-items-center mb-9">
                                                <div class="symbol me-5 position-relative">
                                                    <span class="symbol-label1">
                                                        <i class="bi bi-check-circle fs-3x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                        <span class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                    </span>
                                                </div>

                                                <div class="m-0">
                                                    <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                        style="color:#003153;">
                                                        Approved Quotations
                                                    </h4>

                                                    <div class="d-flex d-grid gap-5">
                                                        <div class="d-flex flex-column flex-shrink-0 me-4">
                                                            <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                style="animation-delay: 0.1s">
                                                                <span class="fs-2 ms-1" style="color:#003153;">0</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="m-0 animate__animated animate__fadeInUp"
                                                style="animation-delay: 0.4s">
                                                <a href="{{ route('insuarer.quotations') }}" class="btn btn-sm mb-2 btn-hover-rise"
                                                    style="background-color: #9aa89b; color:white">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Slider Widget 1-->

                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-xl-6 mb-5 mb-xl-10">

                            <!--begin::Slider Widget 2-->
                            <div id="kt_sliders_widget_2_slider"
                                class="card card-flush carousel carousel-custom carousel-stretch slide h-xl-100"
                                data-bs-ride="carousel" data-bs-interval="5500">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <h4 class="card-title d-flex align-items-start flex-column">
                                        <span class="card-label fw-bold" style="color: #003153">Account Status</span>
                                    </h4>
                                    <div class="card-toolbar">
                                        <ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-warning">
                                            <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="0"
                                                class="active ms-1"></li>
                                            <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="1"
                                                class="ms-1"></li>
                                        </ol>
                                    </div>
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div class="card-body py-6">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active show">
                                            <div class="d-flex align-items-center mb-9">
                                                <div class="symbol me-5 position-relative">
                                                    <span class="symbol-label1">
                                                        <i class="bi bi-person-circle fs-3x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                        <span class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                    </span>
                                                </div>
                                                <div class="m-0">
                                                    <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                        style="color:#003153;">
                                                        Account Name</h4>
                                                    <div class="d-flex d-grid gap-5">
                                                        <div class="d-flex flex-column flex-shrink-0 me-4">
                                                            <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                style="animation-delay: 0.1s">
                                                                <span class="fs-6 ms-1" style="color:#003153;">{{ Auth::guard('insuarer')->user()->name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="m-0 animate__animated animate__fadeInUp"
                                                style="animation-delay: 0.4s">
                                                <a href="#" class="btn btn-sm mb-2 btn-hover-rise"
                                                    style="background-color: #9aa89b; color:white;">
                                                    Edit Profile
                                                </a>
                                            </div>
                                        </div>

                                        <div class="carousel-item">
                                            <div class="d-flex align-items-center mb-9">
                                                <div class="symbol me-5 position-relative">
                                                    <span class="symbol-label1">
                                                        <i class="bi bi-shield-check fs-3x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                        <span class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                    </span>
                                                </div>

                                                <div class="m-0">
                                                    <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                        style="color:#003153;">
                                                        Account Status</h4>
                                                    <div class="d-flex d-grid gap-5">
                                                        <div class="d-flex flex-column flex-shrink-0 me-4">
                                                            <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                style="animation-delay: 0.1s">
                                                                <span class="fs-6 ms-1" style="color:#003153;">Active</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="m-0 animate__animated animate__fadeInUp"
                                                style="animation-delay: 0.4s">
                                                <a href="{{ route('insuarer.support') }}" class="btn btn-sm mb-2 btn-hover-rise"
                                                    style="background-color: #9aa89b;color:white;">
                                                    Contact Support
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Slider Widget 2-->

                            <!-- Add animate.css for additional animations -->
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->

                    <!--begin::Statistics Cards-->
                    <div class="row g-5 g-xl-10 mt-0">
                        <div class="col-xl-12">
                            <div class="card border-transparent" data-bs-theme="light" style="background-color: #ffffff;">
                                <div class="card card-flush h-xl-100">
                                    <!--begin::Header-->
                                    <div class="card-header pt-7">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-gray-800 d-flex align-items-center">
                                                Quotation Summary
                                                <span class="dropdown ms-2">
                                                    <a href="#" class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-chevron-down fs-8"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                                        <li><a class="dropdown-item" href="#">Last 3 Months</a></li>
                                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                                    </ul>
                                                </span>
                                            </span>
                                        </h3>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                        <div id="kt_charts_insuarer_summary" class="w-100 min-h-auto ps-4 pe-6"
                                            style="min-height: 300px;"></div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Statistics Cards-->

                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Bottom Row-->
            <div class="row g-5 g-xl-10">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Table widget-->
                    <div class="card card-flush h-md-100">
                        <!--begin::Header-->
                        <div class="card-header pt-7">
                            <div class="d-flex align-items-center justify-content-between mb-4 w-100">
                                <h3 class="card-title align-items-start flex-column mb-0">
                                    <span class="card-label fw-bold" style="color:#003153;">Recent Quotations</span>
                                </h3>
                                <a href="{{ route('insuarer.quotations') }}" class="btn" style="background-color: #9aa89b; color: white;">
                                    View All
                                </a>
                            </div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-6">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                    <!--begin::Table head-->
                                    <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                        <tr>
                                            <th class="min-w-100px text-center">ID</th>
                                            <th class="min-w-100px text-center">Client</th>
                                            <th class="min-w-100px text-center">Type</th>
                                            <th class="min-w-100px text-center">Amount</th>
                                            <th class="min-w-100px text-center">Created At</th>
                                            <th class="min-w-100px text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody>
                                        <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                            <td class="ps-9">QT-001</td>
                                            <td class="ps-0">John Doe</td>
                                            <td class="ps-0">Motor</td>
                                            <td class="ps-0">150,000.00</td>
                                            <td class="ps-0">2025-07-14</td>
                                            <td class="text-center">
                                                <span class="badge border border-success text-success d-inline-block text-center"
                                                    style="width: 80px; color: green !important;">
                                                    Approved
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                            <td class="ps-9">QT-002</td>
                                            <td class="ps-0">Jane Smith</td>
                                            <td class="ps-0">Fire</td>
                                            <td class="ps-0">200,000.00</td>
                                            <td class="ps-0">2025-07-10</td>
                                            <td class="text-center">
                                                <span class="badge border border-warning text-Warning d-inline-block text-center"
                                                    style="width: 80px; color: orange !important;">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                            <td class="ps-9">QT-003</td>
                                            <td class="ps-0">Ahmed Hassan</td>
                                            <td class="ps-0">Marine</td>
                                            <td class="ps-0">500,000.00</td>
                                            <td class="ps-0">2025-06-30</td>
                                            <td class="text-center">
                                                <span class="badge border border-info text-info d-inline-block text-center"
                                                    style="width: 80px;">
                                                    Processing
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                        </div>
                        <!--end: Card Body-->
                    </div>
                    <!--end::Table widget-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Bottom Row-->

        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

</div>
<!--end::Content wrapper-->

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    var myCarousel = document.querySelector('#kt_sliders_widget_campaigns');
    var carousel = new bootstrap.Carousel(myCarousel);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{
                name: 'Pending',
                data: [10, 8, 12, 6, 9, 11]
            }, {
                name: 'Approved',
                data: [5, 7, 4, 8, 6, 5]
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                },
                background: 'transparent',
                foreColor: '#333',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: false,
                    columnWidth: '40%',
                    endingShape: 'rounded',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: ['#9aa89b', '#003153'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.25,
                    gradientToColors: ['#003153'],
                    inverseColors: false,
                    stops: [0, 100]
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    colors: ['#333']
                }
            },
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 4,
                padding: {
                    top: 0,
                    right: 20,
                    bottom: 0,
                    left: 20
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                labels: {
                    style: {
                        colors: '#666',
                        fontSize: '12px',
                        fontWeight: 500,
                        fontFamily: 'Arial, sans-serif'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#666',
                        fontSize: '12px',
                        fontWeight: 500,
                        fontFamily: 'Arial, sans-serif'
                    }
                },
                tickAmount: 5,
                title: {
                    text: 'Quotations',
                    style: {
                        color: '#666',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                }
            },
            tooltip: {
                enabled: true,
                style: {
                    fontSize: '12px',
                    fontFamily: 'Arial, sans-serif'
                },
                theme: 'light',
                marker: {
                    show: true
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    },
                    dataLabels: {
                        enabled: false
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#kt_charts_insuarer_summary"), options);
        chart.render();

        // Make chart responsive
        window.addEventListener('resize', function() {
            chart.updateOptions({
                chart: {
                    width: '100%'
                }
            });
        });
    });
</script>

@endsection
