<div class="step-content" id="step1">
    <h5 class="step-title"><i class="bi bi-person me-2"></i>Customer Information
    </h5>

    <div class="d-flex flex-column align-items-end">
        <div class="text-end mb-3">
        </div>
        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-sm text-white" style="background-color: #003153" data-bs-toggle="modal"
                data-bs-target="#existingCustomerModal">
                <i class="fas fa-search"></i> Existing Customer
            </button>
        </div>

    </div>

    <div class="row g-3">
        {{-- Client Full Name --}}
        <div class="col-md-6 form-group autocomplete-wrapper">
            <label for="name" class="form-label required-field">Client Full
                Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name"
                autocomplete="off" required>
        </div>

        {{-- Country --}}
        <div class="col-md-6">
            <label for="country" class="form-label required-field">Country</label>
            <select class="form-select select2" name="country_id" id="country" required>
                <option value="">Select Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>


        {{-- Region --}}
        <div class="col-md-6">
            <label for="region" class="form-label required-field">Region</label>
            <select class="form-select select2" name="region_id" id="region" required>
                <option value="">Select Region</option>
            </select>
        </div>

        {{-- District --}}
        <div class="col-md-6">
            <label for="district" class="form-label required-field">District</label>
            <select class="form-select select2" name="district_id" id="district" required>
                <option value="">Select District</option>
                {{-- You can populate districts via AJAX when region changes --}}
            </select>
        </div>

        {{-- Street --}}
        <div class="col-md-6">
            <label class="form-label required-field">Street</label>
            <input class="form-control" type="text" name="street" placeholder="Street" required>
        </div>

        {{-- Birthday --}}
        <div class="col-md-6">
            <label class="form-label required-field">Date of Birth</label>
            <input class="form-control" type="date" name="dob" id="dob" placeholder="Date of Birth"
                required>
        </div>

        {{-- Policy Holder Type --}}
        <div class="col-md-6">
            <label class="form-label required-field">Policy Holder Type</label>
            <select class="form-select" name="policy_holder_type_id" required>
                <option value="">Select Type</option>
                @foreach ($policyHolderType as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- TIN Number --}}
        <div class="col-md-6">
            <label class="form-label required-field">TIN Number </label>
            <input class="form-control" type="text" name="tin_number" placeholder="TIN Number" required>
        </div>

        {{-- Policy Holder ID Type --}}
        <div class="col-md-6">
            <label class="form-label required-field">Policy Holder ID
                Type</label>
            <select class="form-select" name="policy_holder_id_type_id" required>
                <option value="">Select ID Type</option>
                @foreach ($policyHolderIdType as $idType)
                    <option value="{{ $idType->id }}">{{ $idType->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- ID Number --}}
        <div class="col-md-6">
            <label class="form-label required-field">ID Number</label>
            <input class="form-control" type="text" name="policy_holder_id_number" placeholder="ID Number" required>
        </div>

        {{-- Gender --}}
        <div class="col-md-6">
            <label class="form-label required-field">Gender</label>
            <select class="form-select" name="gender" required>
                <option value="">Select Gender</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
        </div>

        {{-- Phone --}}
        <div class="col-md-6">
            <label class="form-label required-field">Phone</label>
            <input class="form-control" type="text" name="phone" placeholder="Phone" required>
        </div>

        {{-- Fax --}}
        <div class="col-md-6">
            <label class="form-label">Fax</label>
            <input class="form-control" type="text" name="fax" placeholder="Fax">
        </div>

        {{-- Postal Address --}}
        <div class="col-md-6">
            <label class="form-label required-field">Postal Address</label>
            <input class="form-control" type="text" name="postal_address" placeholder="Postal Address" required>
        </div>

        {{-- Email Address --}}
        <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input class="form-control" type="email" name="email_address" placeholder="Email Address">
        </div>


    </div>

    <div class="d-flex justify-content-between mt-4">
        <div></div>
        <button type="button" class="btn text-white" style="background-color: #003153" onclick="changeStep(1)">
            Next <i class="bi bi-arrow-right ms-2 text-white"></i>
        </button>
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

    .select2-selection__rendered {
        line-height: 36px !important;
        font-size: 14px !important;
    }

    .select2-selection__arrow {
        height: 36px !important;
    }

    .select2-container {
        width: 100% !important;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    /* Dropdown items hover style (lightened navy) */
    .select2-results__option--highlighted {
        background-color: #1A4A73 !important;
        color: #fff !important;
    }

    /* Search input inside dropdown */
    .select2-container .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding: 6px 10px !important;
        outline: none !important;
        background-color: #fff !important;
        color: #000 !important;
    }

    .select2-container .select2-search__field:focus {
        border-color: #1A4A73 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .select2-dropdown {
        background-color: #f8f9fa !important;
        border: 1px solid #ced4da !important;
    }

    .select2-results__option {
        font-size: 14px;
        padding: 8px 12px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let dobInput = document.getElementById("dob");

        // today's date minus 18 years
        let today = new Date();
        let year = today.getFullYear() - 18;
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let day = String(today.getDate()).padStart(2, '0');

        dobInput.max = year + "-" + month + "-" + day;
    });
</script>
