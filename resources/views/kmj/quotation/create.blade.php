@extends('kmj.layouts.app')

@section('title', ' Quotation Page')

@section('content')

    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
        }

        .autocomplete-suggestions {
            background: white;
            border: 1px solid #ccc;
            cursor: pointer;
            position: absolute !important;
            z-index: 9999 !important;
        }

        .autocomplete-item { padding: 8px; }
        .autocomplete-item:hover { background-color: #f0f0f0; }

        /* ── Step bar ── */
        .step-bar {
            position: relative;
            display: flex;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            padding: 0 1rem;
            align-items: flex-start;
            padding-top: 8px;
        }

        .fill-line {
            position: absolute;
            top: 28px;
            left: 0;
            height: 4px;
            background: #003153;
            transform: translateY(-50%);
            z-index: 1;
            transition: width 0.3s ease;
        }

        .step-bar::before {
            content: '';
            position: absolute;
            top: 28px;
            left: 0;
            right: 0;
            height: 4px;
            background: #dee2e6;
            transform: translateY(-50%);
            z-index: 0;
        }

        .step-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            font-weight: bold;
            cursor: pointer;
            border: 2px solid transparent;
            transition: background 0.2s, border-color 0.2s, transform 0.15s;
            color: #003153;
            flex-shrink: 0;
        }

        .step:hover { transform: scale(1.1); border-color: #003153; }

        .step.active {
            background: #003153;
            color: white;
            border-color: #003153;
            box-shadow: 0 0 0 4px rgba(0,49,83,0.15);
        }

        .step.completed { background: #198754; color: white; border-color: #198754; }
        .step.completed:hover { background: #146c43; }

        .step-label {
            font-size: 10px;
            margin-top: 6px;
            color: #6c757d;
            white-space: nowrap;
            font-weight: 500;
            text-align: center;
        }

        .step-wrapper.active-wrapper .step-label { color: #003153; font-weight: 600; }

        .step-title {
            color: #003153;
            border-bottom: 2px solid #003153;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .required-field::after { content: " *"; color: red; }

        .file-upload-card { transition: all 0.3s ease; }
        .file-upload-card.dragover {
            border-color: #0d6efd;
            background-color: rgba(13,110,253,0.05);
        }

        /* ── Addon page styles ── */
        .addon-row-selected { background-color: #f1f8ff !important; }
        .addon-row-selected td { color: #003153 !important; }
        .addon-checkbox { width: 18px; height: 18px; cursor: pointer; accent-color: #003153; }

        .summary-card-inline {
            position: sticky;
            top: 80px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #e4e6ef;
        }
        .summary-line:last-child { border-bottom: none; }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0 0 0;
        }

        .badge-premium {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-normal {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
        }

        /* ── Review card headers ── */
        .review-card-header {
            background-color: #9aa89b !important;
            color: white;
        }

        .review-card-header span,
        .review-card-header i { color: white !important; }
    </style>

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="app-container container-xxl mt-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-10 col-12">

                            <div id="selectedCustomerInfo" class="d-none mt-3" style="font-size:1.1rem;">
                                <div class="alert alert-info d-flex align-items-center py-2">
                                    <i class="bi bi-person-check-fill me-2"></i>
                                    <strong class="me-2">Selected Customer:</strong>
                                    <span id="selectedCustomerName"></span>
                                    <span class="mx-2 text-muted">|</span>
                                    <span id="selectedCustomerPhone"></span>
                                    <span class="mx-2 text-muted">|</span>
                                    <span id="selectedCustomerEmail"></span>
                                </div>
                            </div>

                            <div class="card card-flush">
                                <div class="card-body">

                                    @php
                                        $isMotor    = $coverage->product->insurance->id == 2;
                                        $stepLabels = $isMotor
                                            ? ['Customer','Motor','Duration','Premium','Addons','Review','Payment','Finalize']
                                            : ['Customer','Duration','Premium','Review','Payment','Finalize'];
                                        $totalSteps = count($stepLabels);
                                        $reviewIdx  = $isMotor ? 5 : 3;
                                    @endphp

                                    <!-- ── Progress Bar ── -->
                                    <div class="step-bar" id="stepBar">
                                        <div class="fill-line" id="fillLine" style="width:0%;"></div>
                                        @foreach($stepLabels as $i => $label)
                                            <div class="step-wrapper" id="step-wrapper-{{ $i + 1 }}">
                                                <div class="step {{ $i === 0 ? 'active' : '' }}"
                                                     id="step-circle-{{ $i + 1 }}"
                                                     onclick="goToStep({{ $i + 1 }})"
                                                     title="{{ $label }}">
                                                    {{ $i + 1 }}
                                                </div>
                                                <span class="step-label">{{ $label }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Form -->
                                    <form method="post" action="{{ route('kmj.quotation.store') }}"
                                          enctype="multipart/form-data" novalidate>
                                        @csrf
                                        <input type="hidden" name="insuarer_id"   value="{{ $insuarerId }}">
                                        <input type="hidden" name="coverage_id"   value="{{ $coverage->id }}">
                                        <input type="hidden" name="insurance_id"  value="{{ $coverage->product->insurance->id }}">
                                        <input type="hidden" name="product_id"    value="{{ $coverage->product->id }}">
                                        <input type="hidden" name="risk_code"     value="{{ $coverage->risk_code }}">
                                        <input type="hidden" name="product_code"  value="{{ $coverage->product->code }}">
                                        <input type="hidden" name="officer_name"  value="{{ auth()->user()->name ?? 'System Officer' }}">
                                        <input type="hidden" name="officer_title" value="Sales Officer">
                                        <input type="hidden" id="customer_id"    name="customer_id">
                                        <input type="hidden" id="customer_name"  name="customer_name">
                                        <input type="hidden" id="customer_phone" name="customer_phone">
                                        <input type="hidden" id="customer_email" name="customer_email">

                                        {{-- ── Hidden inputs for PDF preview (synced by calculate()) ── --}}
                                        <input type="hidden" id="h_premium_rate"                name="premium_rate">
                                        <input type="hidden" id="h_premium"                     name="premium">
                                        <input type="hidden" id="h_tax_rate"                    name="tax_rate">
                                        <input type="hidden" id="h_tax_amount"                  name="tax_amount">
                                        <input type="hidden" id="h_total_premium_excluding_tax" name="total_premium_excluding_tax">
                                        <input type="hidden" id="h_total_premium_including_tax" name="total_premium_including_tax">
                                        <input type="hidden" id="h_cover_note_end_date"         name="cover_note_end_date">
                                        <input type="hidden" id="h_coverage_name"               name="coverage_name"
                                               value="{{ $coverage->name ?? '' }}">
                                        <input type="hidden" id="h_coverage_rate"               name="coverage_rate"
                                               value="{{ $coverage->rate ?? 0 }}">
                                        <input type="hidden" id="h_product_name"                name="product_name"
                                               value="{{ $coverage->product->name ?? '' }}">
                                        <input type="hidden" id="h_insurance_type"              name="insurance_type"
                                               value="{{ $coverage->product->insurance->type ?? '' }}">
                                        <input type="hidden" id="h_insurance_name"              name="insurance_name"
                                               value="{{ $coverage->product->insurance->name ?? '' }}">
                                        <input type="hidden" id="h_insuarer_name"               name="insuarer_name"
                                               value="{{ $coverage->product->insurance->name ?? 'KMJ Insurance Brokers Ltd' }}">

                                        {{-- ── STEP 1: Customer ── --}}
                                        @include('kmj.quotation.create.customer')

                                        @if ($isMotor)
                                            {{-- ── STEP 2: Motor ── --}}
                                            @include('kmj.quotation.create.motor')
                                        @endif

                                        {{-- ── STEP 3 (Motor) / 2 (non-Motor): Duration ── --}}
                                        @include('kmj.quotation.create.duration_covernote')

                                        {{-- ── STEP 4 (Motor) / 3 (non-Motor): Premium ── --}}
                                        @include('kmj.quotation.create.premium_calculation')

                                        @if ($isMotor)
{{-- ── STEP 5 (Motor only): Addons ── --}}
<div class="step-content d-none">
    <h5 class="step-title">
        <i class="bi bi-puzzle me-2"></i>Addons
    </h5>

    <div class="row g-5 mt-1">
        {{-- LEFT: Addon table --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header card-header-stretch">
                    <div class="card-title">
                        <h3 class="m-0 text-gray-800">Available Addons</h3>
                    </div>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fs-7 fw-bold"
                              id="selected-count-inline">0 selected</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                <tr>
                                    <th class="w-40px text-center"></th>
                                    <th class="min-w-200px">Name &amp; Description</th>
                                    <th class="min-w-100px text-center">Type</th>
                                    <th class="min-w-150px text-end pe-6">Amount (excl. tax)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($addons ?? [] as $addon)
                                    @php

                                        $isChecked = false;

                                        if ($addon->amount_type === 'PREMIUM') {
                                            $initialAmt = $addon->rate * ($coverage->minimum_amount ?? 0);
                                            $typeLabel  = number_format($addon->rate * 100, 2) . '% of base';
                                        } else {
                                            $initialAmt = $addon->amount;
                                            $typeLabel  = 'Flat amount';
                                        }
                                    @endphp
                                    {{--
                                        IMPORTANT: id must match JS selector "addon-row-X"
                                        data-rate is used by recalcAddons() for PREMIUM type
                                        data-amount is kept for NORMAL type (static)
                                        data-type lets JS know how to compute the amount
                                    --}}
                                    <tr class="text-gray-600 fs-6 fw-semibold addon-row"
                                        id="addon-row-{{ $addon->id }}">
                                        <td class="text-center">
                                            <input type="checkbox"
                                                   class="addon-checkbox addon-step-checkbox"
                                                   name="addon_ids[]"
                                                   value="{{ $addon->id }}"
                                                   data-name="{{ $addon->name }}"
                                                   data-type="{{ $addon->amount_type }}"
                                                   data-rate="{{ $addon->rate ?? 0 }}"
                                                   data-amount="{{ $initialAmt }}">
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block addon-name">{{ $addon->name }}</span>
                                            <span class="text-gray-500 fs-7">{{ Str::limit($addon->description, 90) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($addon->amount_type === 'PREMIUM')
                                                <span class="badge-premium">PREMIUM</span>
                                            @else
                                                <span class="badge-normal">NORMAL</span>
                                            @endif
                                            <div class="text-gray-500 fs-8 mt-1">{{ $typeLabel }}</div>
                                        </td>
                                        <td class="text-end pe-6">
                                            {{-- This span is updated dynamically by JS for PREMIUM type --}}
                                            <span class="text-gray-800 fw-bold fs-6 addon-display-amount"
                                                  id="addon-amt-{{ $addon->id }}">
                                                TZS {{ number_format($initialAmt, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-10">
                                            No addon products available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Premium summary card --}}
        <div class="col-lg-4">
            <div class="card summary-card-inline">
                <div class="card-header card-header-stretch">
                    <div class="card-title">
                        <h3 class="m-0 text-gray-800">Premium Summary</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="summary-line">
                        <span class="text-gray-600 fw-semibold fs-6">Base Premium</span>
                        <span class="text-gray-800 fw-bold fs-6" id="addon-base-premium">TZS 0.00</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-gray-600 fw-semibold fs-6">Addons Subtotal</span>
                        <span class="text-primary fw-bold fs-6" id="addon-addons-total">TZS 0.00</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-gray-600 fw-semibold fs-6">Premium (excl. tax)</span>
                        <span class="text-gray-800 fw-bold fs-6" id="addon-excl-tax">TZS 0.00</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-gray-600 fw-semibold fs-6">VAT (18%)</span>
                        <span class="text-gray-800 fw-bold fs-6" id="addon-tax-amount">TZS 0.00</span>
                    </div>
                    <div class="summary-total mt-3">
                        <span class="text-gray-800 fw-bolder fs-5">Total (incl. tax)</span>
                        <span class="fw-bolder fs-3 text-primary" id="addon-grand-total">TZS 0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- /row --}}

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn text-white"
                style="background-color:#9aa89b" onclick="changeStep(-1)">
            <i class="bi bi-arrow-left me-2"></i> Back
        </button>
        <button type="button" class="btn text-white"
                style="background-color:#003153" onclick="changeStep(1)">
            Next <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>
</div>
{{-- /Addons step --}}
@endif

                                        {{-- ── STEP 6 (Motor) / 4 (non-Motor): Review ── --}}
                                        <div class="step-content d-none">
                                            <h5 class="step-title">
                                                <i class="bi bi-eye me-2"></i>Review Quotation
                                            </h5>

                                            <div class="alert alert-light border d-flex align-items-center mb-4"
                                                 style="border-color:#9aa89b !important; background:#f5f7f5 !important;">
                                                <i class="bi bi-info-circle me-2" style="color:#9aa89b;"></i>
                                                <span style="font-size:13px;">
                                                    Review all details below. Click the
                                                    <i class="bi bi-pencil mx-1"></i>
                                                    pencil icon on any section to go back and edit.
                                                </span>
                                            </div>

                                            <div class="row g-4">

                                                {{-- Customer Card --}}
                                                <div class="col-md-6">
                                                    <div class="card h-100 border shadow-sm">
                                                        <div class="card-header review-card-header d-flex justify-content-between align-items-center py-2 rounded-top">
                                                            <span class="fw-semibold fs-6">
                                                                <i class="bi bi-person me-2"></i>Customer
                                                            </span>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-light py-0 px-2"
                                                                    onclick="goToStep(1)" title="Edit Customer">
                                                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                                                            </button>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                                                                <tr><td class="text-muted" style="width:45%">Name</td>
                                                                    <td class="fw-semibold" id="rv-name">—</td></tr>
                                                                <tr><td class="text-muted">Phone</td>
                                                                    <td id="rv-phone">—</td></tr>
                                                                <tr><td class="text-muted">Email</td>
                                                                    <td id="rv-email">—</td></tr>
                                                                <tr><td class="text-muted">TIN</td>
                                                                    <td id="rv-tin">—</td></tr>
                                                                <tr><td class="text-muted">Gender</td>
                                                                    <td id="rv-gender">—</td></tr>
                                                                <tr><td class="text-muted">Date of Birth</td>
                                                                    <td id="rv-dob">—</td></tr>
                                                                <tr><td class="text-muted">ID Number</td>
                                                                    <td id="rv-id-number">—</td></tr>
                                                                <tr><td class="text-muted">Postal Address</td>
                                                                    <td id="rv-postal">—</td></tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Policy / Vehicle Card --}}
                                                <div class="col-md-6">
                                                    <div class="card h-100 border shadow-sm">
                                                        <div class="card-header review-card-header d-flex justify-content-between align-items-center py-2 rounded-top">
                                                            <span class="fw-semibold fs-6">
                                                                @if($isMotor)
                                                                    <i class="bi bi-car-front me-2"></i>Vehicle / Policy
                                                                @else
                                                                    <i class="bi bi-file-earmark-text me-2"></i>Policy Details
                                                                @endif
                                                            </span>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-light py-0 px-2"
                                                                    onclick="goToStep({{ $isMotor ? 2 : 1 }})"
                                                                    title="Edit">
                                                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                                                            </button>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            @if($isMotor)
                                                            <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                                                                <tr><td class="text-muted" style="width:45%">Reg. Number</td>
                                                                    <td class="fw-semibold" id="rv-reg">—</td></tr>
                                                                <tr><td class="text-muted">Chassis No.</td>
                                                                    <td id="rv-chassis">—</td></tr>
                                                                <tr><td class="text-muted">Make</td>
                                                                    <td id="rv-make">—</td></tr>
                                                                <tr><td class="text-muted">Model</td>
                                                                    <td id="rv-model">—</td></tr>
                                                                <tr><td class="text-muted">Color</td>
                                                                    <td id="rv-color">—</td></tr>
                                                                <tr><td class="text-muted">Year of Manufacture</td>
                                                                    <td id="rv-year">—</td></tr>
                                                                <tr><td class="text-muted">Engine No.</td>
                                                                    <td id="rv-engine">—</td></tr>
                                                                <tr><td class="text-muted">Body Type</td>
                                                                    <td id="rv-body">—</td></tr>
                                                            </table>
                                                            @else
                                                            <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                                                                <tr><td class="text-muted" style="width:45%">Insurance</td>
                                                                    <td class="fw-semibold">{{ $coverage->product->insurance->name ?? '—' }}</td></tr>
                                                                <tr><td class="text-muted">Product</td>
                                                                    <td>{{ $coverage->product->name ?? '—' }}</td></tr>
                                                                <tr><td class="text-muted">Coverage</td>
                                                                    <td>{{ $coverage->name ?? '—' }}</td></tr>
                                                                <tr><td class="text-muted">Risk Code</td>
                                                                    <td>{{ $coverage->risk_code ?? '—' }}</td></tr>
                                                                <tr><td class="text-muted">Product Code</td>
                                                                    <td>{{ $coverage->product->code ?? '—' }}</td></tr>
                                                            </table>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Duration Card --}}
                                                <div class="col-md-6">
                                                    <div class="card h-100 border shadow-sm">
                                                        <div class="card-header review-card-header d-flex justify-content-between align-items-center py-2 rounded-top">
                                                            <span class="fw-semibold fs-6">
                                                                <i class="bi bi-calendar3 me-2"></i>Duration
                                                            </span>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-light py-0 px-2"
                                                                    onclick="goToStep({{ $isMotor ? 3 : 2 }})"
                                                                    title="Edit Duration">
                                                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                                                            </button>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                                                                <tr><td class="text-muted" style="width:45%">Start Date</td>
                                                                    <td id="rv-start-date">—</td></tr>
                                                                <tr><td class="text-muted">End Date</td>
                                                                    <td id="rv-end-date">—</td></tr>
                                                                <tr><td class="text-muted">Currency</td>
                                                                    <td id="rv-currency">—</td></tr>
                                                                <tr><td class="text-muted">Exchange Rate</td>
                                                                    <td id="rv-exchange-rate">—</td></tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Premium Card --}}
                                                <div class="col-md-6">
                                                    <div class="card h-100 border shadow-sm">
                                                        <div class="card-header review-card-header d-flex justify-content-between align-items-center py-2 rounded-top">
                                                            <span class="fw-semibold fs-6">
                                                                <i class="bi bi-calculator me-2"></i>Premium
                                                            </span>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-light py-0 px-2"
                                                                    onclick="goToStep({{ $isMotor ? 4 : 3 }})"
                                                                    title="Edit Premium">
                                                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                                                            </button>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                                                                <tr><td class="text-muted" style="width:55%">Sum Insured</td>
                                                                    <td class="fw-semibold" id="rv-sum-insured">—</td></tr>
                                                                <tr><td class="text-muted">Premium Rate</td>
                                                                    <td id="rv-premium-rate">—</td></tr>
                                                                <tr><td class="text-muted">Premium (excl. tax)</td>
                                                                    <td id="rv-excl-tax">—</td></tr>
                                                                <tr><td class="text-muted">VAT (18%)</td>
                                                                    <td id="rv-tax">—</td></tr>
                                                                <tr><td class="text-muted">Commission Rate</td>
                                                                    <td id="rv-commission">—</td></tr>
                                                                <tr><td class="fw-semibold">Total (incl. tax)</td>
                                                                    <td class="fw-bold text-primary" id="rv-total">—</td></tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($isMotor)
                                                {{-- Addons Card (Motor only) --}}
                                                <div class="col-12">
                                                    <div class="card border shadow-sm">
                                                        <div class="card-header review-card-header d-flex justify-content-between align-items-center py-2 rounded-top">
                                                            <span class="fw-semibold fs-6">
                                                                <i class="bi bi-puzzle me-2"></i>Selected Addons
                                                            </span>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-light py-0 px-2"
                                                                    onclick="goToStep(5)" title="Edit Addons">
                                                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                                                            </button>
                                                        </div>
                                                        <div class="card-body p-3" id="rv-addons">
                                                            <p class="text-muted mb-0" style="font-size:13px;">No addons selected.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- Preview Button --}}
                                                <div class="col-12 text-center">
                                                    <button type="button"
                                                            class="btn btn-outline-secondary px-4"
                                                            onclick="downloadQuotationPreview()"
                                                            style="border-color:#9aa89b;color:#9aa89b;">
                                                        <i class="bi bi-eye me-2"></i>Preview Quotation
                                                    </button>
                                                </div>

                                            </div>{{-- /row --}}

                                            <div class="d-flex justify-content-between mt-4">
                                                <button type="button" class="btn text-white"
                                                        style="background-color:#9aa89b" onclick="changeStep(-1)">
                                                    <i class="bi bi-arrow-left me-2"></i> Back
                                                </button>
                                                <button type="button" class="btn text-white"
                                                        style="background-color:#003153" onclick="changeStep(1)">
                                                    Proceed to Payment <i class="bi bi-arrow-right ms-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- /Review step --}}

                                        {{-- ── STEP 7 (Motor) / 5 (non-Motor): Payment ── --}}
                                        @include('kmj.quotation.create.payment')

                                        {{-- ── STEP 8 (Motor) / 6 (non-Motor): Finalize ── --}}
                                        @include('kmj.quotation.create.finalize')

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @include('kmj.quotation.model.search_customer')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // ── Constants from PHP ─────────────────────────────────────────
        const IS_MOTOR   = {{ $isMotor ? 'true' : 'false' }};
        const REVIEW_IDX = {{ $reviewIdx }};

        // ── Step navigation ────────────────────────────────────────────
        let currentStep    = 0;
        const stepCircles  = document.querySelectorAll('.step-bar .step');
        const stepContents = document.querySelectorAll('.step-content');
        const fillLine     = document.getElementById('fillLine');

        function updateUI() {
            stepCircles.forEach((circle, i) => {
                circle.classList.remove('active', 'completed');
                const wrapper = document.getElementById('step-wrapper-' + (i + 1));
                if (wrapper) wrapper.classList.remove('active-wrapper');
                if (i < currentStep)   circle.classList.add('completed');
                if (i === currentStep) {
                    circle.classList.add('active');
                    if (wrapper) wrapper.classList.add('active-wrapper');
                }
            });

            stepContents.forEach((panel, i) => {
                panel.classList.toggle('d-none', i !== currentStep);
            });

            const pct = stepCircles.length > 1
                ? (currentStep / (stepCircles.length - 1)) * 100 : 0;
            fillLine.style.width = pct + '%';
        }

        function goToStep(targetStep) {
            const idx = targetStep - 1;
            if (idx < 0 || idx >= stepContents.length) return;

            if (idx === REVIEW_IDX && typeof populateReview === 'function') {
                populateReview();
            }

            currentStep = idx;
            updateUI();
            document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function changeStep(direction) {
            const nextIdx = currentStep + direction;

            if (direction > 0) {
                if (currentStep === 0 && $('#customer_id').val() && parseInt($('#customer_id').val()) > 0) {
                    goToStep(nextIdx + 1);
                    return;
                }

                const panel = stepContents[currentStep];
                const requiredFields = panel.querySelectorAll('[required]');
                let allValid = true;

                requiredFields.forEach(field => {
                    field.style.borderColor = '';
                    const prev = field.parentNode.querySelector('.field-error');
                    if (prev) prev.remove();

                    const isEmpty = !field.value || (field.type === 'checkbox' && !field.checked);
                    if (isEmpty) {
                        allValid = false;
                        field.style.borderColor = 'red';
                        const msg = document.createElement('div');
                        msg.className  = 'field-error';
                        msg.style.cssText = 'color:red;font-size:12px;margin-top:2px;';
                        msg.textContent = 'This field is required';
                        field.parentNode.appendChild(msg);
                    }
                });

                if (!allValid) return;
            }

            goToStep(nextIdx + 1);
        }

        // ── Addon recalculation (Motor only) ──────────────────────────
        function fmtTZS(n) {
            return 'TZS ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function recalcAddons() {
            if (!IS_MOTOR) return;

            // Current base premium from the live hidden input (set by calculate())
            const basePremium = parseFloat(
                document.getElementById('h_total_premium_excluding_tax')?.value || 0
            );
            const taxRate = parseFloat(
                document.getElementById('h_tax_rate')?.value || 0.18
            );

            let addonsTotal   = 0;
            let selectedCount = 0;

            // First pass: update data-amount for PREMIUM type checkboxes
            // so their dynamic value reflects the current basePremium
            document.querySelectorAll('.addon-step-checkbox').forEach(cb => {
                if (cb.dataset.type === 'PREMIUM') {
                    const rate = parseFloat(cb.dataset.rate || 0);
                    const dynamicAmt = rate * basePremium;

                    // Update data-amount so it's correct when summed below
                    cb.dataset.amount = dynamicAmt.toFixed(4);

                    // Update the displayed amount in the table row
                    const amtSpan = document.getElementById('addon-amt-' + cb.value);
                    if (amtSpan) {
                        amtSpan.textContent = fmtTZS(dynamicAmt);
                    }
                }
            });

            // Second pass: sum only checked ones
            document.querySelectorAll('.addon-step-checkbox:checked').forEach(cb => {
                addonsTotal += parseFloat(cb.dataset.amount || 0);
                selectedCount++;
            });

            const exclTax = basePremium + addonsTotal;
            const taxAmt  = exclTax * taxRate;
            const inclTax = exclTax + taxAmt;

            const el = id => document.getElementById(id);
            if (el('addon-base-premium'))    el('addon-base-premium').textContent    = fmtTZS(basePremium);
            if (el('addon-addons-total'))    el('addon-addons-total').textContent    = fmtTZS(addonsTotal);
            if (el('addon-excl-tax'))        el('addon-excl-tax').textContent        = fmtTZS(exclTax);
            if (el('addon-tax-amount'))      el('addon-tax-amount').textContent      = fmtTZS(taxAmt);
            if (el('addon-grand-total'))     el('addon-grand-total').textContent     = fmtTZS(inclTax);
            if (el('selected-count-inline')) el('selected-count-inline').textContent = selectedCount + ' selected';
        }

        // Checkbox change: toggle highlight row + recalc
        // Note: uses "addon-row-X" to match the fixed HTML id above
        document.querySelectorAll('.addon-step-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const row = document.getElementById('addon-row-' + this.value);
                if (row) row.classList.toggle('addon-row-selected', this.checked);
                recalcAddons();
            });
        });


        // ── Review population ──────────────────────────────────────────
        function populateReview() {
            const gv = name => {
                const el = document.querySelector(`[name="${name}"]`);
                if (!el) return '—';
                return el.value || '—';
            };

            const gt = name => {
                const el = document.querySelector(`[name="${name}"]`);
                if (!el) return '—';
                if (el.tagName === 'SELECT') {
                    return el.options[el.selectedIndex]?.text || '—';
                }
                return el.value || '—';
            };

            const fmt = v => {
                const n = parseFloat(v);
                return isNaN(n) || n === 0 ? '—'
                    : 'TZS ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            };

            const set = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val || '—';
            };

            // ── Customer ──
            const existingName  = document.getElementById('customer_name')?.value;
            const existingPhone = document.getElementById('customer_phone')?.value;
            const existingEmail = document.getElementById('customer_email')?.value;

            set('rv-name',      existingName  || gv('name'));
            set('rv-phone',     existingPhone || gv('phone'));
            set('rv-email',     existingEmail || gv('email_address'));
            set('rv-tin',       gv('tin_number'));
            const gender = gv('gender');
            set('rv-gender',    gender === 'M' ? 'Male' : gender === 'F' ? 'Female' : '—');
            set('rv-dob',       gv('dob'));
            set('rv-id-number', gv('policy_holder_id_number'));
            set('rv-postal',    gv('postal_address'));

            @if($isMotor)
            set('rv-reg',     gv('registration_number'));
            set('rv-chassis', gv('chassis_number'));
            set('rv-make',    gv('make'));
            set('rv-model',   gv('model'));
            set('rv-color',   gv('color'));
            set('rv-year',    gv('year_of_manufacture'));
            set('rv-engine',  gv('engine_number'));
            set('rv-body',    gv('body_type'));
            @endif

            // ── Duration ──
            set('rv-start-date',    gv('cover_note_start_date'));
            set('rv-end-date',      document.getElementById('h_cover_note_end_date')?.value || '—');
            set('rv-currency',      gt('currency_id'));
            set('rv-exchange-rate', gv('exchange_rate'));

            // ── Premium — read from hidden inputs (synced by calculate()) ──
            const premRate = parseFloat(document.getElementById('h_premium_rate')?.value || 0);
            set('rv-sum-insured',  fmt(gv('sum_insured')));
            set('rv-premium-rate', isNaN(premRate) || premRate === 0 ? '—' : (premRate * 100).toFixed(2) + '%');
            set('rv-excl-tax',     fmt(document.getElementById('h_total_premium_excluding_tax')?.value));
            set('rv-tax',          fmt(document.getElementById('h_tax_amount')?.value));
            set('rv-commission',   (gv('commission_rate') || '0') + '%');
            set('rv-total',        fmt(document.getElementById('h_total_premium_including_tax')?.value));

            @if($isMotor)
            const checked  = document.querySelectorAll('.addon-step-checkbox:checked');
            const rvAddons = document.getElementById('rv-addons');
            if (rvAddons) {
                if (checked.length === 0) {
                    rvAddons.innerHTML = '<p class="text-muted mb-0" style="font-size:13px;">No addons selected.</p>';
                } else {
                    let html = '<div class="d-flex flex-wrap gap-2">';
                    checked.forEach(cb => {
                        const name = cb.dataset.name || cb.value;
                        const amt  = parseFloat(cb.dataset.amount || 0);
                        html += `<span class="badge rounded-pill py-2 px-3"
                            style="background:#e1f5ee;color:#0f6e56;border:1px solid #9fe1cb;font-size:12px;">
                            ${name} — TZS ${amt.toLocaleString('en-US',{minimumFractionDigits:2})}
                        </span>`;
                    });
                    html += '</div>';
                    rvAddons.innerHTML = html;
                }
            }
            @endif
        }

        // ── Init ───────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            updateUI();
            recalcAddons();
        });
    </script>

    {{-- ── Select2 & AJAX dropdowns ── --}}
    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%', placeholder: "Select an option", allowClear: true });

            $('#country').on('change', function () {
                var cId = $(this).val();
                $('#region').empty().append('<option value="">Select Region</option>');
                $('#district').empty().append('<option value="">Select District</option>');
                if (cId) {
                    $.ajax({
                        url: '/dash/countries/' + cId + '/regions', type: 'GET', dataType: 'json',
                        success: function (data) {
                            $.each(data, function (k, r) {
                                $('#region').append('<option value="' + r.id + '">' + r.name + '</option>');
                            });
                            $('#region').trigger('change.select2');
                        }
                    });
                }
            });

            $('#region').on('change', function () {
                var rId = $(this).val();
                if (rId) {
                    $.ajax({
                        url: '/dash/regions/' + rId + '/districts', type: 'GET', dataType: 'json',
                        success: function (data) {
                            $('#district').empty().append('<option value="">Select District</option>');
                            $.each(data, function (k, d) {
                                $('#district').append('<option value="' + d.id + '">' + d.name + '</option>');
                            });
                            $('#district').trigger('change.select2');
                        }
                    });
                } else {
                    $('#district').empty().append('<option value="">Select District</option>');
                    $('#district').trigger('change.select2');
                }
            });
        });
    </script>

    {{-- ── Date calculation ── --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.getElementById('cover_note_start_date');
            const durationSelect = document.getElementById('cover_note_duration_id');
            const endDateInput   = document.getElementById('cover_note_end_date');
            const hEndDate       = document.getElementById('h_cover_note_end_date');

            const today = new Date();
            const pad   = n => String(n).padStart(2, '0');
            const todayStr = `${today.getFullYear()}-${pad(today.getMonth()+1)}-${pad(today.getDate())}`;
            startDateInput.min   = todayStr;
            startDateInput.value = todayStr;

            function calculateEndDate() {
                const startDate = new Date(startDateInput.value);
                const months    = parseInt(durationSelect.options[durationSelect.selectedIndex]?.getAttribute('data-months'));
                if (startDate && months) {
                    const end = new Date(startDate);
                    end.setMonth(end.getMonth() + months);
                    end.setDate(end.getDate() - 1);
                    const endStr = `${end.getFullYear()}-${pad(end.getMonth()+1)}-${pad(end.getDate())}`;
                    if (endDateInput) endDateInput.value = endStr;
                    // ── Sync to hidden input for preview ──
                    if (hEndDate) hEndDate.value = endStr;
                } else {
                    if (endDateInput) endDateInput.value = '';
                    if (hEndDate)     hEndDate.value     = '';
                }
            }

            startDateInput.addEventListener('change', calculateEndDate);
            durationSelect.addEventListener('change', calculateEndDate);
            calculateEndDate();
        });
    </script>

    {{-- ── Premium calculation ── --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sumInsuredInput    = document.querySelector('input[name="sum_insured"]');
            const commissionRateInput = document.querySelector('input[name="commission_rate"]');
            const taxExemptSelect    = document.getElementById('is_tax_exempted');

            // Visible inputs that may exist in premium_calculation partial
            const premiumInput                  = document.querySelector('input[name="premium"]');
            const premiumRateInput              = document.querySelector('input[name="premium_rate"]');
            const taxRateInput                  = document.querySelector('input[name="tax_rate"]');
            const taxAmountInput                = document.querySelector('input[name="tax_amount"]');
            const totalPremiumIncludingTaxInput = document.querySelector('input[name="total_premium_including_tax"]');
            const totalPremiumExcludingTaxInput = document.querySelector('input[name="total_premium_excluding_tax"]');
            const commissionPaidInput           = document.querySelector('input[name="commission_paid"]');

            // Hidden inputs for preview POST
            const hPremiumRate   = document.getElementById('h_premium_rate');
            const hPremium       = document.getElementById('h_premium');
            const hTaxRate       = document.getElementById('h_tax_rate');
            const hTaxAmount     = document.getElementById('h_tax_amount');
            const hExclTax       = document.getElementById('h_total_premium_excluding_tax');
            const hInclTax       = document.getElementById('h_total_premium_including_tax');

            const coverageRate   = {{ $coverage->rate }};
            const minimumAmount  = {{ $coverage->minimum_amount }};
            const defaultTaxRate = 0.18;

            function syncHidden(el, value) {
                if (el) el.value = value;
            }

            function calculate() {
                const sumInsured    = parseFloat(sumInsuredInput?.value) || 0;
                const isTaxExempted = taxExemptSelect?.value === 'Y';
                const premiumRate   = coverageRate / 100;
                const premium       = sumInsured * premiumRate;
                const totalExTax    = premium > minimumAmount ? premium : minimumAmount;
                const taxAmount     = isTaxExempted ? 0 : totalExTax * defaultTaxRate;
                const totalIncTax   = totalExTax + taxAmount;
                const commissionRate = parseFloat(commissionRateInput?.value) || 0;
                const commissionPaid = (totalExTax * commissionRate) / 100;

                // ── Update visible inputs (from premium_calculation partial) ──
                syncHidden(premiumRateInput,              premiumRate.toFixed(4));
                syncHidden(premiumInput,                  premium.toFixed(2));
                syncHidden(taxRateInput,                  (isTaxExempted ? 0 : defaultTaxRate).toFixed(2));
                syncHidden(taxAmountInput,                taxAmount.toFixed(2));
                syncHidden(totalPremiumExcludingTaxInput, totalExTax.toFixed(2));
                syncHidden(totalPremiumIncludingTaxInput, totalIncTax.toFixed(2));
                syncHidden(commissionPaidInput,           commissionPaid.toFixed(2));

                // ── Sync to hidden inputs for preview POST ──
                syncHidden(hPremiumRate, premiumRate.toFixed(4));
                syncHidden(hPremium,     premium.toFixed(2));
                syncHidden(hTaxRate,     (isTaxExempted ? 0 : defaultTaxRate).toFixed(2));
                syncHidden(hTaxAmount,   taxAmount.toFixed(2));
                syncHidden(hExclTax,     totalExTax.toFixed(2));
                syncHidden(hInclTax,     totalIncTax.toFixed(2));

                recalcAddons();
            }

            if (sumInsuredInput)    sumInsuredInput.addEventListener('input', calculate);
            if (taxExemptSelect)    taxExemptSelect.addEventListener('change', calculate);
            if (commissionRateInput) commissionRateInput.addEventListener('input', calculate);

            // Run on load so hidden inputs are populated immediately
            calculate();
        });
    </script>

    {{-- ── Payment mode toggle ── --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentSelect = document.getElementById('payment_mode_id');
            const cashFields    = document.getElementById('cash-fields');
            const chequeFields  = document.getElementById('cheque-fields');
            const eftFields     = document.getElementById('eft-fields');

            if (paymentSelect) {
                paymentSelect.addEventListener('change', function () {
                    [cashFields, chequeFields, eftFields].forEach(d => { if(d) d.classList.add('d-none'); });
                    if (this.value === '1' && cashFields)        cashFields.classList.remove('d-none');
                    else if (this.value === '2' && chequeFields) chequeFields.classList.remove('d-none');
                    else if (this.value === '3' && eftFields)    eftFields.classList.remove('d-none');
                });
            }
        });
    </script>

    {{-- ── PDF Preview Side Panel ── --}}
    <div id="pdf-preview-overlay"
         style="display:none;position:fixed;top:0;right:0;width:100%;height:100%;z-index:9999;background:rgba(0,0,0,0.5);">

        <div id="pdf-preview-panel"
             style="position:absolute;top:0;right:0;width:55%;height:100%;background:#fff;
                    box-shadow:-4px 0 20px rgba(0,0,0,0.3);display:flex;flex-direction:column;">

            {{-- Panel Header --}}
            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:12px 16px;background:#003153;color:#fff;flex-shrink:0;">
                <span style="font-weight:600;font-size:14px;">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Quotation Preview
                </span>
                <div style="display:flex;gap:8px;">
                    <button type="button" onclick="downloadFromPreview()"
                            style="background:#9aa89b;border:none;color:#fff;padding:5px 14px;
                                   border-radius:4px;font-size:13px;cursor:pointer;">
                        <i class="bi bi-download me-1"></i>Download
                    </button>
                    <button type="button" onclick="closePdfPreview()"
                            style="background:transparent;border:1px solid rgba(255,255,255,0.4);
                                   color:#fff;padding:5px 12px;border-radius:4px;font-size:13px;cursor:pointer;">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Loading indicator --}}
            <div id="pdf-loading"
                 style="flex:1;display:flex;flex-direction:column;align-items:center;
                        justify-content:center;gap:12px;color:#555;">
                <div class="spinner-border" style="color:#003153;" role="status"></div>
                <span style="font-size:13px;">Generating preview...</span>
            </div>

            {{-- iFrame --}}
            <iframe id="pdf-preview-iframe"
                    style="flex:1;border:none;display:none;"
                    src="about:blank">
            </iframe>
        </div>
    </div>

    {{-- ── Preview Script (single, clean, no duplicate function) ── --}}
    <script>
        let pdfBlobUrl = null;

        function downloadQuotationPreview() {

            // ── Helper to get field value by name ──
            const gv = name => {
                const el = document.querySelector(`[name="${name}"]`);
                return el ? el.value : '';
            };
            const gi = id => {
                const el = document.getElementById(id);
                return el ? el.value : '';
            };

            // ── Build data object explicitly — never rely on FormData for dynamic fields ──
            const data = {
                // CSRF
                _token: document.querySelector('input[name="_token"]').value,

                // Hidden policy fields
                insuarer_id:   gv('insuarer_id'),
                coverage_id:   gv('coverage_id'),
                insurance_id:  gv('insurance_id'),
                product_id:    gv('product_id'),
                risk_code:     gv('risk_code'),
                product_code:  gv('product_code'),

                // Customer
                customer_id:   gi('customer_id'),
                customer_name: gi('customer_name'),
                customer_phone:gi('customer_phone'),
                customer_email:gi('customer_email'),

                // New customer fields (if existing customer not selected)
                name:                    gv('name'),
                phone:                   gv('phone'),
                email_address:           gv('email_address'),
                tin_number:              gv('tin_number'),
                postal_address:          gv('postal_address'),
                street:                  gv('street'),
                gender:                  gv('gender'),
                dob:                     gv('dob'),
                policy_holder_id_number: gv('policy_holder_id_number'),

                // Coverage/insurance labels (from hidden inputs)
                coverage_name:   gi('h_coverage_name'),
                coverage_rate:   gi('h_coverage_rate'),
                product_name:    gi('h_product_name'),
                insurance_type:  gi('h_insurance_type'),
                insurance_name:  gi('h_insurance_name'),
                insuarer_name:   gi('h_insuarer_name'),

                // Duration
                cover_note_duration_id: gv('cover_note_duration_id'),
                cover_note_start_date:  gv('cover_note_start_date'),
                cover_note_end_date:    gi('h_cover_note_end_date'),
                cover_note_desc:        gv('cover_note_desc'),
                operative_clause:       gv('operative_clause'),
                currency_id:            gv('currency_id'),
                exchange_rate:          gv('exchange_rate'),

                // Financial — from hidden inputs synced by calculate()
                sum_insured:                    gv('sum_insured'),
                premium_rate:                   gi('h_premium_rate'),
                premium:                        gi('h_premium'),
                tax_rate:                       gi('h_tax_rate'),
                tax_amount:                     gi('h_tax_amount'),
                total_premium_excluding_tax:    gi('h_total_premium_excluding_tax'),
                total_premium_including_tax:    gi('h_total_premium_including_tax'),
                commission_rate:                gv('commission_rate'),
                commission_paid:                gv('commission_paid'),
                is_tax_exempted:                gv('is_tax_exempted'),

                // Motor fields
                motor_category_id:   gv('motor_category_id'),
                motor_type_id:       gv('motor_type_id'),
                registration_number: gv('registration_number'),
                chassis_number:      gv('chassis_number'),
                make:                gv('make'),
                model:               gv('model'),
                model_number:        gv('model_number'),
                body_type:           gv('body_type'),
                color:               gv('color'),
                engine_number:       gv('engine_number'),
                engine_capacity:     gv('engine_capacity'),
                fuel_used:           gv('fuel_used'),
                number_of_axles:     gv('number_of_axles'),
                sitting_capacity:    gv('sitting_capacity'),
                year_of_manufacture: gv('year_of_manufacture'),
                tare_weight:         gv('tare_weight'),
                gross_weight:        gv('gross_weight'),
                motor_usage_id:      gv('motor_usage_id'),
                owner_category_id:   gv('owner_category_id'),

                // Payment
                payment_mode_id: gv('payment_mode_id'),
            };

            // ── addon_ids[] — checked checkboxes only ──
            data['addon_ids'] = [...document.querySelectorAll('input[name="addon_ids[]"]:checked')]
                .map(el => el.value);

            // ── items_covered rows (non-motor) ──
            const itemsCovered = [];
            document.querySelectorAll('#items-covered-wrapper .item-covered-row').forEach((row, index) => {
                const nameEl       = row.querySelector(`input[name="items_covered[${index}][name]"]`);
                const sumInsuredEl = row.querySelector(`input[name="items_covered[${index}][sum_insured]"]`);
                const premiumEl    = row.querySelector(`input[name="items_covered[${index}][premium]"]`);
                const name         = nameEl?.value?.trim() ?? '';
                if (name) {
                    itemsCovered.push({
                        name,
                        sum_insured: sumInsuredEl?.value ?? 0,
                        premium:     premiumEl?.value ?? 0,
                    });
                }
            });
            data['items_covered'] = JSON.stringify(itemsCovered);

            // ── Show loading overlay ──
            const overlay = document.getElementById('pdf-preview-overlay');
            const loading = document.getElementById('pdf-loading');
            const iframe  = document.getElementById('pdf-preview-iframe');
            overlay.style.display = 'block';
            loading.style.display = 'flex';
            iframe.style.display  = 'none';
            iframe.src = 'about:blank';

            fetch('{{ route("kmj.quotation.preview.download") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token,
                    'Accept': 'application/pdf',
                },
                body: JSON.stringify(data),
            })
            .then(response => {
                if (!response.ok) throw new Error('Server returned ' + response.status);
                return response.blob();
            })
            .then(blob => {
                if (pdfBlobUrl) window.URL.revokeObjectURL(pdfBlobUrl);
                pdfBlobUrl = window.URL.createObjectURL(blob);
                iframe.src            = pdfBlobUrl;
                loading.style.display = 'none';
                iframe.style.display  = 'block';
            })
            .catch(err => {
                loading.style.display = 'none';
                overlay.style.display = 'none';
                alert('Preview failed: ' + err.message);
                console.error('Preview error:', err);
            });
        }

         function downloadFromPreview() {
            if (!pdfBlobUrl) return;
            const a   = document.createElement('a');
            a.href     = pdfBlobUrl;
            a.download = 'Quotation-Preview.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
        }

        function closePdfPreview() {
            document.getElementById('pdf-preview-overlay').style.display = 'none';
            document.getElementById('pdf-preview-iframe').src = 'about:blank';
            if (pdfBlobUrl) {
                window.URL.revokeObjectURL(pdfBlobUrl);
                pdfBlobUrl = null;
            }
        }

        // Close on overlay background click
        document.getElementById('pdf-preview-overlay').addEventListener('click', function (e) {
            if (e.target === this) closePdfPreview();
        });
    </script>

@endsection
