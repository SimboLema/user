<?php
use Spatie\Permission\Models\Role;


?>
<div class="card">
<div class="card-body table-border-style table-responsive">
    <table class="table dataTable " >
        <thead>
            <tr>
                <th scope="col" >{{ __('S/N') }}</th>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Email') }}</th>
                <th scope="col">{{ __('Phone') }}</th>
                <th scope="col">{{ __('Role') }}</th>

                {{-- @if(auth()->user()->role == 1)
                    <th scope="col">{{ __('Account') }}</th>
                @endif --}}

                <th scope="col">{{ __('Status') }}</th>
                @if(Gate::check('manage user status'))
                <th scope="col">{{ __('Office') }}</th>
                @endif
                <th scope="col" >{{ __('Action') }}</th>

            </tr>
        </thead>
        <tbody class="list">
            @foreach ($data as $index => $docu)
            <?php
               $role = Role::find($docu->role);
            ?>
                <tr class="row-clickable" data-id="{{ \Crypt::encrypt($docu->id) }}">
                        <td class="hide"> {{ $index + 1 }}</td>

                        <td>{{$docu->name ?? ''}}</td>
                        <td>{{$docu->email ?? ''}}</td>
                        <td>{{$docu->phone ?? ''}}</td>
                        <td>{{$docu->roleRow->name ?? ''}}</td>
                        {{-- @if(auth()->user()->role == 1)
                            <td>
                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        title="Impersonate User"
                                        onclick="openImpersonateWindow('{{ route('impersonate', $docu->id) }}')">
                                    <i class="bi bi-person-lines-fill"></i> View Account
                                </button>
                            </td>
                        @endif --}}

                        <td>
                              <span>
                                    @if($docu->status == "active")
                                    <span style="background-color: green; padding: 5px; color: white; border-radius: 50%; display: inline-block;"></span> Active

                                    @else
                                    <span style="background-color: red; padding: 5px; color: white; border-radius: 50%; display: inline-block;"></span> In-Active
                                    @endif
                              </span>
                        </td>
                        @if(Gate::check('manage user status'))
                        <td>
                              <label class="switch">
                                    <input type="checkbox"
                                          onchange="toggleStatus({{ $docu->id }}, this.checked)"
                                          {{ $docu->status == 'active' ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                              </label>
                        </td>
                        @endif

                        <td class="Action">
                              <span>
                              {{-- @if(Gate::check('show user'))
                                        <a href='{{ "/user/show/".Crypt::encrypt($docu->id) }}' class="btn btn-success btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif --}}
                                    @if( Gate::check('edit user ')  || Gate::check('delete user') )
                                          @if( Gate::check('edit user'))
                                                <button type="button" onclick="editUser('<?=$docu->id?>')" class="btn btn-success btn-sm"><i class="bi bi-pencil"></i></button>
                                          @endif
                                          @if( Gate::check('edit user'))
                                                <button type="button" onclick="deleteUser('<?=$docu->id?>')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                          @endif
                                    @endif
                              </span>
                        </td>
                  </tr>
                  @endforeach
            </tbody>
      </table>
</div>
</div>

<script>
       $(document).ready(function() {
            initializeDataTable()
            $('.dataTable tbody').on('click', 'tr.row-clickable td:not(:last-child)', function() {

            });
      });

</script>
