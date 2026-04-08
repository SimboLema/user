@extends('layout.index')

@section('page-title')
Illegal Dumping
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create illegal dumping')
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
                <h5 class="modal-title" id="modalTitle">New Illegal Dumping</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" id="hidden_id" name="hidden_id">
                <div class="modal-body overflow-auto" style="max-height: 400px;">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="location_name" class="form-label">Location Name <span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="location_name" name="location_name" required>
                        </div>



                        <div class="col-md-12 form-group">
                            <label for="description" class="form-label">Description<span style="color:red">*</span></label>
                            <textarea rows="3" class="form-control" id="description" name="description" required></textarea>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="photo" class="form-label">Photos</label>
                            <input type="file" class="form-control" name="photo[]" id="photo" multiple accept="image/*">
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
        $("#modalTitle").html("New Illegal Dumping");
    }

    function getView() {
        showLoader('getView', true);
        $.get("/illegal_dumping/view", function (data) {
            showLoader('getView', false);
            $("#getView").html(data);
        });
    }

    function save(e) {
        e.preventDefault();
        disableBtn("submitBtn", true);
        let formData = new FormData($('#form')[0]);
        $.ajax({
            url: "/illegal_dumping/saveIllegalDumping",
            type: "POST",
            dataType: 'json',
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

    function editIllegalDumping(id) {
        $.ajax({
            type: "GET",
            url: "/illegal_dumping/editIllegalDumping/" + id,
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);
                $("#location_name").val(data.data.location_name);
                $("#latitude").val(data.data.latitude);
                $("#longitude").val(data.data.longitude);
                $("#description").val(data.data.description);

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Illegal Dumping");
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteIllegalDumping(id) {
        if (!confirm("Are you sure?")) return;
        $.ajax({
            type: "GET",
            url: "/illegal_dumping/deleteIllegalDumping/" + id,
            dataType: 'json',
            success: function (data) {
                showFlashMessage(data.status == 200 ? "success" : "warning", data.message);
                getView();
            }
        });
    }
</script>
@endsection
