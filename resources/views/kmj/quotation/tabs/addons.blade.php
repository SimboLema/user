@extends('kmj.layouts.app')

@section('title', 'Addons')

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

        

        .addon-row-selected {
            background-color: #f1f8ff !important;
        }

        .addon-row-selected td {
            color: #003153 !important;
        }

        .addon-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #003153;
        }

        .summary-card {
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

        .summary-line:last-child {
            border-bottom: none;
        }

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
    </style>

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card">
                    <div class="card-body pt-6">

                        @include('kmj.quotation.components.tabs-nav')

                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center mt-5 p-5">
                                <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                                <div class="d-flex flex-column">
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('kmj.quotation.addons.save', $quotation->id) }}" method="POST">
                            @csrf

                            <div class="row g-5 mt-2">

                                {{-- LEFT: Addon Table --}}
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header card-header-stretch">
                                            <div class="card-title">
                                                <h3 class="m-0 text-gray-800">Available Addons</h3>
                                            </div>
                                            <div class="card-toolbar">
                                                <span class="badge badge-light-primary fs-7 fw-bold" id="selected-count">0 selected</span>
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
                                                        @forelse ($addons as $addon)
                                                            @php
                                                                $savedAddon = $quotation->quotationAddons->firstWhere('addon_product_id', $addon->id);
                                                                $isChecked  = !is_null($savedAddon);

                                                                if ($addon->amount_type === 'PREMIUM') {
                                                                    $calculatedAmount = $addon->rate * $quotation->premium_before_discount;
                                                                    $typeLabel        = number_format($addon->rate * 100, 2) . '% of base';
                                                                } else {
                                                                    $calculatedAmount = $addon->amount;
                                                                    $typeLabel        = 'Flat amount';
                                                                }
                                                            @endphp
                                                            <tr class="text-gray-600 fs-6 fw-semibold addon-row {{ $isChecked ? 'addon-row-selected' : '' }}"
                                                                id="row-{{ $addon->id }}">
                                                                <td class="text-center">
                                                                    <input
                                                                        type="checkbox"
                                                                        class="addon-checkbox"
                                                                        name="addon_ids[]"
                                                                        value="{{ $addon->id }}"
                                                                        data-amount="{{ $calculatedAmount }}"
                                                                        {{ $isChecked ? 'checked' : '' }}
                                                                    >
                                                                </td>
                                                                <td>
                                                                    <span class="text-gray-800 fw-bold d-block">{{ $addon->name }}</span>
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
                                                                    <span class="text-gray-800 fw-bold fs-6">
                                                                        TZS {{ number_format($calculatedAmount, 2) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center text-gray-500 py-10">
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

                                {{-- RIGHT: Premium Summary --}}
                                <div class="col-lg-4">
                                    <div class="card summary-card">
                                        <div class="card-header card-header-stretch">
                                            <div class="card-title">
                                                <h3 class="m-0 text-gray-800">Premium Summary</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">

                                            <div class="summary-line">
                                                <span class="text-gray-600 fw-semibold fs-6">Base Premium</span>
                                                <span class="text-gray-800 fw-bold fs-6">
                                                    TZS {{ number_format($quotation->premium_before_discount, 2) }}
                                                </span>
                                            </div>

                                            <div class="summary-line">
                                                <span class="text-gray-600 fw-semibold fs-6">Addons Subtotal</span>
                                                <span class="text-primary fw-bold fs-6" id="addons-total">TZS 0.00</span>
                                            </div>

                                            <div class="summary-line">
                                                <span class="text-gray-600 fw-semibold fs-6">Premium (excl. tax)</span>
                                                <span class="text-gray-800 fw-bold fs-6" id="excl-tax">
                                                    TZS {{ number_format($quotation->total_premium_excluding_tax, 2) }}
                                                </span>
                                            </div>

                                            <div class="summary-line">
                                                <span class="text-gray-600 fw-semibold fs-6">VAT (18%)</span>
                                                <span class="text-gray-800 fw-bold fs-6" id="tax-amount">
                                                    TZS {{ number_format($quotation->tax_amount, 2) }}
                                                </span>
                                            </div>

                                            <div class="summary-total mt-3">
                                                <span class="text-gray-800 fw-bolder fs-5">Total (incl. tax)</span>
                                                <span class="fw-bolder fs-3 text-primary" id="grand-total">
                                                    TZS {{ number_format($quotation->total_premium_including_tax, 2) }}
                                                </span>
                                            </div>

                                            <div class="separator my-5"></div>

                                            <button type="submit" class="btn btn-light w-100">
                                                <i class="ki-duotone ki-check fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                                Save Addons &amp; Update Premium
                                            </button>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const basePremium = {{ $quotation->premium_before_discount }};
        const taxRate     = {{ $quotation->tax_rate }};

        function fmt(n) {
            return 'TZS ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function recalculate() {
            let addonsTotal = 0;
            let selectedCount = 0;

            document.querySelectorAll('.addon-checkbox:checked').forEach(cb => {
                addonsTotal += parseFloat(cb.dataset.amount);
                selectedCount++;
            });

            const exclTax = basePremium + addonsTotal;
            const taxAmt  = exclTax * taxRate;
            const inclTax = exclTax + taxAmt;

            document.getElementById('addons-total').textContent = fmt(addonsTotal);
            document.getElementById('excl-tax').textContent     = fmt(exclTax);
            document.getElementById('tax-amount').textContent   = fmt(taxAmt);
            document.getElementById('grand-total').textContent  = fmt(inclTax);
            document.getElementById('selected-count').textContent = selectedCount + ' selected';
        }

        document.querySelectorAll('.addon-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const row = document.getElementById('row-' + this.value);
                row.classList.toggle('addon-row-selected', this.checked);
                recalculate();
            });
        });

        // Run on load to reflect already-saved addons
        recalculate();
    </script>

@endsection
