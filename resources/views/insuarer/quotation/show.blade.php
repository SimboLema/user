@extends('kmj.layouts.app')

@section('title', 'View Quotation')

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
                    Quotation Details
                </h1>
                <!--end::Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('insuarer.dashboard') }}" class="text-muted text-hover-primary">
                            Home | </a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('insuarer.quotations') }}" class="text-muted text-hover-primary">
                            Quotations | </a>
                    </li>
                    <li class="breadcrumb-item text-muted">View</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->

            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('insuarer.quotations') }}" class="btn btn-sm"
                    style="text-decoration: none; color: inherit; background-color: #9aa89b; color: white;">
                    <i class="bi bi-arrow-left me-2"></i><span>Back to Quotations</span>
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

            <!--begin::Quotation Header Section-->
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-xl-12">
                    <div class="card card-flush shadow-sm">
                        <div class="card-body py-8 px-8">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div>
                                        <h2 class="fw-bold mb-2" style="color: #003153; font-size: 1.75rem;">
                                            Quotation #Q-{{ $quotation->id + 1000 }}
                                        </h2>
                                        <p class="text-gray-600 fw-semibold mb-0">
                                            Created on {{ $quotation->created_at->format('M d, Y \a\t H:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-end">
                                    @php
                                        $statusConfig = match($quotation->status ?? 'pending') {
                                            'success' => ['class' => 'bg-success bg-opacity-10 text-success', 'text' => 'Approved', 'icon' => 'check-circle-fill'],
                                            'approved' => ['class' => 'bg-success bg-opacity-10 text-success', 'text' => 'Approved', 'icon' => 'check-circle-fill'],
                                            'pending' => ['class' => 'bg-warning bg-opacity-10 text-warning', 'text' => 'Pending', 'icon' => 'clock-history'],
                                            'cancelled' => ['class' => 'bg-danger bg-opacity-10 text-danger', 'text' => 'Cancelled', 'icon' => 'x-circle-fill'],
                                            default => ['class' => 'bg-secondary bg-opacity-10 text-dark', 'text' => 'Unknown', 'icon' => 'question-circle']
                                        };
                                    @endphp
                                    <div class="badge {{ $statusConfig['class'] }} px-4 py-3 fw-bold fs-6">
                                        <i class="bi bi-{{ $statusConfig['icon'] }} me-2"></i>{{ $statusConfig['text'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Quotation Header Section-->

            <!--begin::Customer & Insurance Info Section-->
            <div class="row gx-5 gx-xl-10 mb-10">
                <!-- Customer Information -->
                <div class="col-xl-6">
                    <div class="card card-flush shadow-sm">
                        <div class="card-header border-0 pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold" style="color: #003153;">
                                    <i class="bi bi-person-fill me-2"></i>Customer Information
                                </span>
                            </h3>
                        </div>
                        <div class="card-body py-6">
                            <div class="mb-5">
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Customer Name</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->customer->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="mb-5">
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Email Address</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->customer->email ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="mb-5">
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Phone Number</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->customer->phone ?? 'N/A' }}
                                </span>
                            </div>

                            <div>
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Address</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->customer->address ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insurance Information -->
                <div class="col-xl-6">
                    <div class="card card-flush shadow-sm">
                        <div class="card-header border-0 pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold" style="color: #003153;">
                                    <i class="bi bi-shield-check me-2"></i>Insurance Details
                                </span>
                            </h3>
                        </div>
                        <div class="card-body py-6">
                            <div class="mb-5">
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Insurance Type</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->coverage->product->insurance->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="mb-5">
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Product</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->coverage->product->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="mb-5">
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Risk Name</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->coverage->risk_name ?? 'N/A' }}
                                </span>
                            </div>

                            <div>
                                <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Coverage Details</span>
                                <span class="d-block text-gray-800 fw-bold fs-5">
                                    {{ $quotation->coverage->description ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Customer & Insurance Info Section-->

            <!--begin::Risk & Coverage Details Section-->
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-xl-12">
                    <div class="card card-flush shadow-sm">
                        <div class="card-header border-0 pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold" style="color: #003153;">
                                    <i class="bi bi-info-circle me-2"></i>Risk & Coverage Details
                                </span>
                            </h3>
                        </div>
                        <div class="card-body py-6">
                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Risk Description</span>
                                    <span class="d-block text-gray-800 fw-bold fs-5">
                                        {{ $quotation->coverage->description ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="col-md-6 mb-5">
                                    <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Coverage Type</span>
                                    <span class="d-block text-gray-800 fw-bold fs-5">
                                        {{ $quotation->coverage->coverage_type ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="col-md-6 mb-5">
                                    <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Sum Insured</span>
                                    <span class="d-block text-gray-800 fw-bold fs-5">
                                        TZS {{ number_format($quotation->coverage->sum_insured ?? 0, 2) }}
                                    </span>
                                </div>

                                <div class="col-md-6 mb-5">
                                    <span class="d-block text-gray-500 fw-semibold fs-8 mb-2">Deductible</span>
                                    <span class="d-block text-gray-800 fw-bold fs-5">
                                        TZS {{ number_format($quotation->coverage->deductible ?? 0, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Risk & Coverage Details Section-->

            <!--begin::Premium Calculation Section-->
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-xl-8 offset-xl-4">
                    <div class="card card-flush shadow-sm" style="border-left: 4px solid #003153;">
                        <div class="card-header border-0 pt-7 bg-light">
                            <h3 class="card-title fw-bold" style="color: #003153;">
                                <i class="bi bi-calculator me-2"></i>Premium Calculation
                            </h3>
                        </div>
                        <div class="card-body py-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td class="text-gray-600 fw-semibold">Base Premium</td>
                                            <td class="text-end text-gray-800 fw-bold">
                                                TZS {{ number_format($quotation->base_premium ?? 0, 2) }}
                                            </td>
                                        </tr>

                                        @if($quotation->additional_charges > 0)
                                        <tr class="border-bottom">
                                            <td class="text-gray-600 fw-semibold">Additional Charges</td>
                                            <td class="text-end text-gray-800 fw-bold">
                                                TZS {{ number_format($quotation->additional_charges ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        @endif

                                        @if($quotation->discount > 0)
                                        <tr class="border-bottom">
                                            <td class="text-gray-600 fw-semibold">Discount</td>
                                            <td class="text-end text-success fw-bold">
                                                -TZS {{ number_format($quotation->discount ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        @endif

                                        <tr class="border-bottom">
                                            <td class="text-gray-600 fw-semibold">Subtotal (Ex VAT)</td>
                                            <td class="text-end text-gray-800 fw-bold">
                                                TZS {{ number_format($quotation->total_premium ?? 0, 2) }}
                                            </td>
                                        </tr>

                                        <tr class="border-bottom">
                                            <td class="text-gray-600 fw-semibold">VAT (18%)</td>
                                            <td class="text-end text-gray-800 fw-bold">
                                                TZS {{ number_format($quotation->vat ?? 0, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-gray-800 fw-bold fs-5" style="color: #003153;">Total Premium (Inc VAT)</td>
                                            <td class="text-end fw-bold fs-4" style="color: #003153;">
                                                TZS {{ number_format($quotation->total_premium_including_tax ?? 0, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Premium Calculation Section-->

            <!--begin::Actions Section-->
            @if(in_array($quotation->status ?? 'pending', ['pending']))
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-xl-12">
                    <div class="card card-flush shadow-sm bg-light">
                        <div class="card-body py-8 px-8">
                            <h4 class="fw-bold mb-6" style="color: #003153;">Actions</h4>
                            <div class="d-flex gap-3">
                                <form action="{{ route('insuarer.quotation.updateStatus', $quotation->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn fw-bold px-8 py-3"
                                            style="background-color: #00a99d; color: white; border-radius: 8px;">
                                        <i class="bi bi-check-circle me-2"></i>Approve Quotation
                                    </button>
                                </form>

                                <form action="{{ route('insuarer.quotation.updateStatus', $quotation->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn fw-bold px-8 py-3"
                                            style="background-color: #ef4444; color: white; border-radius: 8px;"
                                            onclick="return confirm('Are you sure you want to cancel this quotation?')">
                                        <i class="bi bi-x-circle me-2"></i>Cancel Quotation
                                    </button>
                                </form>

                                <a href="{{ route('insuarer.quotations') }}" class="btn btn-light fw-bold px-8 py-3"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-xl-12">
                    <div class="card card-flush shadow-sm bg-light">
                        <div class="card-body py-8 px-8">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="fw-bold mb-0" style="color: #003153;">
                                    <i class="bi bi-info-circle me-2"></i>Quotation Status
                                </h4>
                                <a href="{{ route('insuarer.quotations') }}" class="btn btn-light fw-bold px-8 py-3"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Quotations
                                </a>
                            </div>
                            <p class="text-gray-600 mt-3 mb-0">
                                This quotation is currently <strong>{{ ucfirst($quotation->status ?? 'unknown') }}</strong> and no further actions can be taken.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!--end::Actions Section-->

            <!--begin::Additional Notes Section-->
            @if($quotation->notes)
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-xl-12">
                    <div class="card card-flush shadow-sm">
                        <div class="card-header border-0 pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold" style="color: #003153;">
                                    <i class="bi bi-file-text me-2"></i>Additional Notes
                                </span>
                            </h3>
                        </div>
                        <div class="card-body py-6">
                            <p class="text-gray-700 fw-semibold lh-lg">
                                {{ $quotation->notes }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!--end::Additional Notes Section-->

        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

</div>
<!--end::Content wrapper-->

<style>
    /* Card Styling */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .card-flush {
        border: none;
    }

    /* Badge Styling */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Table Styling */
    .table {
        margin-bottom: 0;
    }

    .table tbody tr {
        padding: 0.75rem 0;
    }

    .table td {
        padding: 0.75rem 0;
    }

    /* Button Hover Effects */
    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Text Utilities */
    .text-gray-500 { color: #6b7280; }
    .text-gray-600 { color: #4b5563; }
    .text-gray-700 { color: #374151; }
    .text-gray-800 { color: #1f2937; }
    .bg-light { background-color: #f9fafb; }
</style>

@endsection
