<div class="card">
    <div class="card-body table-border-style table-responsive">
        <table class="table dataTable">
            <thead>
                <tr>
                    <th>{{ __('S/N') }}</th>
                    <th>{{ __('Collection Center') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Gender') }}</th>

                    <th>{{ __('GPS') }}</th>
                    <th>{{ __('ID Type') }}</th>
                    <th>{{ __('ID Number') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $user)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($user->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->collection_center->name ?? '' }}</td>
                        <td>{{ $user->user->name ?? '' }}</td>
                        <td>{{ $user->user->email ?? '' }}</td>
                        <td>{{ $user->user->phone ?? '' }}</td>
                        <td>{{ ucfirst($user->user->gender ?? "") ?? '' }}</td>

                        <td>{{ "--"}}</td>
                        <td>{{ $user->id_type ?? '' }}</td>
                        <td>{{ $user->id_number ?? '' }}</td>
                        <td class="Action">
                            @can('edit collection center user')
                                <button type="button" onclick="editWastePicker('{{ $user->id }}')" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @endcan
                            @can('delete collection center user')
                                <button type="button" onclick="deleteWastePicker('{{ $user->id }}')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        initializeDataTable();
        $('.dataTable tbody').on('click', 'tr.row-clickable td:not(:last-child)', function() {
            // Optional row click action
        });
    });
</script>
