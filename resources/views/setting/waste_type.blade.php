@extends('layout.index')
@section('page-title')
Waste Type
@endsection
@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create waste type')
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

                        <div class="col-md-6 form-group">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select class="form-control select2" name="parent_id"  id="parent_id"  >
                                <option value="" >{{ __('Select Category') }}</option>
                                @foreach ($wasteTypes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Name -->
                        <div class="col-md-6 form-group">
                            <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter Name') }}" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="waste_sources" class="form-label">Waste Sources</label>
                            <select class="form-control select2" name="waste_sources[]" multiple id="waste_sources"  >
                                <option value="" disabled>{{ __('Select Waste Sources') }}</option>
                                @foreach ($wasteSources as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="name" class="form-label">Description</label>
                            <textarea rows="3" class="form-control" id="description" name="description" placeholder="{{ __('Enter Description') }}" ></textarea>
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
        $('#form')[0].reset();
        $("#hidden_id").val("");
        $("#submitBtn").html("Save");
        $("#modalTitle").html("New Waste Type");
        $('#waste_sources').val([]).trigger('change');
    }

    function getView() {
        showLoader('getView', true);
        $.get("/waste_type/view", function (data) {
            showLoader('getView', false);
            $("#getView").html(data);
        });
    }

    function save(e) {
        e.preventDefault();
        disableBtn("submitBtn", true);
        let formData = new FormData($('#form')[0]);
        $.post({
            url: "/waste_type/saveWasteType",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status === 200) {
                    showFlashMessage("success", data.message);
                    hideModal();
                } else {
                    showFlashMessage("warning", data.message);
                }
                disableBtn("submitBtn", false);
            }
        });
    }
    function editWasteType(id) {
        $.ajax({
            type: "GET",
            url: "/waste_type/editWasteType/" + id,
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);
                $("#parent_id").val(data.data.parent_id);
                $("#name").val(data.data.name);
                $("#description").val(data.data.description);
                $('#waste_sources').val(JSON.parse(data.data.waste_sources)).trigger('change');
                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Waste Type");
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteWasteType(id) {
        if (!confirm("Are you sure?")) return;
        $.ajax({
            type: "GET",
            url: "/waste_type/deleteWasteType/" + id,
            dataType: 'json',
            success: function (data) {
                showFlashMessage(data.status == 200 ? "success" : "warning", data.message);
                getView();
            }
        });
    }

</script>
@endsection
