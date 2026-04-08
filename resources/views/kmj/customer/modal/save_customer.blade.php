<div class="modal fade" id="CustomerInfo" tabindex="-1" aria-labelledby="CustomerInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header text-white">
                <h5 class="modal-title">Customer Information</h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form id="createCustomerForm" method="post" action="{{ route('kmj.customer.store') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="modal-body">



                    <div class="row g-3">
                        {{-- Client Full Name --}}
                        <div class="col-md-6 form-group autocomplete-wrapper">
                            <label for="name" class="form-label required-field">Client Full
                                Name<span style="color: red">*</span> </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter Full Name" autocomplete="off" required>
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
                            <label for="region" class="form-label required-field">Region<span
                                    style="color: red">*</span> </label>
                            <select class="form-select select2" name="region_id" id="region" required>
                                <option value="">Select Region</option>
                            </select>
                        </div>

                        {{-- District --}}
                        <div class="col-md-6">
                            <label for="district" class="form-label required-field">District<span
                                    style="color: red">*</span> </label>
                            <select class="form-select select2" name="district_id" id="district" required>
                                <option value="">Select District</option>
                                {{-- You can populate districts via AJAX when region changes --}}
                            </select>
                        </div>

                        {{-- Street --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">Street<span style="color: red">*</span> </label>
                            <input class="form-control" type="text" name="street" placeholder="Street" required>
                        </div>

                        {{-- Birthday --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">Date of Birth<span style="color: red">*</span>
                            </label>
                            <input class="form-control" type="date" id="dob" name="dob"
                                placeholder="Date of Birth" required>
                        </div>

                        {{-- Policy Holder Type --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">Policy Holder Type<span style="color: red">*</span>
                            </label>
                            <select class="form-select" name="policy_holder_type_id" required>
                                <option value="">Select Type</option>
                                @foreach ($policyHolderType as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TIN Number --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">TIN Number<span style="color: red">*</span>
                            </label>
                            <input class="form-control" type="text" name="tin_number" placeholder="TIN Number"
                                required>
                        </div>

                        {{-- Policy Holder ID Type --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">Policy Holder ID
                                Type<span style="color: red">*</span> </label>
                            <select class="form-select" name="policy_holder_id_type_id" required>
                                <option value="">Select ID Type</option>
                                @foreach ($policyHolderIdType as $idType)
                                    <option value="{{ $idType->id }}">{{ $idType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ID Number --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">ID Number<span style="color: red">*</span> </label>
                            <input class="form-control" type="text" name="policy_holder_id_number"
                                placeholder="ID Number" required>
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">Gender<span style="color: red">*</span> </label>
                            <select class="form-select" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">Phone<span style="color: red">*</span> </label>
                            <input class="form-control" type="text" name="phone" placeholder="Phone" required>
                        </div>

                        {{-- Fax --}}
                        <div class="col-md-6">
                            <label class="form-label">Fax</label>
                            <input class="form-control" type="text" name="fax" placeholder="Fax">
                        </div>

                        {{-- Postal Address --}}
                        <div class="col-md-6">
                            <label class="form-label required-field">Postal Address<span style="color: red">*</span>
                            </label>
                            <input class="form-control" type="text" name="postal_address"
                                placeholder="Postal Address" required>
                        </div>

                        {{-- Email Address --}}
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input class="form-control" type="email" name="email_address"
                                placeholder="Email Address">
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submitBtn" class="btn"
                        style="background-color: #003153; color: white;">
                        <i class="bi bi-check-circle me-2 text-white"></i>
                        <span id="submitText">Save Customer</span>
                        <span id="submitSpinner" class="spinner-border spinner-border-sm text-light ms-2"
                            style="display: none;"></span>
                    </button>
                </div>

            </form>



        </div>
    </div>
</div>

<script>
    //     document.getElementById("createCustomerForm").addEventListener("submit", function (e) {
    //     let dob = new Date(document.getElementById("dob").value);
    //     let today = new Date();
    //     let age = today.getFullYear() - dob.getFullYear();
    //     let m = today.getMonth() - dob.getMonth();

    //     if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
    //         age--;
    //     }

    //     if (age < 18) {
    //         e.preventDefault();
    //         alert("Customer must be at least 18 years old.");
    //     }
    // });



    document.addEventListener('DOMContentLoaded', function() {
        let dobInput = document.getElementById('dob');

        // today's date minus 18 years
        let today = new Date();
        let year = today.getFullYear() - 18;
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let day = String(today.getDate()).padStart(2, '0');

        dobInput.max = year + '-' + month + '-' + day;


    });
</script>
