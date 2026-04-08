@extends('layout.index')

@section('page-title')
Production Company
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create production company')
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#largeModal" onclick="showModal()">
                {{ __('Create') }}
            </button>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="getView"></div>
    </div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="row g-3 needs-validation" novalidate id="form" onsubmit=save(event) enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="hidden_id" name="hidden_id" >
            <div class="modal-body overflow-auto" style="max-height: 400px;">
                <div class="row g-6">

                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Producer Name" required>
                    </div>

                   

                    <div class="col-md-6 form-group">
                        <label for="country_id" class="form-label">{{ __('Country') }}</label>
                        <select class="form-control select2" id="country_id" name="country_id" onchange="getCountryRegion()">
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" >{{$country->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="region_id" class="form-label">{{ __('Region') }}</label>
                        <select class="form-control select2" id="region_id" onchange="getRegionDistrict()"  name="region_id">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="district_id" class="form-label">{{ __('District') }}<span style="color:red">*</span></label>
                        <select class="form-control select2" onchange="getDistrictWard()" id="district_id"  name="district_id" >
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="ward_id" class="form-label">{{ __('Ward') }}<span style="color:red">*</span></label>
                        <select class="form-control select2"  id="ward_id"  name="ward_id" >
                            <option value="">Select</option>
                        </select>
                    </div>



                    <div class="col-md-6 form-group">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" >
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" >
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm m-1" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" id="submitBtn" class="btn btn-primary btn-sm m-1">{{ __('Save') }}</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        getView()
        getCountryRegion()
    });

    function getView() {
        showLoader('getView', true)
        jQuery.ajax({
            type: "GET",
            url: "/producer/view",
            dataType: 'html',
            cache: false,
            success: function (data) {
                showLoader('getView', false)
                $("#getView").html(data)
            }
        });
    }

    function showModal(){
        clear_input();
    }

    function hideModal(){
        $('#largeModal').modal('hide');
        clear_input();
        getView()
    }

    function clear_input() {
        document.getElementById('form').reset();
        $("#hidden_id").val("")
        $("#submitBtn").html("Save")
        $("#modalTitle").html("New Production Company");
    }

    function save(e) {
        e.preventDefault();
        disableBtn("submitBtn", true);
        var form = document.getElementById('form');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/producer/save",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    hideModal()
                } else {
                    showFlashMessage("warning", data.message);
                }
                disableBtn("submitBtn", false);
                $("#submitBtn").html("Save")
            }
        });
    }

    function editProducer(id) {
        document.getElementById('form').reset();
        $("#hidden_id").val("")

        jQuery.ajax({
            type: "GET",
            url: "/producer/edit/" + id,
            dataType: 'json',
            success: function (data) {
                let row = data.data;
                let district = row.district || {};
                let ward = row.ward || {};
                let region = (district && district.region) || {};
                let country = (region && region.country) || {};

                $("#hidden_id").val(data.id)
                $("#name").val(row.name);
                $("#district_id").val(row.district_id);
                $("#ward_id").val(row.ward_id);
                $("#latitude").val(row.latitude);
                $("#longitude").val(row.longitude);
                $("#country_id").val(country.id || "");

                if (region.id) {
                    $("#region_id").html('<option value="' + region.id + '">' + (region.name || "") + '</option>').val(region.id);
                } else {
                    $("#region_id").html('<option value="">Select Region</option>').val("").change();
                }

                if (district.id) {
                    $("#district_id").html('<option value="' + district.id + '">' + (district.name || "") + '</option>').val(district.id);
                } else {
                    $("#district_id").html('<option value="">Select District</option>').val("").change();
                }

                if (ward.id) {
                    $("#ward_id").html('<option value="' + ward.id + '">' + (ward.name || "") + '</option>').val(ward.id).change();
                } else {
                    $("#ward_id").html('<option value="">Select Ward</option>').val("").change();
                }

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Production Company");

                $('#largeModal').modal('show');
            }
        });
    }

    function deleteProducer(id) {
        if (!confirm("Are you sure you want to delete this company?")) return;

        jQuery.ajax({
            type: "GET",
            url: "/producer/delete/" + id,
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    getView()
                } else {
                    showFlashMessage("warning", data.message);
                }
            }
        });
    }


</script>

@include('layout.location')
@endsection
