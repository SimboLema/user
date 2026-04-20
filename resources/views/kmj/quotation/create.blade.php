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

        .autocomplete-item {
            padding: 8px;
        }

        .autocomplete-item:hover {
            background-color: #f0f0f0;
        }

        /* Additional styling for better UI */
        .step-bar {
            position: relative;
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding: 0 2rem;
        }

        .fill-line {
            position: absolute;
            top: 50%;
            left: 0;
            height: 4px;
            background: #003153;
            transform: translateY(-50%);
            z-index: 1;
            transition: width 0.3s ease;
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
        }

        .step.active {
            background: #003153;
            color: white;
        }

        .step.completed {
            background: #003153;
            color: white;
        }

        .step-title {
            color: #003153;
            border-bottom: 2px solid #003153;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .file-upload-card {
            transition: all 0.3s ease;
        }

        .file-upload-card.dragover {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }

        .file-preview {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }

        .file-remove {
            margin-left: auto;
            cursor: pointer;
            color: #dc3545;
        }
    </style>

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">


        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">

                <div class="app-container container-xxl mt-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-10 col-12">

                            <!-- Customer selection alert -->
                            <div id="selectedCustomerInfo" class="d-none mt-3" style="font-size: 1.25rem;">
                                <strong>Selected Customer:</strong>
                                <span id="selectedCustomerName"></span> |
                                <span id="selectedCustomerPhone"></span> |
                                <span id="selectedCustomerEmail"></span>
                            </div>

                            <div class="card card-flush">
                                <div class="card-body">
                                    <!-- Progress Bar -->
                                    <div class="step-bar" id="stepBar">
                                        <div class="fill-line" id="fillLine" style="width: 0%;"></div>


                                        @if ($coverage->product->insurance->id == 2)
                                            @for ($i = 1; $i <= 6; $i++)
                                                <div class="step {{ $i == 1 ? 'active' : '' }}">{{ $i }}</div>
                                            @endfor
                                        @else
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="step {{ $i == 1 ? 'active' : '' }}">{{ $i }}</div>
                                            @endfor
                                        @endif
                                    </div>


                                    <!-- Form -->
                                    <form method="post" action="{{ route('kmj.quotation.store') }}"
                                        enctype="multipart/form-data" novalidate>
                                        @csrf
                                        <input type="hidden" name="insuarer_id" value="{{ $insuarerId }}">
                                        <!-- Hidden fields for product information -->
                                        <input type="hidden" name="coverage_id" value="{{ $coverage->id }}">
                                        <input type="hidden" name="insurance_id"
                                            value="{{ $coverage->product->insurance->id }}">
                                        <input type="hidden" name="product_id" value="{{ $coverage->product->id }}">
                                        <input type="hidden" name="risk_code" value="{{ $coverage->risk_code }}">
                                        <input type="hidden" name="product_code" value="{{ $coverage->product->code }}">
                                        <input type="hidden" name="officer_name"
                                            value="{{ auth()->user()->name ?? 'System Officer' }}">
                                        <input type="hidden" name="officer_title" value="Sales Officer">


                                        <!-- Hidden fields for selected customer -->
                                        <input type="hidden" id="customer_id" name="customer_id">
                                        <input type="hidden" id="customer_name" name="customer_name">
                                        <input type="hidden" id="customer_phone" name="customer_phone">
                                        <input type="hidden" id="customer_email" name="customer_email">


                                        <!-- STEP 1: Customer Information -->
                                        @include('kmj.quotation.create.customer')

                                        @if ($coverage->product->insurance->id == 2)
                                            <!-- : Motor Information -->
                                            @include('kmj.quotation.create.motor')
                                        @endif

                                        <!-- STEP 2: Duration and Cover Note Information -->
                                        @include('kmj.quotation.create.duration_covernote')

                                        <!-- STEP 3: Premium Calculation -->
                                        @include('kmj.quotation.create.premium_calculation')

                                        <!-- Step 4: Payment Details -->
                                        @include('kmj.quotation.create.payment')

                                        <!-- Step 5: Finalize Quotation -->
                                        @include('kmj.quotation.create.finalize')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>


    <!--end::Content wrapper-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    {{--     search customer modal --}}
    @include('kmj.quotation.model.search_customer')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        // Step navigation
        let currentStep = 0;
        const steps = document.querySelectorAll(".step-bar .step");
        const stepContents = document.querySelectorAll(".step-content");
        const fillLine = document.getElementById("fillLine");

        function updateUI() {
            steps.forEach((step, index) => {
                step.classList.remove("active", "completed");
                if (index < currentStep) step.classList.add("completed");
                if (index === currentStep) step.classList.add("active");
            });

            stepContents.forEach((content, index) => {
                content.classList.toggle("d-none", index !== currentStep);
            });

            fillLine.style.width = ((currentStep) / (steps.length - 1)) * 100 + "%";
        }

        function changeStep(n) {
            const currentStepEl = stepContents[currentStep];

            if (n > 0) {

                // ✅ Kama ni Step 1 na customer tayari yupo, ruka validation kabisa
                if (currentStep === 0 && $('#customer_id').val() && parseInt($('#customer_id').val()) > 0) {
                    currentStep += n;
                    updateUI();
                    document.querySelector('.card-body').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    return;
                }


                const requiredFields = currentStepEl.querySelectorAll('[required]');
                let allValid = true;

                requiredFields.forEach(field => {
                    // Reset previous error
                    let errorEl = field.nextElementSibling;
                    if (errorEl && errorEl.classList.contains('field-error')) {
                        errorEl.remove();
                    }
                    field.style.borderColor = ''; // remove red border


                    if (!field.value || (field.type === 'checkbox' && !field.checked)) {
                        allValid = false;

                        // Show red border
                        field.style.borderColor = 'red';

                        // Add error message below field
                        if (!errorEl || !errorEl.classList.contains('field-error')) {
                            const errorMsg = document.createElement('div');
                            errorMsg.classList.add('field-error');
                            errorMsg.style.color = 'red';
                            errorMsg.style.fontSize = '12px';
                            errorMsg.textContent = 'This field is required';
                            field.parentNode.appendChild(errorMsg);
                        }
                    }
                });

                if (!allValid) {
                    // Stop going to next step
                    return;
                }
            }

            // Update step index
            const newStep = currentStep + n;
            if (newStep >= 0 && newStep < stepContents.length) {
                currentStep = newStep;
                updateUI();
                document.querySelector('.card-body').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    </script>


    <script>
        $(document).ready(function() {
            // Initialize all select2 elements
            $('.select2').select2({
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });

            // When selecting Country → Load Regions
            $('#country').on('change', function() {
                var countryId = $(this).val();

                $('#region').empty().append('<option value="">Select Region</option>');
                $('#district').empty().append('<option value="">Select District</option>');

                if (countryId) {
                    $.ajax({
                        url: '/dash/countries/' + countryId + '/regions',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, region) {
                                $('#region').append('<option value="' + region.id +
                                    '">' + region.name + '</option>');
                            });
                            $('#region').trigger('change.select2');
                        }
                    });
                } else {
                    $('#region').trigger('change.select2');
                    $('#district').trigger('change.select2');
                }
            });



            $('#region').on('change', function() {
                var regionId = $(this).val();
                if (regionId) {
                    $.ajax({
                        url: '/dash/regions/' + regionId + '/districts',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#district').empty();
                            $('#district').append('<option value="">Select District</option>');
                            $.each(data, function(key, district) {
                                $('#district').append('<option value="' + district.id +
                                    '">' + district.name + '</option>');
                            });
                            $('#district').trigger('change.select2'); // refresh select2
                        }
                    });
                } else {
                    $('#district').empty();
                    $('#district').append('<option value="">Select District</option>');
                    $('#district').trigger('change.select2');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const startDateInput = document.getElementById('cover_note_start_date');
            const durationSelect = document.getElementById('cover_note_duration_id');
            const endDateInput = document.getElementById('cover_note_end_date');

            // Set start date minimum to tomorrow
            const today = new Date();

            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');

            const tomorrowStr = `${yyyy}-${mm}-${dd}`;
            startDateInput.min = tomorrowStr;
            startDateInput.value = tomorrowStr; // default = tomorrow

            // Function to calculate end date
            function calculateEndDate() {
                const startDate = new Date(startDateInput.value);
                const selectedOption = durationSelect.options[durationSelect.selectedIndex];
                const months = parseInt(selectedOption.getAttribute('data-months'));

                if (startDate && months) {
                    const endDate = new Date(startDate);
                    endDate.setMonth(endDate.getMonth() + months);
                    endDate.setDate(endDate.getDate() - 1); // minus 1 day

                    const dd = String(endDate.getDate()).padStart(2, '0');
                    const mm = String(endDate.getMonth() + 1).padStart(2, '0');
                    const yyyy = endDate.getFullYear();

                    endDateInput.value = `${yyyy}-${mm}-${dd}`;
                } else {
                    endDateInput.value = '';
                }
            }

            // Event listeners
            startDateInput.addEventListener('change', calculateEndDate);
            durationSelect.addEventListener('change', calculateEndDate);

            // Initial calculation
            calculateEndDate();

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sumInsuredInput = document.querySelector('input[name="sum_insured"]');
            const premiumRateInput = document.querySelector('input[name="premium_rate"]');
            const taxRateInput = document.querySelector('input[name="tax_rate"]');
            const taxAmountInput = document.querySelector('input[name="tax_amount"]');
            const totalPremiumIncludingTaxInput = document.querySelector(
                'input[name="total_premium_including_tax"]');
            const totalPremiumExcludingTaxInput = document.querySelector(
                'input[name="total_premium_excluding_tax"]');
            const premiumIncludingTaxInput = document.querySelector('input[name="premium_including_tax"]');
            const premiumExcludingTaxEquivalentInput = document.querySelector(
                'input[name="premium_excluding_tax_equivalent"]');
            const commissionRateInput = document.querySelector('input[name="commission_rate"]');
            const commissionPaidInput = document.querySelector('input[name="commission_paid"]');
            const taxExemptSelect = document.getElementById('is_tax_exempted');

            // Hizi values zinatoka PHP (Coverage)
            const coverageRate = {{ $coverage->rate }};
            const minimumAmount = {{ $coverage->minimum_amount }};
            const defaultTaxRate = 0.18;

            // Event: inapohesabu automatically ukijaza Sum Insured
            function calculatePremium() {
                const sumInsured = parseFloat(sumInsuredInput.value) || 0;
                const isTaxExempted = taxExemptSelect.value === 'Y';
                const taxRate = isTaxExempted ? 0 : defaultTaxRate;

                // Step 1: Premium = sumInsured * (rate / 100)
                const premium = sumInsured * (coverageRate / 100);

                // Step 2: Total Premium Excluding Tax = premium or minimum
                const totalExclTax = premium > minimumAmount ? premium : minimumAmount;

                // Step 3: Tax Amount = totalExclTax * taxRate
                const taxAmount = totalExclTax * taxRate;

                // Step 4: Total Premium Including Tax
                const totalInclTax = totalExclTax + taxAmount;

                // Step 5: Update fields
                premiumRateInput.value = (coverageRate / 100);
                taxRateInput.value = taxRate.toFixed(2);
                taxAmountInput.value = taxAmount.toFixed(2);
                totalPremiumExcludingTaxInput.value = totalExclTax.toFixed(2);
                totalPremiumIncludingTaxInput.value = totalInclTax.toFixed(2);
                premiumIncludingTaxInput.value = totalInclTax.toFixed(2);
                premiumExcludingTaxEquivalentInput.value = totalExclTax.toFixed(2);

                // Commission (optional logic)
                const commissionRate = parseFloat(commissionRateInput.value) || 0;
                const commissionPaid = (totalExclTax * commissionRate) / 100;
                commissionPaidInput.value = commissionPaid.toFixed(2);
            }

            // Trigger calculation when sum_insured changes
            sumInsuredInput.addEventListener('input', calculatePremium);

            // Also update when tax exemption changes
            taxExemptSelect.addEventListener('change', calculatePremium);

            // Also update when commission rate changes
            commissionRateInput.addEventListener('input', calculatePremium);
        });
    </script>

    {{-- 💡 Add JavaScript section --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sumInsuredInput = document.querySelector('input[name="sum_insured"]');
            const premiumInput = document.querySelector('input[name="premium"]');
            const premiumRateInput = document.querySelector('input[name="premium_rate"]');
            const taxRateInput = document.querySelector('input[name="tax_rate"]');
            const taxAmountInput = document.querySelector('input[name="tax_amount"]');
            const totalPremiumExTaxInput = document.querySelector('input[name="total_premium_excluding_tax"]');
            const totalPremiumIncTaxInput = document.querySelector('input[name="total_premium_including_tax"]');
            const isTaxExemptedSelect = document.getElementById('is_tax_exempted');

            const rate = {{ $coverage->rate }};
            const minAmount = {{ $coverage->minimum_amount }};
            const defaultTaxRate = 0.18;

            function calculate() {
                const sumInsured = parseFloat(sumInsuredInput.value) || 0;
                const isTaxExempted = isTaxExemptedSelect.value === 'Y';
                const premiumRate = rate / 100;
                const premium = sumInsured * premiumRate;

                // total premium excluding tax
                const totalExTax = premium > minAmount ? premium : minAmount;

                // tax amount
                const taxAmount = isTaxExempted ? 0 : totalExTax * defaultTaxRate;

                // total including tax
                const totalIncTax = totalExTax + taxAmount;

                // update fields
                premiumRateInput.value = premiumRate.toFixed(4);
                premiumInput.value = premium.toFixed(2);
                totalPremiumExTaxInput.value = totalExTax.toFixed(2);
                taxAmountInput.value = taxAmount.toFixed(2);
                totalPremiumIncTaxInput.value = totalIncTax.toFixed(2);
            }

            // run when user types
            sumInsuredInput.addEventListener('input', calculate);
            isTaxExemptedSelect.addEventListener('change', calculate);
        });
    </script>

    {{-- JavaScript to handle dynamic fields --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentSelect = document.getElementById('payment_mode_id');
            const cashFields = document.getElementById('cash-fields');
            const chequeFields = document.getElementById('cheque-fields');
            const eftFields = document.getElementById('eft-fields');

            paymentSelect.addEventListener('change', function() {
                const value = this.value;

                // Hide all first
                [cashFields, chequeFields, eftFields].forEach(div => div.classList.add('d-none'));

                // Show based on selected mode
                if (value === '1') {
                    cashFields.classList.remove('d-none');
                } else if (value === '2') {
                    chequeFields.classList.remove('d-none');
                } else if (value === '3') {
                    eftFields.classList.remove('d-none');
                }
            });
        });
    </script>

@endsection
