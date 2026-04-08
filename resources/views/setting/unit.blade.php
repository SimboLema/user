@extends('layout.index')
@section('page-title')
Unit
@endsection
@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create unit')
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
                <div class="modal-body overflow-auto"  style="max-height: 400px;">
                    <div class="row">

                        <!-- Name -->
                        <div class="col-md-12 form-group">
                            <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter Name') }}" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="base_unit_id" class="form-label">Base Unit</label>
                            <select class="form-control" name="base_unit_id" id="base_unit_id">
                                <option value="">-- Select Base Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="operation_type" class="form-label">Operation</label>
                            <select class="form-control" name="operation_type" id="operation_type">
                                <option value="">-- Select --</option>
                                <option value="multiply">Multiply</option>
                                <option value="divide">Divide</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="operation_value" class="form-label">Value</label>
                            <input type="number" step="0.0001" class="form-control" id="operation_value" name="operation_value" placeholder="e.g., 1000">
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
        $("#modalTitle").html("New Unit");
    }

    function getView() {
        showLoader('getView', true);
        $.get("/unit/view", function (data) {
            showLoader('getView', false);
            $("#getView").html(data);
        });
    }

    function save(e) {
        e.preventDefault();
        disableBtn("submitBtn", true);
        let formData = new FormData($('#form')[0]);
        $.post({
            url: "/unit/saveUnit",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    hideModal();
                } else {
                    showFlashMessage("warning", data.message);
                }
                disableBtn("submitBtn", false);
            }
        });
    }

    function editUnit(id) {
        $.ajax({
            type: "GET",
            url: "/unit/editUnit/" + id,
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);
                $("#name").val(data.data.name);
                $("#base_unit_id").val(data.data.base_unit_id);
                $("#operation_type").val(data.data.operation_type);
                $("#operation_value").val(data.data.operation_value);


                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Unit");
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteUnit(id) {
        if (!confirm("Are you sure?")) return;
        $.ajax({
            type: "GET",
            url: "/unit/deleteUnit/" + id,
            dataType: 'json',
            success: function (data) {
                showFlashMessage(data.status == 200 ? "success" : "warning", data.message);
                getView();
            }
        });
    }

</script>
@endsection
