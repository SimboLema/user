@extends('layout.index')

@section('page-title')
collector
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create collection center user')
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
        <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="hidden_id" name="hidden_id">

            <div class="modal-body overflow-auto" style="max-height: 400px;">
                <div class="row g-6">
                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full name" required>
                    </div>



                    <div class="col-md-6 form-group">
                        <label for="phone" class="form-label">Phone Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone number" required>
                    </div>
                     <div class="col-md-6 form-group">
                        <label for="email" class="form-label">Email Address<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                    </div>

                    <div class="col-md-6 form-group passwordDiv">
                        <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="gender" class="form-label">Gender<span class="text-danger">*</span></label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="collection_center_id" class="form-label">Collection Center<span class="text-danger">*</span></label>
                        <select class="form-control select2" id="collection_center_id" name="collection_center_id" required>
                            <option value="">Select Collection Center</option>
                            @foreach($collection_centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="id_type" class="form-label">ID Type</label>
                        <select class="form-control" id="id_type" name="id_type" required>
                            <option value="">Select ID Type</option>
                            <option value="National ID">National ID</option>
                            <option value="Vote ID">Vote ID</option>
                            <option value="Passport">Passport</option>
                            <option value="Student ID">Student ID</option>
                            <option value="Driver's License">Driver's License</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="id_number" class="form-label">ID Number</label>
                        <input type="text" class="form-control" id="id_number" name="id_number" placeholder="Enter ID number" required>
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
        getView()
    });

    function getView() {
        showLoader('getView', true)
        $.ajax({
            url: "/collection_center_user/view",
            type: "GET",
            success: function (data) {
                showLoader('getView', false)
                $("#getView").html(data);
            }
        });
    }

    function showModal() {
        clear_input();

        $(".passwordDiv").fadeIn()
        $("#password").prop('required',true)
    }

    function hideModal() {
        $('#largeModal').modal('hide');
        clear_input();
        getView();
    }

    function clear_input() {
        document.getElementById('form').reset();
        $("#hidden_id").val("")
        $("#submitBtn").html("Save")
        $("#modalTitle").html("New collector");
    }

    function save(e) {
        e.preventDefault();
        disableBtn("submitBtn", true);
        let formData = new FormData(document.getElementById('form'));

        $.ajax({
            url: "/collection_center_user/save",
            type: "POST",
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
                $("#submitBtn").html("Save")
            }
        });
    }

    function editCollectionCenterUser(id) {
        $.ajax({
            url: "/collection_center_user/edit/" + id,
            type: "GET",
            dataType : 'json',
            success: function (data) {
                let row = data.data;
                $("#hidden_id").val(data.id)

                if(row.user){
                    $("#name").val(row.user.name || "");
                    $("#email").val(row.user.email || "");
                    $("#phone").val(row.user.phone || "");
                    $("#gender").val(row.user.gender || "");
                }

                $("#collection_center_id").val(row.collection_center_id);
                $("#id_type").val(row.id_type);
                $("#id_number").val(row.id_number);

                $(".passwordDiv").fadeOut()
                $("#password").prop('required',false)

                $("#submitBtn").html("Update");
                $("#modalTitle").html("Update Collector");

                $('#largeModal').modal('show');
            }
        });
    }

    function deleteCollectionCenterUser(id) {
        if (!confirm("Are you sure?")) return;
        $.ajax({
            url: "/collection_center_user/delete/" + id,
            type: "GET",
            dataType : 'json',
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
