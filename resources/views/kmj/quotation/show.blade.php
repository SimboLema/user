@extends('kmj.layouts.app')

@section('title', ' Quotation Details')

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




                <!--begin::Navbar-->
                <div class="card card-flush mb-9" id="kt_user_profile_panel">
                    <!--begin::Hero nav-->
                    <div class="card-header rounded-top bgi-size-cover h-200px"
                        style="background-position: 100% 50%; background-image:url('../../assets/media/misc/profile-head-bg.jpg')">
                    </div>
                    <!--end::Hero nav-->

                    <!--begin::Body-->
                    <div class="card-body mt-n19">
                        <!--begin::Details-->
                        <div class="m-0">
                            <!--begin: Pic-->
                            <div class="d-flex flex-stack align-items-end pb-4 mt-n19">
                                <div class="symbol symbol-125px symbol-lg-150px symbol-fixed position-relative mt-n3">
                                    <img src="../../assets/media/avatars/300-3.jpg" alt="image"
                                        class="border border-white border-4" style="border-radius: 20px" />
                                    <div
                                        class="position-absolute translate-middle bottom-0 start-100 ms-n1 mb-9 bg-success rounded-circle h-15px w-15px">
                                    </div>
                                </div>

                                <!--begin::Toolbar-->
                                <div class="me-0">
                                    <button class="btn btn-icon btn-sm btn-active-color-primary  justify-content-end pt-3"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="fonticon-settings fs-2"></i>
                                    </button>

                                    <!--begin::Menu 3-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                        data-kt-menu="true">
                                        <!--begin::Heading-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                Payments
                                            </div>
                                        </div>
                                        <!--end::Heading-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                Create Invoice
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link flex-stack px-3">
                                                Create Payment

                                                <span class="ms-2" data-bs-toggle="tooltip"
                                                    title="Specify a target name for future usage and reference">
                                                    <i class="ki-duotone ki-information fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i> </span>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                Generate Bill
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-end">
                                            <a href="overview.html#" class="menu-link px-3">
                                                <span class="menu-title">Subscription</span>
                                                <span class="menu-arrow"></span>
                                            </a>

                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Plans
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Billing
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Statements
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu separator-->
                                                <div class="separator my-2"></div>
                                                <!--end::Menu separator-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <div class="menu-content px-3">
                                                        <!--begin::Switch-->
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <!--begin::Input-->
                                                            <input class="form-check-input w-30px h-20px" type="checkbox"
                                                                value="1" checked="checked" name="notifications" />
                                                            <!--end::Input-->

                                                            <!--end::Label-->
                                                            <span class="form-check-label text-muted fs-6">
                                                                Recuring
                                                            </span>
                                                            <!--end::Label-->
                                                        </label>
                                                        <!--end::Switch-->
                                                    </div>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3 my-1">
                                            <a href="overview.html#" class="menu-link px-3">
                                                Settings
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu 3-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Pic-->

                            <!--begin::Info-->
                            <div class="d-flex flex-stack flex-wrap align-items-end">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="overview.html#"
                                            class="text-gray-800 text-hover-primary fs-2 fw-bolder me-1">Bessie Cooper</a>
                                        <a href="overview.html#" class="" data-bs-toggle="tooltip"
                                            data-bs-placement="right" title="Account is verified">
                                            <i class="ki-duotone ki-verify fs-1 text-primary"><span
                                                    class="path1"></span><span class="path2"></span></i> </a>
                                    </div>
                                    <!--end::Name-->

                                    <!--begin::Text-->
                                    <span class="fw-bold text-gray-600 fs-6 mb-2 d-block">
                                        Design is like a fart. If you have to force it, it’s probably shit.
                                    </span>
                                    <!--end::Text-->

                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center flex-wrap fw-semibold fs-7 pe-2">
                                        <a href="overview.html#"
                                            class="d-flex align-items-center text-gray-500 text-hover-primary">
                                            UI/UX Design
                                        </a>
                                        <span class="bullet bullet-dot h-5px w-5px bg-gray-500 mx-3"></span>
                                        <a href="overview.html#"
                                            class="d-flex align-items-center text-gray-500 text-hover-primary">
                                            Austin, TX
                                        </a>
                                        <span class="bullet bullet-dot h-5px w-5px bg-gray-500 mx-3"></span>
                                        <a href="overview.html#" class="text-gray-500 text-hover-primary">
                                            3,450 Followers
                                        </a>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex">
                                    <a href="overview.html#" class="btn btn-sm btn-light me-3"
                                        id="kt_drawer_chat_toggle">Send Message</a>

                                    <button class="btn btn-sm btn-primary" id="kt_user_follow_button">
                                        <i class="ki-duotone ki-check fs-2 d-none"></i>
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">
                                            Follow</span>
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



                <!--begin::Nav items-->
                @include('kmj.quotation.components.tabs-nav')

                <!--end::Nav items-->
                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-6">

                        <!--begin::List widget 1-->
                        <div class="card card-flush h-md-100 mb-5 mb-lg-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Highlights</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Latest social statistics</span>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <button
                                        class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                        data-kt-menu-overflow="true">

                                        <i class="ki-duotone ki-dots-square fs-1"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span></i>
                                    </button>

                                    <!--begin::Menu 2-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions
                                            </div>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu separator-->
                                        <div class="separator mb-3 opacity-75"></div>
                                        <!--end::Menu separator-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                New Ticket
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                New Customer
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-start">
                                            <!--begin::Menu item-->
                                            <a href="overview.html#" class="menu-link px-3">
                                                <span class="menu-title">New Group</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <!--end::Menu item-->

                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Admin Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Staff Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Member Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
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
                                                <a class="btn btn-primary  btn-sm px-4" href="overview.html#">
                                                    Generate Reports
                                                </a>
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu 2-->

                                    <!--end::Menu-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-5">
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Section-->
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Avg. Client Rating</div>
                                    <!--end::Section-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-senter">
                                        <i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span
                                                class="path1"></span><span class="path2"></span></i>

                                        <!--begin::Number-->
                                        <span class="text-gray-900 fw-bolder fs-6">7.8</span>
                                        <!--end::Number-->

                                        <span class="text-gray-500 fw-bold fs-6">/10</span>
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->

                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Section-->
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Instagram Followers</div>
                                    <!--end::Section-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-senter">
                                        <i class="ki-duotone ki-arrow-down-right fs-2 text-danger me-2"><span
                                                class="path1"></span><span class="path2"></span></i>

                                        <!--begin::Number-->
                                        <span class="text-gray-900 fw-bolder fs-6">730k</span>
                                        <!--end::Number-->


                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->

                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Section-->
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Google Ads CPC</div>
                                    <!--end::Section-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-senter">
                                        <i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span
                                                class="path1"></span><span class="path2"></span></i>

                                        <!--begin::Number-->
                                        <span class="text-gray-900 fw-bolder fs-6">$2.09</span>
                                        <!--end::Number-->


                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->



                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::LIst widget 1-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-xl-6">

                        <!--begin::List widget 2-->
                        <div class="card card-flush h-md-100 mb-5 mb-lg-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">External Links</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Most used resources</span>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <button
                                        class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                        data-kt-menu-overflow="true">

                                        <i class="ki-duotone ki-dots-square fs-1"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span></i>
                                    </button>

                                    <!--begin::Menu 3-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                        data-kt-menu="true">
                                        <!--begin::Heading-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                Payments
                                            </div>
                                        </div>
                                        <!--end::Heading-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                Create Invoice
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link flex-stack px-3">
                                                Create Payment

                                                <span class="ms-2" data-bs-toggle="tooltip"
                                                    title="Specify a target name for future usage and reference">
                                                    <i class="ki-duotone ki-information fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i> </span>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                Generate Bill
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-end">
                                            <a href="overview.html#" class="menu-link px-3">
                                                <span class="menu-title">Subscription</span>
                                                <span class="menu-arrow"></span>
                                            </a>

                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Plans
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Billing
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Statements
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu separator-->
                                                <div class="separator my-2"></div>
                                                <!--end::Menu separator-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <div class="menu-content px-3">
                                                        <!--begin::Switch-->
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <!--begin::Input-->
                                                            <input class="form-check-input w-30px h-20px" type="checkbox"
                                                                value="1" checked="checked" name="notifications" />
                                                            <!--end::Input-->

                                                            <!--end::Label-->
                                                            <span class="form-check-label text-muted fs-6">
                                                                Recuring
                                                            </span>
                                                            <!--end::Label-->
                                                        </label>
                                                        <!--end::Switch-->
                                                    </div>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3 my-1">
                                            <a href="overview.html#" class="menu-link px-3">
                                                Settings
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu 3-->

                                    <!--end::Menu-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-5">
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Title-->
                                    <a href="overview.html#" class="text-primary opacity-75-hover fs-6 fw-semibold">Google
                                        Analytics</a>
                                    <!--end::Title-->

                                    <!--begin::Action-->
                                    <button type="button"
                                        class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                        <i class="ki-duotone ki-exit-right-corner fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> </button>
                                    <!--end::Action-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Title-->
                                    <a href="overview.html#"
                                        class="text-primary opacity-75-hover fs-6 fw-semibold">Facebook Ads</a>
                                    <!--end::Title-->

                                    <!--begin::Action-->
                                    <button type="button"
                                        class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                        <i class="ki-duotone ki-exit-right-corner fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> </button>
                                    <!--end::Action-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Title-->
                                    <a href="overview.html#"
                                        class="text-primary opacity-75-hover fs-6 fw-semibold">Seranking</a>
                                    <!--end::Title-->

                                    <!--begin::Action-->
                                    <button type="button"
                                        class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                        <i class="ki-duotone ki-exit-right-corner fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> </button>
                                    <!--end::Action-->
                                </div>
                                <!--end::Item-->


                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List widget 2-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-6">

                        <!--begin::Engage widget 1-->
                        <div class="card h-xl-100" dir="ltr">
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column flex-center">
                                <!--begin::Heading-->
                                <div class="mb-2">
                                    <!--begin::Title-->
                                    <h1 class="fw-semibold text-gray-800 text-center lh-lg">
                                        Have you tried <br /> new
                                        <span class="fw-bolder"> Invoice Manager?</span>
                                    </h1>
                                    <!--end::Title-->

                                    <!--begin::Illustration-->
                                    <div class="py-10 text-center">
                                        <img src="../../assets/media/svg/illustrations/easy/2.svg"
                                            class="theme-light-show w-200px" alt="" />
                                        <img src="../../assets/media/svg/illustrations/easy/2-dark.svg"
                                            class="theme-dark-show w-200px" alt="" />
                                    </div>
                                    <!--end::Illustration-->
                                </div>
                                <!--end::Heading-->

                                <!--begin::Links-->
                                <div class="text-center mb-1">
                                    <!--begin::Link-->
                                    <a class="btn btn-sm btn-primary me-2" data-bs-target="#kt_modal_new_card"
                                        data-bs-toggle="modal">
                                        Try now </a>
                                    <!--end::Link-->

                                    <!--begin::Link-->
                                    <a class="btn btn-sm btn-light" href="followers.html">
                                        Learn more </a>
                                    <!--end::Link-->
                                </div>
                                <!--end::Links-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Engage widget 1-->

                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-xl-6">

                        <!--begin::Lists Widget 19-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Heading-->
                            <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"
                                style="background-image:url('/keen/demo3/assets/media/svg/shapes/top-green.png"
                                data-bs-theme="light">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column text-white pt-15">
                                    <span class="fw-bold fs-2x mb-3">My Tasks</span>

                                    <div class="fs-4 text-white">
                                        <span class="opacity-75">You have</span>

                                        <span class="position-relative d-inline-block">
                                            <a href="projects.html"
                                                class="link-white opacity-75-hover fw-bold d-block mb-1">4 tasks</a>

                                            <!--begin::Separator-->
                                            <span
                                                class="position-absolute opacity-50 bottom-0 start-0 border-2 border-body border-bottom w-100"></span>
                                            <!--end::Separator-->
                                        </span>

                                        <span class="opacity-75">to comlete</span>
                                    </div>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar pt-5">
                                    <!--begin::Menu-->
                                    <button
                                        class="btn btn-sm btn-icon btn-active-color-primary btn-color-white bg-white bg-opacity-25 bg-hover-opacity-100 bg-hover-white bg-active-opacity-25 w-20px h-20px"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                        data-kt-menu-overflow="true">

                                        <i class="ki-duotone ki-dots-square fs-4"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span></i>
                                    </button>


                                    <!--begin::Menu 2-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions
                                            </div>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu separator-->
                                        <div class="separator mb-3 opacity-75"></div>
                                        <!--end::Menu separator-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                New Ticket
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
                                                New Customer
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-start">
                                            <!--begin::Menu item-->
                                            <a href="overview.html#" class="menu-link px-3">
                                                <span class="menu-title">New Group</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <!--end::Menu item-->

                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Admin Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Staff Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="overview.html#" class="menu-link px-3">
                                                        Member Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="overview.html#" class="menu-link px-3">
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
                                                <a class="btn btn-primary  btn-sm px-4" href="overview.html#">
                                                    Generate Reports
                                                </a>
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu 2-->

                                    <!--end::Menu-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Heading-->

                            <!--begin::Body-->
                            <div class="card-body mt-n20">
                                <!--begin::Stats-->
                                <div class="mt-n20 position-relative">
                                    <!--begin::Row-->
                                    <div class="row g-3 g-lg-6">
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-flask fs-1 text-primary"><span
                                                                class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->

                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">37</span>
                                                    <!--end::Number-->

                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">Courses</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-bank fs-1 text-primary"><span
                                                                class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->

                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">6</span>
                                                    <!--end::Number-->

                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">Certificates</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-award fs-1 text-primary"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->

                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">4,7</span>
                                                    <!--end::Number-->

                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">Avg. Score</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-timer fs-1 text-primary"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->

                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">822</span>
                                                    <!--end::Number-->

                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">Hours Learned</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->

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
                </div>
                <!--end::Row-->

                <!--begin::Row-->
                <div class="row g-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xxl-6">

                        <!--begin::List widget 20-->
                        <div class="card h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Recommended for you</span>

                                    <span class="text-muted mt-1 fw-semibold fs-7">8k social visitors</span>
                                </h3>

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <a href="overview.html#" class="btn btn-sm btn-light">All Courses</a>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-6">

                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40px me-4">
                                        <div class="symbol-label fs-2 fw-semibold bg-danger text-inverse-danger">M</div>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <!--begin:Author-->
                                        <div class="flex-grow-1 me-2">
                                            <a href="overview.html"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">UI/UX Design</a>

                                            <span class="text-muted fw-semibold d-block fs-7">40+ Courses</span>
                                        </div>
                                        <!--end:Author-->

                                        <!--begin::Actions-->
                                        <a href="overview.html#"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span
                                                    class="path2"></span></i> </a>
                                        <!--begin::Actions-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-4"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40px me-4">
                                        <div class="symbol-label fs-2 fw-semibold bg-success text-inverse-success">Q</div>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <!--begin:Author-->
                                        <div class="flex-grow-1 me-2">
                                            <a href="overview.html"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">QA Analysis</a>

                                            <span class="text-muted fw-semibold d-block fs-7">18 Courses</span>
                                        </div>
                                        <!--end:Author-->

                                        <!--begin::Actions-->
                                        <a href="overview.html#"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span
                                                    class="path2"></span></i> </a>
                                        <!--begin::Actions-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-4"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40px me-4">
                                        <div class="symbol-label fs-2 fw-semibold bg-info text-inverse-info">W</div>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <!--begin:Author-->
                                        <div class="flex-grow-1 me-2">
                                            <a href="overview.html"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">Web Development</a>

                                            <span class="text-muted fw-semibold d-block fs-7">120+ Courses</span>
                                        </div>
                                        <!--end:Author-->

                                        <!--begin::Actions-->
                                        <a href="overview.html#"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span
                                                    class="path2"></span></i> </a>
                                        <!--begin::Actions-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-4"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40px me-4">
                                        <div class="symbol-label fs-2 fw-semibold bg-primary text-inverse-primary">M</div>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <!--begin:Author-->
                                        <div class="flex-grow-1 me-2">
                                            <a href="overview.html"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">Marketing</a>

                                            <span class="text-muted fw-semibold d-block fs-7">50+ Courses.</span>
                                        </div>
                                        <!--end:Author-->

                                        <!--begin::Actions-->
                                        <a href="overview.html#"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span
                                                    class="path2"></span></i> </a>
                                        <!--begin::Actions-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-4"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40px me-4">
                                        <div class="symbol-label fs-2 fw-semibold bg-warning text-inverse-warning">P</div>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <!--begin:Author-->
                                        <div class="flex-grow-1 me-2">
                                            <a href="overview.html"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">Philosophy</a>

                                            <span class="text-muted fw-semibold d-block fs-7">24+ Courses</span>
                                        </div>
                                        <!--end:Author-->

                                        <!--begin::Actions-->
                                        <a href="overview.html#"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span
                                                    class="path2"></span></i> </a>
                                        <!--begin::Actions-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-4"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40px me-4">
                                        <div class="symbol-label fs-2 fw-semibold bg-dark text-inverse-dark">M</div>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <!--begin:Author-->
                                        <div class="flex-grow-1 me-2">
                                            <a href="overview.html"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">Mathematics</a>

                                            <span class="text-muted fw-semibold d-block fs-7">24+ Courses</span>
                                        </div>
                                        <!--end:Author-->

                                        <!--begin::Actions-->
                                        <a href="overview.html#"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span
                                                    class="path2"></span></i> </a>
                                        <!--begin::Actions-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->



                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List widget 20-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-xl-6">

                        <!--begin::List widget 21-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Active Lessons</span>

                                    <span class="text-muted mt-1 fw-semibold fs-7">Avg. 72% completed lessons</span>
                                </h3>

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <a href="overview.html#" class="btn btn-sm btn-light">All Lessons</a>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-5">

                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex align-items-center me-3">
                                        <!--begin::Logo-->
                                        <img src="../../assets/media/svg/brand-logos/laravel-2.svg" class="me-4 w-30px"
                                            alt="" />
                                        <!--end::Logo-->

                                        <!--begin::Section-->
                                        <div class="flex-grow-1">
                                            <!--begin::Text-->
                                            <a href="overview.html#"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Laravel</a>
                                            <!--end::Text-->

                                            <!--begin::Description-->
                                            <span class="text-gray-500 fw-semibold d-block fs-6">PHP Framework</span>
                                            <!--end::Description--->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-center w-100 mw-125px">
                                        <!--begin::Progress-->
                                        <div class="progress h-6px w-100 me-2 bg-light-success">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%"
                                                aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <!--end::Progress-->

                                        <!--begin::Value-->
                                        <span class="text-gray-500 fw-semibold">
                                            65%
                                        </span>
                                        <!--end::Value-->
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex align-items-center me-3">
                                        <!--begin::Logo-->
                                        <img src="../../assets/media/svg/brand-logos/vue-9.svg" class="me-4 w-30px"
                                            alt="" />
                                        <!--end::Logo-->

                                        <!--begin::Section-->
                                        <div class="flex-grow-1">
                                            <!--begin::Text-->
                                            <a href="overview.html#"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Vue 3</a>
                                            <!--end::Text-->

                                            <!--begin::Description-->
                                            <span class="text-gray-500 fw-semibold d-block fs-6">JS Framework</span>
                                            <!--end::Description--->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-center w-100 mw-125px">
                                        <!--begin::Progress-->
                                        <div class="progress h-6px w-100 me-2 bg-light-warning">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 87%"
                                                aria-valuenow="87" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <!--end::Progress-->

                                        <!--begin::Value-->
                                        <span class="text-gray-500 fw-semibold">
                                            87%
                                        </span>
                                        <!--end::Value-->
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex align-items-center me-3">
                                        <!--begin::Logo-->
                                        <img src="../../assets/media/svg/brand-logos/bootstrap5.svg" class="me-4 w-30px"
                                            alt="" />
                                        <!--end::Logo-->

                                        <!--begin::Section-->
                                        <div class="flex-grow-1">
                                            <!--begin::Text-->
                                            <a href="overview.html#"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Bootstrap 5</a>
                                            <!--end::Text-->

                                            <!--begin::Description-->
                                            <span class="text-gray-500 fw-semibold d-block fs-6">CSS Framework</span>
                                            <!--end::Description--->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-center w-100 mw-125px">
                                        <!--begin::Progress-->
                                        <div class="progress h-6px w-100 me-2 bg-light-primary">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 44%"
                                                aria-valuenow="44" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <!--end::Progress-->

                                        <!--begin::Value-->
                                        <span class="text-gray-500 fw-semibold">
                                            44%
                                        </span>
                                        <!--end::Value-->
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex align-items-center me-3">
                                        <!--begin::Logo-->
                                        <img src="../../assets/media/svg/brand-logos/angular-icon.svg" class="me-4 w-30px"
                                            alt="" />
                                        <!--end::Logo-->

                                        <!--begin::Section-->
                                        <div class="flex-grow-1">
                                            <!--begin::Text-->
                                            <a href="overview.html#"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Angular 16</a>
                                            <!--end::Text-->

                                            <!--begin::Description-->
                                            <span class="text-gray-500 fw-semibold d-block fs-6">JS Framework</span>
                                            <!--end::Description--->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-center w-100 mw-125px">
                                        <!--begin::Progress-->
                                        <div class="progress h-6px w-100 me-2 bg-light-info">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 70%"
                                                aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <!--end::Progress-->

                                        <!--begin::Value-->
                                        <span class="text-gray-500 fw-semibold">
                                            70%
                                        </span>
                                        <!--end::Value-->
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex align-items-center me-3">
                                        <!--begin::Logo-->
                                        <img src="../../assets/media/svg/brand-logos/spring-3.svg" class="me-4 w-30px"
                                            alt="" />
                                        <!--end::Logo-->

                                        <!--begin::Section-->
                                        <div class="flex-grow-1">
                                            <!--begin::Text-->
                                            <a href="overview.html#"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Spring</a>
                                            <!--end::Text-->

                                            <!--begin::Description-->
                                            <span class="text-gray-500 fw-semibold d-block fs-6">Java Framework</span>
                                            <!--end::Description--->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-center w-100 mw-125px">
                                        <!--begin::Progress-->
                                        <div class="progress h-6px w-100 me-2 bg-light-danger">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 56%"
                                                aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <!--end::Progress-->

                                        <!--begin::Value-->
                                        <span class="text-gray-500 fw-semibold">
                                            56%
                                        </span>
                                        <!--end::Value-->
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->


                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex align-items-center me-3">
                                        <!--begin::Logo-->
                                        <img src="../../assets/media/svg/brand-logos/typescript-1.svg" class="me-4 w-30px"
                                            alt="" />
                                        <!--end::Logo-->

                                        <!--begin::Section-->
                                        <div class="flex-grow-1">
                                            <!--begin::Text-->
                                            <a href="overview.html#"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">TypeScript</a>
                                            <!--end::Text-->

                                            <!--begin::Description-->
                                            <span class="text-gray-500 fw-semibold d-block fs-6">Better Tooling</span>
                                            <!--end::Description--->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-center w-100 mw-125px">
                                        <!--begin::Progress-->
                                        <div class="progress h-6px w-100 me-2 bg-light-success">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 82%"
                                                aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <!--end::Progress-->

                                        <!--begin::Value-->
                                        <span class="text-gray-500 fw-semibold">
                                            82%
                                        </span>
                                        <!--end::Value-->
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->



                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List widget 21-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->



            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->


@endsection
