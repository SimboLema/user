<div class="modal fade" id="InsuranceType" tabindex="-1" aria-labelledby="InsuranceTypeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">Select Insurance Type</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('kmj.quotation.getQuotation') }}" method="GET">
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Insuarer name:</label>
                        <select class="form-select select2" id="insuarerSelect" name="insuarer_id" required>
                            <option value="">-- Select Insuarer --</option>
                            @foreach ($insuarers as $insuarer)
                                <option value="{{ $insuarer->id }}">{{ $insuarer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Insurance Type:</label>
                        <select class="form-select select2" id="insuranceSelect" name="insurance_id" required>
                            <option value="">-- Select Insurance --</option>
                            @foreach ($insurance as $ins)
                                <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Product:</label>
                        <select class="form-select select2" id="productSelect" name="product_id" required>
                            <option value="">-- Select Product --</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Coverage:</label>
                        <select class="form-select select2" id="coverageSelect" name="coverage_id" required>
                            <option value="">-- Select Coverage --</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn text-white" style="background-color: #001f33">Proceed</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
    /* Rekebisha urefu wa select2 ili ifanane na Bootstrap form-select */
    .select2-container .select2-selection--single {
        height: 38px !important;
        display: flex;
        align-items: center;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding-left: 8px;
    }

    /* Andika text vizuri katikati */
    .select2-selection__rendered {
        line-height: 36px !important;
    }

    /* Rekebisha arrow (dropdown icon) */
    .select2-selection__arrow {
        height: 36px !important;
    }

    /* Hakikisha modal layout haivunjiki */
    .modal .select2-container {
        width: 100% !important;
    }

    /* Rekebisha space kati ya selects */
    .select2-container {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    /* Background ya dropdown item (option) */
    .select2-results__option--highlighted {
        background-color: #001f33 !important;
        color: white !important;
    }

    /* Search input ndani ya dropdown ya Select2 */
    .select2-container .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding: 6px 10px !important;
        outline: none !important;
        background-color: #fff !important;
        /* badilisha kama unataka dark mode */
        color: #000 !important;
    }

    /* Ikiwa unataka dark mode kwa dropdown search (optional) */
    .modal .select2-dropdown {
        background-color: #f8f9fa !important;
        /* light gray background */
        border: 1px solid #ced4da !important;
    }

    /* Ondoa blue border wakati wa focus */
    .select2-container .select2-search__field:focus {
        border-color: #001f33 !important;
        /* theme color yako */
        box-shadow: none !important;
        outline: none !important;
    }

    /* Rekebisha font-size na spacing za options */
    .select2-results__option {
        font-size: 14px;
        padding: 8px 12px;
    }

    /* Dropdown items ziwe na hover style safi */
    .select2-results__option--highlighted {
        background-color: #001f33 !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 14px !important;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 on the select elements
        $('#insuranceSelect, #productSelect, #coverageSelect').select2({
            dropdownParent: $('#InsuranceType') // Ensure the dropdown appears within the modal
        });

        // Fetch products based on selected insurance type
        $('#insuranceSelect').on('change', function() {
            var insuranceId = $(this).val();
            if (insuranceId) {
                $.ajax({
                    url: `/dash/insurance/${insuranceId}/products`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#productSelect').empty().append(
                            '<option value="">-- Select Product --</option>');
                        $('#coverageSelect').empty().append(
                            '<option value="">-- Select Coverage --</option>'
                        ); // Clear coverage dropdown
                        $.each(data, function(key, value) {
                            $('#productSelect').append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#productSelect').empty().append('<option value="">-- Select Product --</option>');
                $('#coverageSelect').empty().append(
                    '<option value="">-- Select Coverage --</option>'); // Clear coverage dropdown
            }
        });

        // Fetch coverages based on selected product
        $('#productSelect').on('change', function() {
            var productId = $(this).val();
            if (productId) {
                $.ajax({
                    url: `/dash/product/${productId}/coverages`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#coverageSelect').empty().append(
                            '<option value="">-- Select Coverage --</option>');
                        $.each(data, function(key, value) {
                            $('#coverageSelect').append('<option value="' + value
                                .id + '">' + value.risk_name + '</option>');
                        });
                    }
                });
            } else {
                $('#coverageSelect').empty().append('<option value="">-- Select Coverage --</option>');
            }
        });
    });
</script>
