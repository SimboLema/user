@extends('layout.index')

@section('page-title')
Facility Waste Collection
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create recycling waste collection')
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
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
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


                    <div class="col-md-4 form-group">
                        <label for="facility_id" class="form-label">Facility<span style="color:red">*</span></label>
                        <select class="form-control select2" id="facility_id" name="facility_id" required>
                            <option value="">Select</option>
                            @foreach($facilities as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label">Collect From <span style="color:red">*</span></label><br>
                        <label><input type="radio" name="collect_from" value="collection_center" checked> Collection Center</label>
                        <label class="ms-3"><input type="radio" name="collect_from" value="producer"> Producer</label>
                    </div>


                    <div class="col-md-4 form-group collectionCenterDiv">
                        <label for="collection_center_id" class="form-label">Collection Center<span style="color:red">*</span></label>
                        <select class="form-control select2" id="collection_center_id" name="collection_center_id" required>
                            <option value="">Select</option>
                            @foreach($collectionCenters as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group producerDiv" style="display: none;">
                        <label for="producer_id" class="form-label">Producer<span style="color:red">*</span></label>
                        <select class="form-control select2" id="producer_id" name="producer_id" required>
                            <option value="">Select</option>
                            @foreach($producers as $producer)
                                <option value="{{ $producer->id }}">{{ $producer->name }}</option>
                            @endforeach
                        </select>
                    </div>






                    <div class="col-md-4 form-group">
                        <label for="total_amount" class="form-label">Total Amount<span style="color:red">*</span></label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" required>
                    </div>


                    <div class="col-md-4 form-group">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="tableID">
                              <tr>

                                    <th>Waste Type</th>
                                    <th>Sub Waste Type</th>
                                    <th>Unit</th>
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Action</th>


                              </tr>
                              <tr>
                                    <td>
                                          <input type="hidden" id="item_hidden_id0" name="item_hidden_id[]" class="form-control"   >
                                          <select class="form-control " id="parent_id0" name="parent_id[]" onchange="getSubWasteType(0)" required>
                                            <option value="" >Select</option>
                                            @foreach($wasteTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                          <select class="form-control " id="waste_type_id0" name="waste_type_id[]" required>
                                            <option value="" >Select</option>
                                        </select>
                                    </td>


                                    <td>
                                        <select class="form-control " id="unit_id0" name="unit_id[]" required>
                                            <option value="">Select</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control " id="color_id0" name="color_id[]" required>
                                            <option value="">Select</option>
                                            @foreach($colors as $color)
                                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="quantity0" name="quantity[]" required>
                                    </td>


                                    <td ><button type="button"  onclick="addMore()" class="btn btn-success btn-sm" class="closebtn"><i class="fa fa-plus" ></i></button></td>

                              </tr>
                        </table>
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

        toggleSource(); // initial state

        $('input[name="collect_from"]').on('change', function() {
            toggleSource();
        });


    });

    function getView() {
        showLoader('getView', true)
        jQuery.ajax({
            type: "GET",
            url: "/recycling_waste_collection/view",
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
        $("#modalTitle").html("New Facility Waste Collection");
    }

    function save(e) {
        e.preventDefault();
        disableBtn("submitBtn", true);
        var form = document.getElementById('form');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/recycling_waste_collection/save",
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

    function editRecyclingWasteCollection(id) {
        document.getElementById('form').reset();
        $("#hidden_id").val("")
        jQuery.ajax({
            type: "GET",
            url: "/recycling_waste_collection/edit/" + id,
            dataType: 'json',
            success: function (data) {
                let row = data.data;
                let items = row.items;

                $("#hidden_id").val(data.id)
                $("#facility_id").val(row.facility_id).change();
                $("#total_amount").val(row.total_amount);

                if (row.collection_center_id) {
                    $("input[name='collect_from'][value='collection_center']").prop("checked", true);
                    $("#collection_center_id").val(row.collection_center_id).change();
                } else if (row.producer_id) {
                    $("input[name='collect_from'][value='producer']").prop("checked", true);
                    $("#producer_id").val(row.producer_id).change();
                }

                toggleSource();

                $.each(items,function(index,row){
                    $("#item_hidden_id"+(index)).val(row.id);
                    $("#parent_id"+(index)).val(row.waste_type.parent_id || "");
                    $("#waste_type_id"+(index)).html('<option value='+row.waste_type_id+'>'+(row.waste_type.name || "")+'</option>');
                    $("#unit_id"+(index)).val(row.unit_id);
                    $("#color_id"+(index)).val(row.color_id);
                    $("#quantity"+(index)).val(row.quantity);


                    if((index+1) < items.length){
                        addMore()
                    }
                })

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Facility Waste Collection");
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteFacilityWasteCollection(id) {
        if (!confirm("Are you sure you want to delete this waste collection entry?")) return;

        jQuery.ajax({
            type: "GET",
            url: "/recycling_waste_collection/delete/" + id,
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


    function addMore() {
        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len - 1);


        var div = '<td> '+
                '<input type="hidden" id="item_hidden_id'+id+'" name="item_hidden_id[]" class="form-control"   >'+
                '<select class="form-control select2" id="parent_id'+id+'" name="parent_id[]" required onchange="getSubWasteType('+id+') ">'+
                '<option value="">Select</option>'+
                '@foreach($wasteTypes as $type)'+
                    '<option value="{{ $type->id }}">{{ $type->name }}</option>'+
                '@endforeach'+
            '</select>'+
        '</td>'+
        '<td> '+
                '<select class="form-control select2" id="waste_type_id'+id+'" name="waste_type_id[]" required>'+
                '<option value="">Select</option>'+
            '</select>'+
        '</td>'+


        '<td>'+
            '<select class="form-control select2" id="unit_id'+id+'" name="unit_id[]" required>'+
                '<option value="">Select</option>'+
                '@foreach($units as $unit)'+
                 '<option value="{{ $unit->id }}">{{ $unit->name }}</option>'+
                '@endforeach'+
            '</select>'+
        '</td>'+
        '<td>'+
            '<select class="form-control select2" id="color_id'+id+'" name="color_id[]" required>'+
                '<option value="">Select</option>'+
                '@foreach($colors as $color)'+
                 '<option value="{{ $color->id }}">{{ $color->name }}</option>'+
                '@endforeach'+
            '</select>'+
        '</td>'+
        '<td>'+
            '<input type="number" class="form-control" id="quantity'+id+'" name="quantity[]" required>'+
        '</td>';

        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'>" + div + "<td><button type='button' onclick='delete_row(" + id + ")' class='btn btn-danger btn-sm'><i class=' fa fa-times '></i></i></button></td></tr>";

    }

    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).remove();
        $("#item_hidden_id" + id).remove();

    }

    function getSubWasteType(param) {
        var parent_id = $("#parent_id"+param).val();
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
                        var name = $("#parent_id"+param).find(':selected').text();
                        options += "<option value='" + parent_id + "'>" + name + "</option>";
                    }

                    $("#waste_type_id"+param).html(options);
                } else {
                    showFlashMessage("warning", data.message);
                }
            }
        });
    }

    function toggleSource() {
        let selected = $('input[name="collect_from"]:checked').val();

        if (selected === 'collection_center') {
            $('.collectionCenterDiv').show();
            $('#collection_center_id').prop('required', true);

            $('.producerDiv').hide();
            $('#producer_id').prop('required', false).val('').trigger('change');
        } else {
            $('.producerDiv').show();
            $('#producer_id').prop('required', true);

            $('.collectionCenterDiv').hide();
            $('#collection_center_id').prop('required', false).val('').trigger('change');
        }
    }

</script>
@include('recycling_waste_collection.payment')

@endsection
