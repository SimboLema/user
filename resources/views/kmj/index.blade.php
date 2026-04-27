@extends('kmj.layouts.app')

@section('title', 'Dashboard')

@section('content')




    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">


        <!--begin::Toolbar-->

        <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-18 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center me-3 ">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-white fw-bold fs-3 flex-column justify-content-center my-0 "
                        style="color: #003153 !important; ">
                        Insurance Portal
                    </h1>
                    <!--end::Title-->


                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="https:/.SURETECH Systems.co.tz/dashboard" class="text-muted text-hover-primary">
                                Home | </a>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Dashboard </li>
                        <!--end::Item-->

                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center">
                    <!--begin::Floating label card (static)-->
                    <div class="toolbar-select form-floating w-175px w-lg-175px h-55px me-4 bg-white rounded">
                        <!-- Centered text container -->
                        <div class="h-55px d-flex align-items-center justify-content-center">
                            <span class="fs-5 fw-bold" style="color: #003153 !important;">Quotation</span>
                        </div>
                        <!-- Floating label -->
                    </div>
                    <!--end::Floating label card-->

                    <!--begin::New Campaign Button-->
                    {{-- <a href="#" class="btn btn-icon h-55px w-55px" data-bs-toggle="modal" style="background-color: #003153"
                        data-bs-target="#dashmodal" title="New product">
                        <i class="fas fa-plus fs-2 text-white"></i>
                    </a> --}}
                    <a href="#" class="btn btn-sm me-3"
                        style="text-decoration: none; color: inherit;background-color: #9aa89b;color:white;">
                        <span>Support</span></a>
                    <!--end::New Campaign Button-->
                </div>

                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">


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
                                            <a href="#"
                                                class="text-white fs-2x fw-bold d-block mb-1">Tsh.
                                                0.00 </a>

                                            <!--begin::Separator-->
                                            {{-- <span
                                                                class="position-absolute opacity-50 bottom-0 start-0 border-2 border-body border-bottom w-100"></span> --}}
                                            <!--end::Separator-->
                                        </span>


                                    </div>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar pt-5">
                                    <!--begin::Menu Trigger Button-->

                                    <!--end::Menu Trigger Button-->

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
                                            <a href="#" class="menu-link px-3">
                                                New Ticket
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                New Customer
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item with Submenu-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-start">
                                            <a href="#" class="menu-link px-3">
                                                <span class="menu-title">New Group</span>
                                                <span class="menu-arrow"></span>
                                            </a>

                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">
                                                        Admin Group
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">
                                                        Staff Group
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">
                                                        Member Group
                                                    </a>
                                                </div>
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item with Submenu-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                New Contact
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu separator-->
                                        <div class="separator mt-3 opacity-75"></div>
                                        <!--end::Menu separator-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3 py-3">
                                                <a class="btn btn-primary btn-sm px-4" href="#">
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
                                        <!-- Example of one card -->
                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="#" class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1 rounded-3">
                                                            <i class="bi bi-house fs-1 fw-bold text-primary "></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Dashboard</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center ">
                                            <a href="{{ route('kmj.getBranches') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-fullscreen-exit fs-1 fw-bold text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Branches</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('admin.insuarers.index') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-person-plus fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Insuarers</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.getAgents') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-person-plus fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Agents</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.getProducts') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-box fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Products</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.quotation') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-archive fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Quotation</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.risknote') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-journal-text fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Covernote</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.renewals') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-repeat fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Renawals</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.transaction') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-briefcase fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Tansaction</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.quotation.report') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-file-earmark-medical fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Reports</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.customers') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-person fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Customers</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.claims') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-arrow-clockwise fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Claims</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.reinsurance.index') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-file-earmark-break fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Reinsurance</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.downloads') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-download fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Downloads</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.messages') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-chat-left-text fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Messages</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.cancellation') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-x-square fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Cancellation</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('covernote.verification.index') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-award fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">verification</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('policy.submission.index') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                        <span class="symbol-label1   rounded-3">
                                                            <i class="bi bi-gear fs-1 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Policy
                                                            Sub</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('kmj.notifications') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100 position-relative">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center position-relative">
                                                        <span class="symbol-label1 position-relative   rounded-3">
                                                            <i class="bi bi-bell fs-1 text-primary"></i>
                                                            <!-- Notification badge -->
                                                            <span
                                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
                                                                3
                                                                <span class="visually-hidden">unread
                                                                    messages</span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Notifications</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="{{ route('user.index') }}"
                                                class="d-block w-100 text-decoration-none">
                                                <div
                                                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100 position-relative">
                                                    <div
                                                        class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center position-relative">
                                                        <span class="symbol-label1 position-relative   rounded-3">
                                                            <i class="bi bi-people fs-1 text-primary"></i>
                                                            <!-- Notification badge -->

                                                        </span>
                                                    </div>
                                                    <div class="text-center">
                                                        <span
                                                            class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Hr Mngt</span>
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
                            0% {
                                background-position: 0% 50%;
                            }

                            50% {
                                background-position: 100% 50%;
                            }

                            100% {
                                background-position: 0% 50%;
                            }
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
                                            <span class="card-label fw-bold" style="color: #003153">Latest
                                                Updates</span>
                                        </h4>
                                        <div class="card-toolbar">
                                            <ol
                                                class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-warning">
                                                <li data-bs-target="#kt_sliders_widget_campaigns" data-bs-slide-to="0"
                                                    class="active ms-1"></li>
                                                <li data-bs-target="#kt_sliders_widget_campaigns" data-bs-slide-to="1"
                                                    class="ms-1"></li>
                                                <li data-bs-target="#kt_sliders_widget_campaigns" data-bs-slide-to="2"
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
                                                            <i class="bi bi-fullscreen-exit fs-3x">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!-- Glow effect -->
                                                            <span
                                                                class="position-absolute top-0 start-0 w-100 h-100 rounded-circle  opacity-10 animate-pulse"></span>
                                                        </span>
                                                    </div>

                                                    <div class="m-0">
                                                        <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                            style="color:#003153;">
                                                            Branches
                                                        </h4>

                                                        <div class="d-flex d-grid gap-5">
                                                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                                                <div class="d-flex align-items-center fs-10 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                    style="animation-delay: 0.1s">
                                                                    <i class=" fs-6 text-gray-600 me-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                        <span class="path3"></span>
                                                                    </i>
                                                                    <span class="fs-1 ms-1"
                                                                        style="color:#003153;">5</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="m-0 animate__animated animate__fadeInUp"
                                                    style="animation-delay: 0.4s;">
                                                    <a href="Branches.html" class="btn btn-sm  mb-2 btn-hover-rise"
                                                        style="background-color: #9aa89b; color:white">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="carousel-item ">
                                                <div class="d-flex align-items-center mb-9">
                                                    <div class="symbol me-5 position-relative">
                                                        <span class="symbol-label1">
                                                            <i class="bi bi-person-plus fs-3x">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!-- Glow effect -->
                                                            <span
                                                                class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                        </span>
                                                    </div>

                                                    <div class="m-0">
                                                        <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                            style="color:#003153;">
                                                            Total Agents
                                                        </h4>

                                                        <div class="d-flex d-grid gap-5">
                                                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                                                <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                    style="animation-delay: 0.1s">

                                                                    <span class="fs-2 ms-1"
                                                                        style="color:#003153;">101</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="m-0 animate__animated animate__fadeInUp"
                                                    style="animation-delay: 0.4s">
                                                    <a href="Agents.html" class="btn btn-sm  mb-2 btn-hover-rise"
                                                        style="background-color: #9aa89b; color:white">

                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="carousel-item ">
                                                <div class="d-flex align-items-center mb-9">
                                                    <div class="symbol me-5 position-relative">
                                                        <span class="symbol-label1">
                                                            <i class="bi bi-person fs-3x">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!-- Glow effect -->
                                                            <span
                                                                class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                        </span>
                                                    </div>

                                                    <div class="m-0">
                                                        <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                            style="color:#003153;">
                                                            Total Customers
                                                        </h4>

                                                        <div class="d-flex d-grid gap-5">
                                                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                                                <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                    style="animation-delay: 0.1s">

                                                                    <span class="fs-2  ms-1"
                                                                        style="color:#003153;">{{ $totalCustomers }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="m-0 animate__animated animate__fadeInUp"
                                                    style="animation-delay: 0.4s">
                                                    <a href="Customer.html" class="btn btn-sm  mb-2 btn-hover-rise"
                                                        style="background-color: #9aa89b;color:white;">
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
                                        <!--begin::Title-->
                                        <h4 class="card-title d-flex align-items-start flex-column">
                                            <span class="card-label fw-bold" style="color: #003153">Latest
                                                Updates</span>

                                        </h4>
                                        <!--end::Title-->

                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Carousel Indicators-->
                                            <ol
                                                class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-warning">
                                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="0"
                                                    class="active ms-1"></li>
                                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="1"
                                                    class="ms-1"></li>
                                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="2"
                                                    class="ms-1"></li>
                                            </ol>
                                            <!--end::Carousel Indicators-->
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div class="card-body py-6">
                                        <!--begin::Carousel-->
                                        <div class="carousel-inner">
                                            <!--begin::Item-->
                                            <div class="carousel-item active show">
                                                <!--begin::Wrapper-->
                                                <div class="d-flex align-items-center mb-9">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol me-5 position-relative">
                                                        <span class="symbol-label1">
                                                            <i class="bi bi-briefcase fs-3x">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!-- Glow effect -->
                                                            <span
                                                                class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                        </span>
                                                    </div>
                                                    <div class="m-0">
                                                        <!--begin::Subtitle-->
                                                        <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                            style="color:#003153;">
                                                            Total Transaction</h4> <!--end::Subtitle-->
                                                        <!--begin::Items-->
                                                        <div class="d-flex d-grid gap-5">
                                                            <!--begin::Item-->
                                                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                                                <!-- Status with animated checkmark -->
                                                                <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                    style="animation-delay: 0.1s">
                                                                    <div class="status-indicator me-2">

                                                                    </div>
                                                                    <span class="fs-2 ms-1"
                                                                        style="color:#003153;">10,000.00</span>
                                                                </div>

                                                                <!-- Request date with calendar icon -->

                                                            </div>

                                                        </div>
                                                        <!--end::Items-->
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                                <!--end::Wrapper-->

                                                <!--begin::Action-->
                                                <div class="m-0 animate__animated animate__fadeInUp"
                                                    style="animation-delay: 0.4s">

                                                    <a href="Transaction.html" class="btn btn-sm  mb-2 btn-hover-rise"
                                                        style="background-color: #9aa89b; color:white;">

                                                        view Details
                                                    </a>
                                                </div>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Item-->
                                            <!--begin::Item-->
                                            <div class="carousel-item ">
                                                <!--begin::Wrapper-->
                                                <div class="d-flex align-items-center mb-9">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol me-5 position-relative">
                                                        <span class="symbol-label1">
                                                            <i class="bi bi-check-all fs-3x">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!-- Glow effect -->
                                                            <span
                                                                class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Info-->
                                                    <div class="m-0">
                                                        <h4 class="fw-bold mb-3 animate__animated animate__fadeInRight"
                                                            style="color:#003153;">
                                                            Successfully Transaction</h4>
                                                        <div class="d-flex d-grid gap-5">
                                                            <!--begin::Item-->
                                                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                                                <!-- Status with animated checkmark -->
                                                                <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                    style="animation-delay: 0.1s">
                                                                    <div class="status-indicator me-2">

                                                                    </div>
                                                                    <span class="fs-2 ms-1"
                                                                        style="color:#003153;">10,000.00</span>
                                                                </div>

                                                                <!-- Request date with calendar icon -->

                                                            </div>

                                                        </div>
                                                        <!--end::Items-->
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                                <!--end::Wrapper-->

                                                <!--begin::Action-->
                                                <div class="m-0 animate__animated animate__fadeInUp"
                                                    style="animation-delay: 0.4s">

                                                    <a href="Transaction.html" class="btn btn-sm mb-2 btn-hover-rise"
                                                        style="background-color: #9aa89b; color:white;">

                                                        view Details
                                                    </a>
                                                </div>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Item-->
                                            <!--begin::Item-->
                                            <div class="carousel-item ">
                                                <!--begin::Wrapper-->
                                                <div class="d-flex align-items-center mb-9">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol me-5 position-relative">
                                                        <span class="symbol-label1">
                                                            <i class="bi bi-award fs-3x">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!-- Glow effect -->
                                                            <span
                                                                class="position-absolute top-0 start-0 w-100 h-100 rounded-circle opacity-10 animate-pulse"></span>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Info-->
                                                    <div class="m-0">
                                                        <!--begin::Subtitle-->
                                                        <h4 class="fw-bold  mb-3 animate__animated animate__fadeInRight"
                                                            style="color:#003153;">
                                                            Commission</h4> <!--end::Subtitle-->

                                                        <!--begin::Items-->
                                                        <div class="d-flex d-grid gap-5">
                                                            <!--begin::Item-->
                                                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                                                <!-- Status with animated checkmark -->
                                                                <div class="d-flex align-items-center fs-7 fw-bold text-gray-500 mb-2 animate__animated animate__fadeInUp"
                                                                    style="animation-delay: 0.1s">
                                                                    <div class="status-indicator me-2">

                                                                    </div>
                                                                    <span class="fs-2 ms-1"
                                                                        style="color:#003153;">100,000.00</span>
                                                                </div>

                                                                <!-- Request date with calendar icon -->

                                                            </div>

                                                        </div>
                                                        <!--end::Items-->
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                                <!--end::Wrapper-->

                                                <!--begin::Action-->
                                                <div class="m-0 animate__animated animate__fadeInUp"
                                                    style="animation-delay: 0.4s">

                                                    <a href="Commission.html" class="btn btn-sm mb-2 btn-hover-rise"
                                                        style="background-color: #9aa89b; color:white;">

                                                        view Details
                                                    </a>
                                                </div>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Item-->


                                            <!--end::Item-->
                                            <!--begin::Item-->

                                            <!--end::Item-->
                                        </div>
                                        <!--end::Carousel-->
                                    </div>
                                    <!--end::Body-->
                                </div>

                                <!-- Add animate.css for additional animations -->
                                <link rel="stylesheet"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
                                <!--end::Slider Widget 2-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Engage widget 4-->
                        <div class="card border-transparent " data-bs-theme="light" style="background-color: #ffffff;">
                            <!--begin::Body-->
                            <div class="card card-flush h-xl-100">
                                <!--begin::Header-->
                                <div class="card-header pt-7">
                                    <!--begin::Title-->
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-800 d-flex align-items-center">
                                            Transactions Trend
                                            <span class="dropdown ms-2">
                                                <a href="#" class="btn btn-sm btn-icon btn-light"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-chevron-down fs-8"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">Todays
                                                            Transactions</a></li>
                                                    <li><a class="dropdown-item" href="#">Last 7
                                                            Days</a></li>
                                                    <li><a class="dropdown-item" href="#">This
                                                            Month</a>
                                                    </li>

                                                </ul>
                                            </span>
                                        </span>
                                        {{-- <span class="text-gray-500 mt-1 fw-semibold fs-6">Real-time
                                                            Transaction analytics</span> --}}
                                    </h3>
                                    <!--end::Title-->

                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                        <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left"
                                            class="btn btn-sm btn-light d-flex align-items-center px-4">
                                            <!--begin::Display range-->
                                            <div class="text-gray-600 fw-bold">
                                                Loading date range...
                                            </div>
                                            <!--end::Display range-->

                                            <i class="bi bi-calendar text-gray-500 lh-0 fs-2 ms-2 me-0"><span
                                                    class="path1"></span><span class="path2"></span><span
                                                    class="path3"></span><span class="path4"></span><span
                                                    class="path5"></span><span class="path6"></span></i>
                                        </div>
                                        <!--end::Daterangepicker-->
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div class="card">
                                    <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                        <!-- Chart Container -->
                                        <div id="kt_charts_widget_18_chart" class="w-100 min-h-auto ps-4 pe-6"
                                            style="min-height: 1000px;"></div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <div class="card mt-5">
                            <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                                <h3 class="card-title fw-bold text-dark mb-0">Insurance Type
                                    Distribution</h3>
                                <span class="badge bg-light text-dark fs-7">Category Comparison</span>
                            </div>
                            <div class="card-body">
                                <div id="insuranceTypeChart" style="height: 380px;"></div>
                            </div>
                        </div>

                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->


                <!--begin::Row-->
                <div class="row g-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-4">

                        <!--begin::List widget 21-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Top Customers</span>

                                    <span class="text-muted mt-1 fw-semibold fs-7">With Achieved high
                                        Amount of Transactions</span>
                                </h3>

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    {{-- <a href="#" class="btn btn-sm btn-light">All Customers</a> --}}
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-5">
                                @foreach($listCustomers as $customer)
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-3">
                                            <div class="flex-grow-1">
                                                <a href="{{ route('kmj.index', $customer->id) }}"
                                                   class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">
                                                   {{ $customer->name }}
                                                </a>
                                                <span class="text-gray-500 fw-semibold d-block fs-6">
                                                    {{ number_format($customer->amount) }}
                                                </span>
                                                </div>
                                            </div>
                                        <div class="d-flex align-items-center w-100 mw-125px">
                                            <div class="progress h-6px w-100 me-2 bg-light-success">
                                                <div class="progress-bar bg-warning"
                                                     role="progressbar"
                                                     style="width: {{ $customer->percentage }}%"
                                                     aria-valuenow="{{ $customer->percentage }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="text-gray-500 fw-semibold">
                                                {{ $customer->percentage }}%
                                            </span>
                                            </div>
                                        </div>
                                    {{-- Only show separator if it's not the last item in the list --}}
                                    @if(!$loop->last)
                                        <div class="separator separator-dashed my-3"></div>
                                    @endif
                                    @endforeach
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List widget 21-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-xl-8">
                        <!--begin::Chart widget 18-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->

                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title justify-content-center">Agents Location</h5>
                                    <div class="ratio ratio-16x9">
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12786523.282728717!2d28.0322658!3d-6.369028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x184b5109bcb3b3d5%3A0x2b1866d03d4a6474!2sTanzania!5e0!3m2!1sen!2stz!4v1621780079143!5m2!1sen!2stz"
                                            width="100%" height="100%" style="border:0;" allowfullscreen=""
                                            loading="lazy">
                                        </iframe>
                                    </div>
                                </div>
                            </div>


                            <!--end: Card Body-->
                        </div>
                        <!--end::Chart widget 18-->

                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <br><br>


                <div class="col-xl-12">

                    <!--begin::Table widget 14-->
                    <div class="card card-flush h-md-100">
                        <!--begin::Header-->
                        <div class="card-header pt-7">

                            <div class="d-flex align-items-center justify-content-between mb-4 w-100">
                                <h3 class="card-title align-items-start flex-column mb-0">
                                    <span class="card-label fw-bold"style="color:#003153;">Latest
                                        Transactions</span>
                                </h3>

                                <button type="button" class="btn" style="background-color: #9aa89b; color: white;">
                                    View Transactions
                                </button>
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
                                            <th class="min-w-100px text-center">Client</th>
                                            <th class="min-w-100px text-center">Type</th>
                                            <th class="min-w-100px text-center">Company</th>
                                            <th class="min-w-100px text-center">Payment</th>
                                            <th class="min-w-100px text-center">Premium</th>
                                            <th class="min-w-100px text-center">Created At</th>
                                            <th class="min-w-100px text-center">Status</th>
                                        </tr>
                                    </thead>

                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody>
                                        <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                            <td class="ps-9">Ahmed Siasa</td>
                                            <td class="ps-0">Motor</td>
                                            <td class="ps-0">Heritage Insurance</td>
                                            <td class="ps-9">Mobile</td>
                                            <td class="ps-0">100,000.00</td>
                                            <td class="ps-0">2025-07-14 10:30 AM</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge border border-success text-success d-inline-block text-center"
                                                    style="width: 80px;color: green !important;">
                                                    Success
                                                </span>

                                            </td>

                                        </tr>

                                        <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                            <td class="ps-9">Aisha Bello</td>
                                            <td class="ps-0">Non Motor</td>
                                            <td class="ps-0">Jubilee Insurance</td>
                                            <td class="ps-9">Bank</td>
                                            <td class="ps-0">85,500.00</td>
                                            <td class="ps-0">2025-07-10 2:45 PM</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge border border-warning text-Warning d-inline-block text-center"
                                                    style="width: 80px; color: orange !important;">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                            <td class="ps-9">John Peter</td>
                                            <td class="ps-0">Fire</td>
                                            <td class="ps-0">Resolution Insurance</td>
                                            <td class="ps-9">Cash</td>
                                            <td class="ps-0">230,000.00</td>
                                            <td class="ps-0">2025-06-30 09:15 AM</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge border border-danger text-danger d-inline-block text-center"
                                                    style="width: 80px; color: red !important;">
                                                    Failed
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
                    <!--end::Table widget 14-->
                </div>



            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

        <script>
            var myCarousel = document.querySelector('#kt_sliders_widget_campaigns');
            var carousel = new bootstrap.Carousel(myCarousel);
        </script>


    </div>
    <!--end::Content wrapper-->






    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'Amount Spent (TZS)',
                    data: [54, 42, 75, 110, 23, 87, 50, 90, 40, 100, 110, 50]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
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
                        columnWidth: '30%',
                        endingShape: 'rounded',
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                colors: ['#003153'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.25,
                        gradientToColors: ['#003153'],
                        inverseColors: false,
                        // opacityFrom: 0.9,
                        // opacityTo: 0.8,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        // return 'AA';
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
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
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
                        },
                        formatter: function(val) {
                            return val;
                        }
                    },
                    tickAmount: 6,
                    title: {
                        text: 'Amount (TZS)',
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
                    // y: {
                    //     formatter: function(val) {
                    //         return val;
                    //     }
                    // },
                    marker: {
                        show: true
                    }
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

            var chart = new ApexCharts(document.querySelector("#kt_charts_widget_18_chart"), options);
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sample data — unaweza kubadilisha kwa real values kutoka DB
            var insuranceNames = [
                'Goods In Transit',
                'Motor',
                'Miscellaneous and Accidents',
                'Engineering',
                'Fire',
                'Marine'
            ];

            var insuranceValues = [12, 40, 10, 15, 13, 10]; // mfano wa distribution %

            var options = {
                series: insuranceValues,
                chart: {
                    type: 'pie',
                    height: 380,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 1000
                    }
                },
                labels: insuranceNames,
                colors: ['#a45a52', '#5f9ea0', '#ffa07a', '#454d32', '#e6be8a', '#003153'],
                legend: {
                    position: 'bottom',
                    fontSize: '13px',
                    fontWeight: 600,
                    labels: {
                        colors: '#333'
                    },
                    markers: {
                        width: 14,
                        height: 14,
                        radius: 4
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + "%";
                    },
                    style: {
                        fontSize: '13px',
                        fontWeight: 'bold',
                        colors: ['#fff']
                    },
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 2,
                        blur: 3,
                        color: '#000',
                        opacity: 0.3
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['#fff']
                },
                tooltip: {
                    y: {
                        formatter: function(val, opts) {
                            return insuranceNames[opts.seriesIndex] + ': ' + val + "%";
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#insuranceTypeChart"), options);
            chart.render();
        });
    </script>

@endsection
