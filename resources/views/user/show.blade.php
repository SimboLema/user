@extends('layout.index')

@section('page-title')
Users
@endsection

@section('content')
<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ $user->avatar  ? asset('storage/'.$user->avatar) : 'https://via.placeholder.com/100' }}" alt="--"  style="max-width: 150px;height: auto;">
                    <h2>{{ $user->name }}</h2>
                    <span>
                    @if($user->status == "active")
                       <span style="background-color: green; padding: 5px; color: white; border-radius: 50%; display: inline-block;"></span> Active

                    @else
                       <span style="background-color: red; padding: 5px; color: white; border-radius: 50%; display: inline-block;"></span> In-Active
                    @endif
                    </span>
                </div>

                <div class="m-3">
                    <div class="row mb-2">
                        <div class="col-md-6"><strong><i class="bi bi-phone"></i> Phone:</strong></div>
                        <div class="col-md-6">{{ $user->phone ?? '--' }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6"><strong><i class="bi bi-gender-ambiguous"></i> Gender:</strong></div>
                        <div class="col-md-6">{{ $user->gender ?? '--' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong><i class="bi bi-envelope"></i> Email:</strong></div>
                        <div class="col-md-6">{{ $user->email ?? '--' }}</div>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('User Information') }}</h5>

                    <!-- Tabs for different sections -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="user-info-tab" data-bs-toggle="tab" href="#user-info" role="tab" aria-controls="user-info" aria-selected="true">{{ __('User Info') }}</a>
                        </li>

                        @if(Auth::user()->id === $user->id)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">{{ __('Edit Profile') }}</a>
                        </li>
                        @endif
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <!-- User Info Tab -->
                        <div class="tab-pane fade show active" id="user-info" role="tabpanel" aria-labelledby="user-info-tab">
                            <div class="mt-3">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <h5>{{ __('Address Details') }}</h5>
                                        <div class="row mb-2">
                                            <div class="col-md-6"><strong><i class="bi bi-globe"></i> Country:</strong></div>
                                            <div class="col-md-6">{{ $user->country->name ?? '--' }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6"><strong><i class="bi bi-map"></i> Region:</strong></div>
                                            <div class="col-md-6">{{ $user->region->name ?? '--' }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6"><strong><i class="bi bi-geo-alt"></i> District:</strong></div>
                                            <div class="col-md-6">{{ $user->district->name ?? '--' }}</div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-6"><strong><i class="bi bi-geo-alt"></i> Ward:</strong></div>
                                            <div class="col-md-6">{{ $user->ward->name ?? '--' }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6"><strong><i class="bi bi-house"></i> Physical Address:</strong></div>
                                            <div class="col-md-6">{{ $user->address ?? '--' }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6"><strong><i class="bi bi-house-door"></i> Home Address:</strong></div>
                                            <div class="col-md-6">{{ $user->home_address ?? '--' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- edit Tab -->
                        @if(Auth::user()->id === $user->id)
                        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                            <form class="row g-3 needs-validation" novalidate id="form" onsubmit=save(event) enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" class="form-control" id="user_id" name="user_id"  value="{{Crypt::encrypt($user->id)}}">
                                <div class="modal-body">
                                <h5>{{ __('Change Profile') }}</h5>
                                    <div class="row g-6">

                                        <div class="col-md-6 form-group">
                                            <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter A Full Name') }}" value="{{$user->name}}" required>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="name" class="form-label">Phone Number<span style="color:red">*</span></label>
                                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="{{ __('Enter A Phone Number') }}" value="{{$user->phone}}" required>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="name" class="form-label">Email Address<span style="color:red">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('Enter A Email Address') }}" value="{{$user->email}}" required>
                                        </div>



                                        <!-- Gender -->
                                        <div class="col-md-6 form-group">
                                            <label for="gender" class="form-label">Gender<span style="color:red">*</span></label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                @if($user->gender)
                                                    <option value="{{$user->gender}}">{{$user->gender ?? ""}}</option>
                                                @endif
                                                <option value="">Select</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>

                                        <!-- Country -->
                                        <div class="col-md-6 form-group">
                                            <label for="country" class="form-label">{{ __('Country') }}</label>

                                            <select class="form-control select2" id="country_id" name="country_id" onchange="getCountryRegion()" >
                                                @foreach($countries as $country)
                                                <option value="{{$country->id}}" @if($user->country_id == $country->id)selected @endif>{{$country->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="region_id" class="form-label">{{ __('Region') }}</label>
                                            <select class="form-control select2" id="region_id" onchange="getRegionDistrict()"  name="region_id" >
                                                <option value="">Select</option>

                                                @if($user->region_id)
                                                    <option value="{{$user->region_id}}" selected>{{$user->region->name ?? ""}}</option>
                                                @endif
                                            </select>
                                        </div>


                                        <div class="col-md-6 form-group">
                                            <label for="district_id" class="form-label">{{ __('District') }}</label>
                                            <select class="form-control select2" onchange="getDistrictWard()" id="district_id"  name="district_id" >
                                                <option value="">Select</option>
                                                @if($user->district_id)
                                                    <option value="{{$user->district_id}}" selected>{{$user->district->name ?? ""}}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="ward_id" class="form-label">{{ __('Ward') }}</label>
                                            <select class="form-control select2"  id="ward_id"  name="ward_id" >
                                                <option value="">Select</option>
                                                @if($user->ward_id)
                                                    <option value="{{$user->ward_id}}" selected>{{$user->ward->name ?? ""}}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <!-- Street Address -->
                                        <div class="col-md-6 form-group">
                                            <label for="address" class="form-label">{{ __('Physical Address') }}</label>
                                            <input type="text" class="form-control" id="address" name="address"  value="{{$user->address}}" placeholder="{{ __('Enter Address') }}" >
                                        </div>

                                        <!-- Street Address -->
                                        <div class="col-md-6 form-group">
                                            <label for="home_address" class="form-label">{{ __('Address') }}</label>
                                            <input type="text" class="form-control" id="home_address" name="home_address"  value="{{$user->home_address}}" placeholder="{{ __('Enter home Address') }}" >
                                        </div>

                                        <!-- Street Address -->
                                        <div class="col-md-6 form-group">
                                            <label for="address" class="form-label">{{ __('Picture') }}</label>
                                            <input type="file" class="form-control" id="avatar" name="avatar"   >
                                        </div>




                                 </div>
                                <div class="modal-footer">
                                    <button type="submit" id="submitBtn" class="btn btn-primary btn-sm m-1">{{ __('Update Profile') }}</button>
                                </div>
                            </form>


                            <form class="row g-3 needs-validation" novalidate id="form2" onsubmit=save2(event) enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{Crypt::encrypt($user->id)}}" >
                                <div class="modal-body">
                                <h5>{{ __('Update Password') }}</h5>
                                    <div class="row">


                                        <div class="col-md-6 form-group">
                                            <label for="old_password" class="form-label">Old Password<span style="color:red">*</span></label>
                                            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="{{ __('Enter Old Password') }}"  required>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="new_password" class="form-label">New Password<span style="color:red">*</span></label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="{{ __('Enter New Password') }}" required>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="re_new_password" class="form-label">Repeat New Password<span style="color:red">*</span></label>
                                            <input type="password" class="form-control" id="re_new_password" name="re_new_password" placeholder="{{ __('Repeat New Password') }}" required>
                                        </div>




                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" id="submitBtn2" class="btn btn-primary btn-sm m-1">{{ __('Change Password') }}</button>
                                </div>
                            </form>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        getCountryRegion();
    });

    function getCountryRegion() {
        var id = $("#country_id").val();
        jQuery.ajax({
            type: "GET",
            url: "/location/getCountryRegion/" + id,
            dataType: 'json',
            success: function (data) {
                var div = '';
                $.each(data, function (index, row) {
                    div += "<option value='" + row.id + "'>" + row.name + "</option>";
                });

                $("#region_id").html("<option value=''>Select Region</option>");
                $("#region_id").append(div);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    function getRegionDistrict() {
        var id = $("#region_id").val();
        jQuery.ajax({
            type: "GET",
            url: "/location/getRegionDistrict/" + id,
            dataType: 'json',
            success: function (data) {
                var div = '';
                $.each(data, function (index, row) {
                    div += "<option value='" + row.id + "'>" + row.name + "</option>";
                });

                $("#district_id").html("<option value=''>Select District</option>");
                $("#district_id").append(div);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    function getDistrictWard() {
        var id = $("#district_id").val();
        jQuery.ajax({
            type: "GET",
            url: "/location/getDistrictWard/" + id,
            dataType: 'json',
            success: function (data) {
                var div = '';
                $.each(data, function (index, row) {
                    div += "<option value='" + row.id + "'>" + row.name + "</option>";
                });

                $("#ward_id").html("<option value=''>Select ward</option>");
                $("#ward_id").append(div);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    function save(e) {
        e.preventDefault();

        disableBtn("submitBtn", true);
        var form = document.getElementById('form');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/user/saveProfile",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    setTimeout(() => {
                        window.location.reload(true)
                    }, 3000);
                } else {
                    showFlashMessage("warning", data.message);
                }

                disableBtn("submitBtn", false);
                $("#submitBtn").html("Update Profile ")
            }
        });
    }

    function save2(e) {
        e.preventDefault();

        disableBtn("submitBtn2", true);
        var form = document.getElementById('form2');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/user/changePassword",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    setTimeout(() => {
                        window.location.reload(true)
                    }, 3000);
                } else {
                    showFlashMessage("warning", data.message);
                }

                disableBtn("submitBtn2", false);
                $("#submitBtn2").html("Change Password ")
            }
        });
    }
</script>
@endsection
