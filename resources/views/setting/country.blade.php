@extends('layout.index')
@section('page-title')
Country
@endsection
@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">

        @can('create country')
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
                        <div class="col-md-6 form-group">
                            <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter Name') }}" required>
                        </div>

                        <!-- Code -->
                        <div class="col-md-6 form-group">
                            <label for="name" class="form-label">Country Code<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="{{ __('Enter Country Code') }}" required>
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
        $("#modalTitle").html("New Country");
    }

    function getView() {
        showLoader('getView', true);
        jQuery.ajax({
            type: "GET",
            url: "/country/view", // Updated URL
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
            url: "/country/saveCountry", // Updated URL
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

    function editCountry(id) { // Updated function name
        document.getElementById('form').reset();
        $("#hidden_id").val("");

        jQuery.ajax({
            type: "GET",
            url: "/country/editCountry/" + id, // Updated URL
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);

                var rowData = data.data;

                // Update the form fields with the retrieved data
                $("#name").val(rowData.name);
                $("#code").val(rowData.code);

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Country");

                // Open the modal
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteCountry(id) { // Updated function name
        var conf = confirm("Are you sure you want to delete this country?");
        if (!conf) {
            return;
        }

        jQuery.ajax({
            type: "GET",
            url: "/country/deleteCountry/" + id, // Updated URL
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
</script>

@endsection
