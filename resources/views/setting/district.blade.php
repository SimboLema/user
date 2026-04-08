@extends('layout.index')
@section('page-title')
District
@endsection
@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create district')
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#largeModal" onclick="showModal()">
                {{ __('Create') }}
            </button>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="getView"></div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" id="hidden_id" name="hidden_id">
                <div class="modal-body overflow-auto" style="max-height: 400px;">
                    <div class="row">

                        <!-- Country -->
                        <div class="col-md-6 form-group">
                            <label for="country_id" class="form-label">Country<span style="color:red">*</span></label>
                            <select class="form-control" name="country_id" id="country_id" onchange="getCountryRegion()" required>
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach ($countries as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Region -->
                        <div class="col-md-6 form-group">
                            <label for="region_id" class="form-label">Region<span style="color:red">*</span></label>
                            <select class="form-control" name="region_id" id="region_id" required>
                                <option value="">{{ __('Select Region') }}</option>
                            </select>
                        </div>

                        <!-- Name -->
                        <div class="col-md-12 form-group">
                            <label for="name" class="form-label">District<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter Name') }}" required>
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
        getView();

    });

    function showModal() {
        clear_input();
        getCountryRegion()
    }

    function hideModal() {
        $('#largeModal').modal('hide');
        clear_input();
        getView();
    }

    function clear_input() {
        document.getElementById('form').reset();
        $("#hidden_id").val("");
        $("#submitBtn").html("Save");
        $("#modalTitle").html("New District");
        $('#region_id').html('<option value="">Select Region</option>');
    }

    function getView() {
        showLoader('getView', true);
        jQuery.ajax({
            type: "GET",
            url: "/district/view",
            dataType: 'html',
            cache: false,
            success: function (data) {
                showLoader('getView', false);
                $("#getView").html(data);
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
            url: "/district/saveDistrict",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    hideModal();
                } else {
                    showFlashMessage("warning", data.message);
                }

                disableBtn("submitBtn", false);
                $("#submitBtn").html("Save");
            }
        });
    }

    function editDistrict(id) {
        document.getElementById('form').reset();
        $("#hidden_id").val("");

        jQuery.ajax({
            type: "GET",
            url: "/district/editDistrict/" + id,
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);
                var rowData = data.data;

                $("#name").val(rowData.name);
                $("#country_id").val(rowData.country.id);
                $("#region_id").html('<option value="'+rowData.region_id+'">'+(rowData.region.name || "")+'</option>');

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update District");

                $('#largeModal').modal('show');
            }
        });
    }

    function deleteDistrict(id) {
        var conf = confirm("Are you sure you want to delete this district?");
        if (!conf) return;

        jQuery.ajax({
            type: "GET",
            url: "/district/deleteDistrict/" + id,
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    getView();
                } else {
                    showFlashMessage("warning", data.message);
                }
            }
        });
    }

    function getCountryRegion() {
        var country_id = $("#country_id").val();

        if (country_id) {
            $('#region_id').html('<option value="">Loading...</option>');

            $.ajax({
                type: 'GET',
                url: '/location/getCountryRegion/' + country_id,
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select Region</option>';
                    data.forEach(function (item) {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#region_id').html(options);
                },
                error: function () {
                    $('#region_id').html('<option value="">Error loading regions</option>');
                }
            });
        } else {
            $('#region_id').html('<option value="">Select Region</option>');
        }
    }
</script>
@endsection
