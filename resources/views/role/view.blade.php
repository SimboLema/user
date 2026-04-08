<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm dataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/N') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Permissions') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $docu)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($docu->id) }}">
                        <td> {{ $index + 1 }}</td>
                        <td>{{ $docu->name ?? '' }}</td>
                        <td>
                            @php
                                $permissions = $docu->permissions; // Assuming the permissions relationship is already loaded
                                $permissionsList = $permissions->pluck('name')->toArray();
                            @endphp

                            <div class="permissions-list">
                                <span class="initial-permissions">
                                    @foreach (array_slice($permissionsList, 0, 5) as $permission)
                                        <span class="badge bg-primary text-white">{{ $permission }}</span>
                                    @endforeach
                                </span>

                                @if(count($permissionsList) > 5)
                                    <span class="more-permissions" style="display:none;">
                                        @foreach (array_slice($permissionsList, 5) as $permission)
                                            <span class="badge bg-primary text-white">{{ $permission }}</span>
                                        @endforeach
                                    </span>

                                    <button type="button" class="btn btn-link view-all-btn">{{ __('View All') }}</button>
                                @endif
                            </div>
                        </td>
                        <td class="Action">
                            <span>
                                @if(Gate::check('edit role') || Gate::check('delete role'))
                                    @if(Gate::check('edit role'))
                                        <button type="button" onclick="editRole('{{ $docu->id }}')" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif
                                    @if(Gate::check('delete role'))
                                        <button type="button" onclick="deleteRole('{{ $docu->id }}')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
        // Initialize DataTable
        initializeDataTable();

        // Handle the View All button click
        $('.view-all-btn').click(function() {
            var morePermissions = $(this).siblings('.more-permissions');
            var isVisible = morePermissions.is(':visible');

            // Toggle visibility of additional permissions
            morePermissions.toggle();
            $(this).text(isVisible ? 'View All' : 'Hide');
        });
    });
</script>
