@extends('layout.index')

@section('page-title')
Roles & Permissions
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        @can('create role')
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
    <div class="modal-dialog  modal-lg">
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

                    <div class="col-md-12 form-group">
                        <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter A Full Name') }}" required>
                    </div>
                    <div class="col-md-12">

                         <!-- Tabs for Different Sections -->
                      <ul class="nav nav-tabs mb-3" id="roleTabs" role="tablist">
                        @foreach($modules as $module => $entities)
                          <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ strtolower($module) }}-tab" data-bs-toggle="tab" href="#{{ strtolower($module) }}" role="tab">{{ $module }}</a>
                          </li>
                        @endforeach
                      </ul>

                      <!-- Tab Content -->
                      <div class="tab-content" id="roleTabContent">
                        @foreach($modules as $module => $entities)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ strtolower($module) }}" role="tabpanel">
                          <h6 class="mb-3">{{ $module }} Permissions</h6>
                          <div class="table-responsive">
                            <table class="table table-bordered">
                              <thead class="table-light">
                                <tr>
                                  <th><input type="checkbox" class="select-all" data-group="{{ strtolower($module) }}">    Feature</th>
                                  <th>{{ __('Manage') }}</th>
                                  <th>{{ __('Show') }}</th>
                                  <th>{{ __('Create') }}</th>
                                  <th>{{ __('Edit') }}</th>
                                  <th>{{ __('Delete') }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($entities as $entity)
                                <tr>
                                  <td>{{ ucfirst($entity) }}</td>
                                  @foreach(['manage', 'show', 'create', 'edit', 'delete'] as $action)
                                    @php
                                      $permissionName = "{$action} {$entity}";
                                    @endphp
                                    <td>
                                          @if($permissions->contains('name', $permissionName))
                                            <input type="checkbox"
                                                  class="select-row"
                                                  data-group="{{ strtolower($module) }}"
                                                  name="permissions[]"
                                                  value="{{ $permissionName }}" >
                                          @endif
                                    </td>
                                  @endforeach
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                        @endforeach
                      </div>


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
    // Handle Select All checkbox
    $('.select-all').on('change', function () {
      const group = $(this).data('group');
      $(`.select-row[data-group='${group}']`).prop('checked', $(this).is(':checked'));
    });

    // Handle individual row checkbox
    $('.select-row').on('change', function () {
      const group = $(this).data('group');
      const allChecked = $(`.select-row[data-group='${group}']`).length === $(`.select-row[data-group='${group}']:checked`).length;
      $(`.select-all[data-group='${group}']`).prop('checked', allChecked);
    });
  });
</script>


<script>
    $(document).ready(function () {
        getView()
    });

    function getView() {
        showLoader('getView',true)
        jQuery.ajax({
            type: "GET",
            url: "/role/view",
            dataType: 'html',
            cache: false,
            success: function (data) {
                showLoader('getView',false)
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
        $("#modalTitle").html("New Role");

    }

    function save(e) {
        e.preventDefault();

        disableBtn("submitBtn", true);
        var form = document.getElementById('form');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/role/saveRole",
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

    function editRole(id) {
      document.getElementById('form').reset();  // Reset the form
      $("#hidden_id").val("");  // Clear hidden field

      jQuery.ajax({
          type: "GET",
          url: "/role/editRole/" + id,
          dataType: 'json',
          success: function (data) {
              // Set hidden ID and basic form data
              var rowData=data.data;
              $("#hidden_id").val(data.id);
              $("#name").val(rowData.name);

              // Clear all permission checkboxes
              $("input[name='permissions[]']").prop('checked', false);

              // Preselect the permissions associated with the role
              data.permissions.forEach(function(permission) {
                  $("input[value='" + permission + "']").prop('checked', true);
              });

              // Change modal title and button text
              $("#submitBtn").html("Update");
              $("#modalTitle").html("Update Role");

              // Open the modal
              $('#largeModal').modal('show');
          }
      });
  }


    function deleteRole(id) {
        var conf = confirm("Are you sure you want to delete a role  ?");
        if (!conf) {
                return;
        }

        jQuery.ajax({
            type: "GET",
            url: "/role/deleteRole/"+id,
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

</script>



@endsection
