@extends('kmj.layouts.app')

@section('title', 'Insurers Directory')

@section('content')

<style>
    @keyframes gradientShift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .insurer-card {
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        background-color: #f5f8fa;
        border: none;
    }
    .insurer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.75rem 2rem rgba(0, 49, 83, 0.12) !important;
    }

    .insurer-icon-wrap {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px rgba(0,49,83,0.10);
        margin: 0 auto 1rem auto;
    }

    .btn-sage {
        background-color: #9aa89b;
        color: #fff;
        border: none;
    }
    .btn-sage:hover {
        background-color: #8a9889;
        color: #fff;
    }
</style>

<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-18">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <h1 class="page-heading d-flex text-white fw-bold fs-3 flex-column justify-content-center my-0"
                    style="color: #003153 !important;">
                    Insurance Portal
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home |</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Insurers Directory</li>
                </ul>
            </div>
            <div class="d-flex align-items-center">
                <div class="toolbar-select form-floating w-175px h-55px me-4 bg-white rounded">
                    <div class="h-55px d-flex align-items-center justify-content-center">
                        <span class="fs-5 fw-bold" style="color: #003153 !important;">Insurers</span>
                    </div>
                </div>
                <a href="#" class="btn btn-sm me-3 btn-sage"><span>Support</span></a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row gx-5 gx-xl-10">

                <!--begin::Left Sidebar Col-->
                <div class="col-xl-4 mb-10">
                    <div class="card card-flush h-xl-100">

                        <!--begin::Gradient Header-->
                        <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"
                            style="background: linear-gradient(135deg, #9aa89b 0%, #9aa89b 30%, #9aa89b 100%);
                                   background-size: 300% 300%;
                                   animation: gradientShift 10s ease infinite;"
                            data-bs-theme="light">
                            <h3 class="card-title align-items-start flex-column text-white pt-15">
                                <span class="fw-bold fs-1x mb-3">Management</span>
                                <div class="fs-4 text-white">
                                    <span class="position-relative d-inline-block">
                                        <a href="#" class="text-white fs-2x fw-bold d-block mb-1">
                                            {{ $insuarers->count() }} Insurers
                                        </a>
                                    </span>
                                </div>
                            </h3>
                        </div>
                        <!--end::Gradient Header-->

                        <!--begin::Body-->
                        <div class="card-body mt-n20">
                            <div class="mt-n20 position-relative">
                                <div class="row g-3 mt-10 g-lg-6">

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('dashboard') }}" class="d-block w-100 text-decoration-none">
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
                                        <a href="{{ route('kmj.getBranches') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-fullscreen-exit fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Branches</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('admin.insuarers.index') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100" style="background-color: rgba(0,49,83,0.08) !important; border: 1.5px solid rgba(0,49,83,0.18);">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-person-plus fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Insurers</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.getAgents') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-person-plus fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Agents</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.getProducts') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-box fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Products</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.quotation') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-archive fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Quotation</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.risknote') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-journal-text fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Covernote</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.renewals') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-repeat fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Renewals</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.transaction') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-briefcase fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Transaction</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.report') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-file-earmark-medical fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Reports</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.customers') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-person fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Customers</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.claims') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-arrow-clockwise fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Claims</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.reinsurance.index') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-file-earmark-break fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Reinsurance</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.downloads') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-download fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Downloads</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.messages') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-chat-left-text fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Messages</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.cancellation') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-x-square fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Cancellation</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('covernote.verification.index') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-award fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Verification</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('policy.submission.index') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-gear fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Policy Sub</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('kmj.notifications') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100 position-relative">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center position-relative">
                                                    <span class="symbol-label1 position-relative rounded-3">
                                                        <i class="bi bi-bell fs-1 text-primary"></i>
                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
                                                            3<span class="visually-hidden">unread messages</span>
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Notifications</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-4 d-flex justify-content-center">
                                        <a href="{{ route('user.index') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100 position-relative">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center position-relative">
                                                    <span class="symbol-label1 position-relative rounded-3">
                                                        <i class="bi bi-people fs-1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Hr Mngt</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="button"
                                                class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2"
                                                style="background-color: #9aa89b; color: white;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_add_insuarer">
                                            <i class="bi bi-plus-lg text-white"></i>
                                            <span>Add New Insurer</span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end::Body-->

                    </div>
                </div>
                <!--end::Left Sidebar Col-->

                <!--begin::Main Content Col-->
                <div class="col-xl-8 mb-10">

                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <div>
                            <h2 class="fw-bolder mb-1" style="color:#003153;">Insurers Directory</h2>
                            <span class="text-muted fw-semibold fs-7">All registered insurance providers</span>
                        </div>
                        <button type="button"
                                class="btn btn-sm px-6 py-3 d-flex align-items-center shadow-sm"
                                style="background-color: #9aa89b; color: white;"
                                data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_insuarer">
                            <i class="bi bi-plus-lg me-2 text-white"></i>
                            <span>Add New Insurer</span>
                        </button>
                    </div>

                    <div class="row g-5">
                        @foreach($insuarers as $insuarer)
                        <div class="col-xl-4 col-md-6">
                            <div class="card insurer-card h-100 shadow-sm">
                                <div class="card-body p-8 text-center">
                                    <div class="insurer-icon-wrap mb-5">
                                        <i class="bi bi-building fs-1" style="color:#003153;"></i>
                                    </div>
                                    <span class="text-gray-800 fw-bolder fs-5 d-block mb-1">{{ $insuarer->name }}</span>
                                    <span class="text-muted fw-semibold fs-7 d-block mb-1">{{ $insuarer->email }}</span>
                                    @if(isset($insuarer->phone))
                                    <span class="text-muted fw-semibold fs-8 d-block mb-4">
                                        <i class="bi bi-telephone me-1" style="color:#9aa89b;"></i>
                                        {{ $insuarer->phone }}
                                    </span>
                                    @endif
                                    <div class="separator separator-dashed my-4 border-gray-200"></div>
                                    <div class="d-flex justify-content-center gap-3">
                                        <a href="#" class="btn btn-sm btn-sage px-5 fw-bold">View Profile</a>
                                        <button class="btn btn-sm btn-light px-4 fw-bold text-gray-700">
                                            <i class="bi bi-three-dots fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($insuarers->isEmpty())
                        <div class="col-12">
                            <div class="card card-flush">
                                <div class="card-body text-center py-20">
                                    <i class="bi bi-building-x fs-5x mb-5 d-block" style="color:#9aa89b;"></i>
                                    <h4 class="fw-bold text-gray-700 mb-2">No Insurers Found</h4>
                                    <p class="text-muted fw-semibold mb-6">No insurance providers have been added yet.</p>
                                    <button type="button"
                                            class="btn px-8"
                                            style="background-color: #9aa89b; color: white;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_insuarer">
                                        <i class="bi bi-plus-lg me-2 text-white"></i> Add First Insurer
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
                <!--end::Main Content Col-->

            </div>
        </div>
    </div>
    <!--end::Content-->

