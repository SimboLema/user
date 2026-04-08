@extends('kmj.layouts.app')

@section('title', 'Fleet Motor')

@section('content')
    @include('kmj.layouts.partials.datatable')

    <style>
        .card {
            margin-bottom: 20px;
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .json-preview {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">





        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">

                <form method="post" action="{{ route('kmj.quotation.fleet.store') }}" enctype="multipart/form-data"
                    novalidate>
                    @csrf

                    <div class="container-fluid">
                        <div class="card p-4">

                            <h4><i class="bi bi-people me-2"></i>Search Customer</h4>

                            {{-- search customer --}}

                            <div class="d-flex flex-row align-items-end">
                                <div class="text-end mb-3 col-md-6" style="padding-right:10px">
                                    <!-- Select2 Dropdown -->
                                    <select class="form-select customer-select2" id="customerSelect2" name="customer_id"
                                        required>
                                        <option value="">Search customer by name, phone, email or ID...</option>
                                    </select>

                                    <!-- Hidden spinner inside Select2 -->
                                    <div id="select2Spinner"
                                        style="display:none; position:absolute; right:10px; top:38px; z-index:1050;">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-sm text-white" style="background-color: #003153"
                                        data-bs-toggle="modal" data-bs-target="#CustomerInfo">
                                        <i class="fas fa-plus"></i> New Customer
                                    </button>
                                </div>

                            </div>
                            <h4><i class="bi bi-truck me-2"></i>Fleet Cover Note Information</h4>

                            {{-- ================= FLEET HEADER ================= --}}
                            <div class="row g-3 mt-1">
                                {{-- <div class="col-md-3">
                                    <label class="form-label required-field">Fleet ID</label>
                                    <input type="text" class="form-control" id="fleet_id" name="fleet_id"
                                        placeholder="Enter Fleet ID">
                                </div> --}}

                                {{-- <div class="col-md-3">
                                    <label class="form-label required-field">Cover Note Type</label>

                                    <select class="form-select" name="cover_note_type_id" id="cover_note_type_id" required>
                                        <option value="">Select Cover Note Type</option>
                                        @foreach ($coverNoteTypes as $coverNoteType)
                                            <option value="{{ $coverNoteType->id }}">{{ $coverNoteType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <div class="col-md-3">
                                    <label class="form-label required-field">Fleet Type</label>
                                    <select class="form-select" name="fleet_type" id="fleet_type" required>
                                        <option selected value="1">New</option>
                                        <option value="2">Addition</option>
                                    </select>
                                </div> --}}
                                <div class="col-md-3">
                                    <label class="form-label required-field">Fleet Size</label>
                                    <input type="number" class="form-control" id="fleet_size" name="fleet_size"
                                        placeholder="e.g. 50">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Comprehensive Insured</label>
                                    <input type="number" class="form-control" id="comprehensive_insured"
                                        name="comprehensive_insured" placeholder="e.g. 20">
                                </div>
                                {{-- </div>

                            <div class="row g-3 mt-1"> --}}
                                {{-- <div class="col-md-3">
                                    <label class="form-label">Sale Point Code</label>
                                    <input type="text" class="form-control" id="sale_point_code" name="sale_point_code"
                                        value="SP235" placeholder="e.g. SP001">
                                </div> --}}

                                <div class="col-md-3">
                                    <label class="form-label">Duration (months)</label>
                                    {{-- <input type="number" class="form-control" id="cover_note_duration" placeholder="e.g. 12"> --}}
                                    <select class="form-select" name="cover_note_duration_id" id="cover_note_duration_id"
                                        required>
                                        <option value="">Select Cover Note Type</option>
                                        @foreach ($coverNoteDurations as $coverNoteDuration)
                                            <option value="{{ $coverNoteDuration->id }}"
                                                data-months="{{ $coverNoteDuration->months }}">
                                                {{ $coverNoteDuration->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Cover Note Start Date</label>
                                    {{-- <input type="date" class="form-control" id="cover_note_start_date"> --}}
                                    <input class="form-control" type="date" name="cover_note_start_date"
                                        id="cover_note_start_date">

                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Cover Note End Date</label>
                                    {{-- <input type="date" class="form-control" id="cover_note_start_date"> --}}
                                    <input class="form-control" type="date" name="cover_note_end_date"
                                        id="cover_note_end_date" disabled>

                                </div>
                                {{-- </div>

                            <div class="row g-3 mt-1"> --}}
                                <div class="col-md-3">
                                    <label class="form-label">Currency Code</label>
                                    {{-- <input type="text" class="form-control" id="currency_code" placeholder="e.g. TZS"> --}}
                                    <select class="form-select" name="currency_id" id="currency_id" required>
                                        <option value="">Select Currency Code</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Payment Mode</label>
                                    {{-- <input type="text" class="form-control" id="currency_code" placeholder="e.g. TZS"> --}}
                                    <select class="form-select" name="payment_mode_id" id="payment_mode_id" required>
                                        <option value="">Select Payment Mode</option>
                                        @foreach ($paymentModes as $paymentMode)
                                            <option value="{{ $paymentMode->id }}">{{ $paymentMode->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Exchange Rate</label>
                                    <input type="number" step="0.01" class="form-control" id="exchange_rate"
                                        name="exchange_rate" value="1" placeholder="e.g. 2500">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Commission Paid</label>
                                    <input type="number" step="0.01" class="form-control" id="commission_paid"
                                        name="commission_paid" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Commission Rate</label>
                                    <input type="number" step="0.01" class="form-control" id="commission_rate"
                                        name="commission_rate" value="0">
                                </div>



                                <div class="col-md-3">
                                    <label class="form-label">Product</label>
                                    <select class="form-select" name="product_id" id="product_id" required>
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Coverage</label>
                                    <select class="form-select" name="coverage_id" id="coverage_id" required>
                                        <option value="">Select Coverage</option>
                                    </select>
                                </div>

                            </div>
                        </div>





                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn text-white" style="background-color: #003153"
                                id="submitBtn">
                                <i class="bi bi-check-circle me-2 text-white"></i>
                                <span id="submitText">Submit Quotation</span>
                                <span id="submitSpinner" class="spinner-border spinner-border-sm text-light ms-2"
                                    style="display: none;"></span>
                            </button>
                        </div>



                </form>




                <div class="mb-4"></div>

                <div class="col-xl-12">

                    <div class="card card-flush h-md-100">


                        <div class="card-body pt-6">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table id="myTable"
                                    class="table align-middle table-row-bordered table-row-solid gy-4 gs-9"
                                    style="padding-top:10px;">
                                    <!--begin::Table head-->
                                    <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                        <tr>
                                            <th class="min-w-100px text-center">sno</th>
                                            <th class="min-w-100px text-center">Client</th>
                                            <th class="min-w-100px text-center">CoverNoteRef</th>
                                            <th class="min-w-100px text-center">Type</th>
                                            <th class="min-w-100px text-center">Payment</th>
                                            {{-- <th class="min-w-100px text-center">Premium</th> --}}
                                            <th class="min-w-100px text-center">Created At</th>
                                            <th class="min-w-100px text-center">Status</th>
                                            <th class="min-w-100px text-center">Actions</th>

                                        </tr>
                                    </thead>
                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach ($quotations as $index => $quotation)
                                            <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ ucwords(strtolower($quotation->customer->name)) }}</td>
                                                <td>{{ $quotation->cover_note_reference ?? '-' }}</td>
                                                <td>{{ ucwords(strtolower($quotation->coverage->product->insurance->name)) }}
                                                </td>
                                                <td>{{ ucwords(strtolower($quotation->paymentMode->name)) }}</td>
                                                {{-- <td>{{ number_format($quotation->total_premium_including_tax) }}

                                                </td> --}}

                                                <td>{{ \Carbon\Carbon::parse($quotation->created_at)->format('d M Y, h:i A') }}
                                                </td>

                                                <td>
                                                    @if ($quotation->status === 'pending')
                                                        <span
                                                            class="badge border border-warning text-success d-inline-block text-center"
                                                            style="width: auto; color: orange !important;">
                                                            Awaiting receipt
                                                        </span>
                                                    @elseif($quotation->status === 'success')
                                                        <span
                                                            class="badge border border-success text-success d-inline-block text-center"
                                                            style="width: auto; color: green !important;">
                                                            Risknote Issued
                                                        </span>
                                                    @elseif($quotation->status === 'cancelled')
                                                        <span
                                                            class="badge border border-danger text-danger d-inline-block text-center"
                                                            style="width: auto; color: red !important;">
                                                            Cancelled
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span>
                                                        <a href="{{ route('kmj.quotation.show', $quotation->id) }}"
                                                            class="btn btn-sm"
                                                            style="background-color: #003153; color: white; padding: 4px 8px; font-size: 10px; width: auto;"
                                                            title="View More">
                                                            <small>View More</small>
                                                        </a>
                                                    </span>
                                                    {{-- <span>
                                                        <button class="btn btn-sm"
                                                            style="background-color: #003153; color: white; padding: 4px 8px; font-size: 10px;"
                                                            data-bs-toggle="modal" data-bs-target="#reinsuranceModal"
                                                            data-quotation-id="{{ $quotation->id }}">
                                                            <small>Reinsurance</small>
                                                        </button>
                                                    </span> --}}



                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                    </div>
                    <!--end::Table widget 14-->


                </div>







            </div>


        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

    </div>
    <!--end::Content wrapper-->

    @include('kmj.customer.modal.save_customer')




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize all select2 elements
            $('.select2').select2({
                dropdownParent: $('#CustomerInfo'),
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
        $(document).ready(function() {
            $('#customerSelect2').select2({
                placeholder: "Search customer...",
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '/dash/customers/search',
                    dataType: 'json',
                    delay: 400,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(customer => ({
                                id: customer.id,
                                text: `${customer.name} | ${customer.phone || 'No phone'} | ${customer.email_address || 'No email'}`
                            }))
                        };
                    },
                    beforeSend: function() {
                        $('#select2Spinner').show();
                    },
                    complete: function() {
                        $('#select2Spinner').hide();
                    }
                },
                templateResult: function(item) {
                    if (item.loading) return item.text;
                    return $(`<span><strong>${item.text.split(' | ')[0]}</strong><br>
                      <small class="text-muted">${item.text.split(' | ').slice(1).join(' • ')}</small></span>`);
                },
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            // Optional: Reload recent customers on focus
            $('#customerSelect2').on('select2:open', function() {
                if ($('.select2-results__options').is(':empty')) {
                    $.get('/dash/customers/search', {
                        q: ''
                    }, function(data) {
                        const select = $('#customerSelect2');
                        select.empty().append('<option value="">Search customer...</option>');
                        data.forEach(c => {
                            select.append(
                                `<option value="${c.id}">${c.name} | ${c.phone || '-'} | ${c.email_address || '-'}</option>`
                            );
                        });
                    });
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

    {{-- save fleet --}}
    <script>
        document.querySelector('form[action="{{ route('kmj.quotation.fleet.store') }}"]').addEventListener('submit',
            function(
                e) {

                e.preventDefault();
                console.log('✅ Submit event captured!');
                const submitBtn = document.getElementById('submitBtn');
                const submitText = document.getElementById('submitText');
                const submitSpinner = document.getElementById('submitSpinner');

                // Prevent double submit
                if (this.dataset.submitted) {
                    e.preventDefault();
                    return false;
                }

                this.dataset.submitted = true;

                // Show spinner before actual submit
                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                submitSpinner.style.display = 'inline-block';

                // Allow spinner to render
                setTimeout(() => {
                    this.submit(); // submit form after spinner is visible
                }, 50);

            });
    </script>

    <script>
        const products = @json($products);

        document.getElementById('product_id').addEventListener('change', function() {
            const productId = this.value;
            const coverageSelect = document.getElementById('coverage_id');

            // Clear existing coverages
            coverageSelect.innerHTML = '<option value="">Select Coverage</option>';

            if (!productId) return;

            // Tafuta product iliyochaguliwa
            const selectedProduct = products.find(p => p.id == productId);

            if (selectedProduct && selectedProduct.coverages) {
                selectedProduct.coverages.forEach(cov => {
                    const opt = document.createElement('option');
                    opt.value = cov.id;
                    opt.textContent = cov.risk_name;
                    coverageSelect.appendChild(opt);
                });
            }
        });
    </script>



    <style>
        /* Navy Blue + White + Black Theme */
        .customer-select2+.select2-container {
            width: 100% !important;
        }

        /* Input Field */
        .select2-selection {
            /* background-color: #003153 !important; */
            border: 2px solid #004a80 !important;
            color: #001f36;
            border-radius: 12px !important;
            height: 48px !important;
            box-shadow: 0 4px 15px rgba(0, 49, 83, 0.4) !important;
            transition: all 0.3s ease !important;
        }

        .select2-selection:hover {
            border-color: #00d4ff !important;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.5) !important;
        }

        /* Placeholder & Text */
        .select2-selection__placeholder {
            /* color: #a0d8ff !important; */
            font-style: italic;
        }

        .select2-selection__rendered {
            /* color: white !important; */
            font-weight: 500;
            padding-left: 16px !important;
            line-height: 44px !important;
        }

        /* Arrow */
        .select2-selection__arrow {
            top: 12px !important;
            right: 12px !important;
        }

        .select2-selection__arrow b {
            border-color: #00d4ff transparent transparent transparent !important;
        }

        /* Dropdown */
        .select2-dropdown {
            background-color: #001f36 !important;
            border: 2px solid #004a80 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
            margin-top: 5px !important;
        }

        .select2-results__option {
            color: #e0f4ff !important;
            padding: 10px 16px !important;
            border-bottom: 1px solid #003a66 !important;
        }

        /* .select2-results__option--highlighted {
                                                                                                                                                                background: linear-gradient(135deg, #004a80, #0066aa) !important;
                                                                                                                                                                color: white !important;
                                                                                                                                                                font-weight: bold;
                                                                                                                                                            } */

        /* Loading */
        /* .select2-results__option-loading {
                                                                                                                                                                color: #00d4ff !important;
                                                                                                                                                                font-style: italic;
                                                                                                                                                            } */

        /* Scrollbar */
        .select2-results__options::-webkit-scrollbar {
            width: 8px;
        }

        .select2-results__options::-webkit-scrollbar-track {
            background: #001f36;
        }

        .select2-results__options::-webkit-scrollbar-thumb {
            background: #004a80;
            border-radius: 4px;
        }
    </style>
@endsection
