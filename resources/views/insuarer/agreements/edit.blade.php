@extends('kmj.layouts.app')

@section('title', 'Insurer Settings')

@section('content')

<div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-18">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <h1 class="page-heading d-flex text-white fw-bold fs-3 flex-column justify-content-center my-0"
                    style="color: #003153 !important;">
                    Insurer Settings
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('insuarer.dashboard') }}" class="text-muted text-hover-primary">Home | </a>
                    </li>
                    <li class="breadcrumb-item text-muted">Settings</li>
                </ul>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('insuarer.support') }}" class="btn btn-sm"
                    style="background-color: #9aa89b; color: white;">
                    <span>Support</span>
                </a>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="row g-5 g-xl-10">
                <div class="col-xl-4 mb-10">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"
                            style="background: linear-gradient(135deg, #9aa89b 0%, #9aa89b 30%, #9aa89b 100%);">

                        </div>

                        <div class="card-body mt-n20">
                            <div class="mt-n20 position-relative">
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
                                        <a href="{{ route('insuarer.agreements.show') }}" class="d-block w-100 text-decoration-none">
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 shadow-lg mt-3 w-100 h-100">
                                                <div class="symbol symbol-30px mb-4 d-flex justify-content-center align-items-center">
                                                    <span class="symbol-label1 rounded-3">
                                                        <i class="bi bi-file-earmark-check fs-1 fw-bold text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="text-gray-700 fw-bolder d-block fs-9 text-nowrap">Agreements</span>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title fw-bold text-gray-800">Configuration</h3>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                    <i class="bi bi-check-circle fs-2hx text-success me-4"></i>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-dark">Success</h4>
                                        <span>{{ session('success') }}</span>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="#">
                                @csrf
                                <div class="fv-row mb-10">
                                    <label class="form-label fs-6 fw-bold text-gray-700 mb-3">
                                        Auto Approval Limit (TZS)
                                    </label>
                                    <input type="number"
                                           name="auto_approval_limit"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ $insurer->auto_approval_limit }}">
                                    <div class="form-text text-muted">Set the maximum amount for automatic claim approvals.</div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn fw-bold px-6"
                                        style="background-color: #9aa89b; color: white; border: none;">
                                        Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
    </div>
@endsection
