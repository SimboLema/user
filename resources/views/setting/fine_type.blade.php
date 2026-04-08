@extends('layout.index')
@section('page-title')
Fine Type
@endsection
@section('breadcrumb')
<div class="d-flex justify-content-between align-items-center mx-4 ">
    <!-- Breadcrumb -->
    <div class="pagetitle">
        <h3>{{ __('Fine Type') }}</h3>
        <nav>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Fine Type') }}</li>
            </ol>
        </nav>
    </div>

    @can('create fine type')
    <div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#largeModal" onclick="showModal()">
            {{ __('Create') }}
        </button>
    </div>
    @endcan
</div>
@endsection

@section('content')
<!-- Modal Dialog Scrollable -->
<div class="row">
    <div class="col-md-12" id="getView"></div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">{{ __('New Fine Type') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
            </div>
            <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" id="hidden_id" name="hidden_id">
                <div class="modal-body overflow-auto"  style="max-height: 400px;">
                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-12 form-group">
                            <label for="title" class="form-label">Title<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter Fine Type') }}" required>
                        </div>
                        
                        <div class="col-md-12 form-group">
                            <label for="title" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" id="description" name="description" placeholder="{{ __('Enter Description') }}" ></textarea>
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
        $("#modalTitle").html("New Fine Type");
    }

    function getView() {
        showLoader('getView', true);
        jQuery.ajax({
            type: "GET",
            url: "/fine-type/fine_type_view",
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
            url: "/fine-type/saveFineType",
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

    function editFineType(id) {
        document.getElementById('form').reset();
        $("#hidden_id").val("");

        jQuery.ajax({
            type: "GET",
            url: "/fine-type/editFineType/" + id,
            dataType: 'json',
            success: function (data) {
                $("#hidden_id").val(data.id);

                var rowData = data.data;

                // Update the form fields with the retrieved data
                $("#name").val(rowData.name);
                $("#description").val(rowData.description);

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Fine Type");

                // Open the modal
                $('#largeModal').modal('show');
            }
        });
    }

    function deleteFineType(id) {
        var conf = confirm("Are you sure you want to delete this fine type?");
        if (!conf) {
            return;
        }

        jQuery.ajax({
            type: "GET",
            url: "/fine-type/deleteFineType/" + id,
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
