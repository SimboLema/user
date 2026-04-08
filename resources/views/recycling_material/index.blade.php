@extends('layout.index')

@section('page-title')
Recycling Material
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">

        @can('create recycling material')
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
                    <div class="row g-6">
                        <div class="col-md-6 form-group">
                            <label for="facility_id" class="form-label">Facility<span style="color:red">*</span></label>
                            <select class="form-control" id="facility_id" name="facility_id" required>
                                @foreach($facilities as $row)
                                <option value="{{$row->id}}" >{{$row->name}}</option>
                            @endforeach

                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="process" class="form-label">Process<span style="color:red">*</span></label>
                            <select class="form-control" id="process" name="process" required>
                                <option value="Recycling">Recycling</option>
                                <option value="Sorting">Sorting</option>
                                <option value="Processing">Processing</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="waste_type_id" class="form-label">Waste Type<span style="color:red">*</span></label>
                            <select class="form-control" id="waste_type_id" name="waste_type_id" onchange="updateMaxQuantity()" required>
                                @foreach($balance as $row)
                                <option value="{{$row->waste_type_id}}" data-balance="{{ $row->quantity ?? 0 }}" >{{$row->wasteType->name ?? ""}} - Balance {{$row->quantity ?? 0}} {{$row->unit->name ?? ""}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="input_quantity" class="form-label">Input Quantity<span style="color:red">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="input_quantity" name="input_quantity" required>
                            <small id="quantityHelp" class="form-text text-muted"></small>
                        </div>



                        <div class="col-md-6 form-group">
                            <label for="output_product_id" class="form-label">Output Product<span style="color:red">*</span></label>
                            <select class="form-control" id="output_product_id" name="output_product_id" required>
                                @foreach($products as $product)
                                <option value="{{$product->id}}" >{{$product->name}}</option>
                            @endforeach

                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="output_product_quantity" class="form-label">Output Quantity<span style="color:red">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="output_product_quantity" name="output_product_quantity" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="output_product_quantity" class="form-label">Image<span style="color:red">*</span></label>
                            <input type="file"  class="form-control" id="image" name="image" required>
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
</div><!-- End Modal Dialog Scrollable-->

<script>
    $(document).ready(function () {
        getView();
        updateMaxQuantity();
    });

    function showModal() {
        clear_input();
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
        $("#modalTitle").html("New Recycling Material");
    }

    function getView() {
        showLoader('getView', true);
        jQuery.ajax({
            type: "GET",
            url: "/recycling_material/view",
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
            url: "/recycling_material/save",
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

    function editRecyclingMaterial(id) {
        document.getElementById('form').reset();
        $("#hidden_id").val("");

        jQuery.ajax({
            type: "GET",
            url: "/recycling_material/edit/" + id,
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);

                let rowData = data.data;
                $("#facility_id").val(rowData.facility_id);
                $("#process").val(rowData.process);
                $("#input_quantity").val(rowData.input_quantity);
                $("#waste_type_id").val(rowData.waste_type_id);
                $("#output_product_id").val(rowData.output_product_id);
                $("#output_product_quantity").val(rowData.output_product_quantity);

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Recycled Material");
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteRecyclingMaterial(id) {
        var conf = confirm("Are you sure you want to delete this recycled material?");
        if (!conf) return;

        jQuery.ajax({
            type: "GET",
            url: "/recycling_material/delete/" + id,
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
    function updateMaxQuantity() {
        const select = document.getElementById('waste_type_id');
        const selectedOption = select.options[select.selectedIndex];
        const balance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;

        const input = document.getElementById('input_quantity');
        const help = document.getElementById('quantityHelp');

        input.max = balance;
        input.value = '';
        help.textContent = `Max allowed quantity: ${balance}`;
    }

    // Initialize on page lo
</script>
@endsection
