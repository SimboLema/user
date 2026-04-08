@extends('layout.index')
@section('page-title')
Payment Status
@endsection
@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">

        @can('create payment status')
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
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter Payment Status Name') }}" required>
                        </div>

                        <!-- Color -->
                        <div class="col-md-6 form-group">
                            <label for="color" class="form-label">Color<span style="color:red">*</span></label>
                            <input type="color" class="form-control" id="color" name="color" required>
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
        $("#modalTitle").html("New Payment Status");
    }

    function getView() {
        showLoader('getView', true);
        jQuery.ajax({
            type: "GET",
            url: "/payment-status/payment_status_view", // Updated URL
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
            url: "/payment-status/savePaymentStatus", // Updated URL
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

    function editPaymentStatus(id) { // Updated function name
        document.getElementById('form').reset();
        $("#hidden_id").val("");

        jQuery.ajax({
            type: "GET",
            url: "/payment-status/editPaymentStatus/" + id, // Updated URL
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);

                var rowData = data.data;

                // Update the form fields with the retrieved data
                $("#name").val(rowData.name);
                $("#color").val(rowData.color);

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Payment Status");

                // Open the modal
                $('#largeModal').modal('show');
            }
        });
    }

    function deletePaymentStatus(id) { // Updated function name
        var conf = confirm("Are you sure you want to delete this payment status?");
        if (!conf) {
            return;
        }

        jQuery.ajax({
            type: "GET",
            url: "/payment-status/deletePaymentStatus/" + id, // Updated URL
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
