@extends('kmj.layouts.app')

@section('title', 'My Quotations')

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
                    My Quotations
                </h1>
                <!--end::Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('insuarer.dashboard') }}" class="text-muted text-hover-primary">
                            Home | </a>
                    </li>
                    <li class="breadcrumb-item text-muted">Quotations</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->

            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('insuarer.dashboard') }}" class="btn btn-sm"
                    style="text-decoration: none; color: inherit; background-color: #9aa89b; color: white;">
                    <i class="bi bi-arrow-left me-2"></i><span>Back to Dashboard</span>
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

            <!--begin::Search and Filter Section-->
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-xl-12">
                    <div class="card card-flush shadow-sm">
                        <div class="card-header border-0 pt-7">
                            <div class="card-title d-flex align-items-center gap-3 w-100">
                                <i class="bi bi-funnel fs-4" style="color: #003153;"></i>
                                <h3 class="card-title fw-bold mb-0" style="color: #003153;">Search & Filter</h3>
                            </div>
                        </div>
                        <div class="card-body py-6">
                            <form action="{{ route('insuarer.quotations') }}" method="GET" class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold mb-3">Search</label>
                                    <div class="position-relative">
                                        <i class="bi bi-search position-absolute top-50 translate-middle-y ms-4"
                                            style="color: #9aa89b;"></i>
                                        <input type="text"
                                               name="search"
                                               value="{{ $search ?? '' }}"
                                               class="form-control ps-12 border-1"
                                               placeholder="Search by customer, ID, or risk..."
                                               style="padding-left: 2.5rem; border-radius: 10px;">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold mb-3">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn fw-bold px-6"
                                            style="background-color: #003153; color: white; border-radius: 8px;">
                                            <i class="bi bi-search me-2"></i>Search
                                        </button>

                                        @if($search)
                                            <a href="{{ route('insuarer.quotations') }}" class="btn btn-light fw-bold px-6"
                                                style="border-radius: 8px;">
                                                <i class="bi bi-x-circle me-2"></i>Clear
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Search and Filter Section-->

            <!--begin::Stats Row-->
            <div class="row gx-5 gx-xl-10 mb-10">
                <div class="col-md-3">
                    <div class="card card-flush shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center py-6">
                            <div>
                                <span class="d-block text-gray-500 fw-semibold fs-7 mb-1">Total Quotations</span>
                                <span class="d-block text-dark fw-bold fs-2">{{ $totalQuotations ?? 0 }}</span>
                            </div>
                            <div class="symbol symbol-50px" style="background: #e8f0ff; border-radius: 10px;">
                                <span class="symbol-label d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-text fs-2 text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-flush shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center py-6">
                            <div>
                                <span class="d-block text-gray-500 fw-semibold fs-7 mb-1">Pending</span>
                                <span class="d-block text-warning fw-bold fs-2">{{ $pendingQuotations ?? 0 }}</span>
                            </div>
                            <div class="symbol symbol-50px" style="background: #fff8e1; border-radius: 10px;">
                                <span class="symbol-label d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clock-history fs-2 text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-flush shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center py-6">
                            <div>
                                <span class="d-block text-gray-500 fw-semibold fs-7 mb-1">Approved</span>
                                <span class="d-block text-success fw-bold fs-2">{{ $approvedQuotations ?? 0 }}</span>
                            </div>
                            <div class="symbol symbol-50px" style="background: #e1f5f0; border-radius: 10px;">
                                <span class="symbol-label d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check-circle fs-2 text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-flush shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center py-6">
                            <div>
                                <span class="d-block text-gray-500 fw-semibold fs-7 mb-1">Cancelled</span>
                                <span class="d-block text-danger fw-bold fs-2">{{ $cancelledQuotations ?? 0 }}</span>
                            </div>
                            <div class="symbol symbol-50px" style="background: #ffe1e1; border-radius: 10px;">
                                <span class="symbol-label d-flex align-items-center justify-content-center">
                                    <i class="bi bi-x-circle fs-2 text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Stats Row-->

            <!--begin::Quotations Table-->
            <div class="row gx-5 gx-xl-10">
                <div class="col-xl-12">
                    <div class="card card-flush h-md-100 shadow-sm">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold" style="color: #003153;">Quotations List</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">
                                    @if($search)
                                        Showing results for "{{ $search }}"
                                    @else
                                        All quotations in the system
                                    @endif
                                </span>
                            </h3>
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
                                            <th class="min-w-50px text-center">#</th>
                                            <th class="min-w-150px text-start">Customer</th>
                                            <th class="min-w-150px text-start">Product & Risk</th>
                                            <th class="min-w-100px text-center">Premium (TZS)</th>
                                            <th class="min-w-120px text-center">Created Date</th>
                                            <th class="min-w-100px text-center">Status</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                        @forelse ($quotations as $quotation)
                                            <tr>
                                                <td class="text-center text-gray-500">{{ $loop->iteration }}</td>
                                                <td class="text-start">
                                                    <div class="d-block">
                                                        <span class="text-gray-800 fw-bold">
                                                            {{ ucwords(strtolower($quotation->customer->name ?? 'N/A')) }}
                                                        </span>
                                                    </div>
                                                    <span class="text-gray-500 fw-semibold d-block fs-8 mt-1">
                                                        ID: #Q-{{ $quotation->id + 1000 }}
                                                    </span>
                                                </td>
                                                <td class="text-start">
                                                    <div class="d-block">
                                                        <span class="text-gray-800 fw-bold">
                                                            {{ $quotation->coverage->product->insurance->name ?? '-' }}
                                                        </span>
                                                    </div>
                                                    <span class="text-gray-500 fw-semibold d-block fs-8 mt-1">
                                                        {{ $quotation->coverage->risk_name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-gray-800 fw-bold">
                                                        {{ number_format($quotation->total_premium_including_tax ?? 0) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-gray-600 fw-semibold">
                                                        {{ $quotation->created_at->format('M d, Y') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusConfig = match($quotation->status ?? 'pending') {
                                                            'success' => ['class' => 'badge-success', 'text' => 'Approved', 'icon' => 'check-circle'],
                                                            'approved' => ['class' => 'badge-success', 'text' => 'Approved', 'icon' => 'check-circle'],
                                                            'pending' => ['class' => 'badge-warning', 'text' => 'Pending', 'icon' => 'clock-history'],
                                                            'cancelled' => ['class' => 'badge-danger', 'text' => 'Cancelled', 'icon' => 'x-circle'],
                                                            default => ['class' => 'badge-secondary', 'text' => 'Unknown', 'icon' => 'question-circle']
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $statusConfig['class'] }} d-inline-block">
                                                        <i class="bi bi-{{ $statusConfig['icon'] }} me-1"></i>{{ $statusConfig['text'] }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('insuarer.quotation.show', $quotation->id) }}"
                                                           class="btn btn-sm btn-light-primary rounded-pill px-4 fw-bold"
                                                           title="View quotation details">
                                                            <i class="bi bi-eye me-1"></i>View
                                                        </a>

                                                        @if (in_array($quotation->status ?? 'pending', ['pending']))
                                                            <div class="d-flex gap-1">
                                                                <form action="{{ route('insuarer.quotation.updateStatusApprove', $quotation->id) }}"
                                                                      method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <input type="hidden" name="status" value="approved">
                                                                    <button type="submit" class="btn btn-sm btn-light-success rounded-pill px-3"
                                                                            title="Approve this quotation">
                                                                        <i class="bi bi-check me-1"></i>Approve
                                                                    </button>
                                                                </form>

                                                                <form action="{{ route('insuarer.quotation.updateStatusReject', $quotation->id) }}"
                                                                      method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="cancelled">
                                                                    <button type="submit" class="btn btn-sm btn-light-danger rounded-pill px-3"
                                                                            title="Cancel this quotation"
                                                                            onclick="return confirm('Are you sure you want to cancel this quotation?')">
                                                                        <i class="bi bi-x me-1"></i>Cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-20">
                                                    <div class="mb-4">
                                                        <i class="bi bi-inbox fs-1 text-muted" style="font-size: 4rem !important;"></i>
                                                    </div>
                                                    <h5 class="text-gray-600 fw-bold mb-2">No Quotations Found</h5>
                                                    <p class="text-gray-500 mb-4">There are no quotations in the system yet.</p>
                                                    <a href="{{ route('insuarer.dashboard') }}" class="btn btn-sm fw-bold px-6"
                                                        style="background-color: #003153; color: white; border-radius: 8px;">
                                                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->

                            <!--begin::Pagination-->
                            @if($quotations->hasPages())
                                <div class="mt-6 px-4">
                                    {{ $quotations->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                            <!--end::Pagination-->
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
            </div>
            <!--end::Quotations Table-->

        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

</div>
<!--end::Content wrapper-->

<style>
    /* Badge Styles */
    .badge-success {
        background-color: #e1f5f0 !important;
        color: #00a99d !important;
        border: 1px solid rgba(0, 169, 157, 0.2) !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
    }

    .badge-warning {
        background-color: #fff8e1 !important;
        color: #f59e0b !important;
        border: 1px solid rgba(245, 158, 11, 0.2) !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
    }

    .badge-danger {
        background-color: #ffe1e1 !important;
        color: #ef4444 !important;
        border: 1px solid rgba(239, 68, 68, 0.2) !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
    }

    .badge-secondary {
        background-color: #f3f4f6 !important;
        color: #6b7280 !important;
        border: 1px solid rgba(107, 114, 128, 0.2) !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
    }

    /* Button Light Variants */
    .btn-light-primary {
        background-color: #e8f0ff !important;
        color: #0066cc !important;
        border: none !important;
        font-weight: 600 !important;
    }

    .btn-light-primary:hover {
        background-color: #d0e0ff !important;
        color: #0052a3 !important;
    }

    .btn-light-success {
        background-color: #e1f5f0 !important;
        color: #00a99d !important;
        border: none !important;
        font-weight: 600 !important;
    }

    .btn-light-success:hover {
        background-color: #c2ebea !important;
        color: #008078 !important;
    }

    .btn-light-danger {
        background-color: #ffe1e1 !important;
        color: #ef4444 !important;
        border: none !important;
        font-weight: 600 !important;
    }

    .btn-light-danger:hover {
        background-color: #ffc2c2 !important;
        color: #dc2626 !important;
    }

    /* Table Refinement */
    .table thead th {
        background-color: #fcfcfd;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        color: #64748b;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody td {
        border-bottom: 1px solid #f8fafc;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Card Styles */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
    }

    .card-flush {
        border: none;
    }
</style>

@endsection
