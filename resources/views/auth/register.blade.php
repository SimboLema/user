{{-- resources/views/auth/login.blade.php --}}
@extends('layout.auth')
@section('page-title')
 Registration
@endsection
@section('content')
<?php use App\Models\Setting; $settings = Setting::first(); ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bootstrap 4 theme (optional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.2/dist/select2-bootstrap4.min.css">

 <!-- Validation Wizard -->
 <div class="d-flex col-lg-8 align-items-center justify-content-center authentication-bg p-5">


    <div id="wizard-validation" class="bs-stepper mt-2">

        <h4 class="mb-1 text-center">Welcome to {{ $settings->system_name ?? "TakaLink"}}! 👋</h4>
        <p class=" text-center">Please fill details below to create your account</p>
            <div class="text-center my-3">

                @if ( $settings->system_logo)
                    <img src="{{ asset('storage/' . $settings->system_logo) }}" alt="Logo" class="auth-logo" />
                @else
                    <img src="{{ asset('assets/img/icons/brands/laravel-logo.png') }}" alt="Logo" class="auth-logo" />
                @endif
            </div>
      <div class="bs-stepper-header" style="marging-top:-20px;">
        <div class="step" data-target="#account-details-validation">
          <button type="button" class="step-trigger">
            <span class="bs-stepper-circle">1</span>
            <span class="bs-stepper-label mt-1">
              <span class="bs-stepper-title">Company & Industry</span>
              <span class="bs-stepper-subtitle">Setup your company details</span>
            </span>
          </button>
        </div>

        <div class="line">
          <i class="icon-base ti tabler-chevron-right"></i>
        </div>
        <div class="step" data-target="#social-links-validation">
          <button type="button" class="step-trigger">
            <span class="bs-stepper-circle">2</span>
            <span class="bs-stepper-label">
              <span class="bs-stepper-title">Personal Details</span>
              <span class="bs-stepper-subtitle">Add your account details</span>
            </span>
          </button>
        </div>
      </div>
      <div class="bs-stepper-content">
        <form id="wizard-validation-form" onsubmit="submitRegister(event)">
            <input type="hidden" class="form-control" id="latitude" name="latitude" >
            <input type="hidden" class="form-control" id="longitude" name="longitude" >

          <!-- Account Details -->
          <div id="account-details-validation" class="content">
            <div class="content-header mb-4">
              <h6 class="mb-0">Company & Industry</h6>
              <small>Enter Your Account Details.</small>
            </div>
            <div class="row g-6">
                <div class="col-sm-6 form-control-validation">
                    <label class="form-label" for="name">Company / Business Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="eg ABC Co Ltd" required/>
                </div>

                <!-- Country -->
                <div class="col-md-6 form-control-validation">
                    <label for="country" class="form-label">{{ __('Country') }}<span class="text-danger">*</span></label>

                    <select class="form-control select3" id="country_id" name="country_id" onchange="getCountryRegion()" required >
                        @foreach($countries as $country)
                            <option value="{{$country->id}}" >{{$country->name}}</option>
                        @endforeach
                    </select>

                </div>

                <div class="col-md-6 form-control-validation">
                    <label for="region_id" class="form-label">{{ __('Region') }}<span class="text-danger">*</span></label>
                    <select class="form-control select3" id="region_id" onchange="getRegionDistrict()"  name="region_id" required>
                        <option value="">Select</option>

                    </select>
                </div>


                <div class="col-md-6 form-control-validation">
                    <label for="district_id" class="form-label">{{ __('District') }}<span style="color:red">*</span></label>
                    <select class="form-control select3" onchange="getDistrictWard()" id="district_id"  name="district_id"  required>
                        <option value="">Select</option>
                    </select>
                </div>

                <div class="col-md-6 form-control-validation">
                    <label for="ward_id" class="form-label">{{ __('Ward') }}<span style="color:red">*</span></label>
                    <select class="form-control select3"  id="ward_id"  name="ward_id" required>
                        <option value="">Select</option>
                    </select>
                </div>


              <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-label-secondary btn-prev" disabled>
                  <i class="icon-base ti tabler-arrow-left icon-xs me-sm-2 me-0"></i>
                  <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-primary btn-next"><span class="align-middle d-sm-inline-block d-none me-sm-2">Next</span> <i class="icon-base ti tabler-arrow-right icon-xs"></i></button>
              </div>
            </div>
          </div>



          <!-- Social Links -->
          <div id="social-links-validation" class="content">
            <div class="content-header mb-4">
              <h6 class="mb-0">Personal Information</h6>
                <small class="mb-0">Enter Your Personal Information</small>

            </div>
            <div class="row g-6">
                <div class="col-sm-6 form-control-validation">
                    <label class="form-label" for="full_name">Full Name<span class="text-danger">*</span></label>
                    <input type="text" id="full_name" name="full_name" required class="form-control" placeholder="John" />
                </div>

                <div class="col-sm-6 form-control-validation">
                    <label class="form-label" for="phone">Mobile Number<span class="text-danger">*</span></label>
                    <div class="input-group">
                    <input type="text" id="phone" name="phone" class="form-control multi-steps-mobile" placeholder="255 67 555 0111" required />
                    </div>
                </div>
                <div class="col-sm-6 form-control-validation">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" />
                </div>

                <div class="col-md-6 form-control-validation">
                    <label for="gender" class="form-label">Gender<span class="text-danger">*</span></label>
                    <select class="form-control" id="gender" name="gender"  required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="col-sm-6 form-password-toggle form-control-validation">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                    <input type="password" id="password" name="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="multiStepsPass2" required/>
                    <span class="input-group-text cursor-pointer" id="multiStepsPass2"><i class="icon-base ti tabler-eye-off"></i></span>
                    </div>
                </div>
                <div class="col-sm-6 form-password-toggle form-control-validation">
                    <label class="form-label" for="passwordConfirm">Confirm Password</label>
                    <div class="input-group input-group-merge">
                    <input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="multiStepsConfirmPass2" required />
                    <span class="input-group-text cursor-pointer" id="multiStepsConfirmPass2"><i class="icon-base ti tabler-eye-off"></i></span>
                    </div>
                </div>

                <div class="col-md-6 form-control-validation">
                    <label for="id_number" class="form-label">Image<span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="avatar" name="avatar" placeholder="Enter ID number" required>
                </div>


                <div class="col-md-6 form-control-validation">
                    <label for="id_type" class="form-label">ID Type</label>
                    <select class="form-control" id="id_type" name="id_type" >
                        <option value="">Select ID Type</option>
                        <option value="National ID">National ID</option>
                        <option value="Vote ID">Vote ID</option>
                        <option value="Passport">Passport</option>
                        <option value="Student ID">Student ID</option>
                        <option value="Driver's License">Driver's License</option>
                    </select>
                </div>

                <div class="col-md-6 form-control-validation">
                    <label for="id_number" class="form-label">ID Number</label>
                    <input type="text" class="form-control" id="id_number" name="id_number" placeholder="Enter ID number" >
                </div>



                <div id="message" style="display:none; margin-bottom: 5px;"></div>

                <div class="col-12 d-flex justify-content-between">
                    <button class="btn btn-label-secondary btn-prev">
                    <i class="icon-base ti tabler-arrow-left icon-xs me-sm-2 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-success btn-next btn-submit">Submit</button>
                </div>
            </div>
          </div>
        </form>

      </div>
    </div>
    </div>
  <!-- /Validation Wizard -->

  <script>
    $(document).ready(function(){
        getCountryRegion()
        initializeNormalSelector()

    })

    document.addEventListener('DOMContentLoaded', function () {

        const stepperElement = document.querySelector('#wizard-validation');
        const stepper = new Stepper(stepperElement);

        // Handle "Next" buttons
        document.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', function () {
            // 🔥 Always get the currently active step
            const step = document.querySelector('.content.active');
            const inputs = step.querySelectorAll('input, select, textarea');

            let isValid = true;
            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
                } else {
                input.classList.remove('is-invalid');
                }
            });

            if (isValid) {
                stepper.next();
            }
            });
        });

        // Handle "Previous" buttons
        document.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', function () {
            stepper.previous();
            });
        });
    });

function submitRegister(e) {
    e.preventDefault(); // prevent actual form submission

    const form = document.getElementById('wizard-validation-form');
    const formData = new FormData(form);
    var role = "{{$role}}";

        if (role == "collection_center") {
            url = '/collection_center/save';
        }
        else if (role == "recycling_facility") {
            url = '/recycling_facility/save';
        }
        else if (role == "producer") {
            url = '/producer/save';
        }

        showMessage("Registering ...", 'blue');
    $.ajax({
      url: url,
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        if (response.status === 200) {
          showMessage(response.message, 'green');
          setTimeout(() => { window.location.href = response.redirect; }, 3000);

        } else {
          showMessage(response.message, 'red');
        }
      },
      error: function(jqXHR) {
        const response = jqXHR.responseJSON || {};
        showMessage(response.message || 'An error occurred.', 'red');
      }
    });
  }

</script>



@include('layout.location')
@endsection