</div>
<!--end::Content wrapper-->

<!--begin::Add Insurer Modal-->
<div class="modal fade" id="kt_modal_add_insuarer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0 pt-0 rounded-top-4 overflow-hidden"
                 style="background: linear-gradient(135deg, #9aa89b 0%, #7a9880 100%); height: 80px;">
                <div class="d-flex align-items-center ps-10 pt-2">
                    <i class="bi bi-shield-plus fs-2 text-white me-3"></i>
                    <h5 class="text-white fw-bold mb-0 fs-4">New Insurance Provider</h5>
                </div>
                <div class="btn btn-sm btn-icon me-3 mt-3" data-bs-dismiss="modal" style="color:rgba(255,255,255,0.8);">
                    <i class="bi bi-x-lg fs-3"></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-8 pb-15">
                <form action="{{ route('admin.insuarers.store') }}" method="POST">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 mb-6">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <div class="mb-10 text-center">
                        <div class="text-muted fw-semibold fs-6">Fill in the details below to create a new insurer account</div>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2 text-gray-700">Full Name</label>
                        <input type="text" class="form-control form-control-solid bg-gray-100 border-0" name="name" placeholder="e.g. Jubilee Insurance" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2 text-gray-700">Company Code</label>
                        <input type="text" class="form-control form-control-solid bg-gray-100 border-0" name="company_code" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2 text-gray-700">Insurer Code</label>
                        <input type="text" class="form-control form-control-solid bg-gray-100 border-0" name="insuarer_code" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2 text-gray-700">Email Address</label>
                        <input type="email" class="form-control form-control-solid bg-gray-100 border-0" name="email" placeholder="contact@insurer.com" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2 text-gray-700">Phone Number</label>
                        <input type="text" class="form-control form-control-solid bg-gray-100 border-0" name="phone" placeholder="07XXXXXXXX" required />
                    </div>
                    <div class="fv-row mb-10">
                        <label class="required fs-6 fw-bold mb-2 text-gray-700">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form-control-solid bg-gray-100 border-0" name="password" id="password_input" required />
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" onclick="togglePassword()">
                                <i class="bi bi-eye-slash fs-2 text-muted"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-8 mt-2">Use 6 or more characters.</div>
                    </div>
                    <div class="separator separator-dashed my-6 border-gray-200"></div>
                    <div class="text-center d-flex justify-content-center gap-4">
                        <button type="reset" class="btn btn-light px-8 fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn px-10 fw-bold" style="background-color: #9aa89b; color: white;">
                            <i class="bi bi-check2 me-2 text-white"></i> Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Add Insurer Modal-->

<script>
    function togglePassword() {
        const input = document.getElementById('password_input');
        const icon = event.currentTarget.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = "password";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
</script>

@endsection
