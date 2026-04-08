@extends('layout.index')

@section('page-title')
Waste Collection
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create waste collection')
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
                        <label for="waste_picker_id" class="form-label">Waste Picker<span style="color:red">*</span></label>
                        <select class="form-control select2" id="waste_picker_id" name="waste_picker_id" required>
                            <option value="">Select</option>
                            @foreach($wastePickers as $picker)
                                <option value="{{ $picker->id }}">{{ $picker->id_number ?? "" }} - {{ $picker->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="collection_center_id" class="form-label">Collection Center<span style="color:red">*</span></label>
                        <select class="form-control select2" id="collection_center_id" name="collection_center_id" required>
                            <option value="">Select</option>
                            @foreach($collectionCenters as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="waste_type_id" class="form-label">Waste Type<span style="color:red">*</span></label>
                        <select class="form-control select2" id="parent_id" name="parent_id" onchange="getSubWasteType()"  required>
                            <option value="">Select</option>
                            @foreach($wasteTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="waste_type_id" class="form-label">Sub Waste Type<span style="color:red">*</span></label>
                        <select class="form-control select2" id="waste_type_id" name="waste_type_id"  required>
                            <option value="">Select</option>
                            @foreach($wasteTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="unit_id" class="form-label">Unit<span style="color:red">*</span></label>
                        <select class="form-control select2" id="unit_id" name="unit_id" required>
                            <option value="">Select</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="unit_id" class="form-label">Color<span style="color:red">*</span></label>
                        <select class="form-control select2" id="color_id" name="color_id" required>
                            <option value="">Select</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="quantity" class="form-label">Quantity<span style="color:red">*</span></label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="total_amount" class="form-label">Total Amount </label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount">
                    </div>


                    <div class="col-md-12 form-group">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
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

{{-- Keep all JS exactly as you had, just change URLs if needed --}}
<script>
    $(document).ready(function () {
        getView()
    });

    function getView() {
        showLoader('getView', true)
        jQuery.ajax({
            type: "GET",
            url: "/waste_collection/view",
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
        $("#modalTitle").html("New Waste Collection");
    }

    function save(e) {
        e.preventDefault();
        disableBtn("submitBtn", true);
        var form = document.getElementById('form');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/waste_collection/save",
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

    function editWasteCollection(id) {
        document.getElementById('form').reset();
        $("#hidden_id").val("")
        jQuery.ajax({
            type: "GET",
            url: "/waste_collection/edit/" + id,
            dataType: 'json',
            success: function (data) {
                let row = data.data;
                $("#hidden_id").val(data.id)
                $("#waste_picker_id").val(row.waste_picker_id).change();
                $("#collection_center_id").val(row.collection_center_id).change();
                $("#waste_type_id").val(row.waste_type_id).change();
                $("#unit_id").val(row.unit_id).change();
                $("#color_id").val(row.color_id).change();
                $("#quantity").val(row.quantity);
                $("#total_amount").val(row.total_amount);

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Waste Collection");
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteWasteCollection(id) {
        if (!confirm("Are you sure you want to delete this waste collection entry?")) return;

        jQuery.ajax({
            type: "GET",
            url: "/waste_collection/delete/" + id,
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

    function getSubWasteType() {
        var parent_id = $("#parent_id").val();
        if (!parent_id) return;

        $.ajax({
            type: "GET",
            url: "/waste_type/getSubWasteType",
            data: { parent_id: parent_id },
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    var options = "<option value=''>Select</option>";
                    if (data.data.length > 0) {
                        $.each(data.data, function (index, row) {
                            options += "<option value='" + row.id + "'>" + row.name + "</option>";
                        });
                    } else {
                        var name = $("#parent_id").find(':selected').text();
                        options += "<option value='" + parent_id + "'>" + name + "</option>";
                    }

                    $("#waste_type_id").html(options);
                } else {
                    showFlashMessage("warning", data.message);
                }
            }
        });
    }

</script>
@include('waste_collection.payment')
@endsection
