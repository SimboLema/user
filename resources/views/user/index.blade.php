@extends('layout.index')

@section('page-title')
Users
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create user')
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#largeModal" onclick="showModal()">
                {{ __('Create') }}
            </button>
        @endcan
    </div>
</div>

<!-- Modal Dialog Scrollable -->
{{-- <div class="d-flex overflow-auto mb-3" style="gap: 10px;">
    @php
        $tabs = [
            ['label' => 'All', 'role' => 'All','total'=>$all],
            ['label' => 'Waste Pickers', 'role' => 2,'total'=>$waste_pickers],
            ['label' => 'Collection Centers', 'role' => 3,'total'=>$collection_centers],
            ['label' => 'Recyclers', 'role' => 4,'total'=>$recyclers],
            ['label' => 'Producers', 'role' => 5,'total'=>$producers],
        ];
    @endphp
    @foreach ($tabs as $tab)
        <button class="btn btn-outline-primary" onclick="getView('{{ $tab['role'] }}')">
            {{ $tab['label'] }}
            <span class="badge bg-secondary text-white">{{ number_format($tab['total']) ?? 0 }}</span>
        </button>
    @endforeach
</div> --}}


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
            <div class="modal-body overflow-auto"  style="max-height: 400px;">
                <div class="row g-6">

                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter A Full Name') }}" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">Phone Number<span style="color:red">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="{{ __('Enter A Phone Number') }}" required>
                    </div>



                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">Email Address<span style="color:red">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('Enter A Email Address') }}" required>
                    </div>

                    <div class="col-md-6 form-group passwordDiv">
                        <label for="name" class="form-label">Password <span style="color:red">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Enter A Password') }}" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="role" class="form-label">Role <span style="color:red">*</span></label>
                        <select class="form-control " id="role" name="role"  required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
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
        getView("All")
    });

    function getView(role) {
        showLoader('getView',true)
        jQuery.ajax({
            type: "GET",
            url: "/user/view",
            dataType: 'html',
            cache: false,
            data:{role:role},
            success: function (data) {
                showLoader('getView',false)
                $("#getView").html(data)
            }
        });
    }

    function showModal(){
        clear_input();

        $(".passwordDiv").fadeIn()
        $("#password").prop('required',true)
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
        $("#modalTitle").html("New User");

    }

    function save(e) {
        e.preventDefault();

        disableBtn("submitBtn", true);
        var form = document.getElementById('form');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/user/saveUser",
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
                $("#submitBtn").html("Save ")
            }
        });
    }

    function editUser(id) {
        document.getElementById('form').reset();
        $("#hidden_id").val("")


        jQuery.ajax({
                type: "GET",
                url: "/user/editUser/"+id,
                dataType: 'json',
                success: function (data) {
                    $("#hidden_id").val(data.id)

                    var rowData=data.data;

                    $("#name").val(rowData.name);
                    $("#phone").val(rowData.phone);
                    $("#email").val(rowData.email);
                    $("#role").val(rowData.role);

                    $(".passwordDiv").fadeOut()
                    $("#password").prop('required',false)

                    $("#submitBtn").html("Update");
                    $("#modalTitle").html("Update User ");

                    // Open the modal
                    $('#largeModal').modal('show');
                }
        });
      }

    function deleteUser(id) {
        var conf = confirm("Are you sure you want to delete a user  ?");
        if (!conf) {
                return;
        }

        jQuery.ajax({
            type: "GET",
            url: "/user/deleteUser/"+id,
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

    function toggleStatus(userId, isActive) {
        const status = isActive ? 'active' : 'inactive';

        $.ajax({
            url: '/user/updateUserStatus', // Your backend route
            method: 'GET',
            data: {
                user_id: userId,
                status: status,
            },
            success: function(data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    getView()
                } else {
                    showFlashMessage("warning", data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showFlashMessage("warning", "An error occurred. Please try again.");
            }
        });
    }

</script>
<script>
    function openImpersonateWindow(url) {
        // Open a new browser window
        const windowOptions = "width=800,height=600,scrollbars=yes,resizable=yes";

        // Open the impersonated user session in a new window
        const newWindow = window.open(url, "_blank", windowOptions);

        // Check if the new window was successfully opened
        if (newWindow) {
            // Optionally focus the new window
            newWindow.focus();
        } else {
            alert("Please allow popups for this site.");
        }
    }
</script>
@endsection
